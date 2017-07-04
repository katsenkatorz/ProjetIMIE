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

        $formQuestion = $this->createForm(QuestionType::class);

        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid())
        {
            $QuestionRepo->postQuestion($formQuestion["label"]->getData());
        }

        $questions = $QuestionRepo->getQuestions();

        $temperaments = $TemperamentRepo->getTemperaments();

        return $this->render("AdminBundle:app:questions.html.twig", [
            "formQuestion" => $formQuestion->createView(),
            "questions" => $questions,
            "temperaments" => $temperaments
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
        $label = $request->get('label');

        if(!is_null($questionId) && !is_null($label))
        {
            $QuestionRepo->putQuestion($questionId, $label);
            return $this->json(["message" => "Modification bien effectuer"]);
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
        $questionId = $request->attributes->get('idQuestion');
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $temperaments = $TemperamentRepo->getTemperaments();
        $responses = $ResponseRepo->getResponseByQuestionId($questionId);

        return $this->json($this->renderView("AdminBundle:app:response.html.twig", [
            "responses" => $responses,
            "temperaments" => $temperaments
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
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");
        $QuestionRepo = $this->getDoctrine()->getRepository("AdminBundle:Question");

        $value = $request->get('value');
        $image = $request->get('image');
        $label = $request->get('label');
        $idQuestion = $request->attributes->get('idQuestion');
        $idTemperament = $request->get('temperament');

        $question = $QuestionRepo->getQuestionById($idQuestion);
        $temperament = $TemperamentRepo->getTemperamentById($idTemperament);

        if(!is_null($value) && !is_null($image) && !is_null($label) && !is_null($temperament) && !is_null($question))
        {
            $ResponseRepo->postResponse($label, $value, $image, $question, $temperament);

            return $this->json([
                "message" => "Création bien effectuer",
                "idQuestion" => $idQuestion,
                "test" => $idTemperament
            ]);
        }

        return $this->json(['message' => "Erreur lors de l'ajout de réponse"]);
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
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $value = $request->get('value');
        $image = $request->get('image');
        $label = $request->get('label');
        $responseId = $request->attributes->get('idResponse');
        $temperament = $TemperamentRepo->getTemperamentById($request->get('temperament'));

        if(!is_null($value) && !is_null($image) && !is_null($label) && !is_null($temperament) && !is_null($responseId))
        {
            $ResponseRepo->putResponse($responseId, $label, $value, $image, $temperament);
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
        $idResponse = $request->attributes->get('idResponse');
        $response = $ResponseRepo->getResponseById($idResponse);

        if(!is_null($idResponse))
        {
            $ResponseRepo->deleteResponse($idResponse);
        }

        return $this->json(["message" => "Suppression bien effectuer", "idQuestion" => $response->getQuestion()->getId()]);
    }
}
