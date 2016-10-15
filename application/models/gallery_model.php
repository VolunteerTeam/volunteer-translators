<?php

class Gallery_model extends MY_Model{

    var $table_name = 'gallery';


    function __construct()
    {

        parent::__construct();
    }



    function get_categories()
    {
        $this->db->order_by('position');
        $result = $this->db->get('gallery_category');
        if($result->num_rows()>0)
        {
            return $result->result();
        }
        return false;

    }


    function get_category($id)
    {
        $result = $this->db->get_where('gallery_category',array('id'=>$id));
        if($result->num_rows()>0)
        {
            return $result->row();
        }
        return false;

    }


    function update_category($id,$data)
    {
        $result = $this->db->update('gallery_category',$data,array('id'=>$id));
        if($result)
        {
            return true;
        }
        return false;
    }

    /**
     * Добавление категории для галереи
     * @param $data
     * @return bool
     */
    function add_gallery_category($data)
    {
        $result = $this->db->insert('gallery_category',$data);
        if($result)
        {
            return $this->db->insert_id();
        }
        return false;

    }

    function get_gallery($conditions = array(),$order = 'position')
    {
        $this->db->order_by($order);

        $this->db->select("gallery.*");
        $this->db->select("gallery_category.name_translit");
        $this->db->join('gallery_category','gallery_category.id = gallery.category','left');
        $result = $this->db->get_where('gallery',$conditions);
        if($result->num_rows()>0)
        {
            return $result->result();
        }

        return false;
    }


    function get_max_position($field = 'position',$conditions = array())
    {

        $this->db->select_max($field);
        $this->db->where($conditions);
        $result = $this->db->get('gallery');

        if($result->num_rows()>0)
        {
            return $result->row();
        }
        return false;

    }



    function delete_gallery_item($where)
    {

        if( ($gallery_data = $this->get_one($where))!==false )
        {
            $path = FCPATH.'/'.CONTENT_DIR.'/';
            if(is_file($path.$gallery_data->filename))
            {
                unlink($path.$gallery_data->filename);
            }

            if(is_file($path.$gallery_data->cropped_filename))
            {
                unlink($path.$gallery_data->cropped_filename);
            }


            $result = $this->delete($where);
            if($result)
            {
                return true;
            }
            return false;
        }
        return true;

    }


}