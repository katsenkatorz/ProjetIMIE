<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Job;
use AdminBundle\Form\JobTemperamentType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\TemperamentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JobTemperamentController extends Controller
{
    /**
     * Affiche la page de gestion des jobs
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function jobsAction()
    {
        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        // Récupération des paramètres d'image
        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);

        // Création du formulaire pour créer un job
        $formJob = $this->createForm(JobType::class);

        // Récupération des jobs
        $jobs = $JobRepository->getJobs();

        return $this->render("AdminBundle:app:jobs.html.twig", [
            "formJob" => $formJob->createView(),
            "jobs" => $jobs,
            "imageParam" => $imageParam,
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
        $JobTemperamentRepository = $this->getDoctrine()->getRepository("AdminBundle:JobTemperament");

        // Récupération de l'id du job passé dans l'url
        $idJob = $request->attributes->get("idJob");

        // Récupération du job gràce à l'id
        $job = $JobRepository->getJobById($idJob);

        // Récupération des paramètres d'image
        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);

        // Récupération des jobTemperament correspondant au job
        $jobPersonnalities = $JobTemperamentRepository->getTemperamentsByJobId($idJob);

        // Initialisation du tableau de formulaire
        $arrayForm = [];

        // Création des formulaires dans le tableau
        foreach ($jobPersonnalities as $key => $value)
        {
            $arrayForm[$key] = $this->createForm(JobTemperamentType::class)->createView();
        }

        // Actualisation des jobPersonnalités
        $jobPersonnalities = $JobTemperamentRepository->getTemperamentsByJobId($idJob);

        return $this->json($this->renderView("AdminBundle:layout:job.html.twig", [
            "forms" => $arrayForm,
            "job" => $job,
            "jobPersonnalities" => $jobPersonnalities,
            "imageParam" => $imageParam
        ]));
    }

    /**
     * Disponible seulement de post, enregistre les modifications envoyer en ajax venant de la page jobs
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveJobTemperamentAction(Request $request)
    {
        // Récupération des éléments du formulaire
        $jobId = $request->attributes->get('idJob');
        $temperamentId = $request->get('temperament');
        $value = $request->get('value');

        // Récupération du répository
        $JobTemperamentRepository = $this->getDoctrine()->getRepository("AdminBundle:JobTemperament");

        // Sauvegarde de la modification
        $bool = $JobTemperamentRepository->putJobTemperamentByTempIdAndJobId($value, $jobId, $temperamentId);

        if (!$bool)
        {
            return $this->json(["message" => "Il y a eu une erreur lors de la modification"]);
        }
        return $this->json(["message" => "La modification des tempéraments métiers est bien effectué"]);
    }


    /**
     * Affiche la page de gestion des tempéraments
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function temperamentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        // Création du formulaire pour les tempéraments
        $formPT = $this->createForm(TemperamentType::class);

        // Récupération de la requête
        $formPT->handleRequest($request);

        // Traitement pour la création de tempéraments
        if ($formPT->isSubmitted() && $formPT->isValid())
        {
            $TemperamentRepo->postTemperament($formPT["name"]->getData(), $formPT["temperament"]->getData(), $formPT["opposedTemperament"]->getData());
        }

        return $this->render("AdminBundle:app:temperament.html.twig", [
            "formPT" => $formPT->createView(),
        ]);
    }

    /**
     * Récupère les type de personnalité
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTemperamentAction(Request $request)
    {
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $temperament = $TemperamentRepo->getTemperamentById($request->attributes->get("idTemperament"));

        $firstTemperament = $temperament->getTemperament();

        $opposedTemperament = $temperament->getOpposedTemperament();

        return $this->json(["temperament" => "$firstTemperament", "opposedTemperament" => "$opposedTemperament"]);
    }

    /**
     * Supprime un job avec sont id
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteJobAction(Request $request)
    {
        $JobRepo = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $idJob = $request->attributes->get('idJob');

        if(!is_null($idJob))
        {
            $JobRepo->deleteJob($idJob);

            return $this->json(["message" => "La suppression du métier c'est bien effectué"]);
        }

        return $this->json(["message" => "Il y a eu une erreur lors de la suppression"]);
    }

    /**
     * Modifie un job
     * @param Request $request
     * @return JsonResponse
     */
    public function putJobAction(Request $request)
    {
        $JobRepo = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $idJob = $request->attributes->get('idJob');

        $form = $this->createForm(JobType::class);

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())
        {
            $pathToImage = $this->get('kernel')->getRootDir().'/../web/assets/img/imageJob/';

            $imageInfo = [
                'image' => $request->get('croppedImage'),
                'pathToImage' => $pathToImage
            ];

            $job = $JobRepo->putJob($idJob, $form, $imageInfo);

            return $this->json([
                'message'=> "La modification du métier s'est bien effectué",
                "job" => [
                    'name' => $job->getName(),
                    'description' => $job->getDescription(),
                    'maxSalary' => $job->getMaxSalary(),
                    'minSalary' => $job->getMinSalary()
                ]
            ]);
        }

        return $this->json(['message'=> "Il y a eu une erreur lors de la modification du métier"]);
    }

    /**
     * Gère l'ajout de nouveau job
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postJobAction(Request $request)
    {
        $JobRepo = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $form = $this->createForm(JobType::class);

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())
        {
            $pathToImage = $this->get('kernel')->getRootDir().'/../web/assets/img/imageJob/';

            $imageInfo = [
                'image' => $request->get('croppedImage'),
                'pathToImage' => $pathToImage
            ];

            $JobRepo->postJob($form, $imageInfo);

            return $this->json([
                'message'=> "La création du métier s'est bien effectué",
            ]);
        }

        return $this->json(['message'=> "Il y a eu une erreur lors de la création du métier"]);
    }
}
