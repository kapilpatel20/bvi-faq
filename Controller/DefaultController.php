<?php

namespace BviFaqBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BviFaqBundle:Default:index.html.twig');
    }
}
