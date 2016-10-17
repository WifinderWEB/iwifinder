<?php

namespace PiZone\ContentBundle\Controller;

use PiZone\BackendBundle\Controller\AActionController;

/**
 * WebItem controller.
 *
 */
class ActionController extends AActionController
{
    public function __construct(){
        $this->model = 'PiZone\ContentBundle\Entity\Content';
        $this->repository = 'PiZone\ContentBundle\Entity\Content';
        $this->route['delete'] = 'content_delete';
    }
}