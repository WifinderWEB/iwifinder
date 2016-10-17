<?php

namespace PiZone\BackendBundle\Controller;

use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class AActionController extends FOSRestController
{
    protected $model;
    protected $form;
    protected $route = array(
        'delete' => '',
    );

    public function deleteAction($id)
    {
        $entity = $this->getObjectQuery($id)->getOneOrNullResult();

        if (!$entity) {
            $view = new View(array('result' => 'error'), Codes::HTTP_NOT_FOUND);
            return $this->handleView($view);
        }

        try {
            if ('POST' == $this->get('request')->getMethod()) {
                if(!$this->checkToken()){
                    $view = new View(array('result' => 'error'), Codes::HTTP_BAD_REQUEST);
                    return $this->handleView($view);
                }
                else{

                }
                $this->executeObjectDelete($entity);

                $view = new View(array('result' => 'ok'));
                return $this->handleView($view);
            }

        } catch (\Exception $e) {
            $view = new View(array('result' => 'error'), Codes::HTTP_INTERNAL_SERVER_ERROR);
            return $this->handleView($view);
        }
    }

    protected function checkToken(){
        $intention = $this->get('request')->getRequestUri();
        $token = $this->get('request')->request->get('_token');
        if($this->get('form.csrf_provider')->isCsrfTokenValid($intention, $token))
            return true;
        return false;
    }

    protected function executeObjectDelete($entity)
    {
        $em = $this->getDoctrine()->getManagerForClass($this->model);
        $em->remove($entity);
        $em->flush();
        $em->clear();
    }

    protected function getObjectQuery($id)
    {
        return $this->getObjectQueryBuilder($id)->getQuery();
    }

    protected function getObjectQueryBuilder($id)
    {
        return $this->getDoctrine()
            ->getManagerForClass($this->model)
            ->getRepository($this->repository)
            ->createQueryBuilder('q')
            ->where('q.id = :id')
            ->setParameter(':id', $id);
    }
}