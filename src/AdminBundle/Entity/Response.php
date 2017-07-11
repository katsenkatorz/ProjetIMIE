<?php

namespace AdminBundle\Entity;

use Grafikart\UploadBundle\Annotation\Uploadable;
use Grafikart\UploadBundle\Annotation\UploadableField;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RESPONSE
 * @Uploadable()
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
    private $imageName;

    /**
     * @UploadableField(filename="imageName", path="assets/img/imageResponse")
     * @Assert\Image(maxWidth="2000", maxHeight="2000")
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
     * @var \DateTime
     */
    private $updatedAt;

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
     * @return string|null
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
     * @return Question
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     **/
    public function setQuestion($question)
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

