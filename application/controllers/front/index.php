<?php

class Index extends MY_Controller {

    public $page = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('news_model');
        $this->load->model('warehouse_model');
        $this->page['js']['header'][] = array('src'=>'https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false');
        $this->page['js']['header'][] = array('src'=>'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js');
    }

    function index()
    {
        $this->page['news'] = $this->news_model->get_list_extend(array('status'=>1),3,0,'news_date','desc');
        $this->page['cities'] = $this->warehouse_model->get_list_extend(array('status'=>1,'city_status'=>3,'show_on_index'=>1),8,0);
        $this->page['other_cities'] = $this->warehouse_model->get_list_extend(array('status'=>1,'city_status'=>2),10,0);
        $this->page['goroda_status_0'] = $this->warehouse_model->get_list_extend(array('status'=>1,'city_status'=>0),20,0);
        $this->page['goroda_status_1'] = $this->warehouse_model->get_list_extend(array('status'=>1,'city_status'=>1),20,0);
        $this->page['goroda_status_2'] = $this->warehouse_model->get_list_extend(array('status'=>1,'city_status'=>2),20,0);
        $this->page['goroda_status_3'] = $this->warehouse_model->get_list_extend(array('status'=>1,'city_status'=>3),20,0);
        $this->render();
    }
}