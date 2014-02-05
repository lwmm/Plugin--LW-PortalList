<?php

namespace LwPortalList\Model\Portal\CommandResolver;

class getPortalEntityFromPostArray
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
        return new getPortalEntityFromPostArray($params, $data);
    }
    
    public function resolve()
    {       
        $id = false;
        if(isset($this->params["id"])){
            $id = $this->params["id"];
        }
        
        $entity = new \LwPortalList\Model\Portal\Object\Portal($id);
        $entity->setValues($this->data["postArray"]);     

        $this->respone->setDataByKey("PortalEntity", $entity);
        return $this->respone;
    }
}