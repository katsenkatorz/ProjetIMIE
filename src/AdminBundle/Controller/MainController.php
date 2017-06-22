<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Job;
use AdminBundle\Entity\JobPersonnality;
use AdminBundle\Entity\PersonnalityType;
use AdminBundle\Form\JobPersonnalityType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\PersonnalityTypeType;
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

        if (isset($upgrade) && $upgrade === "Upgrade user")
        {
            $userRepo->upgradeUserToAdmin($userId);
        }

        if (isset($downgrade) && $downgrade === "Downgrade user")
        {
            $userRepo->downgradeAdminToUser($userId);
        }

        $users = $userRepo->getUsers();

        return $this->render('AdminBundle:app:manageUser.html.twig', [
            "users" => $users
        ]);
    }

    public function manageJobPersonnalityAction(Request $request)
    {
        // Récupération des répository et entityManager
        $em = $this->getDoctrine()->getManager();
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");
        $JobRepo = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $JobPersonnalityRepo = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Création du formulaire pour les job
        $job = new Job();
        $formJob = $this->createForm(JobType::class, $job);

        // Création du formulaire pour les Type de personnalité
        $pt = new PersonnalityType();
        $formPT = $this->createForm(PersonnalityTypeType::class, $pt);

        // Récupétation de la requête pour les deux précédents formulaires
        $formJob->handleRequest($request);
        $formPT->handleRequest($request);

        // Traitement pour la création de job
        if ($formJob->isSubmitted() && $formJob->isValid())
        {
            $job = $formJob->getData();

            $em->persist($job);
            $em->flush();
        }

        // Traitement pour la création de Type de personnalité
        if($formPT->isSubmitted() && $formPT->isValid())
        {
            $pt = $formPT->getData();

            $em->persist($pt);
            $em->flush();
        }

        // Récupération des jobs et type de personnalités pour les select
        $jobs = $JobRepo->getJobs();
        $personnalityTypes = $PersonnalityTypeRepo->getPersonnalityTypes();


        // Récupération du job sélectionner en select
        $jobId = $request->get('jobId');
        $jobFromForm = $JobRepo->getJobById($jobId);


        if(!is_null($jobId))
        {
            $JobPersonnalityResult = [];

            forEach($personnalityTypes as $personnalityType)
            {
                $pTid = $personnalityType->getId();
                $pTName = $personnalityType->getName();

                $JobPersonnalityResult[$pTid] = (int)$request->get($pTName."Value");
            }

            forEach($JobPersonnalityResult as $id => $value)
            {
                $pt = $PersonnalityTypeRepo->getPersonnalityTypeById($id);
                $JobPersonnalityRepo->postJobPersonnality($JobPersonnalityResult[$id], $jobFromForm, $pt);
            }
        }

        return $this->render("AdminBundle:app:manageJobPersonnality.html.twig", [
            "formJob" => $formJob->createView(),
            "formPT" => $formPT->createView(),
            "personnalityTypes" => $personnalityTypes,
            "jobs" => $jobs
        ]);
    }
}
