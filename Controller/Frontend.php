<?php

namespace LwPortalList\Controller;

class Frontend
{

    protected $request;

    public function __construct()
    {
        $this->request = \lw_registry::getInstance()->getEntry("request");
    }

    public function execute()
    {
        if ($this->request->getAlnum("cmd")) {
            $cmd = $this->request->getAlnum("cmd");
        } else {
            $cmd = "showList";
        }

        $method = $cmd . "Action";
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            die("command " . $method . " doesn't exist");
        }
    }

    public function setAdmin($bool)
    {
        if ($bool === true) {
            $this->admin = true;
        } else {
            $this->admin = false;
        }
    }

    protected function isAdmin()
    {
        return $this->admin;
    }

    protected function showListAction()
    {
        $response = \LwPortalList\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
        $collection = $response->getDataByKey("PortalEntitiesCollection");
        $view = new \LwPortalList\Views\PortalList();
        $view->setCollection($collection);
        $view->setIsAdmin($this->isAdmin());
        return $view->render();
    }

    protected function showAddFormAction($errors = false)
    {
        if ($this->isAdmin()) {
            $response = \LwPortalList\Model\Piwik\CommandResolver\getPiwikSitesCollection::getInstance()->resolve();
            $piwikSites = $response->getDataByKey("PiwikSitesEntitiesCollection");
            $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityFromPostArray::getInstance(array(), array("postArray" => $this->request->getPostArray()))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $view = new \LwPortalList\Views\PortalForm();
            $view->setEntity($entity);
            $view->setPiwikSites($piwikSites);
            $view->setErrors($errors);
            return $view->render();
        }
    }

    protected function addEntryAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalList\Model\Portal\CommandResolver\add::getInstance(array(), array("postArray" => $this->request->getPostArray()))->resolve();
            if ($response->getParameterByKey("error")) {
                return $this->showAddFormAction($response->getDataByKey("error"));
            }
            \LwPortalList\Services\Page::reload(\LwPortalList\Services\Page::getUrl(array("cmd" => "showEditForm", "id" => $response->getParameterByKey("id"))));
        }
    }

    protected function showEditFormAction($errors = false)
    {
        if ($this->isAdmin()) {
            if ($errors) {
                $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityFromPostArray::getInstance(array("id" => $this->request->getInt("id")), array("postArray" => $this->request->getPostArray()))->resolve();
            } else {
                $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            }
            $entity = $response->getDataByKey("PortalEntity");
            $response = \LwPortalList\Model\Piwik\CommandResolver\getPiwikSitesCollection::getInstance()->resolve();
            $piwikSites = $response->getDataByKey("PiwikSitesEntitiesCollection");
            $view = new \LwPortalList\Views\PortalForm();
            $view->setEntity($entity);
            $view->setPiwikSites($piwikSites);
            $view->setErrors($errors);
            return $view->render();
        }
    }

    protected function saveEntryAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalList\Model\Portal\CommandResolver\save::getInstance(array("id" => $this->request->getInt("id")), array("postArray" => $this->request->getPostArray()))->resolve();
            if ($response->getParameterByKey("error")) {
                return $this->showEditFormAction($response->getDataByKey("error"));
            }
            \LwPortalList\Services\Page::reload(\LwPortalList\Services\Page::getUrl(array("cmd" => "showEditForm", "id" => $this->request->getInt("id"))));
        }
    }

    protected function showDetailAction()
    {
        $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
        $entity = $response->getDataByKey("PortalEntity");
        $view = new \LwPortalList\Views\PortalDetail();
        $view->setEntity($entity);
        $view->setIsAdmin($this->isAdmin());
        return $view->render();
    }

    protected function deleteEntryAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalList\Model\Portal\CommandResolver\delete::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            \LwPortalList\Services\Page::reload(\LwPortalList\Services\Page::getUrl(array("cmd" => "showList")));
        }
    }

    protected function getSystemInfoFromPortalAction()
    {
        if ($this->isAdmin()) {
            $db = \lw_registry::getInstance()->getEntry("db");
            $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $collector = new \LwPortalList\Collector\InfoCollector($db);
            $collector->setEntity($entity);
            $collector->execute();

            \LwPortalList\Services\Page::reload(\LwPortalList\Services\Page::getUrl(array("cmd" => "showDetail", "id" => $this->request->getInt("id"))));
        }
    }

    protected function getSystemInfoFromPortalCollectionAction()
    {
        if ($this->isAdmin()) {
            $db = \lw_registry::getInstance()->getEntry("db");
            $response = \LwPortalList\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
            $collection = $response->getDataByKey("PortalEntitiesCollection");

            $collector = new \LwPortalList\Collector\InfoCollector($db);
            $collector->setCollection($collection);
            $collector->execute();

            \LwPortalList\Services\Page::reload(\LwPortalList\Services\Page::getUrl(array("cmd" => "showList")));
        }
    }

    protected function showModulesListAction()
    {
        $response = \LwPortalList\Model\Module\CommandResolver\getModuleCollection::getInstance()->resolve();
        $collection = $response->getDataByKey("ModulesEntitiesCollection");
        $view = new \LwPortalList\Views\ModuleList();
        $view->setCollection($collection);
        return $view->render();
    }

    protected function showModuleDetailAction()
    {
        $response = \LwPortalList\Model\Module\CommandResolver\getModuleEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
        $entity = $response->getDataByKey("ModuleEntity");
        $view = new \LwPortalList\Views\ModuleDetail();
        $view->setEntity($entity);
        return $view->render();
    }

    protected function showMd5CheckFormAction($results = false, $post = false)
    {
        if ($this->isAdmin()) {
            $view = new \LwPortalList\Views\PortalMd5CheckForm();
            if ($this->request->getInt("id")) {
                $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
                $entity = $response->getDataByKey("PortalEntity");
                $view->setEntity($entity);
            }
            $view->setResults($results);
            $view->setPost($post);
            return $view->render();
        }
    }
    
    protected function checkMd5Action()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalList\Model\Portal\CommandResolver\checkMd5::getInstance()->resolve();
            $results = $response->getDataByKey("Md5Results");
            $post = $response->getDataByKey("postArray");
            
            return $this->showMd5CheckFormAction($results, $post);
        }
    }

}
