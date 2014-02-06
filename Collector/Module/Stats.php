<?php

namespace LwPortalList\Collector\Module;

class Stats extends \LwPortalList\Collector\Model\StatsBase
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function execute($id, $stats)
    {
        $this->addStats($id, $stats);
    }

}
