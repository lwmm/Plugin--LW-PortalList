<?php

namespace LwPortalList\Model\Module\DataHandler;

class QueryHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }
    
    public function loadAllModules()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules ORDER BY name");
        return $this->db->pselect();
    }
    
    public function loadModuleById($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect1();
    }
    
    public function loadPortalsWhereModuleIsInstalled($id)
    {
        $this->db->setStatement("SELECT pm.*, p.name FROM t:lw_info_portals_modules pm, t:lw_info_portals p WHERE pm.mid = :id AND p.id = pm.pid ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect();
    }
    
    public function getAmountOfPortalsWhereAModuleIsInstalled()
    {
        $this->db->setStatement("SELECT m.name, COUNT(pm.pid) as portals  FROM t:lw_info_portals_modules pm, t:lw_info_modules m WHERE pm.mid = m.id  GROUP BY pm.mid ");
        $result = $this->db->pselect();
        
        $array = array();
        foreach($result as $module){
            $array[$module["name"]] = $module["portals"];
        }
        
        return $array;
    }
    
}
