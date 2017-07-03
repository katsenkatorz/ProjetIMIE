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

        $parameterId = $request->get('parameterId');
        $label = $request->get('label');
        $value = $request->get('value');

        if(!is_null($parameterId) && !is_null($label) && !is_null($value))
        {
            $ParamRepo->putParameters($parameterId, $label, $value);
        }


        return $this->json(["message" => "Modification(s) bien effectuée(s)"]);
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
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");
        $JobRepo = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $JobPersonnalityRepo = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Récupération des jobs et type de personnalités pour les select
        $jobs = $JobRepo->getJobs();
        $temperaments = $TemperamentRepo->getTemperaments();


        // Récupération du job sélectionner en select
        $jobId = $request->get('jobId');
        $jobFromForm = $JobRepo->getJobById($jobId);

        // Si le formulaire est soumis
        if (!is_null($jobId)) {
            // On créer un tableau de résultat
            $JobPersonnalityResult = [];

            // Pour chaque type de personnalité
            forEach ($temperaments as $temperament) {
                // On récupère sont id et sont nom
                $pTid = $temperament->getId();
                $pTName = $temperament->getName();

                // Et on stocke la value
                $JobPersonnalityResult[$pTid] = (int)$request->get($pTName . "Value");
            }

            // Créer les jobPersonnality
            forEach ($JobPersonnalityResult as $id => $value) {
                $pt = $TemperamentRepo->getTemperamentById($id);
                $JobPersonnalityRepo->postJobPersonnality($JobPersonnalityResult[$id], $jobFromForm, $pt);
            }
        }

        return $this->render("AdminBundle:app:jobPersonnality.html.twig", [
            "temperaments" => $temperaments,
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
        $jobPersonnalities = $JobPersonnalityRepository->getTemperamentsByJobId($idJob);

        // Initialisation du tableau de formulaire
        $arrayForm = [];

        // Création des formulaires dans le tableau
        foreach ($jobPersonnalities as $key => $value) {
            $arrayForm[$key] = $this->createForm(JobTemperamentType::class);
        }

        // Actualisation des jobPersonnalités
        $jobPersonnalities = $JobPersonnalityRepository->getTemperamentsByJobId($idJob);

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
        $temperamentId = $request->get('temperament');
        $value = $request->get('value');

        // Récupération du répository
        $JobPersonnalityRepository = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality");

        // Sauvegarde de la modification
        $bool = $JobPersonnalityRepository->putJobPersonnalityByPtidAndJobId($value, $jobId, $temperamentId);

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
    public function temperamentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        // Création du formulaire pour les Type de personnalité
        $formPT = $this->createForm(TemperamentType::class);

        // Récupération de la requête
        $formPT->handleRequest($request);

        // Traitement pour la création de Type de personnalité
        if ($formPT->isSubmitted() && $formPT->isValid()) {
            $TemperamentRepo->postTemperament($formPT["name"]->getData(), $formPT["temperament"]->getData(), $formPT["opposedTemperament"]->getData());
        }

        return $this->render("AdminBundle:app:temperament.html.twig", [
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
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

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
                $temperament = $TemperamentRepo->getTemperamentById($request->get('temperament'));

                $ResponseRepo->postResponse($label, $value, $image, $question, $temperament);
            }
        }

        $questions = $QuestionRepo->getQuestions();
        $temperaments = $TemperamentRepo->getTemperaments();

        foreach ($arrayFormResponse as $key => $value) {
            $arrayFormResponse[$key] = $value->createView();
        }

        return $this->render("AdminBundle:app:questions.html.twig", [
            "formQuestion" => $formQuestion->createView(),
            "arrayFormResponse" => $arrayFormResponse,
            "questions" => $questions,
            "temperaments" => $temperaments
        ]);
    }

    public function responsesAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $questionId = $request->get('questionId');
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $temperaments = $TemperamentRepo->getTemperaments();
        $responses = $ResponseRepo->getResponseByQuestionId($questionId);

        return $this->json($this->renderView("AdminBundle:app:response.html.twig", [
            "responses" => $responses,
            "temperaments" => $temperaments
        ]));
    }

    public function responseUpdateAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $value = $request->get('value');
        $image = $request->get('image');
        $label = $request->get('label');
        $temperament = $TemperamentRepo->getTemperamentById($request->get('temperament'));
        $responseId = $request->get('responseId');

        if (!is_null($value) && !is_null($image) && !is_null($label) && !is_null($temperament) && !is_null($responseId)) {
            $ResponseRepo->putResponse($responseId, $label, $value, $image, $temperament);
        }

        return $this->json(["message" => "Modification bien effectuer"]);
    }
}
