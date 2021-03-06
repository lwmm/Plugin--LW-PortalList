<?php

namespace LwPortalList\Model\Portal\CommandResolver;

class delete
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
        return new delete($params, $data);
    }

    public function resolve()
    {
        $SH = new \LwPortalList\Model\Portal\DataHandler\StorageHandler();

        $ok = $SH->deleteEntityById($this->params["id"]);
        if ($ok) {
            $this->respone->setParameterByKey('deleted', true);
        } else {
            $this->respone->setDataByKey('error', 'error deleting');
            $this->respone->setParameterByKey('error', true);
        }
        return $this->respone;
    }

}
