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

        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        return $this->render('HomeBundle:app:home.html.twig', [
            'title' => $title,
            'description' => $description,
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metiersAction()
    {
        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        // Récupération des répository et manager
        $JobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");

        $jobs = $JobRepository->getJobs();

        return $this->render('HomeBundle:app:metiers.html.twig', [
            "jobs" => $jobs,
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function quizzAction(Request $request)
    {
        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);

        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

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

        return $this->render('HomeBundle:app:quizz.html.twig', [
            'imageParam' => $imageParam,
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getQuestionSetAction()
    {
        // Génération du set de question
        $questionSet = $this->get("GenerateQuestionSet")->getQuestionSet();

        // Récupération de l'id du visiteur
        $visitorId = $this->container->get('session')->get('client-id');

        // Sauvegarde de l'information que l'utilisateur à commencer le test
        $this->getDoctrine()->getRepository("AdminBundle:Visitor")->setQuizzCompletion($visitorId, false);

        return $this->json($questionSet);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function resolutionAction(Request $request)
    {
        $session = $this->container->get('session');
        $jobRepository = $this->getDoctrine()->getRepository("AdminBundle:Job");
        $QuizzResolver = $this->get('QuizzResolver');

        $QuizzResolver->setResultats($request->get('responses'));

        $userResult = $QuizzResolver->getUserResult();

        $session->set("quizz-result", json_encode($userResult));

        $results = $QuizzResolver->resultHandler($QuizzResolver->resolve());

        $selectedJobId = $results[0]["jobId"];

        // Incrémentation du métiers pour le suivie du quizz
        $jobRepository->incrementDeliveredByQuizzWithJobId($selectedJobId);

        // Récupération de l'id du visiteur
        $visitorId = $session->get('client-id');

        // Sauvegarde de l'information que l'utilisateur à finis le test
        $this->getDoctrine()->getRepository("AdminBundle:Visitor")->setQuizzCompletion($visitorId, true);

        $colors = $this->container->get('admin.parametersColorHandler')->getColors();
        $selectedJob = $jobRepository->getJobById($selectedJobId);
        $jobPersonnalities = $this->getDoctrine()->getRepository("AdminBundle:JobTemperament")->getJobTemperamentByJobId($selectedJobId);

        return $this->json([
            "page" => $this->renderView("HomeBundle:app:metier.html.twig", [
                "primary" => $colors['primary'],
                "secondary" => $colors['secondary'],
                "text" => $colors['text'],
                "job" => $selectedJob,
                "jobPersonnalities" => $jobPersonnalities,
                "quizzResult" => $results
            ]),
            "href" => $this->generateUrl("home_metier", ["jobId" => $selectedJobId])
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metierAction(Request $request)
    {
        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        $jobId = $request->attributes->get('jobId');

        $job = $this->getDoctrine()->getRepository("AdminBundle:Job")->getJobById($jobId);
        $jobPersonnalities = $this->getDoctrine()->getRepository("AdminBundle:JobTemperament")->getJobTemperamentByJobId($jobId);

        return $this->render('HomeBundle:app:metier.html.twig', [
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
            "job" => $job,
            "jobPersonnalities" => $jobPersonnalities
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
        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        $mentionsLegales = $ParamRepo->getParameterById(4);

        return $this->render('HomeBundle:app:mentionsLegales.html.twig', [
            'mentionsLegales' => $mentionsLegales,
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cookiesAction()
    {
        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        return $this->render('HomeBundle:app:cookies.html.twig', [
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function setSharedAction()
    {
        // Récupération de l'id du visiteur
        $visitorId = $this->container->get('session')->get('client-id');

        // Sauvegarde de l'information que l'utilisateur à finis le test
        $visitor = $this->getDoctrine()->getRepository("AdminBundle:Visitor")->setSharedToTrue($visitorId);

        return $this->json([]);
    }
}
