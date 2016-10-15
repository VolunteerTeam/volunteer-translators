<?php

class Warehouse_model extends MY_Model{


    var $table_name = 'cities';

    var $search_fields = array('title');

    function __construct() {
        parent::__construct();
        $this->load->model('seo_model');

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

    function get_list_extend($where = array(),$limit = 30,$offset = 0,$order_by = 'id',$order_mode = 'ASC')
    {
        $result = $this->get_list($where,$limit,$offset,$order_by,$order_mode);
        foreach($result as $k=>$v)
        {
            $result[$k]->meta = $this->seo_model->get_one(array('module_name'=>'warehouse','subject_id'=>$v->id));
        }
        return $result;
    }


}