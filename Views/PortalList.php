<?php

namespace LwPortalList\Views;

class PortalList
{
    protected $view;
    
    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalList.phtml');
    }
        
    public function setCollection($collection)
    {
        $this->view->collection = $collection;
    }
    
    public function setIsAdmin($bool)
    {
        $this->view->isAdmin = $bool;
    }
    
    public function render()
    {
        return $this->view->render();
    }
}