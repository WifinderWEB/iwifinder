<?php

namespace PiZone\LayoutBundle\Controller;

use PiZone\BackendBundle\Controller\AListController;
use PiZone\BackendBundle\Controller\IListController;

/**
 * Layout controller.
 *
 */
class ListController  extends AListController implements IListController
{
    public function __construct(){
        $this->prefixSession = 'PiZone\LayoutBundle\LayoutList';
        $this->routeList = array(
            'list' => 'layout',
            'delete' => 'layout_delete'
        );
        $this->model = 'PiZone\LayoutBundle\Entity\Layout';
        $this->repository = 'PiZone\LayoutBundle\Entity\Layout';
        $this->filtersForm = 'PiZone\LayoutBundle\Form\LayoutFiltersType';
    }

    protected function getlist($entities){
        foreach ($entities as $one) {
            $this->fieldList[] = array(
                'id' => $one->getId(),
                'name' => $one->getName(),
                'description' => $one->getDescription(),
                'isActive' => $one->getIsActive(),
                '_token' => $this->get('form.csrf_provider')->generateCsrfToken($this->generateUrl($this->routeList['delete'], array('id' => $one->getId())))
            );
        }

        return $this->fieldList;
    }

    protected function processFilters($queryFilter)
    {
        $filterObject = $this->getFilters();

        if (isset($filterObject['name']) && null !== $filterObject['name']) {
            $queryFilter->addStringFilter('name', $filterObject['name']);
        }
        if (isset($filterObject['description']) && null !== $filterObject['description']) {
            $queryFilter->addStringFilter('description', $filterObject['description']);
        }
        if (isset($filterObject['is_active']) && null !== $filterObject['is_active']) {
            $queryFilter->addBooleanFilter('is_active', $filterObject['is_active']);
        }
    }
}