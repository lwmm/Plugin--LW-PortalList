<?php

namespace LwPortalList\Model\Portal\DataHandler;

class StorageHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function addEntity($values)
    {
        if (substr($values["url"], -1) == "/") {
            $url = $values["url"];
        } else {
            $url = $values["url"] . "/";
        }

        $this->db->setStatement("INSERT INTO t:lw_info_portals (name, server, path, url, piwik_id) VALUES (:name, :server, :path, :url, :piwik_id) ");
        $this->db->bindParameter("name", "s", htmlentities($values["name"]));
        $this->db->bindParameter("server", "s", htmlentities($values["server"]));
        $this->db->bindParameter("path", "s", $values["path"]);
        $this->db->bindParameter("url", "s", $url);
        $this->db->bindParameter("piwik_id", "i", $values["piwik_id"]);

        $id = $this->db->pdbinsert($this->db->gt("lw_info_portals"));
        
        if($id > 0){
            return $id;
        }
        return false;
    }

    public function saveEntity($values, $id)
    {
        if (substr($values["url"], -1) == "/") {
            $url = $values["url"];
        } else {
            $url = $values["url"] . "/";
        }

        $this->db->setStatement("UPDATE t:lw_info_portals SET name = :name, server = :server, path = :path, url = :url, piwik_id = :piwik_id WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        $this->db->bindParameter("name", "s", htmlentities($values["name"]));
        $this->db->bindParameter("server", "s", htmlentities($values["server"]));
        $this->db->bindParameter("path", "s", $values["path"]);
        $this->db->bindParameter("url", "s", $url);
        $this->db->bindParameter("piwik_id", "i", $values["piwik_id"]);

        return $this->db->pdbquery();
    }

    public function deleteEntityById($id)
    {
        $this->deleteEntitysConnectionsInPortalsModules($id);
        
        $this->db->setStatement("DELETE FROM t:lw_info_portals WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);

        return $this->db->pdbquery();
    }
    
    public function deleteEntitysConnectionsInPortalsModules($id)
    {
        $this->db->setStatement("DELETE FROM t:lw_info_portals_modules WHERE pid = :pid ");
        $this->db->bindParameter("pid", "i", $id);
        
        return $this->db->pdbquery();
    }

}
