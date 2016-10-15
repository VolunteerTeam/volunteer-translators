<?php

class Admin_model extends CI_Model{


    function __construct()
    {
        parent::__construct();
    }


    function calculate_cart(&$disallow_bonus,$discount,$custom_cart = false)
    {


        //Содержимое корзины
        $cart = !empty($custom_cart) ?  json_decode($custom_cart) : json_decode($_COOKIE['cart']);



        //Начинаем калькулировать содержимое
        $total_price = 0;
        $total_discount = 0;


        $calculated_list = array();

        foreach($cart as $id=>$item)
        {
            $data = $this->db->query("
                    SELECT a.*,n.title,n.imgsmall,n.imgsingle,n.no_in_action
                    FROM artikuls a
                    LEFT JOIN nomenclatura n
                    ON n.id=a.nomenclature_id
                    WHERE a.id=?",
                array($id))->row_array();


            $data['dvalue'] = 0;
            //Если есть позиция в корзине, которая блокирует другие акции - значит считаем цену итоговую отдельно
            if($data['no_in_action']==1)
            {
                $disallow_bonus = true;


                $data['amount']=$item->amount;
                $total_price += $item->amount*$data['price'];
                $data['dprice'] = $data['price'];
            }
            //Если есть скидка - считаем у всех остальных позиций скидку
            elseif($discount and $data['no_in_action']!=1) {

                $disallow_bonus = true;
                $data['amount']=$item->amount;

                $total_price += $item->amount*$data['price']*0.9;
                $data['dprice'] = $data['price']*0.9;
                $total_discount +=  $item->amount*$data['price']*0.1;

                $data['dvalue'] = $data['price']*0.1;
                //Добавляем атрибут - чтобы бонусная позиция не появлялась
                $data['no_in_action'] = 1;

            }
            //Обычное поведение
            else{

                $data['amount']=$item->amount;
                $total_price += $item->amount*$data['price'];
                $data['dprice'] = $data['price'];
            }

            $calculated_list[] = $data;

        }

        return  array('calculated'=>$calculated_list,'total_price'=>$total_price,'total_discount'=>$total_discount);


    }





    /**
     * Собираем в большой массив всю инфу о роутах
     * @return array
     */
    function get_matrix_route()
    {
        $data = array();
        $result = $this->db->get('routes');
        if($result->num_rows()>0)
        {
            foreach($result->result() as $row)
            {

                $data[$row->class][$row->method][$row->parameters] = $row->path;
            }

        }

        return $data;

    }




    /**
     * Получаем данные для главной страницы
     */
    function get_blocks_mainpage($where = false)
    {


        if($where != false)
        {
            $this->db->where($where);
        }
        $this->db->select("module_mainpage.*,content_types.helper_function,content_types.css_class");
        $this->db->join('content_types','content_types.id=module_mainpage.content_type','left');

        $result = $this->db->get('module_mainpage');


        if($result->num_rows()>0)
        {
            return $result->result();
        }
        return false;
    }





    /**
     * Получаем необходимый блок
     * @param array $where
     * @return bool
     */
    function get_block_mainpage($where = array())
    {
        $result = $this->db->get_where('module_mainpage',$where);
        if($result->num_rows()==1)
        {


            return $result->row_array();
        }
        return false;
    }



    function get_prepared_blocks(&$var)
    {
        $result = $this->db->get_where('module_mainpage',array('parent_block !='=>0,'status'=>1));
        if($result->num_rows()>0)
        {

            foreach($result->result() as $row)
            {
                $var[$row->block_name] = $row;
            }
            return $var;
        }


    }


    function get_list_block_mainpage($where = array())
    {
        $result = $this->db->get_where('module_mainpage',$where);
        if($result->num_rows()>0)
        {
            return $result->result();
        }
        return false;
    }


    /**
     * Обновляем информацию в блоке
     * @param $block_id
     * @param array $data
     * @return bool
     */
    function update_blocks_mainpage($data=array(),$where = 'block_name')
    {
        $result = $this->db->update_batch('module_mainpage',$data,$where);

        return true;
    }

    function update_block($id,$data=array())
    {
        $result = $this->db->update('module_mainpage',$data,array('id'=>$id));

        return true;
    }



    /**
     * Удалеяем блоки
     * @param array $block_ids
     * @return bool
     */
    function delete_blocks_mainpage($block_ids = array())
    {
        if(!is_array($block_ids))
        {
            $block_ids = array($block_ids);
        }

        $result = $this->db->delete('module_mainpage',$block_ids);
        if($result)
        {
            return true;
        }
        return false;
    }



    function get_navigation_menu()
    {
        $this->db->order_by('position');
        $result = $this->db->get('navigation_menu');

        if($result->num_rows()>0)
        {
            return $result->result();
        }
        return false;
    }


    function update_navigation_menu($id,$data)
    {
        $result = $this->db->update('navigation_menu',$data,array('id'=>$id));
        if($result)
        {
            return true;
        }
        return false;
    }


    function get_navigation_item($where)
    {
        $result = $this->db->get_where('navigation_menu',$where);
        if($result->num_rows()==1)
        {
            return $result->row();
        }
        return false;

    }







