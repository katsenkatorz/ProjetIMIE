<?php

namespace AdminBundle\Entity;

/**
 * Statistique
 */
class Visitor
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $ip;

    /**
     * @var string
     */
    private $browser;

    /**
     * @var string
     */
    private $country;

    /**
     * @var \DateTime
     */
    private $connexionDate;


    public function __construct()
    {
        $this->connexionDate = new \DateTime();
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
     * Set ip
     *
     * @param string $ip
     *
     * @return Visitor
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set browser
     *
     * @param string $browser
     *
     * @return Visitor
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * Get browser
     *
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Visitor
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set connexionDate
     *
     * @param \DateTime $connexionDate
     *
     * @return Visitor
     */
    public function setConnexionDate($connexionDate)
    {
        $this->connexionDate = $connexionDate;

        return $this;
    }

    /**
     * Get connexionDate
     *
     * @return \DateTime
     */
    public function getConnexionDate()
    {
        return $this->connexionDate;
    }
}

