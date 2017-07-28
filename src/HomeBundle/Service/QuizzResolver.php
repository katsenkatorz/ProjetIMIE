<?php

namespace HomeBundle\Service;


use Doctrine\ORM\EntityManager;

class QuizzResolver{

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
     * @description Renvois l'id du métier correspondant au résultat du test
     * @return int
     */
    public function resolve()
    {
        $jobPersonnalityRepo = $this->em->getRepository("AdminBundle:JobPersonnality");
        $jobs = $this->em->getRepository("AdminBundle:Job")->getJobs();
        $array = [];

        // Pour chaque job
        foreach($jobs as $job)
        {
            // On récupère l'id du job
            $jobId = $job->getId();
            // Avec cet id on récupère les personnalités métiers
            $jobPersonnalities = $jobPersonnalityRepo->getJobPersonnalityByJobId($jobId);

            // On prépare un tableau pour chaque métier
            $array[$jobId] = [];

            foreach($jobPersonnalities as $jobPersonnality)
            {
                // Pour chaque résultat
                foreach($this->orderResult() as $resultat)
                {
                    // Pour chaque temperament, on ajoute l'écart entre la valeur métier et la valeur moyenne des réponses
                    if($jobPersonnality->getTemperament()->getId() == $resultat->getTemperamentId())
                        array_push($array[$jobId], abs($jobPersonnality->getValue() - $resultat->getValueAverage()));
                }
            }

            // On fait la somme des écarts
            $array[$jobId] = array_sum($array[$jobId]);
        }

        // On trie le tableau pour avoir en premier le métier qui possède la somme des écarts la plus faible
        asort($array);

        // On récupère le premier élément du tableau
        $array = array_slice($array, 0, 1, true);

        // On renvois la clés qui contient l'id du métier
        return array_keys($array)[0];
    }

    /**
     * Stocke les résultats venants du quizz dans un tableau de ResultatHolder
     * @return array
     */
    private function orderResult()
    {
        $resultatHolders = [];
        $temperaments = $this->em->getRepository('AdminBundle:Temperament')->getTemperaments();

        foreach($temperaments as $temperament)
        {
            $resultatHolders[] = new ResultatHolder($temperament->getId());
        }

        foreach($this->resultats as $resultat)
        {
            $value = $resultat['value'];
            $temperamentId = $resultat['temperamentId'];

            foreach($resultatHolders as $resultatHolder)
            {
                if($temperamentId == $resultatHolder->getTemperamentId())
                {
                    if($value > 0)
                        $resultatHolder->addPosValues($value);

                    if($value < 0)
                        $resultatHolder->addNegValues($value);
                }
            }
        }

        return $resultatHolders;
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
}