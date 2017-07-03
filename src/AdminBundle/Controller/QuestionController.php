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
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        $formQuestion = $this->createForm(QuestionType::class);

        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid())
        {
            $QuestionRepo->postQuestion($formQuestion["label"]->getData());
        }

        $questions = $QuestionRepo->getQuestions();

        $personnalityTypes = $PersonnalityTypeRepo->getPersonnalityTypes();

        return $this->render("AdminBundle:app:questions.html.twig", [
            "formQuestion" => $formQuestion->createView(),
            "questions" => $questions,
            "personnalityTypes" => $personnalityTypes
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

        $questionId = $request->get('question');
        $label = $request->get('label');

        if(!is_null($questionId) && !is_null($label))
        {
            $QuestionRepo->putQuestion($questionId, $label);
        }

        return $this->json(["message" => "Modification bien effectuer"]);
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

        $questionId = $request->get('question');

        if(!is_null($questionId))
        {
            $QuestionRepo->deleteQuestion($questionId);
            return $this->json(["message" => "Suppression bien effectuer"]);
        }

        return $this->json(["message" => "Error lors de la suppression"]);
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
        $questionId = $request->get('questionId');
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        $personnalityTypes = $PersonnalityTypeRepo->getPersonnalityTypes();
        $responses = $ResponseRepo->getResponseByQuestionId($questionId);

        return $this->json($this->renderView("AdminBundle:app:response.html.twig", [
            "responses" => $responses,
            "personnalityTypes" => $personnalityTypes
        ]));
    }

    /**
     * Permet de créer une question
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function responsePostAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");
        $QuestionRepo = $this->getDoctrine()->getRepository("AdminBundle:Question");


        $value = $request->get('value');
        $image = $request->get('image');
        $label = $request->get('label');
        $question = $QuestionRepo->getQuestionById($request->get('question'));
        $personnalityType = $PersonnalityTypeRepo->getPersonnalityTypeById($request->get('personnalityType'));

        if(!is_null($value) && !is_null($image) && !is_null($label) && !is_null($personnalityType) && !is_null($question))
        {
            $ResponseRepo->postResponse($label, $value, $image, $question, $personnalityType);
        }

        return $this->json([
            "message" => "Création bien effectuer",
            "idQuestion" => $request->get('question')
        ]);
    }

    /**
     * Permet la mise à jours d'une réponse en ajax
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function responseUpdateAction(Request $request)
    {
        $ResponseRepo = $this->getDoctrine()->getRepository("AdminBundle:Response");
        $PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

        $value = $request->get('value');
        $image = $request->get('image');
        $label = $request->get('label');
        $responseId = $request->get('responseId');
        $personnalityType = $PersonnalityTypeRepo->getPersonnalityTypeById($request->get('personnalityType'));

        if(!is_null($value) && !is_null($image) && !is_null($label) && !is_null($personnalityType) && !is_null($responseId))
        {
            $ResponseRepo->putResponse($responseId, $label, $value, $image, $personnalityType);
            return $this->json(["message" => "Modification bien effectuer"]);
        }

        return $this->json(["message" => "Erreur lors de la modification"]);
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
        $idResponse = $request->get('responseId');
        $response = $ResponseRepo->getResponseById($idResponse);

        if(!is_null($idResponse))
        {
            $ResponseRepo->deleteResponse($idResponse);
        }

        return $this->json(["message" => "Suppression bien effectuer", "idQuestion" => $response->getQuestion()->getId()]);
    }
}
