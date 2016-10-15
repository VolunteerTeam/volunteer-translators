<?php

Class News extends CP_Controller {


    const NEWS_PER_PAGE = 50;
    public $page = array();


    function __construct()
    {
        $this->access = array(
            'index'=>array('admin','manager'),
            'search'=>array('admin','manager'),
            'add'   =>array('admin','manager'),
            'edit'  =>array('admin','manager'),
            'remove'=>array('admin','manager'),
            'toggle_status'=>array('admin','manager')
        );

        parent::__construct();
        $this->load->model('news_model','n');
        $this->load->model('seo_model','seo');
        $this->load->model('warehouse_model','cities');

        $this->page['js']['footer'][] = array('src'=>'/js/cpanel/chosen/chosen.jquery.min.js');
        $this->page['css'][] = array('src'=>'/js/cpanel/chosen/chosen.min.css');

    }

    function index()
    {

        $where = array();
        $filter = $this->session->userdata('filter');
        if(!empty($filter))
        {
            $where = $filter;
        }
        if(($config['total_rows']= $this->n->get_total($where)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/news');

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::NEWS_PER_PAGE;
            $config['uri_segment'] = 4;


            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['news']   = $this->n->get_list($where,self::NEWS_PER_PAGE,$offset,'news_date','DESC');

        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Новости');
        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();
    }

    function search()
    {
        $q = $this->input->post('q',true);
        $this->page['query']  = $q;
        if(($config['total_rows']= $this->n->search_total($q)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/news');

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::NEWS_PER_PAGE;
            $config['uri_segment'] = 4;

            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['news']   = $this->n->search_list($q,self::NEWS_PER_PAGE,$offset);


        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Новости - результаты поиска');
        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();
    }

    function filter()
    {
        $filters = $this->input->post('filter',true);
        $f_array = array();
        foreach ($filters as $f => $v) {
            if($v!='' && $v!='all')
            {
                $f_array[$f]=$v;
            }

            if($v=='all')
            {
                $this->session->unset_userdata('filter');

            }

        }

        if(!empty($f_array))
        {
            $this->session->set_userdata('filter',$f_array);
        }


        if($this->input->post('do_reset'))
        {
            $this->session->unset_userdata('filter');
        }

        redirect($this->side.'/'.$this->controller);
    }

    function add()
    {

        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('news_date', 'Дата', 'required|xss_clean');

        $this->form_validation->set_rules('full_content', 'Полное содержимое', 'required');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run())
        {
            $item = array(
                'title'      =>$this->input->post('title'),
                'news_date'  =>strtotime($this->input->post('news_date')),
                'short_content'  =>$this->input->post('short_content'),
                'full_content'  =>$this->input->post('full_content'),
                               'status'  =>$this->input->post('status'),
            );
            $id = $this->n->create($item);
            if($id)
            {
                $cities = $this->input->post('cities',true);
                if(!empty($cities))
                {
                    foreach($cities as $v)
                    {
                        $this->db->insert('cities_news',array('news_id'=>$id,'city_id'=>$v));
                    }
                }
                $options   = array(
                    'create_thumb'          =>TRUE,
                    'width'                 =>400,
                    'height'                =>300,
                    'big_resize'            =>FALSE,
                    'big_resize_advanced'   =>TRUE,
                    'max_side_size'         =>800,
                    'crop'                  =>FALSE
                );
                $this->seo_manipulation($item,$id);
                $this->image_process('index',$options,$this->controller,'index',$id);

                $m = show_success_message('Создание прошло успешно');

            }
            else
            {
                $m = show_error_message('Создание невозможно');
            }

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller.'/edit/'.$id);
        }
        else
        {

            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');

            $this->page['cities']  = $this->cities->get_list();
            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title')
            );
            $this->page['news_date'] = array(
                'name'  => 'news_date',
                'id'    => 'datepicker',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('news_date')
            );
            $this->page['short_content'] = array(
                'name'  => 'short_content',
                'id'    => 'short_content',

                'class' =>'form-control required',
                'value' =>set_value('short_content')
            );
            $this->page['full_content'] = array(
                'name'  => 'full_content',
                'id'    => 'full_content',

                'style'=>"height:600px",

                'class' =>'form-control required',
                'value' =>set_value('full_content')
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>true,
                'class'     =>'pull-right',
                'value'     =>set_checkbox('status',1,true)
            );


            $this->page['status'] = array(
                'name'  => 'status',
                'id'    => 'publish',
                'checked'=>true,
                'value' =>'1',


            );

            $this->page['inline_js'][] = '
            ckInit("full_content","'.base_url().'",{toolbar:"News",height:600});

            ';

            //=======SEO=========================
            $this->page['seo_title'] = array(
                'name'  => 'seo[title]',
                'id'    => 'seo_title',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('seo_title')
            );

            $this->page['seo_url'] = array(
                'name'  => 'seo[url]',
                'id'    => 'seo_url',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('seo_url')
            );

            $this->page['seo_description'] = array(
                'name'  => 'seo[description]',
                'id'    => 'seo_description',
                'class' =>'form-control',
                'value' =>set_value('seo_description')
            );

            $this->page['seo_keywords'] = array(
                'name'  => 'seo[keywords]',
                'id'    => 'seo_keywords',
                'class' =>'form-control',
                'value' =>set_value('seo_keywords')
            );


        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Новости');
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();
    }

    function edit($id)
    {

        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('news_date', 'Дата новости', 'required|xss_clean');
        $this->form_validation->set_rules('full_content', 'Полное содержимое', 'required');


        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && ($news_data = $this->n->get_one(array('id'=>$id)) )!=false)
        {

            $item = array(
                'title'      =>$this->input->post('title'),
                'news_date'  =>strtotime($this->input->post('news_date')),
                'short_content'  =>$this->input->post('short_content'),
                'full_content'  =>$this->input->post('full_content'),

                'status'  =>$this->input->post('status'),

            );
            $result = $this->n->update(array('id'=>$id),$item);
            if($result)
            {

                $cities = $this->input->post('cities',true);
                if(!empty($cities))
                {
                    $this->db->delete('cities_news',array('news_id'=>$id));
                    foreach($cities as $v)
                    {
                        $this->db->insert('cities_news',array('news_id'=>$id,'city_id'=>$v));
                    }
                }


                $this->seo_manipulation($item,$id);
                $options   = array(
                    'create_thumb'          =>TRUE,
                    'width'                 =>400,
                    'height'                =>300,
                    'big_resize'            =>FALSE,
                    'big_resize_advanced'   =>TRUE,
                    'max_side_size'         =>800,
                    'crop'                  =>FALSE
                );
                $this->image_process('index',$options,$this->controller,'index',$id);
                $m = show_success_message('Обновление прошло успешно');

            }
            else
            {
                $m = show_error_message('Обновление невозможно');
            }

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller.'/edit/'.$id);


        }
        elseif( ($news_data = $this->n->get_one(array('id'=>$id)) )!=false )
        {


            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');
            $this->page['item'] = $news_data;
            $this->page['cities']  = $this->cities->get_list();
            $this->page['current_cities']  = $this->n->get_current_cities(array('news_id'=>$id));

            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title',$news_data->title)
            );


            $this->page['news_date'] = array(
                'name'  => 'news_date',
                'id'    => 'datepicker',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('news_date',date('d.m.Y',$news_data->news_date))
            );

            $this->page['short_content'] = array(
                'name'  => 'short_content',
                'id'    => 'short_content',
                'class' =>'form-control required',
                'value' =>set_value('short_content',$news_data->short_content)
            );

            $this->page['full_content'] = array(
                'name'  => 'full_content',
                'id'    => 'full_content',
                'class' =>'form-control required',
                'value' =>set_value('full_content',$news_data->full_content)
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)$news_data->status,
                'class'     =>'pull-right',
                'value'     =>1
            );


            $this->page['inline_js'][] = '
            ckInit("full_content","'.base_url().'");
            ';

            $this->page['index_image'] = $this->gm->get_one(array('module_name'=>$this->controller,'group'=>'index','subject_id'=>$id));
            //=======SEO=========================

            $seo_data = $this->seo->get_one(array('module_name'=>$this->controller,'subject_id'=>$id));
            $this->page['seo_title'] = array(
                'name'  => 'seo[title]',
                'id'    => 'seo_title',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('seo_title',!empty($seo_data) ? $seo_data->title : '')
            );

            $this->page['seo_url'] = array(
                'name'  => 'seo[url]',
                'id'    => 'seo_url',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('seo_url',!empty($seo_data) ? $seo_data->url : '')
            );

            $this->page['seo_description'] = array(
                'name'  => 'seo[description]',
                'id'    => 'seo_description',
                'class' =>'form-control',
                'value' =>set_value('seo_description',!empty($seo_data) ? $seo_data->description : '')
            );

            $this->page['seo_keywords'] = array(
                'name'  => 'seo[keywords]',
                'id'    => 'seo_keywords',
                'class' =>'form-control',
                'value' =>set_value('seo_keywords',!empty($seo_data) ? $seo_data->keywords : '')
            );
            $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Новости');
            $this->breadcrumbs[] = array('title'=>'Редактирование');
            $this->render();
        }
        else
        {
            $m = show_error_message('Новость не найдена');
            //Отправляем обратно
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller);
        }

    }

    function remove($id)
    {


        if( $this->gm->delete_gallery_item(array('module_name'=>$this->controller,'group'=>'index','subject_id'=>$id)) &&  $this->n->delete(array('id'=>$id)))
        {
            $m = show_success_message('Удаление прошло успешно');
        }
        else
        {
            $m = show_error_message('Удаление невозможно');
        }

        //Отправляем обратно и сообщаем об успешной операции
        $this->session->set_flashdata('message',$m);
        redirect('cpanel/'.$this->controller.'/index');
    }


    /**
     *
     */
    function toggle_status()
    {
        $id = $this->input->post('id',true);
        if( ($data = $this->n->get_one(array('id'=>$id)))!=false )
        {
            $result = ($data->status == 1) ? $this->n->update(array('id'=>$id),array('status'=>0)) : $this->n->update(array('id'=>$id),array('status'=>1));
            echo json_encode($result);
        }
    }



    function import()
    {

        $items = $this->db->get_where('jos_content',array('sectionid'=>1))->result();

        foreach ($items as $item) {

            $data = array(
                'title'=>$item->name,
                'news_date'=>strtotime($item->created),
                'short_content'=>$item->introtext,
                'full_content'=>$item->fulltext,
                'link_to'       =>'',
                'hits'          =>$item->hits,
                'status'        =>$item->state<1 ? 0 :1
            );


        }


    }



}