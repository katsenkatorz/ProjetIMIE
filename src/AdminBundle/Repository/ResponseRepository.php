<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Temperament;
use AdminBundle\Entity\Question;
use AdminBundle\Entity\Response;


/**
 * RESPONSERepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ResponseRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Renvois toutes les réponses enregistrer en base
     *
     * @return array
     */
    public function getResponses()
    {
        return $this->findAll();
    }

    /**
     * Renvois la réponse correspondant à un id
     *
     * @param $id
     *
     * @return null|object
     */
    public function getResponseById($id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * Renvois les réponses pour une question
     *
     * @param $questionId
     * @return array|bool
     */
    public function getResponseByQuestionId($questionId)
    {
        $question = $this->getEntityManager()->getRepository("AdminBundle:Question")->getQuestionById($questionId);

        return $this->findBy(['question' => $question]);
    }

    /**
     * Créer une réponse
     *
     * @param $label
     * @param $value
     * @param $image
     * @param Question $question
     * @param Temperament $temperament
     *
     * @return Response|bool
     */
    public function postResponse($formResult, $imageInfo)
    {
        $data = $imageInfo['image'];
        $pathToImageFolder = $imageInfo['pathToImage'];

        $em = $this->getEntityManager();
        $question = $em->getRepository("AdminBundle:Question")->getQuestionById($formResult["question"]->getData());
        $blankImageData = json_decode($em->getRepository("AdminBundle:Parameters")->getParameterById(5)->getValue(), true)['emptyImageString'];

        $value = $formResult['value']->getData();
        $label = $formResult['label']->getData();

        if($question)
        {
            $response = new Response();

            $response
                ->setLabel($label)
                ->setValue($value)
                ->setQuestion($question)
                ->setUpdatedAt(new \DateTime());

            if ($data !== $blankImageData)
            {
                list(, $data) = explode(';', $data);
                list(,$data)  = explode(',', $data);

                $data = base64_decode($data);
                $imageName = time().'.png';
                file_put_contents($pathToImageFolder.$imageName, $data);

                $response->setImageName($imageName);
            }

            $em->persist($response);
            $em->flush();

            return $response;
        }

        return false;
    }

    /**
     * Modifie une réponse
     *
     * @param $id
     * @param $form
     * @param $imageInfo
     *
     * @return bool|object
     */
    public function putResponse($id, $form, $imageInfo)
    {
        $data = $imageInfo['image'];
        $pathToImageFolder = $imageInfo['pathToImage'];

        $em = $this->getEntityManager();
        $blankImageData = json_decode($em->getRepository("AdminBundle:Parameters")->getParameterById(5)->getValue(), true)['emptyImageString'];

        $value = $form['value']->getData();
        $label = $form['label']->getData();

        if ($value != 0)
        {
            $response = $this->getResponseById($id);

            $response->setLabel($label)
                ->setValue($value)
                ->setUpdatedAt(new \DateTime());

            if ($data !== $blankImageData)
            {
                list(, $data) = explode(';', $data);
                list(,$data)  = explode(',', $data);

                $data = base64_decode($data);
                $imageName = time().'.png';
                file_put_contents($pathToImageFolder.$imageName, $data);

                if(!is_null($response->getImageName()))
                    unlink($pathToImageFolder.$response->getImageName());

                $response->setImageName($imageName);
            }


            $em->persist($response);
            $em->flush();

            return $response;
        }
        return false;
    }

    /**
     * Supprime une réponse
     * @param $id
     */
    public function deleteResponse($id)
    {
        $em = $this->getEntityManager();

        $response = $this->getResponseById($id);

        $em->remove($response);
        $em->flush();
    }
}
