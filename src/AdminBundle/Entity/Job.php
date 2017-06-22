<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Job
 */
class Job
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
    private $description;

    /**
     * @var integer
     */
    private $minSalary;

    /**
     * @var integer
     */
    private $maxSalary;

    private $jobPersonnalities;

    public function __construct()
    {
        $this->jobPersonnalities = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getMinSalary()
    {
        return $this->minSalary;
    }

    /**
     * @param integer $minSalary
     **/
    public function setMinSalary($minSalary)
    {
        $this->minSalary = $minSalary;
        return $this;
    }

    /**
     * @return integer
     */
    public function getmaxSalary()
    {
        return $this->maxSalary;
    }

    /**
     * @param integer $maxSalary
     **/
    public function setMaxSalary($maxSalary)
    {
        $this->maxSalary = $maxSalary;
        return $this;
    }



    /**
     * @return ArrayCollection
     */
    public function getJobPersonnalities()
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
     * @return Job
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
     * Set description
     *
     * @param string $description
     *
     * @return Job
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

