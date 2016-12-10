<?php

namespace Shop\ApiBundle\Entity;

class Cart{

    protected $goods = array();

    public function clear(){
        $this->goods = array();
    }

    public function remove($ids){
        $ids = explode(',', $ids);
        foreach($ids as $id) {
            if ($this->goods[$id])
                unset($this->goods[$id]);
        }
    }

    public function addGoods($good){
        $this->goods[$good['id']] = array(
            'id' => $good['id'],
            'title' => $good['title'],
            'alias' => $good['alias'],
            'article' => $good['article'],
            'image_path' => $good['image_path'],
            'alt_image' => $good['alt_image'],
            'title_image' => $good['title_image'],
            'count' => 1
        );

        if(isset($good['sale'])){
            if(isset($good['sale']['retail_price']))
                $this->goods[$good['id']]['price'] = $good['sale']['retail_price'];

            if(isset($good['sale']['discount']))
                $this->goods[$good['id']]['discount'] = round($good['sale']['retail_price']/100 * $good['sale']['discount'], 2);
        }
    }

    public function setCountGoods($id, $count){
        if($this->goods[$id]){
            $this->goods[$id]['count'] = $count;
        }
    }

    public function getCount(){
        $count = 0;
        foreach($this->goods as $one){
            $count = $count + $one['count'];
        }
        return $count;
    }

    public function getGoods(){
        return $this->goods;
    }
}