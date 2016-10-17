<?php

namespace PiZone\MenuBundle\Controller\Menu;

use PiZone\BackendBundle\Controller\AListController;
use PiZone\BackendBundle\Controller\IListController;

/**
 * ListController
 */
class ListController extends AListController implements IListController
{
    public function __construct(){
        $this->prefixSession = 'PiZone\MenuBundle\MenuList';
        $this->routeList = array(
            'list' => 'menu',
            'delete' => 'menu_delete'
        );
        $this->model = 'PiZone\MenuBundle\Entity\Menu';
        $this->repository = 'PiZone\MenuBundle\Entity\Menu';
        $this->filtersForm = 'PiZone\MenuBundle\Form\Menu\MenuFiltersType';
    }

    protected function getlist($entities){
        foreach ($entities as $one) {
            $this->fieldList[] = array(
                'id' => $one->getId(),
                'name' => $one->getName(),
                'alias' => $one->getAlias(),
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
        if (isset($filterObject['name']) && null !== $filterObject['name']) {
            $queryFilter->addStringFilter('name', $filterObject['name']);
        }
        if (isset($filterObject['is_active']) && null !== $filterObject['is_active']) {
            $queryFilter->addBooleanFilter('is_active', $filterObject['is_active']);
        }

    }
}
