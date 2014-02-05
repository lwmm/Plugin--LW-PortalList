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
        return $this->view->render();
    }

}
