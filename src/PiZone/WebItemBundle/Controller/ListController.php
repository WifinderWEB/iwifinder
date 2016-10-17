<?php

namespace PiZone\WebItemBundle\Controller;

use PiZone\BackendBundle\Controller\AListController;
use PiZone\BackendBundle\Controller\IListController;

/**
 * WebItem controller.
 *
 */
class ListController extends AListController implements IListController
{
    public function __construct(){
        $this->prefixSession = 'PiZone\WebItemBundle\WebItemList';
        $this->routeList = array(
            'list' => 'web_item',
            'delete' => 'web_item_delete'
        );
        $this->model = 'PiZone\WebItemBundle\Entity\WebItem';
        $this->repository = 'PiZone\WebItemBundle\Entity\WebItem';
        $this->filtersForm = 'PiZone\WebItemBundle\Form\WebItemFiltersType';
    }

    protected function getlist($entities){
        foreach ($entities as $one) {
            $this->fieldList[] = array(
                'id' => $one->getId(),
                'alias' => $one->getAlias(),
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

        if (isset($filterObject['alias']) && null !== $filterObject['alias']) {
            $queryFilter->addStringFilter('alias', $filterObject['alias']);
        }
        if (isset($filterObject['description']) && null !== $filterObject['description']) {
            $queryFilter->addStringFilter('description', $filterObject['description']);
        }
        if (isset($filterObject['content']) && null !== $filterObject['content']) {
            $queryFilter->addStringFilter('content', $filterObject['content']);
        }
        if (isset($filterObject['is_active']) && null !== $filterObject['is_active']) {
            $queryFilter->addBooleanFilter('is_active', $filterObject['is_active']);
        }

    }
}