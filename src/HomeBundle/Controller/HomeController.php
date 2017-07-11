<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $jobs = $JobRepository->getJobs();

        $jobs = array_slice($jobs, 0, 3 );

        return $this->render('HomeBundle:app:home.html.twig', [
            "jobs" => $jobs,
        ]);
    }

    public function jobsAction()
    {
        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $jobs = $JobRepository->getJobs();

        return $this->render('HomeBundle:app:jobs.html.twig', [
            "jobs" => $jobs,
        ]);
    }

    public function question1Action()
    {
        return $this->render('HomeBundle:app:question1.html.twig');
    }

    public function question2Action()
    {
        return $this->render('HomeBundle:app:question2.html.twig');
    }

    public function resultAction()
    {
        return $this->render('HomeBundle:app:result.html.twig');
    }
}
