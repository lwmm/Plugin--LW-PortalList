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
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        return $this->view->render();
    }
}