<?php


class Users_model extends MY_Model {

    var $table_name = 'users';
    var $search_fields = array('mail','name','surname');

    function __construct()
    {
        parent::__construct();
    }

    //insert into user table
    function insertUser($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    function socialUserExists($social_id, $provider){
        $provider_id = $this->getProviderId($provider);

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("social_id = '" . $social_id . "' and provider = '" . $provider_id . "'");
        $query = $this->db->get();
        if(!empty($query->result_array())) return $query->result_array()[0];
        return false;
    }

    function getProviderId($provider){
        $this->db->select('id');
        $this->db->from('providers');
        $this->db->where("name = '" . $provider . "'");
        return intval($this->db->get()->row()->id);
    }

    function getUserGroups($id){
        $this->db->select('groups.description');
        $this->db->from('users_groups');
        $this->db->where("user_id = '" . $id . "'");
        $this->db->join("groups","users_groups.group_id=groups.id","left");
        return $this->db->get()->result();
    }

    function getSexTypeId($sex){
        $this->db->select('id');
        $this->db->from('sex');
        $this->db->where("name_ru = '" . $sex . "' or name_en = '" . $sex . "'");
        $query = $this->db->get();
        if(!empty($query->row())) return intval($query->row()->id);
        return NULL;
    }

    function socialUserData($auther){
        $salt = rand(2589,195568);
        if($auther->getEmail()){
            $email = $auther->getEmail();
            $email_confirm = 1;
        } else {
            $email = "social_profile";
            $email_confirm = 0;
        }
        return array(
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'password' => md5($salt."social_profile".$salt),
            'secret_key' => sha1($salt.$email),
            'salt' => $salt,
            "active" => 1,
            "provider" => $this->getProviderId($auther->getProviderShortName()),
            "social_id" => $auther->getSocialId(),
            "first_name" => $auther->getFirstName(),
            "last_name" => $auther->getLastName(),
            "email" => $email,
            "email_confirm" => $email_confirm,
            $auther->getProviderShortName()."_profile" => $auther->getSocialPage(),
            "sex_type" => $this->getSexTypeId($auther->getSex()),
            "dob" => $auther->getBirthday() ? date('Y-m-d', strtotime($auther->getBirthday())) : NULL,
//            "avatar" => $auther->getAvatar()
        );
    }

    function createSocialUser($auther){
        $data = $this->socialUserData($auther);
        $data['created_on'] = time();
        $this->db->insert('users', $data);
        return $data;
    }

    //activate user account
    function verifyEmailID($params)
    {
        $email = $this->security->xss_clean($params["email"]);
        $secret_key = $this->security->xss_clean($params["s"]);
        if($email && $secret_key) {
            $this->db->select('id,secret_key');
            $this->db->from('users');
            $this->db->where("email = '".$email."'");
            $query = $this->db->get();
            if (!empty($query->result_array())){
                $result = $query->row();
                if($result->secret_key == $secret_key){
                    $this->db->where('id', $result->id);
                    $this->db->update('users', array("email_confirm" => 1,"active" => 1));
                    return true;
                }
            }
        }
        return false;
    }

    function emailExists($email){
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where("email = '".$email."'");
        $query = $this->db->get();
        if (!empty($query->result_array())) return true;
        return false;
    }

    function getCityId($place_id = ""){
        if(!$place_id && $this->input->post('place_id')) {
            $this->db->select('id');
            $this->db->from('locations_google_api');
            $this->db->where("place_id = '" . $this->input->post('place_id') . "'");
            $query = $this->db->get();
            if (empty($query->result_array())) {
                $data = array(
                    'location' => $this->input->post('city'),
                    'country' => $this->input->post('country'),
                    'country_short' => $this->input->post('country_short'),
                    'latlng' => $this->input->post('latlng'),
                    'place_id' => $this->input->post('place_id'),
                );
                $this->db->insert('locations_google_api', $data);
                return $this->db->insert_id();
            } else {
                $result = $query->row();
                return $result->id;
            }
        }
    }

    function getCity($city_id){
        $this->db->select('*');
        $this->db->from('locations_google_api');
        $this->db->where("id = '" . $city_id . "'");
        $query = $this->db->get();
        if(empty($query->result_array())){
            return array(
                "location" => "",
                "country" => "",
                "country_short" => "",
                "latlng" => "",
                "place_id" => "",
                "city" => ""
            );
        } else {
            return $query->result_array()[0];
        }
    }

    function getUserName($id){
        $this->db->select('first_name, last_name');
        $this->db->from('users');
        $this->db->where("id = '" . $id . "'");
        $query = $this->db->get();
        $result = $query->row();
        return $result->first_name . " " . $result->last_name;
    }


    function setGroup($user_id,$group_id){
        $this->db->insert('users_groups', array("user_id" => $user_id, "group_id" => $group_id));
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