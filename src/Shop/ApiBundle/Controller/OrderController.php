<?php

namespace Shop\ApiBundle\Controller;

use Shop\ApiBundle\Form\OrderType;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use Shop\ApiBundle\Entity\Order;

class OrderController extends FOSRestController{

    public function getFormAction(){
        $entity = new Order();
        $form = $this->createCreateForm($entity)->createView();

        $result = array(
            'fields' => $this->get('pizone_form')->formDataToArray($form)
        );

        $view = new View($result);
        return $this->handleView($view);
    }

    public function createAction(Request $request)
    {
        $entity = new Order();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $this->normalizeData($request, $form);
            $order = $this->get('catalog_api')->createOrder($data);
            if($order['result'] == 'ok') {
                $result = $order;
                if ($this->get('session')->has('Cart')) {
                    $this->get('session')->remove('Cart');
                }
            }
            else{
                $result = array(
                    'result' => 'error',
                    'message' => $order['message']
                );
            }

            $view = new View($result);
            return $this->handleView($view);
        }

        $errors = array();
        foreach($form->getErrors(true) as $one){
            $errors[] = $one->getMessage();
        }

        $formView = $form->createView();

        $result = array(
            'result' => 'error',
            'errors' => implode(', ', $errors),
            'fields' => $this->get('pizone_form')->formDataToArray($formView)
        );
        $view = new View($result, Codes::HTTP_BAD_REQUEST);
        return $this->handleView($view);
    }

    private function createCreateForm($entity)
    {
        $form = $this->createForm(new OrderType(), $entity);

        return $form;
    }

    private function normalizeData($request, $form){
        $data = $request->request->get($form->getName());
        $data['project'] = $this->container->getParameter('project_id');
        unset($data['_token']);

        return array('catalog_orderbundle_order' => $data);
    }
}