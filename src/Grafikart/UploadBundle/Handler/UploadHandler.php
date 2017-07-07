<?php
namespace Grafikart\UploadBundle\Handler;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UploadHandler
{

    private $accessor;

    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function uploadFile($entity, $property, $annotation)
    {
        $file = $this->accessor->getValue($entity, $property);

        if($file instanceof UploadedFile)
        {
            $this->removeOldFile($entity, $annotation);
            $fileName = $file->getClientOriginalName();
            $file->move($annotation->getPath(), $fileName);
            $this->accessor->setValue($entity, $annotation->getFilename(), $fileName);
        }
    }

    public function setFileFromFileName($entity, $property, $annotation)
    {
        $file = $this->getFileFromFilename($entity, $annotation);
        $this->accessor->setValue($entity, $property, $file);
    }

    public function removeOldFile($entity, $annotation)
    {
        $file = $this->getFileFromFilename($entity, $annotation);

        if($file !== null)
        {
            @unlink($file->getRealPath());
        }

    }

    /**
     * @param $entity
     * @param $annotation
     * @return File|null
     */
    public function getFileFromFilename($entity, $annotation)
    {
        $fileName = $this->accessor->getValue($entity, $annotation->getFilename());

        if(empty($fileName))
        {
            return null;
        }
        else
        {
            return new File($annotation->getPath().DIRECTORY_SEPARATOR.$fileName, false);
        }

    }

    public function removeFile($entity, $property)
    {
        $file = $this->accessor->getValue($entity, $property);

        if($file instanceof File)
        {
            @unlink($file->getRealPath());
        }
    }
}