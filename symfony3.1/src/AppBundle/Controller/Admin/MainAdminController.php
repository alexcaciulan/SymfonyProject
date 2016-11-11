<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 9/21/2016
 * Time: 11:06 AM
 */

namespace AppBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class MainAdminController
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class MainAdminController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     */
    public function indexAction(){

        return $this->render("admin/homepage.html.twig");

    }
}