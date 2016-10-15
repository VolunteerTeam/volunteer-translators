<?php

class Pages extends MY_Controller
{

    public $page = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('pages_model','model');

    }


    function index($id)
    {
        $url =  $this->uri->uri_string();
        $this->page['item']= $item = $this->model->get_one(array('id'=>$id));
        $this->page['current_uri'] = $cu = $this->menu_model->find_top($url);
        $this->method = 'index';
        $this->page['meta']         = $this->matrix_meta[$this->controller][$item->id];
        $this->render();
    }


    /**
     * Небольшая хитрость -  любой запрос к модулю каталога идет строго в index
     * @param $id
     * @return mixed
     */
    public function _remap($url)
    {
        if(preg_match('/[a-zA-Z_-]{1,}/',$url)){
            $route = $this->db->get_where('seo',array('url'=>$url));
            if($route->num_rows()==1)
            {
                $route_data = $route->row();
                $id = $route_data->subject_id;
            }
            else
            {
                show_404();
            }
        }
        elseif(preg_match('/[0-9]{1,}/',$url)){
            $id = $url;
        }
        else{
            show_404();
        }

        return call_user_func_array(array($this, 'index'), array('id'=>$id));

    }
}