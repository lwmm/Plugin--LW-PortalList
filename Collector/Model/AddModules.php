<?php

namespace LwPortalList\Collector\Model;

class AddModules
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }
    
    public function execute($id, $systemInfo)
    {
        return $this->addSystemInfoForEntityById($id, $systemInfo);
    }

    protected function addSystemInfoForEntityById($id, $systemInfo)
    {
        $this->deletePortalModuleConnections($id);

        foreach ($systemInfo["packages"] as $package) {
            if (!$this->isModuleExisting($package["packagename"], "package")) {
                $this->addModule($package["packagename"], "package");
            }
            $this->addPortalModuleConnection($id, $this->getModuleIdByName($package["packagename"]));
        }

        foreach ($systemInfo["plugins"] as $plugingroup) {
            foreach ($plugingroup as $plugin) {
                if (!$this->isModuleExisting($plugin["pluginname"], "plugin")) {
                    $this->addModule($plugin["pluginname"], "plugin");
                }
                $this->addPortalModuleConnection($id, $this->getModuleIdByName($plugin["pluginname"]));
            }
        }
    }

    protected function isModuleExisting($module, $type)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules WHERE name = :name AND type = :type ");
        $this->db->bindParameter("name", "s", trim($module));
        $this->db->bindParameter("type", "s", $type);

        $result = $this->db->pselect1();
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    protected function addModule($module, $type)
    {
        $this->db->setStatement("INSERT INTO t:lw_info_modules (name, type) VALUES (:name, :type) ");
        $this->db->bindParameter("name", "s", trim($module));
        $this->db->bindParameter("type", "s", $type);

        return $this->db->pdbquery();
    }

    protected function getModuleIdByName($module)
    {
        $this->db->setStatement("SELECT id FROM t:lw_info_modules WHERE name = :name ");
        $this->db->bindParameter("name", "s", trim($module));
        $result = $this->db->pselect1();

        return $result["id"];
    }

    protected function addPortalModuleConnection($portalId, $moduleId)
    {
        $this->db->setStatement("INSERT INTO t:lw_info_portals_modules (pid, mid) VALUES (:pid, :mid) ");
        $this->db->bindParameter("pid", "i", $portalId);
        $this->db->bindParameter("mid", "i", $moduleId);

        return $this->db->pdbquery();
    }

    protected function deletePortalModuleConnections($portalId)
    {
        $this->db->setStatement("DELETE FROM t:lw_info_portals_modules WHERE pid = :pid ");
        $this->db->bindParameter("pid", "i", $portalId);

        return $this->db->pdbquery();
    }

}
