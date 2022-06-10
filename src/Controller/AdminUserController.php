<?php

namespace TwinElements\AdminBundle\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use TwinElements\AdminBundle\Entity\AdminUser;
use TwinElements\AdminBundle\Form\AdminUserPasswordType;
use TwinElements\AdminBundle\Form\AdminUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\AdminBundle\Repository\AdminUserRepository;
use TwinElements\AdminBundle\Role\AdminUserRole;
use TwinElements\Component\AdminTranslator\AdminTranslator;
use TwinElements\Component\Flashes\Flashes;

/**
 * @Route("user")
 */
class AdminUserController extends AbstractController
{
    use CrudControllerTrait;

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function indexAction(
        AdminTranslator     $translator,
        AdminUserRepository $userRepository
    )
    {
        $this->breadcrumbs->setItems([
            $translator->translate('admin.user.users') => null
        ]);

        return $this->render('@TwinElementsAdmin/adminuser/index.html.twig', array(
            'adminUsers' => $userRepository->findAll(),
        ));
    }

    /**
     * @Route("/new", name="user_new", methods={"GET", "POST"})
     */
    public function newAction(
        Request                     $request,
        UserPasswordHasherInterface $userPasswordHasher,
        AdminTranslator             $translator,
        ManagerRegistry             $managerRegistry
    )
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $adminUser = new AdminUser();
        $form = $this->createForm(AdminUserType::class, $adminUser, [
            'enable_password_input' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $managerRegistry->getManager();

            $adminUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $adminUser,
                    $form->get('plainPassword')->getData()
                )
            );

            $em->persist($adminUser);
            $em->flush();
            $this->flashes->successMessage($translator->translate('admin.user.user_has_been_added'));
            return $this->redirectToRoute('user_index');
        }

        $this->breadcrumbs->setItems([
            $translator->translate('admin.user.users') => $this->generateUrl('user_index'),
            $translator->translate('admin.user.add_new_user') => null
        ]);

        return $this->render('@TwinElementsAdmin/adminuser/new.html.twig', array(
            'adminUser' => $adminUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing adminUser entity.
     *
     * @Route("/{id}/edit", name="user_edit", methods={"GET", "POST"})
     *
     */
    public function editAction(
        Request         $request,
        AdminUser       $adminUser,
        ManagerRegistry $managerRegistry)
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $deleteForm = $this->createDeleteForm($adminUser);
        $editForm = $this->createForm(AdminUserType::class, $adminUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $managerRegistry->getManager()->flush();
            $this->flashes->successMessage($this->adminTranslator->translate('admin.success_operation'));

            return $this->redirectToRoute('user_edit', array('id' => $adminUser->getId()));
        }

        $this->breadcrumbs->setItems([
            'admin.users' => $this->generateUrl('user_index'),
            $adminUser->getUsername() => null
        ]);

        return $this->render('@TwinElementsAdmin/adminuser/edit.html.twig', array(
            'adminUser' => $adminUser,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/{id}/change-password", name="user_change_password", methods={"GET", "POST"})
     */
    public function changePassword(
        int                         $id,
        AdminUserRepository         $userRepository,
        Request                     $request,
        ManagerRegistry             $managerRegistry,
        Flashes                     $flashes,
        UserPasswordHasherInterface $userPasswordHasher
    )
    {
        /**
         * @var AdminUser $user
         */
        $user = $userRepository->find($id);
        $form = $this->createForm(AdminUserPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                if (!$userPasswordHasher->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                    throw new \Exception($this->adminTranslator->translate('admin.the_password_is_incorrect'));
                }

                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $managerRegistry->getManager()->flush();
                $flashes->successMessage($this->adminTranslator->translate('admin.password_has_been_changed'));

            } catch (\Exception $exception) {
                $this->flashes->errorMessage($exception->getMessage());
            }

            return $this->redirectToRoute('user_change_password', [
                'id' => $user->getId()
            ]);
        }

        $this->breadcrumbs->setItems([
            'admin.users' => $this->generateUrl('user_index'),
            $user->getUsername() => null
        ]);

        return $this->render('@TwinElementsAdmin/adminuser/change-password.html.twig', [
            'form' => $form->createView(),
            'adminUser' => $user
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function deleteAction(Request $request, AdminUser $adminUser, AdminTranslator $translator)
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $form = $this->createDeleteForm($adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminUser);
            $em->flush();

            $this->flashes->successMessage($translator->translate('admin.user.user_has_been_deleted'));

            return $this->redirectToRoute('user_index');
        }

        throw new \Exception('Form is not submitted');
    }

    /**
     * @param AdminUser $adminUser The adminUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminUser $adminUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $adminUser->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
