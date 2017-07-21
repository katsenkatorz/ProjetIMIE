<?php

namespace AdminBundle\Service;



class QuestionSet
{
    /**
     * @var array
     */
    private $temperament;

    /**
     * @var array
     */
    private $questions;

    /**
     * QuestionSet constructor.
     * @param $temperament
     * @param $questions
     */
    public function __construct($temperament, $questions)
    {
        $this->temperament = $temperament;
        $this->questions = $questions;
    }

    /**
     * @return array
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param $questions
     * @return $this
     */
    public function setQuestions($questions)
    {
        $this->questions = $questions;
        return $this;
    }

    /**
     * @return array
     */
    public function getTemperament()
    {
        return $this->temperament;
    }

    /**
     * @param array $temperament
     * @return $this
     **/
    public function setTemperament($temperament)
    {
        $this->temperament = $temperament;
        return $this;
    }
}
?>