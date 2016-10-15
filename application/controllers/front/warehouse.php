<?php

class Warehouse extends MY_Controller {

    const PER_PAGE = 10;
    public $page = array();

    function __construct()
    {
        parent::__construct();
        $this->load->model('warehouse_model','model');
        $this->load->model('address_model');
        $this->load->model('news_model');
        $this->page['js']['header'][] = array('src'=>'https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false');
        $this->page['js']['header'][] = array('src'=>'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/src/markerclusterer.js');
    }

    function index($page = 1)
    {

        $where = array('status'=>1);
        $offset = 0;
        $this->page['current_uri'] =  $this->get_parent_menu_item('goroda');
        $this->page['items']   = $this->model->get_list_extend($where,1000,$offset,'title');
        $this->render();
    }

    function detail($id)
    {
        if( ($this->page['item'] =$item = $this->model->get_one(array('id'=>$id))) !==false)
        {
            $addresses = array();
            $addresses_relations = $this->address_model->get_relation(array('city_id'=>$item->id));
            if(!empty($addresses_relations))
            {
                foreach($addresses_relations as $k=> $rel){
                    $addresses[$k] = $this->address_model->get_one(array('id'=>$rel->address_id));
                    $addresses[$k]->building_image = $this->gm->get_one(array('module_name'=>'address','group'=>'building','subject_id'=>$rel->address_id));
                    $addresses[$k]->enter_image = $this->gm->get_one(array('module_name'=>'address','group'=>'enter','subject_id'=>$rel->address_id));
                    $addresses[$k]->office_image = $this->gm->get_one(array('module_name'=>'address','group'=>'office','subject_id'=>$rel->address_id));
                }
            }
            $this->page['item']->ceo = null;
            $this->page['item']->addresses = $addresses;
            $ceo = $this->db->get_where('users',array('id'=>$item->user_id))->row();
            if(!empty($ceo)){
                $ceo->avatar = $this->gm->get_one(array('module_name'=>'users','group'=>'avatar','subject_id'=>$item->user_id));
                $this->page['item']->ceo = $ceo;
            }


            $this->page['item']->news = $this->news_model->get_relations_news($id);

        }
        else
        {
            show_404();
        }
        $this->page['current_uri'] =  $this->get_parent_menu_item('goroda');
        $this->page['meta']         = $this->matrix_meta[$this->controller][$item->id];
        $this->render();
    }

    function ajax_list()
    {

        $data['goroda']             = $this->model->get_list(array('status'=>1),150,0,'title','city_status');
        $this->load->view('front/warehouse/ajax_list',$data);

    }

    function ajax_list_json()
    {
        $data['list'] = $this->model->get_list_extend(array('status'=>1),1000,0,'title');
        $data['result'] = true;
        echo json_encode($data);
    }

}