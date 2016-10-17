<?php

namespace Shop\CatalogBundle\Service;

use Symfony\Component\HttpFoundation\Request;

class FormApi extends Api{
    private function getUrl(){
        return $this->container->getParameter('form_api');
    }
    
    public function getTemplate($alias){
        $form = $this->getContent($this->getUrl().'getTemplate/'.$alias);
        return json_decode($form, true);
    }
}
