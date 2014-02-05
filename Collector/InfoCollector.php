<?php

namespace LwPortalList\Collector;

class InfoCollector
{

    protected $db;
    protected $collection;
    protected $entity;

    public function __construct($db)
    {
        $this->db = $db;
        $this->collection = $this->entity = false;
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function execute()
    {
        if ($this->entity) {
            $this->getModules($this->entity);
            $this->getStats($this->entity);
        } elseif ($this->collection) {
            foreach ($this->collection as $entity) {
                $this->getModules($entity);
                $this->getStats($entity);
            }
        }

        return true;
    }

    protected function getModules($entity)
    {
        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }

        $json = file_get_contents($url . "index.php?getSystemInfo=1");
        $modules = json_decode($json, true);

        if (is_array($modules)) {
            $addModules = new \LwPortalList\Collector\Model\AddModules();
            return $addModules->execute($entity->getId(), $modules);
        }
    }

    protected function getStats($entity)
    {
        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }

        $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=getStats");
        $stats = json_decode($json, true);

        if (is_array($stats)) {
            $addStats = new \LwPortalList\Collector\Model\AddStats();
            return $addStats->execute($entity->getId(), $stats);
        }
    }

    public function executeMd5($files)
    {
        $array = array();

        if ($this->entity) {
            $array[$this->entity->getValueByKey("name")] = $this->compareFiles($this->entity, $files);
        } elseif ($this->collection) {
            foreach ($this->collection as $entity) {
                $array[$entity->getValueByKey("name")] = $this->compareFiles($entity, $files);
            }
        }

        return $array;
    }

    protected function compareFiles($entity, $files)
    {
        $array = array();

        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }
        foreach ($files as $file) {
            $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=getMd5&configPath=" . $file["configPath"] . "&filePath=" . urlencode($file["path"]) . "&expectedMd5=" . $file["expectedMd5"]);
            $result = json_decode($json, true);
            if (is_array($result)) {
                $array[] = $result;
            }
        }

        return $array;
    }

}
