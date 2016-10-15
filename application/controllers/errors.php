<?php

class Errors extends MY_Controller{

    public $page = array();
    function __construct()
    {
        parent::__construct();

    }

    function error_404()
    {
        $this->render();
    }
}