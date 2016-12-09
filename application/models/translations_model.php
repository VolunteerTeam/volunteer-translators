<?php

class Translations_model extends MY_Model {
    var $table_name = 'orders';

    function __construct()
    {
        parent::__construct();
    }

    function create($data){
        $this->db->insert('translations', $data);
        return $this->db->insert_id();
    }

    function update($data, $order_id){
        $this->db->where('id', $order_id);
        $this->db->update('translations', $data);
    }

    function delete($id){
        $this->db->where('id', $id);
        $this->db->delete('translations');
    }

    function getTranslationsPortion($sort, $start, $stop, $translator_user_id=null){
        $sort = explode(" ",$sort);
        switch($sort[0]){
            case "created_on": $sort = "t.created_on ".$sort[1]; break;
            case "name_in": $sort = "t.name_in ".$sort[1]; break;
            case "name_out": $sort = "t.name_out ".$sort[1]; break;
            case "language_in": $sort = "l_in.name_ru ".$sort[1]; break;
            case "language_out": $sort = "l_out.name_ru ".$sort[1]; break;
            case "manager_name": $sort = "u.last_name ".$sort[1]; break;
            default: $sort = "t.created_on DESC"; break;
        }
        $sql = "SELECT t.id, t.created_on, t.date_in, t.date_out, t.name_in, t.name_out, t.file_in, t.file_out, t.volume_in, t.order_id,
                       o.manager_user_id, o.date_out AS order_date_out, u.first_name, u.last_name,
                       l_in.name_ru AS language_in, l_out.name_ru AS language_out
                FROM translations AS t
                LEFT JOIN orders AS o on o.id = t.order_id
                LEFT JOIN users AS u on u.id = o.manager_user_id
                LEFT JOIN languages AS l_in on l_in.code = o.language_in
                LEFT JOIN languages AS l_out on l_out.code = o.language_out ";
        if($translator_user_id) $sql .= "WHERE t.translator_user_id = '".$translator_user_id."' ";
        $sql .= "ORDER BY ".$sort." LIMIT ".$start.",".$stop;
        $query = $this->db->query($sql);
        $result = array();
        if($query->result()) $result = $query->result();
        return $result;
    }

    function getTranslationsCount(){
        $sql = "SELECT * FROM translations";
        $query = $this->db->query($sql);
        $result = 0;
        if($query->result()) $result = $query->num_rows();
        return $result;
    }

}