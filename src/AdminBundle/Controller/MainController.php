<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Job;
use AdminBundle\Entity\JobPersonnality;
use AdminBundle\Entity\Parameters;
use AdminBundle\Entity\Temperament;
use AdminBundle\Form\JobTemperamentType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\MentionLegaleType;
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
        $questions = $this->getDoctrine()->getRepository("AdminBundle:Question")->getNumberOfQuestionByTemperament();

        return $this->render('AdminBundle:app:home.html.twig', ["questions" => $questions]);
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

        $parameters = $ParamRepo->getParametersWithoutMentionsLegales();

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

        $parameterId = $request->attributes->get('idParameter');
        $label = $request->get('label');
        $value = $request->get('value');

        if (!is_null($parameterId) && !is_null($label) && !is_null($value))
        {
            $ParamRepo->putParameter($parameterId, $label, $value);
        }


        return $this->json(["message" => "Modification(s) bien effectuée(s)"]);
    }


    /**
     * Modifie la page des mentionsLégales utilisateur
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mentionLegaleAction(Request $request)
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");
        $em = $this->getDoctrine()->getManager();

        $parameter = $ParamRepo->getParameterById(4);
        $form = $this->createForm(MentionLegaleType::class, $parameter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $parameter->setValue($form['value']->getData());

            $em->persist($parameter);
            $em->flush();
        }


        return $this->render('AdminBundle:app:mentionLegale.html.twig', [
            "parameter" => $parameter,
            "form" => $form->createView()
        ]);
    }


    /**
     * Modifie le paramètre de la taille de l'image
     * @param Request $request
     * @return JsonResponse
     */
    public function resizeImageHandlerAction(Request $request)
    {
        $paramId = $request->attributes->get('idParameter');

        $value = $request->get('value');

        $bool = $this->getDoctrine()->getRepository("AdminBundle:Parameters")->putValueOfParameterById($paramId, $value);

        if($bool)
            return $this->json(['message' => "Modification(s) bien effectuée(s)"]);

        return $this->json(['message' => "Erreur lors de la modification"]);
    }
}
