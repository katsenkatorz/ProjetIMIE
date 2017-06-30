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
        $paramRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $parameters = $paramRepo->getParameters();

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
        $paramRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $parameters = $paramRepo->getParameters();

        return $this->json('/admin/putParameters');
    }

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
        if (!is_null($jobId)) {
            // On créer un tableau de résultat
            $JobPersonnalityResult = [];

            // Pour chaque type de personnalité
            forEach ($personnalityTypes as $personnalityType) {
                // On récupère sont id et sont nom
                $pTid = $personnalityType->getId();
                $pTName = $personnalityType->getName();

                // Et on stocke la value
                $JobPersonnalityResult[$pTid] = (int)$request->get($pTName . "Value");
            }

            // Créer les jobPersonnality
            forEach ($JobPersonnalityResult as $id => $value) {
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
        if ($formJob->isSubmitted() && $formJob->isValid()) {
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
        foreach ($jobPersonnalities as $key => $value) {
            $arrayForm[$key] = $this->createForm(JobPersonnalityType::class);
        }

        // Actualisation des jobPersonnalités
        $jobPersonnalities = $JobPersonnalityRepository->getPersonnalityTypesByJobId($idJob);

        // Préparation des formulaires pour les vues
        foreach ($arrayForm as $key => $form) {
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

        if (!$bool) {
            return $this->json(["message" => "Erreur put renvois false"]);
        }
        return $this->json(["message" => "Modification bien effectuer"]);
    }

    /**
     * Affiche la page de gestion des types de personnalités
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function personnalityTypeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        // Création du formulaire pour les Type de personnalité
        $formPT = $this->createForm(PersonnalityTypeType::class);

        // Récupération de la requête
        $formPT->handleRequest($request);

        // Traitement pour la création de Type de personnalité
        if ($formPT->isSubmitted() && $formPT->isValid()) {
            $PersonnalityTypeRepo->postPersonnalityType($formPT["name"]->getData(), $formPT["personnalityType"]->getData(), $formPT["opposedPersonnalityType"]->getData());
        }

        return $this->render("AdminBundle:app:personnalityType.html.twig", [
            "formPT" => $formPT->createView(),
        ]);
    }


    /**
     * Affiche la page de gestion des questions
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function questionsAction(Request $request)
    {
        $QuestionRepo = $this->getDoctrine()->getRepository("AdminBundle:Question");
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        $questions = $QuestionRepo->getQuestions();
        $arrayFormResponse = [];
        foreach ($questions as $question) {
            $arrayFormResponse[$question->getLabel()] = $this->createForm(ResponseType::class);
        }


        $formQuestion = $this->createForm(QuestionType::class);


        $formQuestion->handleRequest($request);

        foreach ($arrayFormResponse as $formResponse) {
            $formResponse->handleRequest($request);
        }

        if ($formQuestion->isSubmitted() && $formQuestion->isValid()) {
            $QuestionRepo->postQuestion($formQuestion["label"]->getData());
        }

        foreach ($arrayFormResponse as $formResponse) {
            if ($formResponse->isSubmitted() && $formResponse->isValid()) {
                $value = $formResponse['value']->getData();
                $question = $QuestionRepo->getQuestionById($formResponse["question"]->getData());
                $image = $formResponse['image']->getData();
                $label = $formResponse["label"]->getData();
                $personnalityType = $PersonnalityTypeRepo->getPersonnalityTypeById($request->get('personnalityType'));

                $ResponseRepo->postResponse($label, $value, $image, $question, $personnalityType);
            }
        }

        $questions = $QuestionRepo->getQuestions();
        $personnalityTypes = $PersonnalityTypeRepo->getPersonnalityTypes();

        foreach ($arrayFormResponse as $key => $value) {
            $arrayFormResponse[$key] = $value->createView();
        }

        return $this->render("AdminBundle:app:questions.html.twig", [
            "formQuestion" => $formQuestion->createView(),
            "arrayFormResponse" => $arrayFormResponse,
            "questions" => $questions,
            "personnalityTypes" => $personnalityTypes
        ]);
    }

    public function responsesAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $questionId = $request->get('questionId');
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        $personnalityTypes = $PersonnalityTypeRepo->getPersonnalityTypes();
        $responses = $ResponseRepo->getResponseByQuestionId($questionId);

        return $this->json($this->renderView("AdminBundle:app:response.html.twig", [
            "responses" => $responses,
            "personnalityTypes" => $personnalityTypes
        ]));
    }

    public function responseUpdateAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        $value = $request->get('value');
        $image = $request->get('image');
        $label = $request->get('label');
        $personnalityType = $PersonnalityTypeRepo->getPersonnalityTypeById($request->get('personnalityType'));
        $responseId = $request->get('responseId');

        if (!is_null($value) && !is_null($image) && !is_null($label) && !is_null($personnalityType) && !is_null($responseId)) {
            $ResponseRepo->putResponse($responseId, $label, $value, $image, $personnalityType);
        }

        return $this->json(["message" => "Modification bien effectuer"]);
    }
}
