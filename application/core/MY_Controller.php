<?php

class MY_Controller extends CI_Controller {


    var $cache_prefix = 'production_';

    function __construct($config = 'front_header')
    {
        parent::__construct();


        $this->load->driver('cache', array('adapter' => 'dummy'));
        $this->load->library('Translit');
        $this->load->library('Seo');

        $this->load->config($config,true);

        $this->page['meta_title']       =  $this->config->item('meta_title',$config);
        $this->page['meta']             =  $this->config->item('meta',$config);
        $this->page['js']               =  $this->config->item('js',$config);
        $this->page['css']              =  $this->config->item('css',$config);
        $this->page['inline_js']        =  $this->config->item('inline_js',$config);

        $this->controller = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();
        $this->page['tpl'] = 'dashboard';
        $this->matrix_route  = $this->get_matrix_route();
        $this->matrix_meta  = $this->get_matrix_meta();
        $this->advanced_title = "";
        $this->page['inline_js'][] = '
            ';
        $this->load->model('gallery_model','gm');
        $this->load->model('menu_model');
        $this->load->model('main_model');
        $this->load->model('warehouse_model');
        $this->page['current_uri'] =  '';
        $this->page['navigation'] = $this->menu_model->get_navigation();
        $this->page['header_information'] = $this->warehouse_model->get_one(array('title'=>USERCITY));
        $this->page['shared_contacts'] = $this->main_model->get_one(array('id'=>1));
		
		$this->load->library('session');
		$cj = $this->session->userdata('mail');
		
        $this->load->model('users');

        $data = $this->users->profile($cj);

		$this->page['is_auth'] = false;
		if($cj == true) {
			$this->page['is_auth'] = true;
            $this->page['NameAndSename'] = $data[0]['first_name'].' '.$data[0]['last_name'];
		}
    }


    /**
     * Метод отвечает за вывод информации, попутно подключая служебные js и css файлы
     */
    function render()
    {

        if(file_exists(FCPATH.'/js/front/'.$this->controller.'.js'))
        {
            $this->page['js']['footer'][] = array('src'=>'/js/front/'.$this->controller.'.js');
        }

        if(file_exists(FCPATH.'/css/front/'.$this->controller.'.css'))
        {
            $this->page['css'][] = array('src'=>'/css/front/'.$this->controller.'.css');
        }

        $this->page['content']['action_dir'] = $this->controller;
        $this->page['content']['action_tpl'] = $this->method;
        $this->load->view('front',  $this->page);
    }


    function get_matrix_route()
    {
        $data = array();
        $result = $this->db->get('seo');
        if($result->num_rows()>0)
        {
            foreach($result->result() as $row)
            {
                $data[$row->module_name][$row->subject_id] = $row->url;
            }
        }

        return $data;
    }

    function get_matrix_meta()
    {
        $data = array();
        $result = $this->db->get('seo');
        if($result->num_rows()>0)
        {
            foreach($result->result() as $row)
            {
                $data[$row->module_name][$row->subject_id] = array(
                    'title'         =>array('content'=>strip_tags($row->title)),
                    'description'   =>array('content'=>$row->description),
                    'keywords'      =>array('content'=>$row->keywords)
                );
            }
        }

        return $data;
    }

    function get_parent_menu_item($target)
    {
        $menu_stack = array();
        $result = $this->db->get('navigation')->result();
        if(!empty($result))
        {
            foreach($result as $k=>$item){
                $menu_stack[$item->id] = $item;
            }


            foreach ($menu_stack as $id=>$menu_data) {
                if(preg_match('/'.$target.'/',$menu_data->url)){
                    return ($menu_data->pid!=0) ?  trim($menu_stack[$menu_data->pid]->url,"/") : trim($menu_data->url,"/");
                }

            }

        }
        return $target;

    }

}