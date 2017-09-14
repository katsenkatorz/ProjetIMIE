<?php

namespace AdminBundle\Service;


use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ParametersColorHandler extends Controller
{
    private $entityManager;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
    }

    public function getColors()
    {
        $ParamRepo = $this->entityManager->getRepository("AdminBundle:Parameters");

        $arrayColors = [
            'primary' => $ParamRepo->getParameterById(8),
            'secondary' => $ParamRepo->getParameterById(9),
            'text' => $ParamRepo->getParameterById(10)
        ];

        return $arrayColors;
    }
}