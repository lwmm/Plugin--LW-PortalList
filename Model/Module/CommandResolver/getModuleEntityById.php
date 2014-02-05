<?php

namespace LwPortalList\Model\Module\CommandResolver;

class getModuleEntityById
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
        return new getModuleEntityById($params, $data);
    }

    public function resolve()
    {
        $QH = new \LwPortalList\Model\Module\DataHandler\QueryHandler();
        $result = $QH->loadModuleById($this->params["id"]);
        $entity = new \LwPortalList\Model\Module\Object\Module($this->params["id"]);
        $entity->setValues($this->prepareModules($result["id"], $result["name"]));
        $this->respone->setDataByKey("ModuleEntity", $entity);
        return $this->respone;
    }

    protected function prepareModules($mid, $moduleName)
    {
        $array = array("mid" => $mid, "modulename" => $moduleName);

        $QH = new \LwPortalList\Model\Module\DataHandler\QueryHandler();
        $result = $QH->loadPortalsWhereModuleIsInstalled($this->params["id"]);

        foreach ($result as $portal) {
            $array["portals"][] = array("id" => $portal["pid"], "name" => $portal["name"]);
        }

        return $array;
    }

}
