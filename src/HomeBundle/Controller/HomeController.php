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
    public function quizzAction()
    {
        $imageParam = json_decode($this->getDoctrine()->getRepository('AdminBundle:Parameters')->getParameterById(5)->getValue(), true);

        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        return $this->render('HomeBundle:app:quizz.html.twig', [
            'imageParam' => $imageParam,
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
        ]);
    }

    public function googleRecaptchaAction(Request $request)
    {
        $key = '6Ldsay8UAAAAAKYJD0Hc9KpDGJ_UOQ0Zj6XBIW9n';

        $response = $request->get('response');

        if (isset($response))
        {
            $ip = $this->container->get('session')->get('client-ip');

            $postdata = http_build_query([
                'secret' => $key,
                'response' => $response,
                'remoteip' => $ip
            ]);

            $opts = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                ]
            ];

            $context  = stream_context_create($opts);

            $url = 'https://www.google.com/recaptcha/api/siteverify';

            $json = file_get_contents($url, false, $context);

            return $this->json($json);
        }

        return $this->json(['message' => "erreur"]);
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

        $session->set("quizz-user-result", json_encode($userResult));

        $results = $QuizzResolver->resultHandler($QuizzResolver->resolve());

        $session->set("quizz-result", json_encode($results));

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
                "quizzResult" => $results,
                "sessionUserQuizzResult" => $session->get("quizz-user-result"),
                "comeFromQuizz" => true
            ]),
            "href" => $this->generateUrl("home_metier", ["jobId" => $selectedJobId])
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metierAction(Request $request)
    {
        $session = $this->container->get('session');
        $colors = $this->container->get('admin.parametersColorHandler')->getColors();

        $jobId = $request->attributes->get('jobId');

        $quizzResult = json_decode($session->get("quizz-result"), true);
        $quizzUserResult = $session->get("quizz-user-result");

        $job = $this->getDoctrine()->getRepository("AdminBundle:Job")->getJobById($jobId);
        $jobPersonnalities = $this->getDoctrine()->getRepository("AdminBundle:JobTemperament")->getJobTemperamentByJobId($jobId);

        return $this->render('HomeBundle:app:metier.html.twig', [
            "primary" => $colors['primary'],
            "secondary" => $colors['secondary'],
            "text" => $colors['text'],
            "job" => $job,
            "jobPersonnalities" => $jobPersonnalities,
            "quizzResult" => $quizzResult,
            "sessionUserQuizzResult" => $quizzUserResult,
            "comeFromQuizz" => false
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
