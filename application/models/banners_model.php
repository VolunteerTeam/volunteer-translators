<?php

class Banners_model extends MY_Model{


    var $table_name = 'banners';

    var $search_fields = array('title');

    function __construct() {
        parent::__construct();

    }

    function search_total($query = '')
    {
        foreach($this->search_fields as $field)
        {
            $this->db->or_like($field,$query);
        }

        return $this->db->get($this->table_name)->num_rows();

    }

    function search_list($query = '',$limit = 30,$offset = 0,$order_by = 'id',$order_mode = 'ASC')
    {
        foreach($this->search_fields as $field)
        {
            $this->db->or_like($field,$query);
        }

        $result = $this->db->limit($limit)->offset($offset)->order_by($order_by,$order_mode)->get($this->table_name);
        return $result->num_rows()>0 ? $result->result() : false;
    }


}