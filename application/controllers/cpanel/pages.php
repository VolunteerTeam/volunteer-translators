<?php

/**
 * Class Pages
 */
Class Pages extends CP_Controller {

    /**
     *
     */
    const PER_PAGE = 50;
    /**
     * @var array
     */
    public $page = array();


    /**
     *
     */
    function __construct()
    {
        $this->access = array(
            'index'=>array('admin','manager'),
            'search'=>array('admin','manager'),
            'add'   =>array('admin','manager'),
            'edit'  =>array('admin','manager'),
            'remove'=>array('admin','manager'),
            'toggle_status'=>array('admin','manager'),
            'filter'=>array('admin','manager')
        );

        parent::__construct();
        $this->load->model('pages_model','model');
        $this->load->model('seo_model','seo');

        $this->page['js']['footer'][] = array('src'=>'/js/cpanel/chosen/chosen.jquery.min.js');
        $this->page['css'][] = array('src'=>'/js/cpanel/chosen/chosen.min.css');

    }

    /**
     *
     */
    function index()
    {

        $where = array();
        $filter = $this->session->userdata('filter');
        if(!empty($filter))
        {
            $where = $filter;
        }
        if(($config['total_rows']= $this->model->get_total($where)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/'.$this->controller);

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::PER_PAGE;
            $config['uri_segment'] = 4;
            $offset = $this->uri->segment(4,0);
            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->get_list($where,self::PER_PAGE,$offset);

            foreach($this->page['items'] as $k=>$v)
            {
                $this->page['items'][$k]->meta = $this->seo->get_one(array('module_name'=>'pages','subject_id'=>$v->id));
            }

        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Статичные страницы');
        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();
    }

    /**
     *
     */
    function search()
    {
        $q = $this->input->post('q',true);
        $this->page['q']  = $q;
        if(($config['total_rows']= $this->model->search_total($q)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/'.$this->controller);

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::PER_PAGE;
            $config['uri_segment'] = 4;

            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->search_list($q,self::PER_PAGE,$offset);


        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Страницы - результаты поиска');
        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();
    }

    /**
     *
     */
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

    /**
     *
     */
    function add()
    {

        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('full_content', 'Полное содержимое', 'required');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run())
        {
            $item = array(
                'title'      =>$this->input->post('title'),
                'full_content'  =>$this->input->post('full_content'),
                'status'  =>$this->input->post('status'),
            );
            $id = $this->model->create($item);
            if($id)
            {
                 $this->seo_manipulation($item,$id);
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

            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title')
            );

            $this->page['full_content'] = array(
                'name'  => 'full_content',
                'id'    => 'full_content',

                'class' =>'form-control required',
                'value' =>set_value('full_content')
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>true,
                'class'     =>'pull-right',
                'value'     =>1
            );

            $this->page['inline_js'][] = '
            ckInit("full_content","'.base_url().'");
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
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();
    }

    /**
     * @param $id
     */
    function edit($id)
    {
        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('full_content', 'Полное содержимое', 'required');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && ($old_item = $this->model->get_one(array('id'=>$id)) )!=false)
        {

            $item = array(
                'title'             =>$this->input->post('title'),
                'full_content'      =>$this->input->post('full_content'),
                'status'            =>$this->input->post('status'),

            );
            $result = $this->model->update(array('id'=>$id),$item);
            if($result)
            {
                $this->seo_manipulation($item,$id);
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
        elseif( ($this->page['item'] = $item = $this->model->get_one(array('id'=>$id)) )!=false )
        {


            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');


            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('title',$item->title)
            );

            $this->page['full_content'] = array(
                'name'  => 'full_content',
                'id'    => 'full_content',
                'class' =>'form-control required',
                'value' =>set_value('full_content',$item->full_content)
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)$item->status,
                'class'     =>'pull-right',
                'value'     =>1
            );


            $this->page['inline_js'][] = '
            ckInit("full_content","'.base_url().'");
            ';


            //=======SEO=========================

            $seo_data = $this->seo->get_one(array('module_name'=>$this->controller,'subject_id'=>$id));
            $this->page['seo_title'] = array(
                'name'  => 'seo[title]',
                'id'    => 'seo_title',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('seo_title',$seo_data->title)
            );

            $this->page['seo_url'] = array(
                'name'  => 'seo[url]',
                'id'    => 'seo_url',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('seo_url',$seo_data->url)
            );

            $this->page['seo_description'] = array(
                'name'  => 'seo[description]',
                'id'    => 'seo_description',
                'class' =>'form-control',
                'value' =>set_value('seo_description',$seo_data->description)
            );

            $this->page['seo_keywords'] = array(
                'name'  => 'seo[keywords]',
                'id'    => 'seo_keywords',
                'class' =>'form-control',
                'value' =>set_value('seo_keywords',$seo_data->keywords)
            );

            $this->render();
        }
        else
        {
            $m = show_error_message('Страница не найдена');
            //Отправляем обратно
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller);
        }

    }

    /**
     * @param $id
     */
    function remove($id)
    {


        if($this->model->delete(array('id'=>$id)))
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
        if( ($data = $this->model->get_one(array('id'=>$id)))!=false )
        {
            $result = ($data->status == 1) ? $this->model->update(array('id'=>$id),array('status'=>0)) : $this->model->update(array('id'=>$id),array('status'=>1));
            echo json_encode($result);
        }
    }


}