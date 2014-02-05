<?php

namespace LwPortalList\Model\Portal\CommandResolver;

class add
{

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
        return new add($params, $data);
    }

    public function resolve()
    {
        $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityFromPostArray::getInstance(array(), array("postArray" => $this->data["postArray"]))->resolve();
        $entity = $response->getDataByKey("PortalEntity");

        $isValidSpecification = \LwPortalList\Model\Portal\Specification\isValid::getInstance();
        if ($isValidSpecification->isSatisfiedBy($entity)) {
            $SH = new \LwPortalList\Model\Portal\DataHandler\StorageHandler();
            $id = $SH->addEntity($entity->getValues());

            $this->respone->setParameterByKey('saved', true);
            $this->respone->setParameterByKey('id', $id);
        } else {
            $this->respone->setDataByKey('error', $isValidSpecification->getErrors());
            $this->respone->setParameterByKey('error', true);
        }

        return $this->respone;
    }

}
