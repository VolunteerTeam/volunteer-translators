<?php

class Menu_model extends MY_Model{

    var $table_name = 'navigation';

    function __construct()
    {
        parent::__construct();
    }

    function get_navigation()
    {
        $top  = $this->get_list(array('status'=>1,'pid'=>0),1000,0,'position','asc');
        foreach($top as $k=>$item)
        {
            $top[$k]->submenu = $this->get_list(array('pid'=>$item->id,'status'=>1),1000,0,'position','asc');
        }
        return $top;
    }

    function find_top($url)
    {
        $item = $this->get_one(array('url'=>'/'.$url));
        if(!empty($item) && $item->pid!=0){
            $top_item = $this->get_one(array('id'=>$item->pid));
            return trim($top_item->url,"/");
        }
        return trim($item->url,"/");
    }
}

