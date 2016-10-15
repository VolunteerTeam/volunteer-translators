<?php

class News_model extends MY_Model{


    var $table_name = 'news';
    var $rel_table_name = 'cities_news';

    var $search_fields = array('title','short_content','full_content');

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


    function get_current_cities($where = array())
    {
        $output = array();
        $result = $this->db->get_where($this->rel_table_name,$where)->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v)
            {
                $output[] = $v->city_id;
            }
        }
        return $output;
    }

    function get_current_news($where = array())
    {
        $output = array();
        $result = $this->db->get_where($this->rel_table_name,$where)->result();
        if(!empty($result))
        {
            foreach($result as $k=>$v)
            {
                $output[] = $v->news_id;
            }
        }
        return $output;
    }

    function get_relations_news($city_id)
    {
        $news_rel_list = $this->get_current_news(array('city_id'=>$city_id));

        if(!empty($news_rel_list)){


            $result = $this->db->order_by('id','desc')->limit(5)->where_in('id',$news_rel_list)->get($this->table_name)->result();
            return $result;

        }

        return false;
    }

    function get_list_extend($where = array(),$limit = 30,$offset = 0,$order_by = 'id',$order_mode = 'DESC'){

        $result = $this->get_list($where,$limit,$offset,$order_by,$order_mode);
        if(!empty($result))
        {
            foreach($result as $k=>$v){
                $result[$k]->image =  $this->gm->get_one(array('module_name'=>'news','group'=>'index','subject_id'=>$v->id));
            }
            return $result;
        }

        return false;
    }

}