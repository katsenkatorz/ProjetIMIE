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

        $jobs = array_slice($jobs, 0, 3);

        return $this->render('HomeBundle:app:home.html.twig', [
            "jobs" => $jobs,
        ]);
    }

    public function metiersAction()
    {
        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $jobs = $JobRepository->getJobs();

        return $this->render('HomeBundle:app:metiers.html.twig', [
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

    public function metierAction()
    {
        return $this->render('HomeBundle:app:metier.html.twig');
    }

    public function renderFooterAction()
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $parameters = $ParamRepo->getParameters();

//        $mentionLegale = $ParamRepo->getParameterById(4);
//        $proprietaire = $ParamRepo->getParameterById(1);
//        $address = $ParamRepo->getParameterById(2);
//        $copyright = $ParamRepo->getParameterById(3);

        return $this->render('HomeBundle:layout:footer.html.twig', [
            'parameters' => $parameters,
        ]);
    }

    public function mentionsLegalesAction()
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $mentionsLegales = $ParamRepo->getParameterById(4);


        return $this->render('HomeBundle:app:mentionsLegales.html.twig', [
            'mentionsLegales' => $mentionsLegales,
        ]);
    }
}
