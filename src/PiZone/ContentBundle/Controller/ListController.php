<?php

namespace PiZone\ContentBundle\Controller;

use PiZone\BackendBundle\Controller\AListController;
use PiZone\BackendBundle\Controller\IListController;

/**
 * Content controller.
 *
 */
class ListController extends AListController implements IListController
{
    public function __construct(){
        $this->prefixSession = 'PiZone\ContentBundle\ContentList';
        $this->routeList = array(
            'list' => 'content',
            'delete' => 'content_delete'
        );
        $this->model = 'PiZone\ContentBundle\Entity\Content';
        $this->repository = 'PiZone\ContentBundle\Entity\Content';
        $this->filtersForm = 'PiZone\ContentBundle\Form\ContentFiltersType';
    }

    protected function getlist($entities){
        foreach ($entities as $one) {
            $this->fieldList[] = array(
                'id' => $one->getId(),
                'title' => $one->getTitle(),
                'alias' => $one->getAlias(),
                'anons' => $one->getAnons(),
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
        if (isset($filterObject['title']) && null !== $filterObject['title']) {
            $queryFilter->addStringFilter('title', $filterObject['title']);
        }
        if (isset($filterObject['anons']) && null !== $filterObject['anons']) {
            $queryFilter->addStringFilter('anons', $filterObject['anons']);
        }
        if (isset($filterObject['is_active']) && null !== $filterObject['is_active']) {
            $queryFilter->addBooleanFilter('is_active', $filterObject['is_active']);
        }

    }
}