<?php

namespace PiZone\BackendBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter as PagerAdapter;
use PiZone\ContentBundle\Services\PiZonePager;

/**
 * WebItem controller.
 *
 */
abstract class AListController extends FOSRestController
{
    protected $fieldList = array();
    protected $prefixSession = '';
    protected $routeList = array(
        'list' =>  'pi_zone_backend'
    );
    protected $model = '';
    protected $repository = '';
    protected $filtersForm = '';

    /**
     * Lists all Layout entities.
     *
     */
    public function indexAction()
    {
        $this->parseRequestForPager();

        $pager = $this->getPager();
        $form = $this->getFilterForm()->createView();

        $result = array(
            'list' => $this->getList($pager),
            'pager' => $this->getPagination($pager),
            'filters' => $this->get('pizone_form')->formDataToArray($form)
        );

        $view = new View($result);
        return $this->handleView($view);
    }

    protected function getList($entities)
    {
        return $this->fieldList;
    }

    protected function getPagination($pager)
    {

        $startEnd = PiZonePager::calculateStartAndEndPage($pager, 3);
        $result = array(
            'current_page' => $pager->getCurrentPage(),
            'nb_result' => $pager->getNbResults(),
            'nb_pages' => $pager->getNbPages(),
            'max_per_page' => $pager->getMaxPerPage(),
            'has_previos_page' => $pager->hasPreviousPage(),
            'has_next_page' => $pager->hasNextPage(),
            'range' => range($startEnd['start'], $startEnd['end']),
            'start' => $startEnd['start'],
            'end' => $startEnd['end']
        );

        if ($pager->hasPreviousPage())
            $result['previos_page'] = $pager->getPreviousPage();
        if ($pager->hasNextPage())
            $result['next_page'] = $pager->getNextPage();

        return $result;
    }

    /**
     * Check if request contains pager parameters and
     * persist them in session if any.
     */
    protected function parseRequestForPager()
    {
        if ($this->get('request')->query->get('page')) {
            $this->setPage($this->get('request')->query->get('page'));
        }

        if ($this->get('request')->query->get('perPage')) {
            $this->setPerPage($this->get('request')->query->get('perPage'));
        }

        if ($this->get('request')->query->get('sort')) {
            $this->setSort($this->get('request')->query->get('sort'), $this->get('request')->query->get('order_by', 'ASC'));
        }
    }

    /**
     * Store in the session service the current page
     *
     * @param integer $page The page number
     */
    protected function setPage($page)
    {
        $this->get('session')->set($this->prefixSession . '\Page', $page);
    }

    /**
     * Return the stored page
     *
     * @return integer $page The page number
     */
    protected function getPage()
    {
        return $this->get('session')->get($this->prefixSession . '\Page', 1);
    }

    /**
     * Store in the session service the perPage
     *
     * @param integer $perPage The perPage number
     */
    protected function setPerPage($perPage)
    {
        $this->get('session')->set($this->prefixSession . '\PerPage', $perPage);
    }

    /**
     * Return the stored perPage
     *
     * @return integer $perPage The perPage number
     */
    protected function getPerPage()
    {
        return $this->get('session')->get($this->prefixSession . '\PerPage', 10);
    }

    protected function getPager()
    {
        $paginator = new Pagerfanta(new PagerAdapter($this->getQuery()));
        $paginator->setMaxPerPage($this->getPerPage());
        $paginator->setCurrentPage($this->getPage(), false, true);

        return $paginator;
    }

    /**
     * Store in the session service the current sort
     *
     * @param string $column The column
     * @param string $order_by The order sorting (ASC,DESC)
     */
    protected function setSort($column, $order_by)
    {
        $this->get('session')->set($this->prefixSession . '\Sort', $column);

        if ($order_by == 'desc') {
            $this->get('session')->set($this->prefixSession . '\OrderBy', 'DESC');
        } else {
            $this->get('session')->set($this->prefixSession . '\OrderBy', 'ASC');
        }
    }

    /**
     * Return the stored sort
     *
     * @return string The column to sort
     */
    protected function getSortColumn()
    {
        return $this->get('session')->get($this->prefixSession . '\Sort');
    }

    /**
     * Return the stored sort order
     *
     * @return string the order mode ASC|DESC
     */
    protected function getSortOrder()
    {
        return $this->get('session')->get($this->prefixSession . '\OrderBy', 'ASC');
    }

