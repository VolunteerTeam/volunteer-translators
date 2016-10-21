<?php


class Users_model extends MY_Model {

    var $table_name = 'users';
    var $search_fields = array('mail','name','surname');

    function __construct()
    {
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

    function get_list_extend($where = array(),$limit = 30,$offset = 0,$order_by = 'id',$order_mode = 'ASC')
    {
        $result = $this->get_list($where,$limit,$offset,$order_by,$order_mode);
        foreach($result as $k=>$v)
        {
            $result[$k]->meta = $this->seo_model->get_one(array('module_name'=>'user','subject_id'=>$v->id));
        }
        return $result;
    }

    function update_user($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }

    function remove_user($id) {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }

    function get_user_info($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id);
        return $this->db->get()->result();
    }

    function get_list_volonter()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id=users.id');
        $this->db->where('group_id', 4);
        $this->db->order_by("last_name", "asc"); 
        $this->db->group_by("users.id"); 
        $result = $this->db->get()->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v){
                $result[$k]->avatar = $this->gm->get_one(array('module_name'=>'user','subject_id'=>$v->user_id));
            }
            return $result;
        }
    }

     function get_list_manager()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id=users.id');
        $this->db->where('group_id', 3);
        $this->db->order_by("last_name", "asc"); 
        $this->db->group_by("users.id"); 
        $result = $this->db->get()->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v){
                $result[$k]->avatar = $this->gm->get_one(array('module_name'=>'users','subject_id'=>$v->user_id));
            }
            return $result;
        }
    }

    function get_list_sovet()
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id=users.id');
        $this->db->or_where('group_id', 5);
        $this->db->order_by("last_name", "asc"); 
        $this->db->group_by("users.id"); 
        $result = $this->db->get()->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v){
                $result[$k]->avatar = $this->gm->get_one(array('module_name'=>'users','subject_id'=>$v->user_id));
            }
            return $result;
        }
    }

}