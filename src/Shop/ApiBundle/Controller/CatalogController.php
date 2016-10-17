<?php

namespace Shop\ApiBundle\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends FOSRestController{
    public function filterAction(Request $request){
        $result = array();

        $data = $request->request->all();

        $content = $this->get('catalog_api')->getCategoryByAlias($data['alias'], $data);

        if($content['result'] != 'error'){
            $result = array(
                'category' => $content['result']['category'],
                'content' => $content['result']['content'],
                'count' => $content['result']['count'],
                'filters' => $content['result']['filters'],
                'alias' => $data['alias']
            );
        }
        else{
            $result = array(
                'category' => $content['result']['category'],
                'content' => '{}',
                'filters' => $content['result']['filters'],
                'alias' => $data['alias']
            );
        }

        $view = new View($result);
        return $this->handleView($view);
    }

    public function searchAction(Request $request) {
        $query = $request->get('query');

        $result = array();
        if ($query && trim($query) != ''){

            $page = $request->get('page');
            if(!$page)
                $page = 1;

            $catalog = $this->get('catalog_api')->search($query, $page);

            $pager = ceil($catalog['result']['count']/10);

            $result = array(
                'query' => $query,
                'content' => $catalog['result']['items'],
                'count' => $catalog['result']['count'],
                'pager' => array('current' => $page, 'list' => $pager)
            );
        }

        $view = new View($result);
        return $this->handleView($view);
    }
}