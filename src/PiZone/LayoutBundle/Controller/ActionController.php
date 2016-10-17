<?php

namespace PiZone\LayoutBundle\Controller;

use PiZone\BackendBundle\Controller\AActionController;
use FOS\RestBundle\View\View;

/**
 * WebItem controller.
 *
 */
class ActionController extends AActionController
{
    public function __construct(){
        $this->model = 'PiZone\LayoutBundle\Entity\Layout';
        $this->repository = 'PiZone\LayoutBundle\Entity\Layout';
        $this->route['delete'] = 'layout_delete';
    }

    public function checkUseAction($id){
        $entity = $this->getObjectQuery($id)->getOneOrNullResult();

        if (!$entity) {
            $view = new View(array('result' => 'error'), Codes::HTTP_NOT_FOUND);
            return $this->handleView($view);
        }

        $list = $this->getDoctrine()->getRepository('PiZoneContentBundle:Content')->getContentByTemplateId($id);
        $result = array();
        foreach($list as $one) {
            $result[] = array(
                'id' => $one->getId(),
                'title' => $one->getTitle()
            );
        }

        $view = new View(array('result' => 'ok', 'list' => $result));
        return $this->handleView($view);
    }
}