<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Job;

/**
 * JobRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class JobRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Renvois tout les métiers
     *
     * @return array
     */
    public function getJobs()
    {
        return $this->findAll();
    }

    /**
     * Renvois un métier choisie grâce à sont id
     *
     * @param $jobId
     * @return null|object
     */
    public function getJobById($jobId)
    {
        return $this->findOneBy(["id" => $jobId]);
    }

    /**
     * Renvois un metier choisie grâce à sont nom
     *
     * @param $name
     * @return null|object
     */
    public function getOneJobByName($name)
    {
        return $this->findOneBy(["name" => $name]);
    }

    /**
     * Renvois les jobs qui correspondes au nom
     *
     * @param $name
     * @return array
     */
    public function getJobsByName($name)
    {
        return $this->findBy(['name' => $name]);
    }

    /**
     * Créer un nouveau job et le renvois
     *
     * @param $name
     * @param $description
     * @param $salaireMax
     * @param $salaireMin
     *
     * @return Job
     */
    public function postJob($name, $description, $salaireMax, $salaireMin)
    {
        $em = $this->getEntityManager();

        $job = new Job();
        $job->setName($name)
            ->setDescription($description)
            ->setSalaireMax($salaireMax)
            ->setSalaireMin($salaireMin);

        $em->persist($job);
        $em->flush();

        return $job;
    }

    /**
     * Modifie un job
     *
     * @param $jobId
     * @param $name
     * @param $description
     * @param $salaireMax
     * @param $salaireMin
     *
     * @return bool|object
     */
    public function putJob($jobId, $name, $description, $salaireMax, $salaireMin)
    {
        $em = $this->getEntityManager();
        $job = $this->getJobById($jobId);

        if(!is_null($job) && (!is_null($name) && !is_null($description) && !is_null($salaireMax) && !is_null($salaireMin)))
        {
            $job->setName($name)
                ->setDescription($description)
                ->setSalaireMax($salaireMax)
                ->setSalaireMin($salaireMin);

            $em->persist($job);
            $em->flush();

            return $job;
        }
        else
        {
            return false;
        }
    }

    /**
     * Supprime un job
     *
     * @param $jobId
     *
     * @return bool
     */
    public function deleteJob($jobId)
    {
        $em = $this->getEntityManager();

        $job = $this->getJobById($jobId);

        if(!is_null($job))
        {
            $em->remove($job);
            $em->flush();

            return true;
        }

        return false;
    }

}
