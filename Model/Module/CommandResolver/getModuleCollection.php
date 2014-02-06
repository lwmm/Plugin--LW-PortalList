<?php

namespace LwPortalList\Model\Module\CommandResolver;

class getModuleCollection
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalList\Services\Response::getInstance();
    }

    public static function getInstance($params = false, $data = false)
    {
        return new getModuleCollection($params, $data);
    }

    public function resolve()
    {
        $collection = array();
        
        $QH = new \LwPortalList\Model\Module\DataHandler\QueryHandler();
        $installationCount = $QH->getAmountOfPortalsWhereAModuleIsInstalled();
        $result = $QH->loadAllModules();
        
        foreach($result as $module){
            if($module["type"] == "plugin"){
                $type = "plugins";
            }elseif($module["type"] == "package"){
                $type = "packages";
            }
            $module["installs"] = $installationCount[$module["name"]];
            $entity = new \LwPortalList\Model\Module\Object\Module($module["id"]);
            $entity->setValues($module);
            $collection[$type][] = $entity;
        }
        
        $this->respone->setDataByKey("ModulesEntitiesCollection", $collection);
        return $this->respone;
    }

}
