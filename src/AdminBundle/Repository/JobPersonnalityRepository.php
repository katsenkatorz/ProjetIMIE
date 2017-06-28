<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Job;
use AdminBundle\Entity\JobPersonnality;
use AdminBundle\Entity\PersonnalityType;

/**
 * JobPersonnalityRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JobPersonnalityRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Renvois tout les JobPersonnalities
     *
     * @return array
     */
    public function getJobPersonnalities()
    {
        return $this->findAll();
    }

    /**
     * Renvois un jobPersonnality choisie par sont id
     *
     * @param $jpId
     * @return null|object
     */
    public function getJobPersonnalityById($jpId)
    {
        return $this->findOneBy(["id" => $jpId]);
    }

    /**
     * Renvois un JobPersonnality choisie par sont job et sont personnality type
     *
     * @param $jobId
     * @param $ptId
     * @return null|object
     */
    public function getJobPersonnalityByPtIdAndJobId($jobId, $ptId)
    {
        $job = $this->getEntityManager()->getRepository("AdminBundle:Job")->getJobById($jobId);
        $pt = $this->getEntityManager()->getRepository("AdminBundle:PersonnalityType")->getPersonnalityTypeById($ptId);

        return $this->findOneBy(["job" => $job, "personnalityType" => $pt]);
    }

    /**
     * Renvois les types de personnalités lié a un job
     *
     * @param $jobId
     * @return bool|array
     */
    public function getPersonnalityTypesByJobId($jobId)
    {
        $job = $this->getEntityManager()->getRepository("AdminBundle:Job")->getJobById($jobId);

        $JobPersonnalities = $this->findBy(['job' => $job]);


        $result = [];
        if(count($JobPersonnalities) > 0)
        {
            foreach($JobPersonnalities as $jobPersonnality)
            {
                $value = $jobPersonnality->getValue();
                $name = $jobPersonnality->getPersonnalityType()->getName();
                $personnalityType = $jobPersonnality->getPersonnalityType()->getPersonnalityType();
                $opposedPersonnalityType = $jobPersonnality->getPersonnalityType()->getOpposedPersonnalityType();
                $idJP = $jobPersonnality->getId();

                $result[$name] = [
                    "name" => $name,
                    "value" => $value,
                    "idJP" => $idJP,
                    "personnalityType" => $personnalityType,
                    "opposedPersonnalityType" => $opposedPersonnalityType,
                    "personnalityTypeId" => $jobPersonnality->getPersonnalityType()->getId()
                ];
            }

            return $result;
        }

        return false;
    }

    /**
     * Créer un nouveau jobPersonnality
     *
     * @param $value
     * @param Job $job
     * @param PersonnalityType $personnalityType
     *
     * @return bool|JobPersonnality
     */
    public function postJobPersonnality($value, Job $job, PersonnalityType $personnalityType)
    {
        $em = $this->getEntityManager();
        $jobPersonnality = new JobPersonnality();

        if(is_numeric($value))
        {
            $jobPersonnality->setValue($value)
                ->setJob($job)
                ->setPersonnalityType($personnalityType);

            $em->persist($jobPersonnality);
            $em->flush();

            return $jobPersonnality;
        }

        return false;
    }

    /**
     * Modifie un JobPersonnality avec une id de jobPersonnality
     *
     * @param $idJP
     * @param $value
     * @param Job $job
     * @param PersonnalityType $personnalityType
     *
     * @return bool|object
     */
    public function putJobPersonnalityByJpid($idJP, $value, Job $job, PersonnalityType $personnalityType)
    {
        $em = $this->getEntityManager();

        $jobPersonnality = $this->getJobPersonnalityById($idJP);

        if(!is_null($jobPersonnality) && (!is_null($value) && is_numeric($value)))
        {
            $jobPersonnality->setValue($value)
                ->setPersonnalityType($personnalityType)
                ->setJob($job);

            $em->persist($jobPersonnality);
            $em->flush();

            return $jobPersonnality;
        }

        return false;
    }

    /**
     * Modifie un JobPersonnality selectionner grâce à sont job et personnalityType
     *
     * @param $value
     * @param $jobId
     * @param $ptId
     *
     * @return bool|null|object
     */
    public function putJobPersonnalityByPtidAndJobId($value, $jobId, $ptId)
    {
        $em = $this->getEntityManager();
        $job = $this->getEntityManager()->getRepository("AdminBundle:Job")->getJobById($jobId);
        $pt = $this->getEntityManager()->getRepository("AdminBundle:PersonnalityType")->getPersonnalityTypeById($ptId);


        $jobPersonnality = $this->getJobPersonnalityByPtIdAndJobId($jobId, $ptId);

        if(!is_null($jobPersonnality) && (!is_null($value) && is_numeric($value)))
        {
            $jobPersonnality->setValue($value)
                ->setPersonnalityType($pt)
                ->setJob($job);

            $em->persist($jobPersonnality);
            $em->flush();

            return $jobPersonnality;
        }

        return false;
    }

    /**
     * Supprime un jobPersonnality
     *
     * @param $id
     * @return bool
     */
    public function deleteJobPersonnality($id)
    {
        $em = $this->getEntityManager();

        $jp = $this->getJobPersonnalityById($id);

        if(!is_null($jp))
        {
            $em->remove($jp);
            $em->flush();

            return true;
        }

        return false;
    }
}

