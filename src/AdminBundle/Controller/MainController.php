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

    public function userAction(Request $request)
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

        return $this->render('AdminBundle:app:user.html.twig', [
            "users" => $users
        ]);
    }

    public function jobPersonnalityAction(Request $request)
    {
        // Récupération des répository et entityManager
        $em = $this->getDoctrine()->getManager();
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");
        $JobRepo = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $JobPersonnalityRepo = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Récupération des jobs et type de personnalités pour les select
        $jobs = $JobRepo->getJobs();
        $personnalityTypes = $PersonnalityTypeRepo->getPersonnalityTypes();


        // Récupération du job sélectionner en select
        $jobId = $request->get('jobId');
        $jobFromForm = $JobRepo->getJobById($jobId);

        // Si le formulaire est soumis
        if(!is_null($jobId))
        {
            // On créer un tableau de résultat
            $JobPersonnalityResult = [];

            // Pour chaque type de personnalité
            forEach($personnalityTypes as $personnalityType)
            {
                // On récupère sont id et sont nom
                $pTid = $personnalityType->getId();
                $pTName = $personnalityType->getName();

                // Et on stocke la value
                $JobPersonnalityResult[$pTid] = (int)$request->get($pTName."Value");
            }

            // Créer les jobPersonnality
            forEach($JobPersonnalityResult as $id => $value)
            {
                $pt = $PersonnalityTypeRepo->getPersonnalityTypeById($id);
                $JobPersonnalityRepo->postJobPersonnality($JobPersonnalityResult[$id], $jobFromForm, $pt);
            }
        }

        return $this->render("AdminBundle:app:jobPersonnality.html.twig", [
            "personnalityTypes" => $personnalityTypes,
            "jobs" => $jobs
        ]);
    }

    public function jobsAction(Request $request)
    {
        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $em = $this->getDoctrine()->getManager();

        // Création du formulaire pour créer un job
        $job = new Job();
        $formJob = $this->createForm(JobType::class, $job);

        $formJob->handleRequest($request);

        // Traitement pour la création de job
        if ($formJob->isSubmitted() && $formJob->isValid())
        {
            $job = $formJob->getData();

            $em->persist($job);
            $em->flush();
        }

        // Récupération des jobs
        $jobs = $JobRepository->getJobs();

        return $this->render("AdminBundle:app:jobs.html.twig", [
            "formJob" => $formJob->createView(),
            "jobs" => $jobs,
        ]);
    }

    public function jobAction(Request $request)
    {
        // Récupération des répository
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $JobPersonnalityRepository = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Récupération de l'id du job passé dans l'url
        $idJob = $request->attributes->get("idJob");

        // Récupération du job gràce à l'id
        $job =$JobRepository->getJobById($idJob);

        // Récupération des jobPersonnality correspondant au job
        $jobPersonnalities = $JobPersonnalityRepository->getPersonnalityTypesByJobId($idJob);

        // Initialisation du tableau de formulaire
        $arrayForm = [];

        // Création des formulaires dans le tableau
        foreach ($jobPersonnalities as $key => $value)
        {
            $arrayForm[$key] = $this->createForm(JobPersonnalityType::class);
        }


        // Utilisation des formulaires
        foreach ($arrayForm as $key => $form)
        {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid())
            {
                $value = $form->getData()->getValue();
                $jobId = $form->getData()->getJob();
                $ptId = $form->getData()->getPersonnalityType();

                $JobPersonnalityRepository->putJobPersonnalityByPtidAndJobId($value, $jobId, $ptId);
            }
        }

        // Actualisation des jobPersonnalités
        $jobPersonnalities = $JobPersonnalityRepository->getPersonnalityTypesByJobId($idJob);

        // Préparation des formulaires pour les vues
        foreach ($arrayForm as $key => $form)
        {
            $arrayForm[$key] = $form->createView();
        }

        return $this->render("AdminBundle:app:job.html.twig", [
            "forms" => $arrayForm,
            "job" => $job,
            "jobPersonnalities" => $jobPersonnalities
        ]);
    }

    public function personnalityTypeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // Création du formulaire pour les Type de personnalité
        $pt = new PersonnalityType();
        $formPT = $this->createForm(PersonnalityTypeType::class, $pt);

        // Récupération de la requête
        $formPT->handleRequest($request);

        // Traitement pour la création de Type de personnalité
        if($formPT->isSubmitted() && $formPT->isValid())
        {
            $pt = $formPT->getData();

            $em->persist($pt);
            $em->flush();
        }

        return $this->render("AdminBundle:app:personnalityType.html.twig", [
            "formPT" => $formPT->createView(),
        ]);
    }
}
