<?php

namespace LwPortalList\Views;

class ModuleDetail
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/ModuleDetail.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        return $this->view->render();
    }

}
