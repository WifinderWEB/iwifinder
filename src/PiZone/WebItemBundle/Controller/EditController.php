<?php

namespace PiZone\WebItemBundle\Controller;

use PiZone\BackendBundle\Controller\AEditController;
use PiZone\BackendBundle\Controller\IEditController;

/**
 * WebItem controller.
 *
 */
class EditController extends AEditController implements IEditController
{
    public function __construct(){
        $this->model = 'PiZone\WebItemBundle\Entity\WebItem';
        $this->form = 'PiZone\WebItemBundle\Form\WebItemType';
        $this->routeList['update'] = 'web_item_update';
        $this->routeList['delete'] = 'web_item_delete';
    }
}