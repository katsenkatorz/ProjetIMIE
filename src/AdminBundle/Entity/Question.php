<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * QUESTION
 */
class Question
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
     * @var ArrayCollection
     */
    private $responses;

    /**
     * @var Temperament
     */
    private $temperament;

    public function __construct()
    {
        $this->responses = new ArrayCollection();
    }

    /**
     * @return Temperament
     */
    public function getTemperament()
    {
        return $this->temperament;
    }

    /**
     * @param mixed $temperament
     **/
    public function setTemperament($temperament)
    {
        $this->temperament = $temperament;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getResponses()
    {
        return $this->responses;
    }

    /**
     * @param Response $responses
     **/
    public function setResponses(Response $responses)
    {
        $this->responses->add($responses);
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
     * @return Question
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

    public function __toString()
    {
        return $this->getId()."";
    }
}

