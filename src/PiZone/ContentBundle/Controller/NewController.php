<?php

namespace PiZone\ContentBundle\Controller;

use PiZone\BackendBundle\Controller\ANewController;
use PiZone\BackendBundle\Controller\INewController;

/**
 * WebItem controller.
 *
 */
class NewController extends ANewController implements INewController
{
    public function __construct(){
        $this->model = 'PiZone\ContentBundle\Entity\Content';
        $this->form = 'PiZone\ContentBundle\Form\ContentType';
        $this->route['create'] = 'content_create';
    }
}
