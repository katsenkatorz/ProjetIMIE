<?php

namespace TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homeAction()
    {
        return $this->render('TestBundle:app:home.html.twig');
    }
    public function formOneAction()
    {
        return $this->render('TestBundle:app:formOne.html.twig');
    }
    public function formTwoAction()
    {
        return $this->render('TestBundle:app:formTwo.html.twig');
    }
}
