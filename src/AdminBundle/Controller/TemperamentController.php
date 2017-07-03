<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Temperament;
use AdminBundle\Form\TemperamentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Personnalitytype controller.
 *
 */
class TemperamentController extends Controller
{
	/**
	 * Affiche la page de gestion des types de personnalités
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function temperamentAction(Request $request)
	{
		$em                   = $this->getDoctrine()->getManager();
		$temperaments    = $em->getRepository('AdminBundle:Temperament')->findAll();
		$TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

		// Création du formulaire pour les Type de personnalité
		$formPT = $this->createForm(TemperamentType::class);

		// Récupération de la requête
		$formPT->handleRequest($request);

		// Traitement pour la création de Type de personnalité
		if ($formPT->isSubmitted() && $formPT->isValid())
		{
			$TemperamentRepo->postTemperament($formPT["name"]->getData(), $formPT["temperament"]->getData(), $formPT["opposedTemperament"]->getData());
			return $this->redirectToRoute('admin_temperament_show');
		}

		return $this->render("AdminBundle:app:temperament.html.twig", ['temperaments' => $temperaments, "formPT" => $formPT->createView(),]);
	}



	/**
	 * Deletes a temperament entity.
	 *
	 * @throws \LogicException
	 */
	public function deleteAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$temperament = $em->getRepository('AdminBundle:Temperament')->findOneBy(['id' => $request->get('id')]);
		$em->remove($temperament);
		$em->flush();

		$this->addFlash(
			'message',
			'La suppréssion de ' . $temperament->getName() . ' est réaliser !'
		);

		return $this->redirectToRoute('admin_temperament_show');
	}

}
