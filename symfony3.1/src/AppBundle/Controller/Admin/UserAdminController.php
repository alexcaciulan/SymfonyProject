<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/21/2016
 * Time: 11:03 AM
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\User;
use AppBundle\Form\UserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserAdminController
 * @Route("/admin/users")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class UserAdminController extends Controller
{
    /**
     * @Route("/", name="admin_user_list")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        return $this->render("admin/user/list.html.twig", [
           'users' => $users
        ]);
    }

    /**
     * @Route("/create", name="admin_user_create")
     */
    public function createAction(Request $request){

        $form = $this->createForm(UserForm::class);
        $form->handleRequest($request);

        if($form->isValid()){
            /**  @var User $user */
            $user = $form->getData();

            //Set user default password
            $user->setPassword('parola');
            $user->setPlainPassword($user->getPassword());

            //Set user photo
            if($user->getPhoto()!=null) {
                $file = $user->getPhoto();
                $fileName = $this->get('app.file_uploader')->upload($file);
                $user->setPhoto($fileName);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'User created!');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/create.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="admin_user_edit")
     */
    public function editAction(User $user, Request $request)
    {
        $photo = $user->getPhoto();
        $password = $user->getPassword();

        $form = $this->createForm(UserForm::class, $user);
        $form->handleRequest($request);

        if($form->isValid()){


            $user = $form->getData();

            $user->setPassword($password);

            //Set user photo
            if($user->getPhoto()!=null){
                $file = $user->getPhoto();
                $this->get('app.file_uploader')->remove($photo);
                $fileName = $this->get('app.file_uploader')->upload($file);
                $user->setPhoto($fileName);
            }
            else $user->setPhoto($photo);
            /////

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User edited!');
            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render("admin/user/edit.html.twig", [
            'userForm' => $form->createView(),
            'photo' => $user->getPhoto()
        ]);
    }

    /**
     * @Route("/confirm_delete/{id}", name="admin_user_confirm_delete")
     */
    public function confirmDeleteAction($id){
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        return $this->render("admin/user/confirm_delete.html.twig", [
           'user' => $user
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_user_delete")
     */
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)
            ->find($id);
        $this->get('app.file_uploader')->remove($user->getPhoto());
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User has been deleted!');
        return $this->redirectToRoute('admin_user_list');
    }

}