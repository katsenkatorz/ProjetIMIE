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
     * Renvois un métier grâce à sont slug
     *
     * @param $jobSlug
     * @return null|object
     */
    public function getJobBySlug($jobSlug)
    {
        return $this->findOneBy(["slug" => $jobSlug]);
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

    public function getValidJobs()
    {
        $array = [];
        $jobs = $this->getJobs();

        foreach ($jobs as $job)
        {
            $valid = true;
            foreach($job->getJobTemperaments() as $jobTemperament)
            {
                if($jobTemperament->getValue() == 0)
                    $valid = false;
            }

            if($valid)
                $array[] = $job;
        }

        return $array;
    }

    /**
     * Créer un nouveau job et le renvois
     * Créer aussi les jobs Temperament pour le job avec une valeur par default a 50
     *
     * @param $name
     * @param $description
     * @param $formGetData
     * @return Job|bool
     */
    public function postJob($formResult, $imageInfo)
    {
        if(!$this->checkIfJobAlreadyExist($formResult['name']->getData(), $formResult['description']->getData()))
        {
            $data = $imageInfo['image'];
            $pathToImageFolder = $imageInfo['pathToImage'];

            $em = $this->getEntityManager();
            $TemperamentRepo = $this->getEntityManager()->getRepository("AdminBundle:Temperament");
            $JobTemperamentRepo = $this->getEntityManager()->getRepository("AdminBundle:JobTemperament");
            $blankImageData = json_decode($em->getRepository("AdminBundle:Parameters")->getParameterById(5)->getValue(), true)['emptyImageString'];

            $temperaments = $TemperamentRepo->getTemperaments();

            $job = new Job();
            $job->setName($formResult['name']->getData())
                ->setDescription($formResult['description']->getData())
                ->setMaxSalary($formResult['maxSalary']->getData())
                ->setMinSalary($formResult['minSalary']->getData())
                ->setUpdatedAt(new \DateTime());

            if ($data !== $blankImageData && !is_null($data) && $data !== '')
            {
                list(, $data)  = explode(',', $data);

                $data = base64_decode($data);
                $imageName = time().'.png';
                file_put_contents($pathToImageFolder.$imageName, $data);

                $job->setImageName($imageName);
            }

            $em->persist($job);
            $em->flush();

            foreach ($temperaments as $temperament)
            {
                $JobTemperamentRepo->postJobTemperament(0, $job, $temperament);
            }

            return $job;
        }

        return false;
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
    public function putJob($jobId, $form, $imageInfo)
    {
        $data = $imageInfo['image'];
        $pathToImageFolder = $imageInfo['pathToImage'];
        $em = $this->getEntityManager();
        $job = $this->getJobById($jobId);
        $blankImageData = json_decode($em->getRepository("AdminBundle:Parameters")->getParameterById(5)->getValue(), true)['emptyImageString'];


        $name = $form['name']->getData();
        $description = $form['description']->getData();
        $minsalary = $form['minSalary']->getData();
        $maxSalary = $form['maxSalary']->getData();

        if($job)
        {
            $job->setName($name)
                ->setDescription($description)
                ->setMinSalary($minsalary)
                ->setMaxSalary($maxSalary)
                ->setUpdatedAt(new \DateTime());

            if ($data !== $blankImageData && !is_null($data) && $data !== '')
            {
                list(,$data)  = explode(',', $data);

                $data = base64_decode($data);
                $imageName = time().'.png';
                file_put_contents($pathToImageFolder.$imageName, $data);

                if(!is_null($job->getImageName()))
                    unlink($pathToImageFolder.$job->getImageName());

                $job->setImageName($imageName);
            }

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

    /**
     * Renvois true si le métier existe déjà
     *
     * @param $name
     * @param $description
     *
     * @return bool
     */
    public function checkIfJobAlreadyExist($name, $description)
    {
        $isHereOrNot = $this->getEntityManager()->createQueryBuilder()
            ->select("j")
            ->from("AdminBundle:Job", "j")
            ->where("j.name = :name")
            ->andWhere("j.description = :description")
            ->setParameter(":name", $name)
            ->setParameter(":description", $description)
            ->getQuery()
            ->getResult();

        if(count($isHereOrNot))
            return true;

        return false;
    }

    /**
     * Incrémente le champ deliveredByQuizz d'un métier
     *
     * @param $jobId
     * @return bool|null|object
     */
    public function incrementDeliveredByQuizzWithJobId($jobId)
    {
        $em = $this->getEntityManager();
        $job = $this->findOneBy(['id' => $jobId]);

        if(!is_null($job))
        {
            $job->setDeliveredByQuizz($job->getDeliveredByQuizz()+1);

            $em->persist($job);
            $em->flush();

            return $job;
        }

        return false;
    }

    /**
     * Retourne un tableau des différents métiers avec leurs pourcentage de sortie du quizz
     *
     * @return array|bool
     */
    public function getMostDeliveredJobByQuizz()
    {
        $jobs = $this->getEntityManager()->createQueryBuilder()
            ->select("j.name, j.deliveredByQuizz")
            ->from('AdminBundle:Job', "j")
            ->getQuery()->getResult();


        $totalDelivered = 0;
        $result = [];

        foreach($jobs as $job)
        {
            $totalDelivered += $job['deliveredByQuizz'];
        }

        if($totalDelivered !== 0)
        {
            foreach($jobs as $job)
            {
                $percentage = ($job['deliveredByQuizz'] * 100) / $totalDelivered;

                $roundPercentage = round($percentage, 1);

                $result[] = [
                    "jobName" => $job['name'],
                    "percentage" => $roundPercentage
                ];

            }

            return $result;
        }
        else
        {
            return false;
        }
    }

    /**
     * Permet de reset les compteurs de tout les métiers
     */
    public function resetMostDeliveredQuizz()
    {
        $em = $this->getEntityManager();
        $jobs = $this->findAll();

        foreach($jobs as $job)
        {
            $job->setDeliveredByQuizz(0);

            $em->persist($job);
        }

        $em->flush();
    }
}