    function get_nav($id=0,$current = 'index',$current_child=false)
    {

        $result = $this->db->get_where('navigation_menu',array('parent_id'=>$id));


        if($result->num_rows()>0)
        {
            foreach($result->result_array() as $k=> $nav_item)
            {

                if($current_child!=false AND $current==$nav_item['mark'])
                {

                    $childs = $this->get_nav($nav_item['id'],$current_child,false);
                }
                else
                {
                    $childs = false;
                }

                $nav_data[$k] = array(
                    'url'       =>$nav_item['mark'],
                    'title'     =>$nav_item[SITELANG.'_title'],
                    'class'     =>($current==$nav_item['mark'])? ' current ':'' ,
                    'childs'    =>$childs


                );

            }

            return $nav_data;
        }

        return false;


    }


    /**
     * Добавляем контакт
     * @param $data
     * @return bool
     */
    function add_contact($data)
    {

        $result = $this->db->insert('contacts',$data);
        if($result)
        {
            return $this->db->insert_id();

        }

        return false;
    }



    /**
     * Получаем контакт
     * @param $where
     * @return bool
     */
    function get_contact($where)
    {

        $result = $this->db->get_where('contacts',$where);
        if($result->num_rows()==1)
        {
            return $result->row();

        }

        return false;
    }

    /**
     * Получаем список контактов
     * @param $where
     * @return bool
     */
    function get_contacts($where = false)
    {
        if($where != false)
        {
            $this->db->where($where);
        }
        $this->db->order_by('position');

        $result = $this->db->get('contacts');
        if($result->num_rows()>0)
        {
            return $result->result();

        }

        return false;
    }

    /**
     * Обновляем контакт
     * @param $id
     * @param $data
     * @return bool
     */
    function update_contact($id,$data)
    {
        $result = $this->db->update('contacts',$data,array('id'=>$id));
        if($result)
        {
            return true;
        }
        return false;

    }

    /**
     * Удаляем контакт
     * @param $id
     * @return bool
     */
    function delete_contact($id)
    {

        if( ($contact_data = $this->get_contact(array('id'=>$id)))!==false )
        {

            if(file_exists(SDIR.'/content/'.$contact_data->image_filename))
            {
                unlink(SDIR.'/content/'.$contact_data->image_filename);
            }

            $result = $this->db->delete('contacts',array('id'=>$id));
            if($result)
            {
                return true;

            }
        }



        return false;
    }



    function get_settings($module = 'shop')
    {
        $result = $this->db->get_where('settings',array('module'=>$module));
        if($result->num_rows()==1)
        {

            $data = $result->row()->parameters;

            return unserialize($data);

        }
        return false;

    }


    function update_settings($module,$data)
    {

        $result = $this->db->update('settings',array('parameters'=>serialize($data)),array('module'=>$module));
        if($result)
        {
            return true;
        }
        return false;

    }


    function get_coords_marker_types()
    {
        $result = $this->db->get('coordinates_marker_types');
        if($result->num_rows()>0)
        {
            return $result->result();
        }
        return false;
    }


    function get_marker_type_by_pk($id)
    {
        $result = $this->db->get_where('coordinates_marker_types',array('cmt_id'=>$id));
        if($result->num_rows()==1)
        {
            return $result->row();
        }
        return false;
    }



    function get_coords_by_pk($id)
    {
        $this->db->join('coordinates_marker_types','coordinates_marker_types.cmt_id=coordinates.marker_type','left');
        $result = $this->db->get_where('coordinates',array('id'=>$id));
        if($result->num_rows()==1)
        {
            return $result->row();
        }
        return false;
    }


    function get_coords($where = false)
    {

        if($where !=false)
        {
            $this->db->where($where);
        }
        $this->db->join('coordinates_marker_types','coordinates_marker_types.cmt_id=coordinates.marker_type','left');
        $result = $this->db->get('coordinates');
        if($result->num_rows()>0)
        {
            return $result->result();
        }
        return false;
    }

    function add_coords($data)
    {
        $result = $this->db->insert('coordinates',$data);
        if($result)
        {
            return $this->db->insert_id();
        }
        return false;
    }

    function update_coords($where,$data)
    {
        $result = $this->db->update('coordinates',$data,$where);
        if($result)
        {
            return true;
        }
        return false;
    }


    function delete_coords($id)
    {
        if($this->get_coords_by_pk($id)!==false)
        {
            $result = $this->db->delete('coordinates',array('id'=>$id));
            if($result)
            {
                return true;
            }
        }
        return false;
    }




    function get_social_buttons()
    {
        $this->db->order_by('position');
        $result = $this->db->get('social_buttons');
        if($result->num_rows()>0)
        {
            return $result->result();

        }

        return false;
    }


    function get_social_button($where)
    {
        $result = $this->db->get_where('social_buttons',$where);
        if($result->num_rows()==1)
        {
            return $result->row();
        }
        return false;
    }

    function update_social_button($id,$data)
    {
        $result = $this->db->update('social_buttons',$data,array('id'=>$id));
        if($result)
        {
            return true;
        }
        return false;
    }


    function delete_social_button($id)
    {
        if($this->get_social_button(array('id'=>$id))!=false)
        {
            $result = $this->db->delete('social_buttons',array('id'=>$id));
            if($result)
            {
                return true;
            }
        }
        return false;
    }





}