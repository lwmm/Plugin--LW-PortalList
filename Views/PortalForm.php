<?php

namespace LwPortalList\Views;

class PortalForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalForm.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function setPiwikSites($sites)
    {
        $this->view->piwikSites = $sites;
    }

    public function render()
    {
        if ($this->view->entity->getId() < 1) {
            $this->view->actionUrl = \LwPortalList\Services\Page::getUrl(array("cmd" => "addEntry"));
            $this->view->formtype = "new";
        } else {
            $this->view->actionUrl = \LwPortalList\Services\Page::getUrl(array("cmd" => "saveEntry", "id" => $this->view->entity->getId()));
            $this->view->formtype = "edit";
        }

        return $this->view->render();
    }

}