    public function filtersAction()
    {
        if ($this->get('request')->get('reset')) {
            $this->setFilters(array());

            return new RedirectResponse($this->generateUrl($this->routeList['list']));
        }

        if ($this->get('request')->getMethod() == "POST") {
            $form = $this->getFilterForm();
            $form->bind($this->get('request'));

            if ($form->isValid()) {
                $filters = $form->getViewData();
            }
        }

        if ($this->get('request')->getMethod() == "GET") {
            $filters = $this->get('request')->query->all();
        }

        if (isset($filters)) {
            $this->setFilters($filters);
        }

        return new RedirectResponse($this->generateUrl($this->routeList['list']));
    }

    /**
     * Store in the session service the current filters
     *
     * @param array the filters
     */
    protected function setFilters($filters)
    {
        $this->get('session')->set($this->prefixSession . '\Filters', $filters);
    }

    /**
     * Get filters from session
     */
    protected function getFilters()
    {
        $filters = $this->get('session')->get($this->prefixSession . '\Filters', array());


        return $filters;
    }

    public function scopesAction()
    {
        if ($this->get('request')->get('reset')) {
            $this->setScopes(array());

            return new RedirectResponse($this->generateUrl($this->routeList['list']));
        }

        $this->setScope($this->get('request')->get('group'), $this->get('request')->get('scope'));

        return new RedirectResponse($this->generateUrl($this->routeList['list']));
    }

    /**
     * Store in the session service the current scopes
     *
     * @param array the scopes
     */
    protected function setScopes($scopes)
    {
        $this->get('session')->set($this->prefixSession . '\Scopes', $scopes);
    }

    /**
     * Change the value of one Scope
     *
     * @param string the group name
     * @param string the scope name
     */
    protected function setScope($groupName, $scopeName)
    {
        $scopes = $this->getScopes();
        $scopes[$groupName] = $scopeName;
        $this->setScopes($scopes);
    }

    protected function getScopes()
    {
        return $this->get('session')->get($this->prefixSession . '\Scopes', $this->getDefaultScopes());
    }

    protected function getDefaultScopes()
    {
        $scopes = array();

        return $scopes;
    }

    /*
    * @return string|null the scope setted for the current group
    */
    protected function getScope($groupName)
    {
        $scopes = $this->getScopes();

        return isset($scopes[$groupName]) ? $scopes[$groupName] : null;
    }


    /**
     * Get additional parameters for rendering.
     *
     * return array
     */
    protected function getAdditionalRenderParameters()
    {
        return array();
    }

    /**
     * This function is for your convinience. Overwrite it if you need to
     * process the query.
     */
    protected function processQuery($query)
    {
        return $query;
    }

    protected function getQuery()
    {
        $query = $this->buildQuery();

        $this->processQuery($query);
        $this->processSort($query);
        $this->processFilters($query);
        $this->processScopes($query);

        return $query->getQuery();
    }

    protected function buildQuery()
    {
        return $this->getDoctrine()
            ->getManagerForClass($this->model)
            ->getRepository($this->repository)
            ->createQueryBuilder('q');
    }

    protected function processSort($query)
    {
        if ($this->getSortColumn()) {
            if (!strstr($this->getSortColumn(), '.')) { //direct column
                $query->orderBy('q.' . $this->getSortColumn(), $this->getSortOrder());
            } else {
                list($table, $column) = explode('.', $this->getSortColumn(), 2);
                $this->addJoinFor($table, $query);
                $query->orderBy($this->getSortColumn(), $this->getSortOrder());
            }
        }
    }

    protected function getFilterForm()
    {
        $filters = $this->getFilters();

        return $this->createForm($this->getFiltersType(), $filters);
    }

    protected function addJoinFor($table, $query)
    {
        $query->leftJoin('q.' . $table, $table);
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

    protected function processScopes($query)
    {
    }

    protected function getFiltersType()
    {
        $type = new $this->filtersForm();
        $type->setSecurityContext($this->get('security.context'));

        return $type;
    }

    protected function getQueryFilter()
    {
        $em = $this->getDoctrine()->getManager();

        return $em->createQueryBuilder()
            ->select("i")
            ->from($this->model, "i");
    }

    public function activeAction($id, $active)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($this->model)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find WebItem entity.');
        }
        try {
            $test = $active == 'active' ? true : false;
            $entity->setIsActive($test);
            $em->flush();

            $result = array(
                'result' => 'ok',
                'active' => $entity->getIsActive()
            );
        } catch (Exception $e) {
            $result = array(
                'result' => 'error',
                'message' => $e->getMessage(),
                'active' => $entity->getIsActive()
            );
        }

        $view = new View($result);
        return $this->handleView($view);
    }
}