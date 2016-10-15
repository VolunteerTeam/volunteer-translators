<?php

class Banners extends CP_Controller {

    const PER_PAGE = 50;
    public $page = array();


    function __construct()
    {
        $this->access = array(
            'index'=>array('admin','manager'),
            'search'=>array('admin','manager'),
            'add'   =>array('admin'),
            'edit'  =>array('admin','manager'),
            'remove'=>array('admin'),
            'toggle_status'=>array('admin','manager'),
            'sorting'      =>array('admin','manager'),
            'filter'      =>array('admin','manager'),
        );

        parent::__construct();
        $this->load->model('banners_model','model');

        $this->page['js']['footer'][] = array('src'=>'/js/cpanel/jquery.nestable.js');
        $this->page['css'][] = array('src'=>'/css/cpanel/jquery.nestable.css');

        $this->upload_config = array(
            'upload_path'   =>$this->content_path,
            'allowed_types' =>'png|jpg',
            'encrypt_name'  =>true,
            'max_size'      =>10240,
            'max_width'     => '10000',
            'max_height'    => '10000'
        );


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

            $config['base_url'] = base_url('cpanel/banners');

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::PER_PAGE;
            $config['uri_segment'] = 4;


            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->get_list($where,self::PER_PAGE,$offset);

        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Баннеры');
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

    function search()
    {

    }

    function add()
    {
        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('start_date', 'Дата начала показов', 'required|xss_clean');
        $this->form_validation->set_rules('end_date', 'Дата прекращения показов', 'required|xss_clean');


        //Если была отправлена форма и валидация прошла
        if($this->input->post('do')
            && $this->form_validation->run()
            && ($static_upload_data = $this->upload_process('static',$this->upload_config))
            && ($swf_upload_data = $this->upload_process('swf',$this->upload_config))
        )
        {

            $item = array(
                'title'         =>$this->input->post('title'),
                'start_date'    =>strtotime($this->input->post('start_date')),
                'end_date'      =>strtotime($this->input->post('end_date')),
                'created_date'  =>time(),
                'link_to'       =>$this->input->post('link_to'),
                'status'        =>$this->input->post('status'),
                'static_filename'      =>isset($static_upload_data['upload_data']) ? $static_upload_data['upload_data']['file_name'] : null,
                'width'         =>isset($static_upload_data['upload_data']) ? $static_upload_data['upload_data']['image_width'] : null,
                'height'         =>isset($static_upload_data['upload_data']) ? $static_upload_data['upload_data']['image_height'] : null,
                'swf_filename'   =>isset($swf_upload_data['upload_data']) ? $swf_upload_data['upload_data']['file_name'] : null,

            );
            $id = $this->model->create($item);
            if($id)
            {

                $m = show_success_message('Создание прошло успешно');
            }
            else
            {
                $m = show_error_message('Создание невозможно');
            }

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller.'/index');
        }
        else
        {


            //$this->page['message'] = (!empty($this->upload->error_msg) ? $this->upload->display_errors('<div class="alert alert-danger" role="alert">', '</div>') :  validation_errors() ? validation_errors(): $this->session->flashdata('message'));
            $this->page['message'] = (!empty($this->upload->error_msg) OR validation_errors() ) ? validation_errors().$this->upload->display_errors('<div class="alert alert-danger" role="alert">', '</div>') :  $this->session->flashdata('message');

            $options = array('0'=>'выбери','1'=>'флеш','2'=>'статичный');
            $this->page['content']['type'] = array(
                'options'  =>$options ,
                'default'   =>set_value('type'),
                'additional'=>' id = "banner_type" class="form-control" ',
            );

            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title')
            );

            $this->page['link_to'] = array(
                'name'  => 'link_to',
                'id'    => 'link_to',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('link_to')
            );

            $this->page['start_date'] = array(
                'name'  => 'start_date',
                'id'    => 'datepicker',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('start_date')
            );

            $this->page['end_date'] = array(
                'name'  => 'end_date',
                'id'    => 'datepicker2',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('end_date')
            );


            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>true,
                'class'     =>'pull-right',
                'value'     =>1
            );




        }
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();

    }


    function edit($id)
    {

        $banner_data = $this->model->get_one(array('id'=>$id));
        if(empty($banner_data))
        {
            redirect($this->side.DIRECTORY_SEPARATOR.$this->controller);
        }
        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('start_date', 'Дата начала показов', 'required|xss_clean');
        $this->form_validation->set_rules('end_date', 'Дата прекращения показов', 'required|xss_clean');


        //Если была отправлена форма и валидация прошла
        if($this->input->post('do')
            && $this->form_validation->run()
            && ($static_upload_data = $this->upload_process('static',$this->upload_config))
            && ($swf_upload_data = $this->upload_process('swf',$this->upload_config))
        )
        {



            $item = array(
                'title'         =>$this->input->post('title'),
                'start_date'    =>strtotime($this->input->post('start_date')),
                'end_date'      =>strtotime($this->input->post('end_date')),
                'created_date'  =>time(),
                'link_to'       =>$this->input->post('link_to'),
                'status'        =>$this->input->post('status'),
                'static_filename'      =>isset($static_upload_data['upload_data']) ? $static_upload_data['upload_data']['file_name'] : null,
                'width'         =>isset($static_upload_data['upload_data']) ? $static_upload_data['upload_data']['image_width'] : null,
                'height'         =>isset($static_upload_data['upload_data']) ? $static_upload_data['upload_data']['image_height'] : null,
                'swf_filename'   =>isset($swf_upload_data['upload_data']) ? $swf_upload_data['upload_data']['file_name'] : null,

            );
            $id = $this->model->create($item);
            if($id)
            {

                $m = show_success_message('Создание прошло успешно');
            }
            else
            {
                $m = show_error_message('Создание невозможно');
            }

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller.'/index');
        }
        else
        {

            $this->page['item'] = $item = $this->model->get_one(array('id'=>$id));

            //$this->page['message'] = (!empty($this->upload->error_msg) ? $this->upload->display_errors('<div class="alert alert-danger" role="alert">', '</div>') :  validation_errors() ? validation_errors(): $this->session->flashdata('message'));
            $this->page['message'] = (!empty($this->upload->error_msg) OR validation_errors() ) ? validation_errors().$this->upload->display_errors('<div class="alert alert-danger" role="alert">', '</div>') :  $this->session->flashdata('message');

            $options = array('0'=>'выбери','1'=>'флеш','2'=>'статичный');
            $this->page['content']['type'] = array(
                'options'  =>$options ,
                'default'   =>$item->type,
                'additional'=>' id = "banner_type" class="form-control" ',
            );

            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('title',$item->title)
            );

            $this->page['link_to'] = array(
                'name'  => 'link_to',
                'id'    => 'link_to',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('link_to',$item->link_to)
            );

            $this->page['start_date'] = array(
                'name'  => 'start_date',
                'id'    => 'datepicker',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>date('d.m.Y',set_value('start_date',$item->start_date))
            );

            $this->page['end_date'] = array(
                'name'  => 'end_date',
                'id'    => 'datepicker2',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>date('d.m.Y',set_value('end_date',$item->end_date))
            );


            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)$item->status,
                'class'     =>'pull-right',
                'value'     =>set_checkbox('status',1,true)
            );
        }
        $this->breadcrumbs[] = array('title'=>'Редактирование');
        $this->render();

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

    function toggle_status()
    {
        $id = $this->input->post('id',true);
        if( ($data = $this->model->get_one(array('id'=>$id)))!=false )
        {
            $result = ($data->status == 1) ? $this->model->update(array('id'=>$id),array('status'=>0)) : $this->model->update(array('id'=>$id),array('status'=>1));
            echo json_encode($result);
        }
    }


    function sorting()
    {
        $input_string = $this->input->post('struct');
        $structure = json_decode($input_string,true,64);

        $readbleArray = $this->parseJsonArray($structure);

        foreach ($readbleArray as $key => $value) {

            // $value should always be an array, but we do a check
            if (is_array($value)) {

                // Update DB
                $this->db->query("UPDATE banners SET
									position='". $key ."'
									WHERE id='".$value['id']."'
									");
            }
        }

        echo json_encode(array('result'=>true));

    }

    function parseJsonArray($jsonArray, $parentID = 0)
    {
        $return = array();
        foreach ($jsonArray as $subArray) {
            $returnSubSubArray = array();
            if (isset($subArray['children'])) {
                $returnSubSubArray = $this->parseJsonArray($subArray['children'], $subArray['id']);
            }
            $return[] = array('id' => $subArray['id'], 'parentID' => $parentID);
            $return = array_merge($return, $returnSubSubArray);
        }

        return $return;
    }


    function upload_process($field,$options)
    {
        $this->upload->initialize($options);

        if(isset($_FILES[$field]) AND $_FILES[$field]['name'] !='')
        {

            if ( ! $this->upload->do_upload($field))
            {
                //return array('error' => $this->upload->display_errors());
                return false;

            }
            else
            {
                return array('upload_data' => $this->upload->data());
            }

        }
        return true;


    }

} 