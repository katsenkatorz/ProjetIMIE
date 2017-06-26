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

    public function __construct()
    {
        $this->responses = new ArrayCollection();
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
}

