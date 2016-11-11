<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/21/2016
 * Time: 9:21 AM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);
        if($form->isValid()){
            /** @var User $user */
             $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //Send email after registration
            $name = $user->getFirstName() .' '. $user->getLastName();
            $message = \Swift_Message::newInstance()
                ->setSubject('Hello '. $user->getEmail())
                ->setFrom('send@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'Emails/registration.html.twig',
                        array('name' => $name)
                    ),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($message);

            //Add success messafe
            $this->addFlash('success', 'Welcome '.$user->getEmail());

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
        }

        return $this->render('user/register.html.twig', [
            'registerForm' => $form->createView()
        ]);
    }
}