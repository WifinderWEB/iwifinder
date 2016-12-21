<?php

namespace Shop\CatalogBundle\Service;

use Symfony\Component\HttpFoundation\Request;

class CatalogApi extends Api {
    public function getProjectList(){
        $t = $this->getContent($this->getUrl() . 'api/getProjectList');
        return json_decode($t, true);
    }

    public function getList(){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getList');
        return json_decode($t, true);
    }

    public function getTree($deep = 0, $alias = null){
        $query = $this->getUrl() . 'api/'.$this->getProjectId().'/getTree';
        $params = array();
        if($deep != 0)
            $params['deep'] = $deep;
        if($alias)
            $params['alias'] = $alias;
        if($params)
            $query = $query . '?' .http_build_query($params);
        $t = $this->getContent($query);
        return json_decode($t, true);
    }

    public function getTreeByAlias($alias, $brand = null){
        $query = $this->getUrl() . 'api/getTreeByAlias/' .$alias .'?project_info=true';
        if($brand)
            $query = $query . '&brand=' . $brand;
        $t = $this->getContent($query);
        return json_decode($t, true);
    }

    public function getTreeByAliasForBrand($alias, $brand){
        $query = $this->getUrl() . 'api/getTreeByAliasForBrand/' .$alias .'/'.$brand.'?project_info=true';
        $t = $this->getContent($query);
        return json_decode($t, true);
    }
    
    public function getTopCategories(){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getCategoriesForLevel/1');
        return json_decode($t, true);
    }
    
    public function getContentByAlias($alias){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getItemByAlias/'.$alias.'?related=true&img_gallery=true&video_gallery=true&sale=true');
        return json_decode($t, true);
    }

    public function getContentByIds($ids){
        $ids = implode(',', $ids);
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getContentByIds/'.$ids.'?sale=true');

        return json_decode($t, true);
    }
    
    public function getContentByAliasShort($alias){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getContentByAlias/'.$alias);
        return json_decode($t, true);
    }
    
    public function getBreadcrumbs($alias){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getBreadcrumbs/'.$alias);
        return json_decode($t, true);
    }
    
    private function getUrl(){
        return $this->container->getParameter('catalog_url');
    }
    
    private function getProjectId(){
        return $this->container->getParameter('project_id');
    }
    
    public function getMenu($alias){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getMenu/'.$alias);
        return json_decode($t, true);
    }

    public function search($query, $page){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/searchContent/'.$query.'?limit=10&page='.$page);
        return json_decode($t, true);
    }

    public function createOrder($data){
        if( $curl = curl_init() ) {
            $url = $this->getUrl() . 'api/'.$this->getProjectId().'/createOrder';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

            if($result = curl_exec($curl)) {
                return json_decode($result, true);
            }
            else {
                $err = curl_error($curl);
                return json_decode($err, true);
            }

            curl_close($curl);
        }

        return json_decode(array(
            'result' => 'error',
            'message' => 'Call to undefined function curl_init()'
        ), true);
    }

    public function getCategoryTree($deep = null){
        $query = '';
        if($deep)
            $query = '?deep='.$deep;
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getCategoryTree'.$query);
        return json_decode($t, true);
    }

    public function getPathCategory($alias){
        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getPathCategory/'.$alias);
        return json_decode($t, true);
    }

    public function getCategoryByAlias($alias, $data = null){
        $queryString = '';
        if($data){
            $query = array();
            foreach($data as $key => $one){
                $query[] = $key . '=' . $one;
            }

            if($query) {
                $queryString = '?' . implode('&', $query);
            }
        }

        $t = $this->getContent($this->getUrl() . 'api/'.$this->getProjectId().'/getCategoryByAlias/'.$alias . $queryString);
        return json_decode($t, true);
    }
}

