<?php

namespace AdminBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use UserAgentParser\Exception\NoResultFoundException;
use UserAgentParser\Provider\WhichBrowser;

class UserInformationHandler extends Controller
{
    private $entityManager;

    private $session;

    private $requestStack;

    public function __construct(EntityManager $em, RequestStack $requestStack, Session $session)
    {
        $this->entityManager = $em;
        $this->requestStack = $requestStack;
        $this->session = $session;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $this->sessionProcess();
    }

    private function sessionProcess()
    {
        if (empty($this->session->get('client-ip')))
        {
            $provider = new WhichBrowser();
            $this->session = new Session();

            $ipClient = $this->requestStack->getCurrentRequest()->getClientIp();
            $browsers = $_SERVER["HTTP_USER_AGENT"];
            $country = $_SERVER["GEOIP_COUNTRY_NAME"];
            $datetime = new \DateTime();

            try
            {
                $result = $provider->parse($browsers);

                if(!$result->isBot())
                {
                    $browser = $result->getBrowser()->getName();

                    $this->session->set("client-ip", $ipClient);

                    $this->session->set("client-browser", $browser);

                    if(is_null($country) || $country === '' || $country === false)
                        $country = "Indefinis";

                    $this->session->set("client-country", $country);

                    $this->session->set("client-connexionDate", $datetime);

                    $visitor = $this->entityManager->getRepository("AdminBundle:Visitor")->postVisitor($ipClient, $browser, $country, $datetime);

                    if($visitor != false)
                        $this->session->set("client-id", $visitor->getId());

                }
            }
            catch(NoResultFoundException $ex) {}
        }
    }
}