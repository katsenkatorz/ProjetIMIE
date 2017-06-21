<?php

namespace AdminBundle\Entity;


/**
 * JobPersonnality
 */
class JobPersonnality
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $value;

    private $job;

    private $personnalityType;

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     **/
    public function setJob($job)
    {
        $this->job = $job;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPersonnalityType()
    {
        return $this->personnalityType;
    }

    /**
     * @param mixed $personnalityType
     **/
    public function setPersonnalityType($personnalityType)
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
     * Set value
     *
     * @param integer $value
     *
     * @return JobPersonnality
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
}

