<?php

class Sovet extends MY_Controller {

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
        $this->page['current_uri'] =  $this->get_parent_menu_item('about');
        $this->page['items']   = $this->users_model->get_list_sovet($where,1000,$offset,'last_name');
        $this->render();
    }

}