<?php


class rating_model extends MY_Model {

    var $table_name = 'rating';
    var $search_fields = array();

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
        return $result;
    }

    function get_rating_volonter()
    {
        $this->db->select('
            rating.rating_year rating_year, rating.rating_month rating_month,
            rating.n1_value u1_value, 
            rating.n2_value u2_value, 
            rating.n3_value u3_value, 
            rating.n4_value u4_value, 
            rating.n5_value u5_value, 
            u1.first_name u1_first_name, u2.first_name u2_first_name, u3.first_name u3_first_name, u4.first_name u4_first_name, u5.first_name u5_first_name,
            u1.last_name u1_last_name, u2.last_name u2_last_name, u3.last_name u3_last_name, u4.last_name u4_last_name, u5.last_name u5_last_name,
            u1.username u1_username, u2.username u2_username, u3.username u3_username, u4.username u4_username, u5.username u5_username,
            g1.filename u1_avatar, g2.filename u2_avatar, g3.filename u3_avatar, g4.filename u4_avatar, g5.filename u5_avatar,
            g1.cropped_filename u1_cropped_avatar, g2.cropped_filename u2_cropped_avatar, g3.cropped_filename u3_cropped_avatar, g4.cropped_filename u4_cropped_avatar, g5.cropped_filename u5_cropped_avatar
            ');
        $this->db->from('rating');
        $this->db->join('users u1', 'u1.id=rating.n1_user_id'); $this->db->join('gallery g1', 'g1.subject_id=rating.n1_user_id');
        $this->db->join('users u2', 'u2.id=rating.n2_user_id'); $this->db->join('gallery g2', 'g2.subject_id=rating.n2_user_id');
        $this->db->join('users u3', 'u3.id=rating.n3_user_id'); $this->db->join('gallery g3', 'g3.subject_id=rating.n3_user_id');
        $this->db->join('users u4', 'u4.id=rating.n4_user_id'); $this->db->join('gallery g4', 'g4.subject_id=rating.n4_user_id');
        $this->db->join('users u5', 'u5.id=rating.n5_user_id'); $this->db->join('gallery g5', 'g5.subject_id=rating.n5_user_id');
        $this->db->where('rating_code', 'volonter');
        $this->db->order_by('rating_year desc, rating_month desc'); 
        $this->db->group_by("rating.id"); 
        $result = $this->db->get()->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v){
                $result[$k]->avatar = $this->gm->get_one(array('module_name'=>'users','subject_id'=>@$v->user_id));
            }
            return $result;
        }
        //return false;
    }

    function get_rating_manager()
    {
        $this->db->select('
            rating.rating_year rating_year, rating.rating_month rating_month,
            rating.n1_value u1_value, 
            rating.n2_value u2_value, 
            rating.n3_value u3_value, 
            rating.n4_value u4_value, 
            rating.n5_value u5_value, 
            u1.first_name u1_first_name, u2.first_name u2_first_name, u3.first_name u3_first_name, u4.first_name u4_first_name, u5.first_name u5_first_name,
            u1.last_name u1_last_name, u2.last_name u2_last_name, u3.last_name u3_last_name, u4.last_name u4_last_name, u5.last_name u5_last_name,
            u1.username u1_username, u2.username u2_username, u3.username u3_username, u4.username u4_username, u5.username u5_username,
            g1.filename u1_avatar, g2.filename u2_avatar, g3.filename u3_avatar, g4.filename u4_avatar, g5.filename u5_avatar,
            g1.cropped_filename u1_cropped_avatar, g2.cropped_filename u2_cropped_avatar, g3.cropped_filename u3_cropped_avatar, g4.cropped_filename u4_cropped_avatar, g5.cropped_filename u5_cropped_avatar
            ');
        $this->db->from('rating');
        $this->db->join('users u1', 'u1.id=rating.n1_user_id'); $this->db->join('gallery g1', 'g1.subject_id=rating.n1_user_id');
        $this->db->join('users u2', 'u2.id=rating.n2_user_id'); $this->db->join('gallery g2', 'g2.subject_id=rating.n2_user_id');
        $this->db->join('users u3', 'u3.id=rating.n3_user_id'); $this->db->join('gallery g3', 'g3.subject_id=rating.n3_user_id');
        $this->db->join('users u4', 'u4.id=rating.n4_user_id'); $this->db->join('gallery g4', 'g4.subject_id=rating.n4_user_id');
        $this->db->join('users u5', 'u5.id=rating.n5_user_id'); $this->db->join('gallery g5', 'g5.subject_id=rating.n5_user_id');
        $this->db->where('rating_code', 'manager');
        $this->db->order_by('rating_year desc, rating_month desc'); 
        $this->db->group_by("rating.id"); 
        $result = $this->db->get()->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v){
                $result[$k]->avatar = $this->gm->get_one(array('module_name'=>'users','subject_id'=>$v->user_id));
            }
            return $result;
        }
        //return false;
    }

}