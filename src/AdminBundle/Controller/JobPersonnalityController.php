<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Job;
use AdminBundle\Entity\JobPersonnality;
use AdminBundle\Entity\PersonnalityType;
use AdminBundle\Form\JobPersonnalityType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\PersonnalityTypeType;
use AdminBundle\Form\QuestionType;
use AdminBundle\Form\ResponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobPersonnalityController extends Controller
{
    /**
     * Affiche la page de gestion des jobPersonnalities
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
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
        if (!is_null($jobId))
        {
            // On créer un tableau de résultat
            $JobPersonnalityResult = [];

            // Pour chaque type de personnalité
            forEach ($personnalityTypes as $personnalityType)
            {
                // On récupère sont id et sont nom
                $pTid = $personnalityType->getId();
                $pTName = $personnalityType->getName();

                // Et on stocke la value
                $JobPersonnalityResult[$pTid] = (int)$request->get($pTName . "Value");
            }

            // Créer les jobPersonnality
            forEach ($JobPersonnalityResult as $id => $value)
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

    /**
     * Affiche la page de gestion des jobs
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jobsAction(Request $request)
    {
        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        // Création du formulaire pour créer un job
        $job = new Job();
        $formJob = $this->createForm(JobType::class, $job);

        $formJob->handleRequest($request);

        // Traitement pour la création de job
        if ($formJob->isSubmitted() && $formJob->isValid())
        {
            $JobRepository->postJob($formJob['name']->getData(), $formJob['description']->getData(), $formJob['maxSalary']->getData(), $formJob['minSalary']->getData());
        }

        // Récupération des jobs
        $jobs = $JobRepository->getJobs();

        return $this->render("AdminBundle:app:jobs.html.twig", [
            "formJob" => $formJob->createView(),
            "jobs" => $jobs,
        ]);
    }

    /**
     * Affiche la vue partielle qui est integrer dans jobs avec un appel ajax
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function jobAction(Request $request)
    {
        // Récupération des répository
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $JobPersonnalityRepository = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Récupération de l'id du job passé dans l'url
        $idJob = $request->attributes->get("idJob");

        // Récupération du job gràce à l'id
        $job = $JobRepository->getJobById($idJob);

        // Récupération des jobPersonnality correspondant au job
        $jobPersonnalities = $JobPersonnalityRepository->getPersonnalityTypesByJobId($idJob);

        // Initialisation du tableau de formulaire
        $arrayForm = [];

        // Création des formulaires dans le tableau
        foreach ($jobPersonnalities as $key => $value)
        {
            $arrayForm[$key] = $this->createForm(JobPersonnalityType::class);
        }

        // Actualisation des jobPersonnalités
        $jobPersonnalities = $JobPersonnalityRepository->getPersonnalityTypesByJobId($idJob);

        // Préparation des formulaires pour les vues
        foreach ($arrayForm as $key => $form)
        {
            $arrayForm[$key] = $form->createView();
        }

        return $this->json($this->renderView("AdminBundle:app:job.html.twig", [
            "forms" => $arrayForm,
            "job" => $job,
            "jobPersonnalities" => $jobPersonnalities
        ]));
    }

    /**
     * Disponible seulement de post, enregistre les modifications envoyer en ajax venant de la page jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveJobPersonnalityAction(Request $request)
    {
        // Récupération des éléments du formulaire
        $jobId = $request->get('job');
        $personnalityTypeId = $request->get('personnalityType');
        $value = $request->get('value');

        // Récupération du répository
        $JobPersonnalityRepository = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Sauvegarde de la modification
        $bool = $JobPersonnalityRepository->putJobPersonnalityByPtidAndJobId($value, $jobId, $personnalityTypeId);

        if (!$bool)
        {
            return $this->json(["message" => "Erreur put renvois false"]);
        }
        return $this->json(["message" => "Modification bien effectuer"]);
    }

	//TODO VAR dump ???
    public function getPersonnalityTypeAction(Request $request)
    {
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        var_dump($request->attributes->get("idPersonnalityType"));

        $personnalityType = $PersonnalityTypeRepo->getPersonnalityTypeById($request->attributes->get("idPersonnalityType"));

        $json = ["personnalityType" => $personnalityType->getPersonnalityType(), "opposedPersonnalityType" => $personnalityType->getOpposedPersonnalityType()];

        $response = new Response(json_encode($json), "200");
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }
}
