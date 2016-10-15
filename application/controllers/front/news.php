<?php

class News extends MY_Controller {

    const PER_PAGE = 10;
    public $page = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('news_model','model');
    }

    function index($page = 1)
    {
        $where = array('status'=>1);

        if(($config['total_rows']= $this->model->get_total($where)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('news');
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li  class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['per_page'] = self::PER_PAGE;
            $config['uri_segment'] = 2;
            $config['page_query_string'] = TRUE;

            $offset = $this->input->get('offset',0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->get_list_extend($where,self::PER_PAGE,$offset,'news_date','DESC');

        }
        $this->page['current_uri'] =  $this->get_parent_menu_item($this->controller);

        $this->render();
    }

    function detail($id)
    {
        $this->page['current_uri'] =  $this->get_parent_menu_item($this->controller);
        $this->page['item'] = $item =  $this->model->get_one(array('FROM_UNIXTIME(news_date,"%Y-%m-%d")'=>$id,'status'=>1));
        $this->page['item']->image  = $this->gm->get_one(array('module_name'=>$this->controller,'group'=>'index','subject_id'=>$item->id));
        $this->page['meta']         = $this->matrix_meta[$this->controller][$item->id];

        $this->render();
    }

}