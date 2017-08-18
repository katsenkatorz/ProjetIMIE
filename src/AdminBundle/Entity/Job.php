<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Grafikart\UploadBundle\Annotation\Uploadable;
use Grafikart\UploadBundle\Annotation\UploadableField;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job
 * @Uploadable()
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

    /**
     * @var string
     */
    private $imageName;

    /**
     * @UploadableField(filename="imageName", path="assets/img/imageJob")
     * @Assert\Image(maxWidth="2000", maxHeight="2000")
     */
    private $image;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    private $jobTemperaments;

    public function __construct()
    {
        $this->jobTemperaments = new ArrayCollection();
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     **/
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string $imageName
     **/
    public function setImageName(string $imageName)
    {
        $this->imageName = $imageName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     **/
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
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
    public function getJobTemperaments()
    {
        return $this->jobTemperaments;
    }

    /**
     * @param JobTemperament $jobTemperaments
     **/
    public function setJobTemperaments(JobTemperament $jobTemperaments)
    {
        $this->jobTemperaments->add($jobTemperaments);
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

