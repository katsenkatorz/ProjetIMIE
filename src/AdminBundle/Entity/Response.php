<?php

namespace AdminBundle\Entity;

/**
 * RESPONSE
 */
class Response
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var int
     */
    private $value;

    /**
     * @var string
     */
    private $image;

    /**
     * @var Question
     */
    private $question;

    /**
     * @var Temperament
     */
    private $temperament;

    /**
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param Question $question
     **/
    public function setQuestion(Question $question)
    {
        $this->question = $question;
        return $this;
    }

    /**
     * @return Temperament
     */
    public function getTemperament()
    {
        return $this->temperament;
    }

    /**
     * @param Temperament $temperament
     **/
    public function setTemperament(Temperament $temperament)
    {
        $this->temperament = $temperament;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Response
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Response
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Response
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}

