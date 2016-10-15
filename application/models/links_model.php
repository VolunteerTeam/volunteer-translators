<?php

class Links_model extends CI_Model{


    function __construct()
    {
        parent::__construct();
    }

    function get_links()
    {
        $result = $this->db->get('routes');
        if($result->num_rows()>0)
        {
            return $result->result();
        }

        return false;
    }


    function get_link($id)
    {
        $result = $this->db->get_where('routes',array('id'=>$id));
        if($result->num_rows()>0)
        {
            return $result->row();
        }

        return false;

    }


    function get_link_by_path($string)
    {
        $result = $this->db->get_where('links',array('path'=>$string));
        if($result->num_rows()>0)
        {
            return $result->row();
        }
        return false;

    }


    function add_link($data = array())
    {
        $this->db->insert('routes',$data);
        if($this->db->affected_rows()>0)
        {
            return $this->db->insert_id();
        }
        return false;
    }


    function add_links($data)
    {
        $this->db->insert_batch('routes',$data);
        if($this->db->affected_rows()>0)
        {
            return true;
        }
        return false;
    }



    function update_link($id,$data = array())
    {
        $result = $this->db->update('routes',$data,array('id'=>$id));
        if($result)
        {
            return true;
        }
        return false;
    }


    function delete_link($id)
    {

        if( ($link_data = $this->get_link($id))!==false )
        {
            $this->db->delete('routes',array('id'=>$id));
            if($this->db->affected_rows()>0)
            {
                return true;
            }
            return false;

        }

        return false;

    }



    function get_titles()
    {
        $result = $this->db->get('titles');
        if($result->num_rows()>0)
        {
            return $result->result();
        }

        return false;
    }


    function get_title($id)
    {
        $result = $this->db->get_where('titles',array('id'=>$id));
        if($result->num_rows()>0)
        {
            return $result->row();
        }

        return false;

    }


    function get_title_by_path($string)
    {

       $string = ($string=='/index/index') ? '/index/': $string;


        $result = $this->db->get_where('titles',array('path'=>$string));
        if($result->num_rows()==1)
        {
            return $result->row();

        }
        return false;

    }



    function verify_title($path)
    {

        $result = $this->db->get_where('routes',array('path'=>'/'.trim($path,'/')));
        if($result->num_rows()==1)
        {
            return $result->row();
        }

        return false;

    }


    function add_title($data = array())
    {
        $this->db->insert('titles',$data);
        if($this->db->affected_rows()>0)
        {
            return $this->db->insert_id();
        }
        return false;
    }


    function update_title($id,$data = array())
    {
        $result = $this->db->update('titles',$data,array('id'=>$id));
        if($result)
        {
            return true;
        }
        return false;
    }


    function delete_title($id)
    {

        if( ($title_data = $this->get_title($id))!==false )
        {
            $this->db->delete('titles',array('id'=>$id));
            if($this->db->affected_rows()>0)
            {
                return true;
            }
            return false;

        }

        return false;

    }



}