<?php

namespace HomeBundle\Service;


use Doctrine\ORM\EntityManager;

class QuizzResolver
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $resultats;

    /**
     * QuizzResolver constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * @return array
     */
    public function getResultats()
    {
        return $this->resultats;
    }

    /**
     * @param $resultats
     * @return $this
     */
    public function setResultats($resultats)
    {
        $this->resultats = $resultats;
        return $this;
    }

    /**
     * @description Renvois l'id du métier correspondant au résultat du test
     * @return int
     */
    public function resolve()
    {
        $jobTemperamentRepo = $this->em->getRepository("AdminBundle:JobTemperament");
        $jobs = $this->em->getRepository("AdminBundle:Job")->getValidJobs();
        $array = [];

        // Pour chaque job
        foreach ($jobs as $job)
        {
            // On récupère l'id du job
            $jobId = $job->getId();
            // Avec cet id on récupère les personnalités métiers
            $jobTemperaments = $jobTemperamentRepo->getJobTemperamentByJobId($jobId);

            // On prépare un tableau pour chaque métier
            $array[$jobId] = [];

            // Pour stocker les pourcentages d'écart de chaque temperament
            $deltaValue = [];
            // L'écart maximum possible
            $maxDelta = 200;

            foreach ($jobTemperaments as $jobTemperament)
            {
                // Pour chaque résultat
                foreach ($this->orderResult() as $resultat)
                {
                    // Pour chaque temperament
                    if ($jobTemperament->getTemperament()->getId() == $resultat->getTemperamentId())
                    {
                        // On récupère la valeur du temperament metier
                        $valeurTemperamentMetier = $jobTemperament->getValue();

                        // On récupère la valeur du quizz
                        $valeurQuizz = $resultat->getValueAverage();

                        // On calcule l'écart entre les deux valeurs
                        $delta = abs($valeurQuizz - $valeurTemperamentMetier);

                        // On en déduit le pourcentage d'ecart
                        $deltaValue[] = ($delta * 100) / $maxDelta;
                    }
                }
            }

            // On fait la moyenne de tout les pourcentages d'ecart
            $moyenneDeltaValue = (array_sum($deltaValue) / count($deltaValue));


            // On récupère le taux de match à partir du taux d'ecart
            $array[$jobId] = round(100 - $moyenneDeltaValue);
        }

        // On trie le tableau pour avoir en premier le métier qui possède le taux de match le plus elever
        arsort($array);

        // On récupère le premier élément du tableau
        $array = array_slice($array, 0, 3, true);

        // On renvois le tableau qui contient les id des trois métiers qui ont le plus fort taux de match
        return $array;
    }

    public function resultHandler($array)
    {
        $jobRepository = $this->getEntityManager()->getRepository("AdminBundle:Job");

        $return = [];

        for ($i = 0; $i < count($array); $i++)
        {
            $jobId = array_keys($array)[$i];

            $percentageValue = $array[$jobId];

            $job = $jobRepository->getJobById($jobId);

            $return[] = [
                "jobId" => $job->getId(),
                "jobName" => $job->getName(),
                "imageName" => $job->getImageName(),
                "percentage" => $percentageValue
            ];
        }

        return $return;
    }

    /**
     * Stocke les résultats venants du quizz dans un tableau de ResultatHolder
     * @return array
     */
    private function orderResult()
    {
        $resultatHolders = [];
        $temperaments = $this->em->getRepository('AdminBundle:Temperament')->getTemperaments();

        foreach ($temperaments as $temperament)
        {
            $resultatHolders[] = new ResultatHolder($temperament->getId());
        }

        foreach ($this->resultats as $resultat)
        {
            $value = $resultat['value'];
            $temperamentId = $resultat['temperamentId'];

            foreach ($resultatHolders as $resultatHolder)
            {
                if ($temperamentId == $resultatHolder->getTemperamentId())
                {
                    if ($value > 0)
                        $resultatHolder->addPosValues($value);

                    if ($value < 0)
                        $resultatHolder->addNegValues($value);
                }
            }
        }

        return $resultatHolders;
    }

    /**
     * Permet de renvoyer les résultats moyens d'un utilisateur
     * @return array
     */
    public function getUserResult()
    {
        $temperaments = $this->em->getRepository('AdminBundle:Temperament')->getTemperaments();
        $results = $this->orderResult();
        $return = [];

        foreach ($temperaments as $temperament)
        {
            foreach ($results as $result)
            {
                if($result->getTemperamentId() == $temperament->getId())
                {
                    $moyenne = $result->getValueAverage();

                    $temperamentName = $temperament->getTemperament();

                    if ($moyenne > 0)
                        $temperamentName = $temperament->getOpposedTemperament();

                    $return[] = [
                        "temperament" => $temperamentName,
                        "moyenne" => $moyenne
                    ];

                }
            }
        }
        return $return;
    }
}