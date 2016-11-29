<?php

class Orders_model extends MY_Model {
    var $table_name = 'orders';

    function __construct()
    {
        parent::__construct();
    }

    function getLanguages(){
        $this->db->select('*');
        $this->db->from('languages');
        $this->db->order_by("name_ru", "asc");
        $result = $this->db->get()->result();
        return $result;
    }

    function create($data){
        $this->db->insert('orders', $data);
        return $this->db->insert_id();
    }

    function addTranslation($data){
        $this->db->insert('translations', $data);
        return $this->db->insert_id();
    }
}