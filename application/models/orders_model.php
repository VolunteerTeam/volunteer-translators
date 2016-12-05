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

    function getOrdersPortion($sort, $start, $stop){
        $sort = explode(" ",$sort);
        switch($sort[0]){
            case "created_on": $sort = "o.created_on ".$sort[1]; break;
            case "order_id": $sort = "o.id ".$sort[1]; break;
            case "user_name": $sort = "u.last_name ".$sort[1]; break;
            case "language_in": $sort = "l_in.name_ru ".$sort[1]; break;
            case "language_out": $sort = "l_out.name_ru ".$sort[1]; break;
            case "manager_name": $sort = "m.last_name ".$sort[1]; break;
            case "city": $sort = "loc.location ".$sort[1]; break;
            default: $sort = "o.created_on DESC"; break;
        }
        $sql = "SELECT o.id as order_id, o.created_on, o.date_in, o.date_out, o.client_user_id, o.manager_user_id, o.purpose, o.language_in, o.language_out,
                       u.id as user_id, u.first_name, u.last_name, u.city as ucity,
                       m.id as manager_id, m.first_name as manager_first_name, m.last_name as manager_last_name,
                       l_in.name_ru as language_in, l_out.name_ru as language_out,
                       loc.location as city, loc.place_id
                FROM orders AS o
                LEFT JOIN users AS u ON u.id = o.client_user_id
                LEFT JOIN users AS m ON m.id = o.manager_user_id
                LEFT JOIN languages AS l_in ON l_in.code = o.language_in
                LEFT JOIN languages AS l_out ON l_out.code = o.language_out
                LEFT JOIN locations_google_api AS loc ON loc.id = u.city
                ORDER BY ".$sort." LIMIT ".$start.",".$stop;
        $query = $this->db->query($sql);
        $result = array();
        if($query->result()) $result = $query->result();
//        var_dump($result);
//        exit;
        return $result;
    }

    function getOrdersCount(){
        $sql = "SELECT * FROM orders";
        $query = $this->db->query($sql);
        $result = 0;
        if($query->result()) $result = $query->num_rows();
        return $result;
    }

    function getOrder($id){
        $this->db->select('*');
        $this->db->from('orders');
        $this->db->where("id", $id);
        $result = $this->db->get()->result();
        return $result;
    }
}