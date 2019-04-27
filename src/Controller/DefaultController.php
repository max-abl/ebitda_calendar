<?php
/**
 * Created by PhpStorm.
 * User: maxime
 * Date: 27/04/2019
 * Time: 22:51
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{


    /**
     * @Route("/", name="app_default_index")
     */
    public function index()
    {
        return $this->render('default.html.twig');
    }

}