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
		$em              = $this->getDoctrine()->getManager();
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
     * Modifie un temperament
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
	public function updateTemperamentAction(Request $request)
    {
        $TemperamentRepo = $this->getDoctrine()->getRepository("AdminBundle:Temperament");

        $temperamentId = $request->attributes->get('idTemperament');
        $oldTemp = $TemperamentRepo->getTemperamentById($temperamentId);
        $name = $request->get('name');
        $temperamentLeft = $request->get('temperament');
        $temperamentRight = $request->get('opposedTemperament');

        if(!is_null($temperamentId) && !is_null($name) && !is_null($temperamentLeft) && !is_null($temperamentRight))
        {
            $temp = $TemperamentRepo->putTemperament($temperamentId, $name, $temperamentLeft, $temperamentRight);

            if($temp)
                return $this->json([
                    'message' => 'La modification de '.$oldTemp->getName().' c\'est bien effectuer',
                    'name' => $temp->getName(),
                    'temperament' => $temp->getTemperament(),
                    'opposedTemperament' => $temp->getOpposedTemperament()
                    ]);
        }

        return $this->json(['message' => 'Il y a eu une erreur lors de la modification']);
    }

	/**
	 * Deletes a temperament entity.
	 *
	 * @throws \LogicException
	 */
	public function deleteAction(Request $request)
	{
		$temperamentRepo = $this->getDoctrine()->getRepository('AdminBundle:Temperament');
		$temperamentId = $request->attributes->get('idTemperament');

		if(!is_null($temperamentId))
        {
            $temperament = $temperamentRepo->getTemperamentById($temperamentId);

            $temperamentRepo->deleteTemperament($temperamentId);

            $this->addFlash(
                'message',
                'La suppression de ' . $temperament->getName() . ' est réaliser !'
            );
        }

		return $this->redirectToRoute('admin_temperament_show');
	}

}
