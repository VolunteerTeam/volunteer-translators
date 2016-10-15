<?php

class Goroda extends CP_Controller {

    const PER_PAGE = 100;
    public $page = array();

    function __construct()
    {
        $this->access = array(
            'index'=>array('admin','manager'),
            'search'=>array('admin','manager'),
            'add'   =>array('admin','manager'),
            'edit'  =>array('admin','manager'),
            'remove'=>array('admin','manager'),
            'toggle_status'=>array('admin','manager'),
            'filter'=>array('admin','manager'),
            'grab_cities'=>array('admin','manager')
        );

        parent::__construct();
        $this->load->model('warehouse_model','model');
        $this->load->model('address_model');
        $this->load->model('seo_model','seo');
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
        if(($config['total_rows']= $this->model->get_total($where)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/'.$this->controller.'/index');
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
            $config['uri_segment'] = 4;


            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->get_list_extend($where,self::PER_PAGE,$offset,'title');

        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Города');
        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();
    }

    function search()
    {
        $q = $this->input->post('q',true);
        $this->page['query']  = $q;
        if(($config['total_rows']= $this->model->search_total($q)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/'.$this->controller.'/search');
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
            $config['uri_segment'] = 4;

            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->search_list($q,self::PER_PAGE,$offset);


        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Города - результаты поиска');
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

        $this->form_validation->set_rules('title', 'Название', 'required|xss_clean');
        $this->form_validation->set_rules('en_title', 'Название', 'xss_clean');
        $this->form_validation->set_rules('intro', 'Интро', 'xss_clean');
        $this->form_validation->set_rules('status', 'status', 'xss_clean');
        $this->form_validation->set_rules('kladr_city_id', 'kladr_city_id', 'xss_clean');
        $this->form_validation->set_rules('user_id', 'user_id', 'xss_clean');
        $this->form_validation->set_rules('shared_email', 'shared_email', 'xss_clean');
        $this->form_validation->set_rules('shared_phone', 'shared_phone', 'xss_clean');
        $this->form_validation->set_rules('work_hours', 'work_hours', 'xss_clean');
        $this->form_validation->set_rules('vk_link', 'vk_link', 'xss_clean');
        $this->form_validation->set_rules('fb_link', 'fb_link', 'xss_clean');
        $this->form_validation->set_rules('od_link', 'od_link', 'xss_clean');
        $this->form_validation->set_rules('google_link', 'google_link', 'xss_clean');
        $this->form_validation->set_rules('our_clients', 'our_clients', 'xss_clean');
        $this->form_validation->set_rules('our_partners', 'our_partners', 'xss_clean');
        $this->form_validation->set_rules('online_consult', 'online_consult', 'xss_clean');
        $this->form_validation->set_rules('details', 'details', 'xss_clean');
        $this->form_validation->set_rules('post', 'post', 'xss_clean');
        $this->form_validation->set_rules('show_on_index', 'show_on_index', 'xss_clean');
        $this->form_validation->set_rules('coordinate', 'coordinate', 'xss_clean');
        $this->form_validation->set_rules('city_population', 'city_population', 'xss_clean');
        $this->form_validation->set_rules('city_status', 'city_status', 'xss_clean');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run())
        {
            $item = array(
                'title'             =>$this->input->post('title'),
                'en_title'          =>$this->input->post('en_title'),
                'intro'             =>$this->input->post('intro'),
                'status'            =>$this->input->post('status'),
                'kladr_city_id'     =>$this->input->post('kladr_city_id'),
                'user_id'           =>$this->input->post('user_id'),
                'shared_email'      =>$this->input->post('shared_email'),
                'shared_phone'      =>$this->input->post('shared_phone'),
                'work_hours'        =>$this->input->post('work_hours'),
                'vk_link'           =>$this->input->post('vk_link'),
                'fb_link'           =>$this->input->post('fb_link'),
                'od_link'           =>$this->input->post('od_link'),
                'google_link'       =>$this->input->post('google_link'),
                'our_clients'       =>$this->input->post('our_clients'),
                'our_partners'      =>$this->input->post('our_partners'),
                'online_consult'    =>$this->input->post('online_consult'),
                'details'           =>$this->input->post('details'),
                'post'              =>$this->input->post('post'),
                'show_on_index'     =>$this->input->post('show_on_index'),
                'coordinate'     =>$this->input->post('coordinate'),
                'city_population'     =>$this->input->post('city_population'),
                'city_status'     =>$this->input->post('city_status'),
            );
            $id = $this->model->create($item);
            if($id)
            {
                $this->seo_manipulation($item,$id,'detail','warehouse');

                $addresses = $this->input->post('addresses');
                if(!empty($addresses))
                {
                    foreach ($addresses as $address_id) {
                        $this->db->insert('cities_addresses',array('address_id'=>$address_id,'city_id'=>$id));
                    }
                }

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

            $pre_options = $this->ion_auth->users()->result();
            $options = array('0'=>'выбери');
            foreach($pre_options as $row)
            {
                $options[$row->id] = $row->username;

            }
            $this->page['content']['user_id'] = array(
                'options'  =>$options ,
                'default'   =>set_value('user_id'),
                'additional'=>' id = "user_id" class="form-control" ',
            );
            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title')
            );

            $this->page['post'] = array(
                'name'  => 'post',
                'id'    => 'post',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('post')
            );

            $this->page['intro'] = array(
                'name'  => 'intro',
                'id'    => 'intro',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('intro')
            );

            $this->page['en_title'] = array(
                'name'  => 'seo[url]',
                'id'    => 'en_title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('seo[url]')
            );

            $this->page['shared_email'] = array(
                'name'  => 'shared_email',
                'id'    => 'shared_email',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('shared_email')
            );
            $this->page['shared_phone'] = array(
                'name'  => 'shared_phone',
                'id'    => 'shared_phone',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('shared_phone')
            );

            $this->page['work_hours'] = array(
                'name'  => 'work_hours',
                'id'    => 'work_hours',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('work_hours')
            );

            $this->page['vk_link'] = array(
                'name'  => 'vk_link',
                'id'    => 'vk_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('vk_link')
            );

            $this->page['fb_link'] = array(
                'name'  => 'fb_link',
                'id'    => 'fb_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('fb_link')
            );

            $this->page['google_link'] = array(
                'name'  => 'google_link',
                'id'    => 'google_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('google_link')
            );
            $this->page['od_link'] = array(
                'name'  => 'od_link',
                'id'    => 'od_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('od_link')
            );

            $this->page['our_clients'] = array(
                'name'  => 'our_clients',
                'id'    => 'our_clients',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('our_clients')
            );

            $this->page['our_partners'] = array(
                'name'  => 'our_partners',
                'id'    => 'our_partners',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('our_partners')
            );

            $this->page['online_consult'] = array(
                'name'  => 'online_consult',
                'id'    => 'online_consult',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('online_consult')
            );

            $this->page['details'] = array(
                'name'  => 'details',
                'id'    => 'details',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('details')
            );


            $this->page['city_population'] = array(
                'name'  => 'city_population',
                'id'    => 'city_population',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('city_population')
            );


            $this->page['city_status'] = array(
                'name'  => 'city_status',
                'id'    => 'city_status',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('city_status')
            );

            $this->page['coordinate'] = array(
                'name'  => 'coordinate',
                'id'    => 'coordinate',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('coordinate')
            );


            $this->page['addresses'] = $this->address_model->get_list();

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)set_value('status'),
                'class'     =>'pull-right',
                'value'     =>1
            );

            $this->page['show_on_index'] = array(
                'name'      => 'status',
                'id'        => 'show_on_index',
                'checked'   =>true,
                'class'     =>'pull-right',
                'value'     =>1
            );


        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Города');
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();
    }

    function edit($id)
    {

        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('coordinate', 'coordinate', 'xss_clean');
        $this->form_validation->set_rules('city_population', 'city_population', 'xss_clean');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && ($news_data = $this->model->get_one(array('id'=>$id)) )!=false)
        {

            $item = array(
                'title'             =>$this->input->post('title'),
                'en_title'          =>$this->input->post('en_title'),
                'intro'             =>$this->input->post('intro'),
                'status'            =>$this->input->post('status'),
                'kladr_city_id'     =>$this->input->post('kladr_city_id'),
                'user_id'           =>$this->input->post('user_id'),
                'shared_email'      =>$this->input->post('shared_email'),
                'shared_phone'      =>$this->input->post('shared_phone'),
                'work_hours'        =>$this->input->post('work_hours'),
                'vk_link'           =>$this->input->post('vk_link'),
                'fb_link'           =>$this->input->post('fb_link'),
                'od_link'           =>$this->input->post('od_link'),
                'google_link'       =>$this->input->post('google_link'),
                'our_clients'       =>$this->input->post('our_clients'),
                'our_partners'      =>$this->input->post('our_partners'),
                'online_consult'    =>$this->input->post('online_consult'),
                'details'           =>$this->input->post('details'),
                'post'              =>$this->input->post('post'),
                'show_on_index'     =>$this->input->post('show_on_index'),
                'coordinate'     =>$this->input->post('coordinate'),
                'city_population'     =>$this->input->post('city_population'),
                'city_status'     =>$this->input->post('city_status'),

            );

            $result = $this->model->update(array('id'=>$id),$item);

            $this->seo_manipulation($item,$id,'detail','warehouse');
            $addresses = $this->input->post('addresses');
            if(!empty($addresses))
            {
                $this->db->delete('cities_addresses',array('city_id'=>$id));
                foreach ($addresses as $address_id) {
                    $this->db->insert('cities_addresses',array('address_id'=>$address_id,'city_id'=>$id));
                }
            }

            if($result)
            {
                //$this->seo_manipulation($item,$id,'detail');
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
            $seo_data = $this->seo->get_one(array('module_name'=>'warehouse','subject_id'=>$id));

            $pre_options = $this->ion_auth->users()->result();
            $options = array('0'=>'выбери');
            foreach($pre_options as $row)
            {
                $options[$row->id] = $row->username;

            }
            $this->page['content']['user_id'] = array(
                'options'  =>$options ,
                'default'   =>set_value('user_id',$item->user_id),
                'additional'=>' id = "user_id" class="form-control" ',
            );
            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title',$item->title)
            );

            $this->page['post'] = array(
                'name'  => 'post',
                'id'    => 'post',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('post',$item->post)
            );


            $this->page['intro'] = array(
                'name'  => 'intro',
                'id'    => 'intro',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('intro',$item->intro)
            );

            $this->page['en_title'] = array(
                'name'  => 'seo[url]',
                'id'    => 'en_title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('seo[url]',!empty($seo_data) ? $seo_data->url :'')
            );

            $this->page['shared_email'] = array(
                'name'  => 'shared_email',
                'id'    => 'shared_email',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('shared_email',$item->shared_email)
            );
            $this->page['shared_phone'] = array(
                'name'  => 'shared_phone',
                'id'    => 'shared_phone',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('shared_phone',$item->shared_phone)
            );

            $this->page['work_hours'] = array(
                'name'  => 'work_hours',
                'id'    => 'work_hours',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('work_hours',$item->work_hours)
            );

            $this->page['vk_link'] = array(
                'name'  => 'vk_link',
                'id'    => 'vk_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('vk_link',$item->vk_link)
            );

            $this->page['fb_link'] = array(
                'name'  => 'fb_link',
                'id'    => 'fb_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('fb_link',$item->fb_link)
            );
            $this->page['od_link'] = array(
                'name'  => 'od_link',
                'id'    => 'od_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('od_link',$item->od_link)
            );

            $this->page['google_link'] = array(
                'name'  => 'google_link',
                'id'    => 'google_link',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('google_link',$item->google_link)
            );

            $this->page['our_clients'] = array(
                'name'  => 'our_clients',
                'id'    => 'our_clients',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('our_clients',$item->our_clients)
            );

            $this->page['our_partners'] = array(
                'name'  => 'our_partners',
                'id'    => 'our_partners',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('our_partners',$item->our_partners)
            );

            $this->page['online_consult'] = array(
                'name'  => 'online_consult',
                'id'    => 'online_consult',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('online_consult',$item->online_consult)
            );

            $this->page['details'] = array(
                'name'  => 'details',
                'id'    => 'details',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('details',$item->details)
            );


            $this->page['city_population'] = array(
                'name'  => 'city_population',
                'id'    => 'city_population',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('city_population',$item->city_population)
            );

            $this->page['coordinate'] = array(
                'name'  => 'coordinate',
                'id'    => 'coordinate',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('coordinate',$item->coordinate)
            );


            $this->page['city_status'] = array(
                'name'  => 'city_status',
                'id'    => 'city_status',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('city_status',$item->city_status)
            );

            $this->page['addresses'] = $this->address_model->get_list();

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)$item->status,
                'class'     =>'pull-right',
                'value'     =>1
            );

            $this->page['show_on_index'] = array(
                'name'      => 'show_on_index',
                'id'        => 'show_on_index',
                'checked'   =>(bool)$item->show_on_index,
                'class'     =>'pull-right',
                'value'     =>1
            );

            $this->page['addresses'] = $this->address_model->get_list();
            $this->page['current_address'] = $this->address_model->get_current_addresses(array('city_id'=>$id));

            $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Города');
            $this->breadcrumbs[] = array('title'=>'Редактирование');
            $this->render();
        }
        else
        {
            $m = show_error_message('Не найдено');
            //Отправляем обратно
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller);
        }

    }

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

    function grab_cities()
    {
        error_reporting(0);
        include FCPATH.APPPATH.DIRECTORY_SEPARATOR.'third_party/domparser/DomParser.php';
        $parser = new DomParser();

        $path = 'http://www.perevodov.info/goroda';
        //Парсим документ
        $html = $parser->str_get_html(file_get_contents($path));

        //Забираем актуальный список станций
        $cities =  $html->find('.sites-tile-name-content-1',0)->find('tr');

        $k = 0;
        $data = array();
        foreach($cities as $opt)
        {
            $new = array(
                'title'             =>$opt->children(0)->plaintext,
                'user_id'           =>3,
                'post'              =>'Представитель',
                'shared_contacts'   =>$opt->children(2)->plaintext,
                'shared_email'      =>$opt->children(3)->plaintext,
                'shared_phone'      =>$opt->children(1)->plaintext,
                'vk_link'=>$opt->children(4)->find("a",0)->href,
                'fb_link'=>$opt->children(5)->find("a",0)->href,
                'od_link'=>$opt->children(6)->find("a",0)->href,
                'google_link'=>$opt->children(7)->find("a",0)->href,
            );

            $this->model->create($new);

           /* echo '<pre>';
            print_r($new);
            echo '</pre>';*/
           $k++;
        }

     /*   echo '<pre>';
        print_r($data);
        echo '</pre>';*/
    }

}