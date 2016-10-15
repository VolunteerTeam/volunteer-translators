<?php

class Users extends MY_Controller {

    const PER_PAGE = 10;
    public $page = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('users_model','model');
        //$this->load->model('address_model');
        //$this->load->model('news_model');
    }

    function index($page = 1)
    {
        $where = array();
        $offset = 0;
        $this->page['current_uri'] =  $this->get_parent_menu_item('about');
        $this->page['items']   = $this->model->get_list_liders($where,1000,$offset,'last_name');
        $this->render();
    }

    function detail($id)
    {
        if( ($this->page['item'] =$item = $this->model->get_one(array('id'=>$id))) !==false)
        {
            $item->avatar = $this->gm->get_one(array('module_name'=>'users','group'=>'avatar','subject_id'=>$item->id));
        }
        else
        {
            show_404();
        }
        $this->page['current_uri'] =  $this->get_parent_menu_item('users');
        $this->page['meta']         = $this->matrix_meta[$this->controller][$item->id];
        $this->render();
    }

    function liders()
    {
        $where = array();
        $offset = 0;
        $this->page['current_uri'] =  $this->get_parent_menu_item('about');
        $this->page['items']   = $this->model->get_list_liders($where,1000,$offset,'last_name');
        $this->render();
    }

    function ajax_list()
    {

        $data['list_with_office'] = $this->model->get_list(array('status'=>1,'city_status'=>3),100,0,'title');
        $data['list_with_partner'] = $this->model->get_list(array('status'=>1,'city_status'=>1),100,0,'title');
        $data['list_with_agent'] = $this->model->get_list(array('status'=>1,'city_status'=>2),100,0,'title');
        $data['list'] = $this->model->get_list(array('status'=>1,'city_status'=>0),100,0,'title');
        $this->load->view('front/warehouse/ajax_list',$data);

    }

    function ajax_list_json()
    {
        $data['list'] = $this->model->get_list_extend(array('status'=>1),1000,0,'title');
        $data['result'] = true;
        echo json_encode($data);
    }

}