<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * PersonnalityType
 */
class PersonnalityType
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $personnalityType;

    /**
     * @var string
     */
    private $opposedPersonnalityType;

    /**
     * @var ArrayCollection
     */
    private $jobPersonnalities;

    /**
     * @var ArrayCollection
     */
    private $responses;

    public function __construct()
    {
        $this->jobPersonnalities = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getJobPersonnalities(): ArrayCollection
    {
        return $this->jobPersonnalities;
    }

    /**
     * @param JobPersonnality $jobPersonnalities
     **/
    public function setJobPersonnalities(JobPersonnality $jobPersonnalities)
    {
        $this->jobPersonnalities->add($jobPersonnalities);
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
     * Set name
     *
     * @param string $name
     *
     * @return PersonnalityType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set personnalityType
     *
     * @param string $personnalityType
     *
     * @return PersonnalityType
     */
    public function setPersonnalityType($personnalityType)
    {
        $this->personnalityType = $personnalityType;

        return $this;
    }

    /**
     * Get personnalityType
     *
     * @return string
     */
    public function getPersonnalityType()
    {
        return $this->personnalityType;
    }

    /**
     * Set opposedPersonnalityType
     *
     * @param string $opposedPersonnalityType
     *
     * @return PersonnalityType
     */
    public function setOpposedPersonnalityType($opposedPersonnalityType)
    {
        $this->opposedPersonnalityType = $opposedPersonnalityType;

        return $this;
    }

    /**
     * Get opposedPersonnalityType
     *
     * @return string
     */
    public function getOpposedPersonnalityType()
    {
        return $this->opposedPersonnalityType;
    }
}

