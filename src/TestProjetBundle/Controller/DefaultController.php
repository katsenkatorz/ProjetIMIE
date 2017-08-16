<?php

namespace TestProjetBundle\Controller;

use function dump;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function homeAction()
    {
        return $this->render('TestProjetBundle:app:home.html.twig');
    }
    public function formOneAction()
    {
        return $this->render('TestProjetBundle:app:formOne.html.twig');
    }
    public function formTwoAction()
    {
        return $this->render('TestProjetBundle:app:formTwo.html.twig');
    }

    public function testRenderUploadImageAction()
    {
	    return $this->render('TestProjetBundle:app:testUpload.html.twig');
    }

    public function testUploadImageAction()
    {

        if(array_key_exists('image', $_POST))
        {
            $pathToImage = $this->get('kernel')->getRootDir().'/../web/assets/img/';
            $data = $_POST['image'];

            list(, $data) = explode(';', $data);
            list(,$data)  = explode(',', $data);

            $data = base64_decode($data);
            $imageName = time().'.png';
            file_put_contents($pathToImage.$imageName, $data);

        }

	    return $this->render('TestProjetBundle:app:testUpload.html.twig');
    }
}
