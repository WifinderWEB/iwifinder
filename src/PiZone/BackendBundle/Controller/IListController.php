<?php

namespace PiZone\BackendBundle\Controller;

interface IListController{
    public function indexAction();
    public function filtersAction();
    public function scopesAction();
    public function activeAction($id, $active);
}