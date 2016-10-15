<?php

class Volonter extends MY_Controller {

    const PER_PAGE = 10;
    public $page = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('rating_model','model');
        $this->load->model('users_model');
        //$this->load->model('news_model');
    }

    function index($page = 1)
    {
        $where = array();
        $offset = 0;
        $this->page['current_uri'] =  $this->get_parent_menu_item('volonter');
        $this->page['items']   = $this->users_model->get_list_volonter($where,1000,$offset,'last_name');
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
        $this->page['current_uri'] =  $this->get_parent_menu_item('volonter');
        $this->page['meta']         = $this->matrix_meta[$this->controller][$item->id];
        $this->render();
    }

    function rating ()
    {
        $where = array('rating_code','volonter');
        $offset = 0;
        $this->page['current_uri'] =  $this->get_parent_menu_item('volonter');
        $this->page['items']   = $this->model->get_rating_volonter($where,1000,$offset,'rating_year');
        $this->render();
    }

}