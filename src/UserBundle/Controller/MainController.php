<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function homeAction()
    {
        return $this->render('UserBundle:app:home.html.twig');
    }
}
