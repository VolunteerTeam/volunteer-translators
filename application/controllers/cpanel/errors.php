<?php


class Errors extends CP_Controller {

    public $page = array();


    function __construct()
    {
        $this->access = array(
            'access_denied'=>array('admin','manager','member'),
        );
        parent::__construct();

    }


    function access_denied()
    {
        $this->page['message'] =  $this->session->flashdata('message');
        $this->render();
    }
}