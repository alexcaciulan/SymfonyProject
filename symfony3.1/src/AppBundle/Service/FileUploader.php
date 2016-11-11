<?php

namespace AppBundle\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file){

        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->targetDir, $fileName);

        return $fileName;
    }

    /**
     * @param $file
     */
    public function remove($file){
        $oldPhotoPath = $this->targetDir.'/'.$file;
        if(file_exists($oldPhotoPath) and $file!=''){
            unlink($oldPhotoPath);
        }
    }


}