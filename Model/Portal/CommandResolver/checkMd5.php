<?php

namespace LwPortalList\Model\Portal\CommandResolver;

class checkMd5
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
        $this->request = \lw_registry::getInstance()->getEntry("request");
    }

    public static function getInstance($params = false, $data = false)
    {
        return new checkMd5($params, $data);
    }

    public function resolve()
    {
        $fileDataArray = $this->request->getFileData("inputFile");
        
        if (!empty($fileDataArray["name"])) {
            $csv = file_get_contents($fileDataArray["tmp_name"]);
        } else {
            $csv = $this->request->getRaw("configPath") . "," . $this->request->getRaw("path") . "," . $this->request->getRaw("expectedMd5") . ";";
            $this->respone->setDataByKey("postArray", array("configPath" => str_replace("CONFIG:path_", "", $this->request->getRaw("configPath")), "path" => $this->request->getRaw("path"), "expectedMd5" => $this->request->getRaw("expectedMd5")));
        }
        
        $collector = new \LwPortalList\Collector\InfoCollector(false); # Beim Md5 Abholen wird keine DB-Anbindung benoetigt

        if ($this->request->getInt("id") && $this->request->getInt("id") > 0) {
            $response = \LwPortalList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $collector->setEntity($entity);
        } else {
            $response = \LwPortalList\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
            $collection = $response->getDataByKey("PortalEntitiesCollection");
            $collector->setCollection($collection);
        }

        $files = $this->prepareFileArray($csv);
        $result = $collector->executeMd5($files);
        
        $this->respone->setDataByKey("Md5Results", $result);
        return $this->respone;
    }

    protected function prepareFileArray($csv)
    {
        $array = array();

        $lines = explode(";", $csv);
        unset($lines[count($lines) - 1]);

        foreach ($lines as $line) {
            $lineContent = explode(",", $line);
            if (count($lineContent) == 3) {
                if (strstr($lineContent[0], "CONFIG:path_")) {
                    $lineContent[0] = str_replace("CONFIG:path_", "", $lineContent[0]);
                } else if (strstr($lineContent[0], "CONFIG:")) {
                    $lineContent[0] = str_replace("CONFIG:", "", $lineContent[0]);
                }

                $array[] = array(
                    "configPath" => trim($lineContent[0]),
                    "path" => trim($lineContent[1]),
                    "expectedMd5" => trim($lineContent[2])
                );
            }
        }
        return $array;
    }

}
