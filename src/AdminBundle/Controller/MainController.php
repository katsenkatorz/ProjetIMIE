<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function homeAction()
    {
        $userRepo = $this->getDoctrine()->getRepository("UserBundle:User");

        $users = $userRepo->getUsers();

        return $this->render('AdminBundle:app:home.html.twig', [
            "users" => $users
        ]);
    }
}
