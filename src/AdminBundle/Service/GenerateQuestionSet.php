<?php

namespace AdminBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class GenerateQuestionSet
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var array
     */
    private $questionSet;

    /**
     * GenerateQuestionSet constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->questionSet = [];
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return array
     */
    public function getQuestionSet()
    {
        $this->generate();
        $this->orderBringuer();
        return $this->questionSet;
    }

    /**
     * Assigne les questions mélangées dans un tableau associatif
     */
    private function generate()
    {
        $temperaments = $this->entityManager->getRepository("AdminBundle:Temperament")->getTemperamentsWithQueryBuilder();

        foreach ($temperaments as $temperament)
        {
            $questions = $this->processQuestion($temperament['id']);

            array_push($this->questionSet, new QuestionSet($temperament, $questions));
        }
    }

    /**
     * Récupère les questions en fonction d'un tempérament, les renvoie mélangées par tranche de 10
     *
     * @param $temperamentId
     * @return array
     */
    private function processQuestion($temperamentId)
    {
        $questions = $this->entityManager->getRepository('AdminBundle:Question')->getQuestionByTemperamentId($temperamentId);

        shuffle($questions);

        $questions = array_slice($questions, 0, 10);

        return $questions;
    }

    /**
     * Permet de définir un nombre pour chaque question
     */
    private function orderBringuer()
    {
        // Récupère le tableau de question
        $questionSets = $this->questionSet;

        // On initialise un compteur
        $i = 0;

        // On passe dans chaque object questionSet
        foreach($questionSets as $questionSet)
        {
            // On initialise un tableau vide
            $array = [];

            // On parcourt le tableau de question de questionSet
            foreach($questionSet->getQuestions() as $question)
            {
                // On ajoute au tableau vide les questions avec un nombre incrémenté
                $array[] = ["question" => $question,"questionNumber" => $i++];
            }

            // On ajoute le nouveau tableau de question avec des nombres dans la classe questionSet
            $questionSet->setQuestions($array);
        }
    }
}

?>