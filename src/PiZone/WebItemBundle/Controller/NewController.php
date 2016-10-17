<?php

namespace PiZone\WebItemBundle\Controller;

use PiZone\BackendBundle\Controller\ANewController;
use PiZone\BackendBundle\Controller\INewController;

/**
 * WebItem controller.
 *
 */
class NewController extends ANewController implements INewController
{
    public function __construct(){
        $this->model = 'PiZone\WebItemBundle\Entity\WebItem';
        $this->form = 'PiZone\WebItemBundle\Form\WebItemType';
        $this->route['create'] = 'web_item_create';
    }
}
