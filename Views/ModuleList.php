<?php

namespace LwPortalList\Views;

class ModuleList
{
    protected $view;
    
    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/ModuleList.phtml');
    }
        
    public function setCollection($collection)
    {
        $this->view->collection = $collection;
    }
    
    public function render()
    {
        return $this->view->render();
    }
}