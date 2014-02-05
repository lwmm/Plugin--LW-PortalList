<?php

namespace LwPortalList\Model\Portal\DataHandler;

class QueryHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }
    
    public function loadAllPortals()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals ORDER BY name ");
        return $this->db->pselect();
    }
    
    public function loadPortalById($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect1();
    }
    
    public function loadPortalModules($id)
    {
        $this->db->setStatement("SELECT m.* FROM t:lw_info_portals_modules pm, t:lw_info_modules m WHERE pm.pid = :pid AND pm.mid = m.id ");
        $this->db->bindParameter("pid", "i", $id);

        return $this->db->pselect();
    }
    
    public function getCountOfPortalByPiwikId($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals WHERE piwik_id = :id ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect();
    }
    
    public function loadAcutalPortalStats($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals_stats WHERE portal_id = :id ORDER BY date DESC ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect(0,1);
    }

}
