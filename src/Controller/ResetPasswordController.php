<?php

namespace TwinElements\AdminBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use TwinElements\AdminBundle\Entity\AdminUser;
use TwinElements\AdminBundle\Form\ChangePasswordFormType;
use TwinElements\AdminBundle\Form\ResetPasswordRequestFormType;
use TwinElements\AdminBundle\Repository\AdminUserRepository;
use TwinElements\Component\Message\MessageBuilderFactory;

/**
 * @Route("/reset-admin-password")
 */
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private ResetPasswordHelperInterface $resetPasswordHelper;
    private EntityManagerInterface $entityManager;
    private MessageBuilderFactory $messageBuilderFactory;
    private TranslatorInterface $translator;
    private AdminUserRepository $userRepository;

    public function __construct(
        ResetPasswordHelperInterface $resetPasswordHelper,
        EntityManagerInterface       $entityManager,
        MessageBuilderFactory        $messageBuilderFactory,
        TranslatorInterface          $translator,
        AdminUserRepository          $userRepository
    )
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->entityManager = $entityManager;
        $this->messageBuilderFactory = $messageBuilderFactory;
        $this->translator = $translator;
        $this->userRepository = $userRepository;
    }

    /**
     * Display & process form to request a password reset.
     * @Route("", name="admin_forgot_password_request")
     */
    public function request(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer
            );
        }

        return $this->render('@TwinElementsAdmin/reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Confirmation page after a user has requested a password reset.
     * @Route("/check-email", name="admin_check_email")
     */
    public function checkEmail(): Response
    {
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('@TwinElementsAdmin/reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Validates and process the reset URL that the user clicked in their email.
     * @Route("/reset/{token}", name="admin_reset_password")
     */
    public function reset(Request $request, UserPasswordHasherInterface $userPasswordHasher, string $token = null): Response
    {
        if ($token) {
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('admin_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem validating your reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('admin_forgot_password_request');
        }

        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->resetPasswordHelper->removeResetRequest($token);

            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('@TwinElementsAdmin/reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    private function processSendingPasswordResetEmail(
        string          $emailFormData,
        MailerInterface $mailer
    ): RedirectResponse
    {
        /**
         * @var AdminUser $user
         */
        $user = $this->userRepository->findOneBy([
            'email' => $emailFormData,
        ]);

        if (!$user || !$user->isEnable()) {
            return $this->redirectToRoute('admin_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('reset_password_error', sprintf(
                'There was a problem handling your password reset request - %s',
                $e->getReason()
            ));

            return $this->redirectToRoute('admin_check_email');
        }

        $message = ($this->messageBuilderFactory->createMessageBuilder()->getMessage(
            $this->translator->trans('admin.forgot_password_form.subject', [], 'messages'),
            [
                'reset_token' => $resetToken
            ],
            '@TwinElementsAdmin/reset_password/email.html.twig'))
            ->addTo($user->getEmail());

        $mailer->send($message);

        $this->setTokenObjectInSession($resetToken);

        return $this->redirectToRoute('admin_check_email');
    }
}
