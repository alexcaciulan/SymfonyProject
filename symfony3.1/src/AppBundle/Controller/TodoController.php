<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/13/2016
 * Time: 9:48 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Todo;
use AppBundle\Form\TodoForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TodoController
 * @Route("/todo")
 * @Security("is_granted('ROLE_MANAGE_TODO')")
 */
class TodoController extends Controller
{
    /**
     * @Route("/list", name="todo_list")
     * //@Security("is_granted('ROLE_ADMIN')") acces denied method 3
     */
    public function listAction(){

        //acces denied method 1
        /*
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_USER')){
           throw $this->createAccessDeniedException('Get out!!');
        }
        //acces denied method 2
        //$this->denyAccessUnlessGranted('ROLE_USER');
        */

        $todos = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->findAllByUser($this->getUser());

        return $this->render('todo/index.html.twig', [
            'todos' => $todos
        ]);
    }

    /**
     * @Route("/create", name="todo_create")
     */
    public function createAction(Request $request){

        $form = $this->createForm(TodoForm::class);
        $form->handleRequest($request);


        //Process form
        if($form->isSubmitted() and $form->isValid()){

            $user = $this->getUser();
            /**
             * @var Todo $todoData
             */
            $todoData = $form->getData();
            $todoData->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($todoData);
            $em->flush();

            $this->addFlash('success',
               sprintf('Todo created! You (%s) are amazing.', $this->getUser()->getEmail())
            );
            return $this->redirectToRoute('todo_list');
        }
        return $this->render('todo/create.html.twig', ['todoForm' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="todo_edit")
     */
    public function editAction(Todo $todo, Request $request){

        $form = $this->createForm(TodoForm::class, $todo);
        $form->handleRequest($request);

        //Process form
        if($form->isSubmitted() and $form->isValid()){

            $todo = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();

            $this->addFlash('success', 'Todo edited!');
            return $this->redirectToRoute('todo_list');
        }
        ///
        return $this->render('todo/edit.html.twig', ['todoForm' => $form->createView()]);
    }

    /**
     * @Route("/details/{id}", name="todo_details")
     */
    public function detailsAction($id){

        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);

        return $this->render('todo/details.html.twig', [
            'todo' => $todo
        ]);
    }

    /**
     * @Route("/confirm_delete/{id}", name="confirm_delete")
     */
    public function confirmDeleteAction($id){
        $todo = $this->getDoctrine()
            ->getRepository('AppBundle:Todo')
            ->find($id);

        return $this->render('todo/confirm_delete.html.twig', [
            'todo' => $todo
        ]);
    }

    /**
     * @Route("/delete/{id}", name="todo_delete")
     */
    public function deleteAction($id){

        $em = $this->getDoctrine()->getManager();
        $todo = $em->getRepository(Todo::class)
            ->find($id);
        $em->remove($todo);
        $em->flush();

        $this->addFlash('success', 'Todo deleted!');
        return $this->redirectToRoute('todo_list');

    }

}