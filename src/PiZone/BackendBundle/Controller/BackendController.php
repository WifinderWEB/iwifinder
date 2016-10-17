<?php

namespace PiZone\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BackendController extends Controller {
    public function indexAction() {
        return $this->render('PiZoneBackendBundle:Templates:base.html.twig');
    }

    public function getMainTemplateAction(){
        return $this->render('PiZoneBackendBundle:Templates:_main.html.twig');
    }

    public function getLoginTemplateAction(){
        return $this->render('PiZoneBackendBundle:Templates:_login.html.twig');
    }

    public function getContentTemplateAction(){
        return $this->render('PiZoneBackendBundle:Templates:_content.html.twig');
    }

    public function uploadFileAction(Request $request, $_format){
        $file = $request->files->get('file');
        if($file){
            $filename = md5($file->getClientOriginalName()).'.'.$file->guessExtension();
            $file->move($this->getUploadRootDir(), $filename);
        }

        $result = array('result' => 'ok', 'path' => $this->getUploadDir() . $filename);

        return $this->get('pizone_view')->ToFormat($_format, $result);
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return '/uploads/images/';
    }
}