<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $parameterRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $description = $parameterRepo->getParameterById(6);
        $title = $parameterRepo->getParameterById(7);

        $primary = $parameterRepo->getParameterById(8);
        $secondary = $parameterRepo->getParameterById(9);
        $text = $parameterRepo->getParameterById(10);

        return $this->render('HomeBundle:app:home.html.twig', [
            'title' => $title,
            'description' => $description,
            "primary" => $primary,
            "secondary" => $secondary,
            "text" => $text,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metiersAction()
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $primary = $ParamRepo->getParameterById(8);
        $secondary = $ParamRepo->getParameterById(9);
        $text = $ParamRepo->getParameterById(10);

        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $jobs = $JobRepository->getJobs();

        return $this->render('HomeBundle:app:metiers.html.twig', [
            "jobs" => $jobs,
            "primary" => $primary,
            "secondary" => $secondary,
            "text" => $text,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function quizzAction(Request $request)
    {
        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);

//        $key = '6LeRpSsUAAAAAEf7hX5n9zp-9iaM2mgAUs0_HkGZ';
//        $response = $_POST['g-recaptcha-response'];
//        $ip = $_SERVER['REMOTE_ADDR'];
//        $gapi = 'https://www.google.com/recaptcha/api/siteverify?secret='. $key .'$response='. $response .'$remoteip=' . $ip;
//
//        $json = json_decode(file_get_contents($gapi), true);
//
//        if (!$json['success']) {
//            foreach ($json['error-codes'] as $error)
//            {
//                echo $error .'<br />';
//            }
//        }

        return $this->render('HomeBundle:app:quizz.html.twig', ['imageParam' => $imageParam]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getQuestionSetAction()
    {
        $questionSet = $this->get("GenerateQuestionSet")->getQuestionSet();

        return $this->json($questionSet);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function resolutionAction(Request $request)
    {
        $QuizzResolver = $this->get('QuizzResolver');

        $QuizzResolver->setResultats($request->get('responses'));

        $selectedJobId = $QuizzResolver->resolve();

        return $this->json(['href' => $this->generateUrl("home_metier", ['jobId' => $selectedJobId, 'bool' => 1])]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metierAction(Request $request)
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $primary = $ParamRepo->getParameterById(8);
        $secondary = $ParamRepo->getParameterById(9);
        $text = $ParamRepo->getParameterById(10);

        $jobId = $request->attributes->get('jobId');
        $bool = $request->attributes->get('bool');

        $job = $this->getDoctrine()->getRepository("AdminBundle:Job")->getJobById($jobId);
        $jobPersonnalities = $this->getDoctrine()->getRepository("AdminBundle:JobPersonnality")->getJobPersonnalityByJobId($jobId);

        return $this->render('HomeBundle:app:metier.html.twig', [
            "primary" => $primary,
            "secondary" => $secondary,
            "text" => $text,
            "job" => $job,
            "jobPersonnalities" => $jobPersonnalities,
            'bool' => $bool
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderFooterAction()
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $parameters = $ParamRepo->getParametersWithout([5, 6, 7, 8, 9, 10]);

        return $this->render('HomeBundle:layout:footer.html.twig', [
            'parameters' => $parameters,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mentionsLegalesAction()
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $primary = $ParamRepo->getParameterById(8);
        $secondary = $ParamRepo->getParameterById(9);
        $text = $ParamRepo->getParameterById(10);

        $mentionsLegales = $ParamRepo->getParameterById(4);

        return $this->render('HomeBundle:app:mentionsLegales.html.twig', [
            'mentionsLegales' => $mentionsLegales,
            "primary" => $primary,
            "secondary" => $secondary,
            "text" => $text,
        ]);
    }

    public function cookiesAction()
    {
        $ParamRepo = $this->getDoctrine()->getRepository("AdminBundle:Parameters");

        $primary = $ParamRepo->getParameterById(8);
        $secondary = $ParamRepo->getParameterById(9);
        $text = $ParamRepo->getParameterById(10);

        return $this->render('HomeBundle:app:cookies.html.twig', [
            "primary" => $primary,
            "secondary" => $secondary,
            "text" => $text,
        ]);
    }
}
