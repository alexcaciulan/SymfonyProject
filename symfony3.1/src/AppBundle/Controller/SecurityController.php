<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/18/2016
 * Time: 4:16 PM
 */

namespace AppBundle\Controller;


use AppBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(){
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render(
            'security/login.html.twig',
            array(
                // last username entered by the user
                'loginForm' => $form->createView(),
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction(){
        throw  new \Exception('This should not be reached');
    }
}