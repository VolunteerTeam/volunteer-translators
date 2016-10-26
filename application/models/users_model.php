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
        return $this->db->insert('users', $data);
    }

    //send verification email to user's email id
    function sendEmail($to_email)
    {
        $from_email = 'team@mydomain.com'; //change this to yours
        $subject = 'Verify Your Email Address';
        $message = 'Dear User,<br /><br />Please click on the below activation link to verify your email address.<br /><br /> http://www.mydomain.com/user/verify/' . md5($to_email) . '<br /><br /><br />Thanks<br />Mydomain Team';

        //configure email settings
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.mydomain.com'; //smtp host name
        $config['smtp_port'] = '465'; //smtp port number
        $config['smtp_user'] = $from_email;
        $config['smtp_pass'] = '********'; //$from_email password
        $config['mailtype'] = 'html';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $config['newline'] = "\r\n"; //use double quotes
        $this->email->initialize($config);

        //send mail
        $this->email->from($from_email, 'Mydomain');
        $this->email->to($to_email);
        $this->email->subject($subject);
        $this->email->message($message);
        return $this->email->send();
    }

    //activate user account
    function verifyEmailID($key)
    {
        $data = array('status' => 1);
        $this->db->where('md5(email)', $key);
        return $this->db->update('users', $data);
    }

    function getCityId($latlng = ""){
        if(!$latlng && $this->input->post('latlng')) {
            $this->db->select('id');
            $this->db->from('locations_google_api');
            $this->db->where("latlng = '" . $this->input->post('latlng') . "'");
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