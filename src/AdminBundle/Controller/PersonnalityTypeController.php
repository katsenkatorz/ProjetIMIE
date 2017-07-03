<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\PersonnalityType;
use AdminBundle\Form\PersonnalityTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Personnalitytype controller.
 *
 */
class PersonnalityTypeController extends Controller
{
	/**
	 * Affiche la page de gestion des types de personnalités
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function personnalityTypeAction(Request $request)
	{
		$em                   = $this->getDoctrine()->getManager();
		$personnalityTypes    = $em->getRepository('AdminBundle:PersonnalityType')->findAll();
		$PersonnalityTypeRepo = $this->getDoctrine()->getRepository("AdminBundle:PersonnalityType");

		// Création du formulaire pour les Type de personnalité
		$formPT = $this->createForm(PersonnalityTypeType::class);

		// Récupération de la requête
		$formPT->handleRequest($request);

		// Traitement pour la création de Type de personnalité
		if ($formPT->isSubmitted() && $formPT->isValid())
		{
			$PersonnalityTypeRepo->postPersonnalityType($formPT["name"]->getData(), $formPT["personnalityType"]->getData(), $formPT["opposedPersonnalityType"]->getData());
			return $this->redirectToRoute('admin_personnalityType_show');
		}

		return $this->render("AdminBundle:app:personnalityType.html.twig", ['personnalityTypes' => $personnalityTypes, "formPT" => $formPT->createView(),]);
	}



	/**
	 * Deletes a personnalityType entity.
	 *
	 * @throws \LogicException
	 */
	public function deleteAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$personnalityType = $em->getRepository('AdminBundle:PersonnalityType')->findOneBy(['id' => $request->get('id')]);
		$em->remove($personnalityType);
		$em->flush();

		$this->addFlash(
			'message',
			'La suppréssion de ' . $personnalityType->getName() . ' est réaliser !'
		);

		return $this->redirectToRoute('admin_personnalityType_show');
	}

}
