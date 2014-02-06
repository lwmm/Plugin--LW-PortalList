<?php

namespace LwPortalList\Views;

class PortalDetail
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalDetail.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function setIsAdmin($bool)
    {
        $this->view->isAdmin = $bool;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        
        $this->view->mediaUrl = $config["url"]["media"];
        $this->view->piwikVisitChart = $config['piwik']['base'].
                        "&method=ImageGraph.get".
                        "&graphType=evolution".
                        "&idSite=".$this->view->entity->getValueByKey("piwik_id").
                        "&period=day".
                        "&date=last30".
                        "&apiModule=VisitsSummary".
                        "&apiAction=get".
                        "&showLegend=1".
                        "&width=680".
                        "&height=200".
                        "&fontSize=9".
                        "&aliasedGraph=1".
                        "&token_auth=".$config['piwik']['token_auth'];
        
        
        return $this->view->render();
    }

}
