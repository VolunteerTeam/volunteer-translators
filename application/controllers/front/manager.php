<?php

class Manager extends MY_Controller {

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
        $this->page['current_uri'] =  $this->get_parent_menu_item('manager');

        $this->render();
    }

    function rating ()
    {
        $where = array('rating_code','manager');
        $offset = 0;
        $this->page['current_uri'] =  $this->get_parent_menu_item('manager');
        $this->page['items']   = $this->model->get_rating_manager($where,1000,$offset,'rating_year');
        $this->render();
    }

}