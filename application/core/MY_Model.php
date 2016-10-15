<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
    
     /**
     * The database table to use.
     * @var string
     */
    public $table_name = '';
    

    function __construct() {
        parent::__construct();
    }
    


    function get_total($where = array())
    {
        if(!empty($where))
        {
            $this->db->where($where);
        }

        $result = $this->db->get($this->table_name)->num_rows();
        return $result;
    }


    function get_list($where = array(),$limit = 30,$offset = 0,$order_by = 'id',$order_mode = 'ASC')
    {

        if(!empty($where))
        {
            $this->db->where($where);
        }

        $result = $this->db->limit($limit)->offset($offset)->order_by($order_by,$order_mode)->get($this->table_name);
       return $result->num_rows()>0 ? $result->result() : false;
    }

    function get_kv_list($where = array(),$limit = 30,$offset = 0,$order_by = 'id',$order_mode = 'ASC')
    {
        if(!empty($where))
        {
            $this->db->where($where);
        }

        $result = $this->db->limit($limit)->offset($offset)->order_by($order_by,$order_mode)->get($this->table_name);
        $response = array();
        if(!empty($result))
        {
            foreach ($result->result() as $item) {
                $response[$item->id] = $item;

            }

        }
        return $response;
    }

    function get_one($where = array())
    {
        $result = $this->db->limit(1)->get_where($this->table_name,$where);
        return $result->num_rows()==1 ? $result->row():false;
    }

    function create($data)
    {
        $result = $this->db->insert($this->table_name,$data);
        return $result ? $this->db->insert_id() : false;
    }


    function update($where = array(),$data = array())
    {
        $result = $this->db->update($this->table_name,$data,$where);
        return $result;

    }


    function delete($where= array())
    {
        $result = $this->db->delete($this->table_name,$where);
        return $result;
    }



}
