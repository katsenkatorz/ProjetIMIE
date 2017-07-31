<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Job;
use AdminBundle\Entity\JobPersonnality;
use AdminBundle\Entity\Response;
use AdminBundle\Entity\Temperament;
use AdminBundle\Form\JobTemperamentType;
use AdminBundle\Form\JobType;
use AdminBundle\Form\TemperamentType;
use AdminBundle\Form\QuestionType;
use AdminBundle\Form\ResponseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;

class QuestionController extends Controller
{
    /**
     * Affiche la page de gestion des questions
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function questionsAction(Request $request)
    {
        // Récupération des répository
        $QuestionRepo = $this->getDoctrine()->getRepository("AdminBundle:Question");
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);

        $formQuestion = $this->createForm(QuestionType::class);
        $formResponse = $this->createForm(ResponseType::class);

        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid())
        {
            $QuestionRepo->postQuestion($formQuestion, $request->get("temperament"));
        }

        $questions = $QuestionRepo->getQuestions();


        $temperaments = $TemperamentRepo->getTemperaments();

        return $this->render("AdminBundle:app:questions.html.twig", [
            "formQuestion" => $formQuestion->createView(),
            "formResponse" => $formResponse->createView(),
            "questions" => $questions,
            "temperaments" => $temperaments,
            "imageParam" => $imageParam
        ]);
    }

    /**
     * Permet de modifier une question
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function questionPutAction(Request $request)
    {
        $QuestionRepo = $this->getDoctrine()->getRepository("AdminBundle:Question");

        $questionId = $request->attributes->get('idQuestion');
        $temperament = $request->get("temperament");
        $formData = $this->createForm(QuestionType::class);

        $formData->handleRequest($request);

        if($formData->isSubmitted() && $formData->isValid())
        {
            $result = $QuestionRepo->putQuestion($questionId, $formData, $temperament);

            if($result)
                return $this->json(["message" => "Le changement de la question est bien effectué", "name" => $result->getLabel()]);

            return $this->json(["message" => "Il y a eu une erreur dans le changement de la question", "name" => $result->getLabel()]);
        }

        return $this->json(["message" => "Erreur lors de la modification"]);
    }

    /**
     * Permet de supprimer une question
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function questionDeleteAction(Request $request)
    {
        $QuestionRepo = $this->getDoctrine()->getRepository("AdminBundle:Question");

        $questionId = $request->attributes->get('idQuestion');

        if(!is_null($questionId))
        {
            $QuestionRepo->deleteQuestion($questionId);
            return $this->json(["message" => "La suppression de la question est bien effectué"]);
        }

        return $this->json(["message" => "Erreur pendant la suppression"]);
    }

    /**
     * Génère la vue partielle response
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function responsesAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");
        $questionId = $request->attributes->get('idQuestion');

        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);
        $temperaments = $TemperamentRepo->getTemperaments();
        $responses = $ResponseRepo->getResponseByQuestionId($questionId);

        return $this->json($this->renderView("AdminBundle:app:response.html.twig", [
            "responses" => $responses,
            "temperaments" => $temperaments,
            "imageParam" => $imageParam
        ]));
    }

    /**
     * Permet de créer une question
     *
     * @param Request $request
     *
     */
    public function responsePostAction(Request $request)
    {
        $pathToImage = $this->get('kernel')->getRootDir().'/../web/assets/img/imageResponse/';
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $idQuestion = $request->attributes->get('idQuestion');

        $imageInfo = [
            'image' => $request->get('croppedImage'),
            'pathToImage' => $pathToImage
        ];

        $form = $this->createForm(ResponseType::class);

        $form->handleRequest($request);

        if($form->isValid() && $form->isSubmitted())
        {
            $ResponseRepo->postResponse($form, $imageInfo);

            return $this->json([
                "message" => "La création de la réponse c'est bien effectué",
                "idQuestion" => $idQuestion,
            ]);
        }

        return $this->json(["message" => "Erreur lors de la création de la réponse"]);
    }

    /**
     * Permet la mise à jours d'une réponse en ajax
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function responseUpdateAction(Request $request)
    {
        $pathToImage = $this->get('kernel')->getRootDir().'/../web/assets/img/imageResponse/';

        $imageInfo = [
            'image' => $request->get('croppedImage'),
            'pathToImage' => $pathToImage
        ];

        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");

        $responseId = $request->attributes->get('idResponse');

        $form = $this->createForm(ResponseType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $response = $ResponseRepo->putResponse($responseId, $form, $imageInfo);
            if(!$response)
                return $this->json([
                    'message' => "Problème lors de l'enregistrement, vérifier que les informations entrées soit valide."
                ]);

            return $this->json(["message" => "La modification de la réponse c'est bien effectué"]);
        }

        return $this->json(["message" => "Erreur lors de la modification de la réponse"]);
    }

    /**
     * Permet de supprimer une réponse
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function responseDeleteAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $idResponse = $request->attributes->get('idResponse');
        $response = $ResponseRepo->getResponseById($idResponse);

        if(!is_null($idResponse))
        {
            $ResponseRepo->deleteResponse($idResponse);
        }

        return $this->json(["message" => "La suppression de la question c'est bien effectué", "idQuestion" => $response->getQuestion()->getId()]);
    }
}
