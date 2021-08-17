<?php

namespace TwinElements\AdminBundle\Controller;

use TwinElements\AdminBundle\Entity\AdminUser;
use TwinElements\AdminBundle\Form\AdminUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TwinElements\AdminBundle\Model\CrudControllerTrait;
use TwinElements\AdminBundle\Role\AdminUserRole;
use TwinElements\AdminBundle\Service\AdminTranslator;

/**
 * @Route("user")
 */
class AdminUserController extends AbstractController
{

    use CrudControllerTrait;

    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function indexAction(AdminTranslator $translator)
    {
        $em = $this->getDoctrine()->getManager();

        $adminUsers = $em->getRepository(AdminUser::class)->findAll();

        $this->breadcrumbs->setItems([
            $translator->translate('admin.user.users') => null
        ]);

        return $this->render('@TwinElementsAdmin/adminuser/index.html.twig', array(
            'adminUsers' => $adminUsers,
        ));
    }

    /**
     * @Route("/new", name="user_new", methods={"GET", "POST"})
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, AdminTranslator $translator)
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $adminUser = new AdminUser();
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $passwordEncoder->encodePassword($adminUser, $adminUser->getPassword());
            $adminUser->setPassword($password);

            $em = $this->getDoctrine()->getManager();
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
    public function editAction(Request $request, AdminUser $adminUser, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted(AdminUserRole::ROLE_ADMIN);

        $oldPassword = $adminUser->getPassword();

        $deleteForm = $this->createDeleteForm($adminUser);
        $editForm = $this->createForm(AdminUserType::class, $adminUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if (null === $editForm->getData()->getPassword()) {
                $adminUser->setPassword($oldPassword);
            } else {
                $password = $passwordEncoder->encodePassword($adminUser, $adminUser->getPassword());
                $adminUser->setPassword($password);
            }

            $this->getDoctrine()->getManager()->flush();
            $this->flashes->successMessage();

            return $this->redirectToRoute('user_edit', array('id' => $adminUser->getId()));
        }

        $this->breadcrumbs->setItems([
            'cms.users' => $this->generateUrl('user_index'),
            $adminUser->getUsername() => null
        ]);

        return $this->render('@TwinElementsAdmin/adminuser/edit.html.twig', array(
            'adminUser' => $adminUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));

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
