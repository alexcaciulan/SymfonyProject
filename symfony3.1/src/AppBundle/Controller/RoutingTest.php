<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/7/2016
 * Time: 12:47 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RoutingTest extends Controller
{
    /**
     * @Route(
     *     "/articles/{_locale}/{year}/{slug}.{_format}",
     *     defaults={"_format": "html"},
     *     requirements={
     *         "_locale": "en|fr",
     *         "_format": "html|rss",
     *         "year": "\d+"
     *     }
     * )
     */
    public function showAction($_locale, $year, $slug)
    {
        $pageLink = $_locale.'/'.$year.'/'.$slug.'.html';

        return $this->render('main/routing_test.html.twig', ['pageLink' => $pageLink]);
    }
}