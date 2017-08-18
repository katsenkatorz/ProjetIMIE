<?php

namespace AdminBundle\Entity;


/**
 * JobPersonnality
 */
class JobTemperament
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

    private $temperament;

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
     * @return JobTemperament
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

