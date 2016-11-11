<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/19/2016
 * Time: 10:24 AM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepageAction(){

        $error = false;
        if($error==false ) {
           // throw new \Exception('aaaaa');
            //throw $this->createNotFoundException('Tha page doesn\'t exists' );
        }

        $articles = $this->getDoctrine()
            ->getRepository('AppBundle:Article')
            ->findAllOrderByLatest();
        //dump($articles);exit();

        return $this->render('main/homepage.html.twig', ['articles' => $articles]);
    }


}