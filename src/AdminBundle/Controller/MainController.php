<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Job;
use AdminBundle\Entity\JobPersonnality;
use AdminBundle\Entity\Temperament;
use AdminBundle\Form\JobTemperamentType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\TemperamentType;
use AdminBundle\Form\QuestionType;
use AdminBundle\Form\ResponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * Affiche la page d'acceuil de l'administration
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction(Request $request)
    {
        return $this->render('AdminBundle:app:home.html.twig');
    }

    /**
     * Affiche la page de gestion des utilisateurs
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAction(Request $request)
    {
        $userRepo = $this->getDoctrine()->getRepository("UserBundle:User");
        $upgrade = $request->get("up");
        $downgrade = $request->get('down');
        $userId = $request->get('userId');

        if (isset($upgrade) && $upgrade === "Upgrade user") {
            $userRepo->upgradeUserToAdmin($userId);
        }

        if (isset($downgrade) && $downgrade === "Downgrade user") {
            $userRepo->downgradeAdminToUser($userId);
        }

        $users = $userRepo->getUsers();

        return $this->render('AdminBundle:app:user.html.twig', [
            "users" => $users
        ]);
    }

    /**
     * Affiche la page des parametres utilisateur
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function parametersAction(Request $request)
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $parameters = $ParamRepo->getParameters();

        foreach ($parameters as $parameter) {

            $arrayParam[$parameter->getLabel()] = [
                $parameter->getLabel() . "id",
                $parameter->getLabel(),
                $parameter->getLabel() . "Value"
            ];
        }

        return $this->render('AdminBundle:app:parameter.html.twig', [
            "parameters" => $parameters
        ]);
    }

    /**
     * Modifie la page des parametres utilisateur
     *
     * @param Request $request
     * @return jsonResponse
     */

    public function updateParametersAction(Request $request)
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $parameters = $ParamRepo->getParameters();

        $parameterId = $request->attributes->get('idParameter');
        $label = $request->get('label');
        $value = $request->get('value');

        if(!is_null($parameterId) && !is_null($label) && !is_null($value))
        {
            $ParamRepo->putParameters($parameterId, $label, $value);
        }


        return $this->json(["message" => "Modification(s) bien effectuée(s)"]);
    }
}
