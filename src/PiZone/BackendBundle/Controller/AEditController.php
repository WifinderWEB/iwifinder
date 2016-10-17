<?php

namespace PiZone\BackendBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class AEditController extends FOSRestController
{
    protected $model;
    protected $form;
    protected $routeList = array(
        'update' => '',
        'delete' => ''
    );

    /**
     * Displays a form to edit an existing WebItem entity.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->model)->find($id);

        if (!$entity) {
            $view = new View(array('result' => 'error'), Codes::HTTP_NOT_FOUND);
            return $this->handleView($view);
        }

        $editForm = $this->createEditForm($entity)->createView();
//        $deleteForm = $this->createDeleteForm($id);

        $result = array(
            'action' => $this->generateUrl($this->routeList['update'], array('id' => $id)),
            'fields' => $this->get('pizone_form')->formDataToArray($editForm),
            '_delete_token' => $this->get('form.csrf_provider')->generateCsrfToken($this->generateUrl($this->routeList['delete'], array('id' => $id)))
        );
        $view = new View($result);
        return $this->handleView($view);

//        return $this->render('PiZoneWebItemBundle:WebItem:edit.html.twig', array('entity' => $entity, 'edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),));
    }

    /**
     * Creates a form to edit entity.
     *
     * @param $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm($entity)
    {
        $form = $this->createForm(new $this->form(), $entity, array(
            'action' => $this->generateUrl(
                $this->routeList['update'],
                array(
                    'id' => $entity->getId()
                )
            ),
            'method' => 'PUT'
        ));

        return $form;
    }

    /**
     * Edits an existing entity.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->model)->find($id);

        if (!$entity) {
            $view = new View(array('result' => 'error'), Codes::HTTP_NOT_FOUND);
            return $this->handleView($view);
        }

//        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $result = array('result' => 'ok');
            $view = new View($result);
            return $this->handleView($view);
        }

        $formView = $editForm->createView();

        $result = array(
            'action' => $this->generateUrl($this->routeList['update'], array('id' => $id)),
            'fields' => $this->get('pizone_form')->formDataToArray($formView)
        );
        $view = new View($result, Codes::HTTP_BAD_REQUEST);
        return $this->handleView($view);
    }
}