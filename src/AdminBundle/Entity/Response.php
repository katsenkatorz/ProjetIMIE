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
     * @var PersonnalityType
     */
    private $personnalityType;

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
     * @return PersonnalityType
     */
    public function getPersonnalityType()
    {
        return $this->personnalityType;
    }

    /**
     * @param PersonnalityType $personnalityType
     **/
    public function setPersonnalityType(PersonnalityType $personnalityType)
    {
        $this->personnalityType = $personnalityType;
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

