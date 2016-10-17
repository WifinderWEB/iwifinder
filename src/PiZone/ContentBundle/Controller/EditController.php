<?php

namespace PiZone\ContentBundle\Controller;

use PiZone\BackendBundle\Controller\AEditController;
use PiZone\BackendBundle\Controller\IEditController;

/**
 * WebItem controller.
 *
 */
class EditController extends AEditController implements IEditController
{
    public function __construct(){
        $this->model = 'PiZone\ContentBundle\Entity\Content';
        $this->form = 'PiZone\ContentBundle\Form\ContentType';
        $this->routeList['update'] = 'content_update';
        $this->routeList['delete'] = 'content_delete';
    }
}