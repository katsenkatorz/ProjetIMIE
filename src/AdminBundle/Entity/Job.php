<?php

namespace AdminBundle\Entity;

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
    private $lABEL;

    /**
     * @var int
     */
    private $mINSALARY;

    /**
     * @var int
     */
    private $mAXSALARY;

    /**
     * @var string
     */
    private $dESCRIPTION;


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
     * Set lABEL
     *
     * @param string $lABEL
     *
     * @return Job
     */
    public function setLABEL($lABEL)
    {
        $this->lABEL = $lABEL;

        return $this;
    }

    /**
     * Get lABEL
     *
     * @return string
     */
    public function getLABEL()
    {
        return $this->lABEL;
    }

    /**
     * Set mINSALARY
     *
     * @param integer $mINSALARY
     *
     * @return Job
     */
    public function setMINSALARY($mINSALARY)
    {
        $this->mINSALARY = $mINSALARY;

        return $this;
    }

    /**
     * Get mINSALARY
     *
     * @return int
     */
    public function getMINSALARY()
    {
        return $this->mINSALARY;
    }

    /**
     * Set mAXSALARY
     *
     * @param integer $mAXSALARY
     *
     * @return Job
     */
    public function setMAXSALARY($mAXSALARY)
    {
        $this->mAXSALARY = $mAXSALARY;

        return $this;
    }

    /**
     * Get mAXSALARY
     *
     * @return int
     */
    public function getMAXSALARY()
    {
        return $this->mAXSALARY;
    }

    /**
     * Set dESCRIPTION
     *
     * @param string $dESCRIPTION
     *
     * @return Job
     */
    public function setDESCRIPTION($dESCRIPTION)
    {
        $this->dESCRIPTION = $dESCRIPTION;

        return $this;
    }

    /**
     * Get dESCRIPTION
     *
     * @return string
     */
    public function getDESCRIPTION()
    {
        return $this->dESCRIPTION;
    }
}

