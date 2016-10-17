<?php

namespace PiZone\WebItemBundle\Controller;

use PiZone\BackendBundle\Controller\AActionController;

/**
 * WebItem controller.
 *
 */
class ActionController extends AActionController
{
    public function __construct(){
        $this->model = 'PiZone\WebItemBundle\Entity\WebItem';
        $this->repository = 'PiZone\WebItemBundle\Entity\WebItem';
        $this->route['delete'] = 'webitem_delete';
    }
}