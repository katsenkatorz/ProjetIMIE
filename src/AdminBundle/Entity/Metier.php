<?php

namespace UserBundle\Entity;

/**
 * Metier
 */
class Metier
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
    private $salaireMin;

    /**
     * @var int
     */
    private $salaireMax;

    /**
     * @var string
     */
    private $description;


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
     * @return Metier
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
     * Set salaireMin
     *
     * @param integer $salaireMin
     *
     * @return Metier
     */
    public function setSalaireMin($salaireMin)
    {
        $this->salaireMin = $salaireMin;

        return $this;
    }

    /**
     * Get salaireMin
     *
     * @return int
     */
    public function getSalaireMin()
    {
        return $this->salaireMin;
    }

    /**
     * Set salaireMax
     *
     * @param integer $salaireMax
     *
     * @return Metier
     */
    public function setSalaireMax($salaireMax)
    {
        $this->salaireMax = $salaireMax;

        return $this;
    }

    /**
     * Get salaireMax
     *
     * @return int
     */
    public function getSalaireMax()
    {
        return $this->salaireMax;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Metier
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

