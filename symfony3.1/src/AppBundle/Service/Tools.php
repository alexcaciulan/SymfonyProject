<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 10/20/2016
 * Time: 4:07 PM
 */

namespace AppBundle\Service;


class Tools
{

    public function cutString($string, $lenght){
        return (strlen($string) > $lenght) ? substr($string,0,$lenght-3).'..' : $string;
    }

}