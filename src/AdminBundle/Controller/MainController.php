<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function homeAction(Request $request)
    {
        return $this->render('AdminBundle:app:home.html.twig');
    }

    public function manageUserAction(Request $request)
    {
        $userRepo = $this->getDoctrine()->getRepository("UserBundle:User");
        $upgrade = $request->get("up");
        $downgrade = $request->get('down');
        $userId = $request->get('userId');

        if(isset($upgrade) && $upgrade === "Upgrade user")
        {
            $userRepo->upgradeUserToAdmin($userId);
        }

        if(isset($downgrade) && $downgrade === "Downgrade user")
        {
            $userRepo->downgradeAdminToUser($userId);
        }

        $users = $userRepo->getUsers();

        return $this->render('AdminBundle:app:manageUser.html.twig', [
            "users" => $users
        ]);
    }
}
