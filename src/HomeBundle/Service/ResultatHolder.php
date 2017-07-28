<?php

namespace HomeBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ResultatHolder
 *
 * @description Utiliser pour stocker les résultats des questions d'un même tempérament
 * @package HomeBundle\Service
 */
class ResultatHolder{

    /**
     * @var integer
     */
    private $temperamentId;

    /**
     * @var ArrayCollection
     */
    private $posValues;

    /**
     * @var ArrayCollection
     */
    private $negValues;

    /**
     * ResultatHolder constructor.
     * @param $temperamentId
     */
    public function __construct($temperamentId)
    {
        $this->temperamentId = $temperamentId;
        $this->posValues = new ArrayCollection();
        $this->negValues = new ArrayCollection();
    }

    /**
     * @description Renvois la moyenne des résultats pour un temperament
     * @return float
     */
    public function getValueAverage()
    {
        $moyPosValue = array_sum($this->posValues->toArray())/sizeof($this->posValues->toArray());
        $moyNegValue = array_sum($this->negValues->toArray())/sizeof($this->negValues->toArray());

        return round(($moyNegValue + $moyPosValue) / 2);
    }

    /**
     * @return mixed
     */
    public function getTemperamentId()
    {
        return $this->temperamentId;
    }

    /**
     * @param mixed $temperamentId
     **/
    public function setTemperamentId($temperamentId)
    {
        $this->temperamentId = $temperamentId;
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getPosValues()
    {
        return $this->posValues;
    }

    /**
     * @param ArrayCollection $posValues
     **/
    public function setPosValues($posValues)
    {
        $this->posValues = $posValues;
        return $this;
    }

    /**
     * @param $posValue
     * @return $this
     */
    public function addPosValues($posValue)
    {
        $this->posValues->add($posValue);
        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getNegValues()
    {
        return $this->negValues;
    }

    /**
     * @param ArrayCollection $negValues
     **/
    public function setNegValues($negValues)
    {
        $this->negValues = $negValues;
        return $this;
    }

    /**
     * @param $negValue
     * @return $this
     */
    public function addNegValues($negValue)
    {
        $this->negValues->add($negValue);
        return $this;
    }
}