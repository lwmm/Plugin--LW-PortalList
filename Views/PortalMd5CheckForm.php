<?php

namespace LwPortalList\Views;

class PortalMd5CheckForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalMd5CheckForm.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function setResults($results)
    {
        $this->view->results = $results;
    }
    
    public function setPost($post)
    {
        $this->view->post = $post;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");        
        foreach ($config["path"] as $key => $path){
            $arr[] = $key;
        }
        array_multisort($arr, SORT_ASC, $config["path"]);
        $this->view->paths = $config["path"];
        
        
        if($this->view->entity){
            $this->view->actionUrl = \LwPortalList\Services\Page::getUrl(array("cmd" => "checkMd5", "id" => $this->view->entity->getId()));
        }else{
            $this->view->actionUrl = \LwPortalList\Services\Page::getUrl(array("cmd" => "checkMd5"));
        }
        
        return $this->view->render();
    }

}
