<?php

namespace PiZone\BackendBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;


class ANewController extends FOSRestController
{
    protected $model;
    protected $form;
    protected $route = array(
        'create' => ''
    );

    /**
     * Creates a new entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $entity = new $this->model();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $result = array('result' => 'ok');
            $view = new View($result);
            return $this->handleView($view);
        }

        $formView = $form->createView();
        $result = $this->get('pizone_form')->formDataToArray($formView);

        $result = array(
            'action' => $this->generateUrl($this->route['create']),
            'fields' => $result
        );
        $view = new View($result, Codes::HTTP_BAD_REQUEST);
        return $this->handleView($view);
    }

    private function createCreateForm($entity)
    {
        $form = $this->createForm(new $this->form(), $entity);

        return $form;
    }

    /**
     * Displays a form to create a new entity.
     *
     */
    public function newAction()
    {
        $entity = new $this->model();
        $form = $this->createCreateForm($entity)->createView();

        $result = array(
            'action' => $this->generateUrl($this->route['create']),
            'fields' => $this->get('pizone_form')->formDataToArray($form)
        );

        $view = new View($result);
        return $this->handleView($view);
    }
}
