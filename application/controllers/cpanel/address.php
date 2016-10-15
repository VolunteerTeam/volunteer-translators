<?php

class Address extends CP_Controller {



    const PER_PAGE = 50;
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
            'sorting'      =>array('admin','manager'),
            'filter'      =>array('admin','manager'),
        );

        parent::__construct();
        $this->load->model('address_model','model');

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

            $config['base_url'] = base_url('cpanel/'.$this->controller);

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::PER_PAGE;
            $config['uri_segment'] = 4;


            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['items']   = $this->model->get_list($where,self::PER_PAGE,$offset);

        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Адреса представительств');
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
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Адреса представительств');
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Адреса - результаты поиска');
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

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run())
        {
            $item = array(
                'title'         =>$this->input->post('title'),
                'address'         =>$this->input->post('address'),
                'coordinates'   =>$this->input->post('coordinates'),
                'parking'       =>$this->input->post('parking'),
                'transport'     =>$this->input->post('transport'),
                'phone'         =>$this->input->post('phone'),
                'work_hours'    =>$this->input->post('work_hours'),

            );
            $id = $this->model->create($item);
            if($id)
            {

                $options   = array(
                    'create_thumb'          =>TRUE,
                    'width'                 =>400,
                    'height'                =>300,
                    'big_resize'            =>FALSE,
                    'big_resize_advanced'   =>TRUE,
                    'max_side_size'         =>960,
                    'crop'                  =>FALSE
                );

                $this->image_process('building',$options,$this->controller,'building',$id);
                $this->image_process('enter',$options,$this->controller,'enter',$id);
                $this->image_process('office',$options,$this->controller,'office',$id);

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

            $this->page['address'] = array(
                'name'  => 'address',
                'id'    => 'address',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('address')
            );
            $this->page['coordinates'] = array(
                'name'  => 'coordinates',
                'id'    => 'coordinates',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('coordinates')
            );

            $this->page['parking'] = array(
                'name'  => 'parking',
                'id'    => 'parking',
                'class' =>'form-control',
                'value' =>set_value('parking')
            );

            $this->page['transport'] = array(
                'name'  => 'transport',
                'id'    => 'transport',
                'class' =>'form-control',
                'value' =>set_value('transport')
            );
            $this->page['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'class' =>'form-control',
                'value' =>set_value('phone')
            );
            $this->page['work_hours'] = array(
                'name'  => 'work_hours',
                'id'    => 'work_hours',
                'class' =>'form-control',
                'value' =>set_value('work_hours')
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>true,
                'class'     =>'pull-right',
                'value'     =>1
            );

        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Адреса представительств');
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();
    }

    function edit($id)
    {

        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && ($old_item= $this->model->get_one(array('id'=>$id)) )!=false)
        {
            $item = array(
                'title'         =>$this->input->post('title'),
                'address'       =>$this->input->post('address'),
                'coordinates'   =>$this->input->post('coordinates'),
                'parking'       =>$this->input->post('parking'),
                'transport'     =>$this->input->post('transport'),
                'phone'         =>$this->input->post('phone'),
                'work_hours'    =>$this->input->post('work_hours'),
            );

            $result = $this->model->update(array('id'=>$id),$item);
            if($result)
            {
                $options   = array(
                    'create_thumb'          =>TRUE,
                    'width'                 =>400,
                    'height'                =>300,
                    'big_resize'            =>FALSE,
                    'big_resize_advanced'   =>TRUE,
                    'max_side_size'         =>960,
                    'crop'                  =>FALSE
                );
                $this->image_process('building',$options,$this->controller,'building',$id);
                $this->image_process('enter',$options,$this->controller,'enter',$id);
                $this->image_process('office',$options,$this->controller,'office',$id);
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
                'class' =>'form-control required',
                'value' =>set_value('title',$item->title)
            );

            $this->page['address'] = array(
                'name'  => 'address',
                'id'    => 'address',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('address',$item->address)
            );
            $this->page['coordinates'] = array(
                'name'  => 'coordinates',
                'id'    => 'coordinates',
                'type'  => 'text',
                'class' =>'form-control',
                'value' =>set_value('coordinates',$item->coordinates)
            );

            $this->page['parking'] = array(
                'name'  => 'parking',
                'id'    => 'parking',
                'class' =>'form-control',
                'value' =>set_value('parking',$item->parking)
            );

            $this->page['transport'] = array(
                'name'  => 'transport',
                'id'    => 'transport',
                'class' =>'form-control',
                'value' =>set_value('transport',$item->transport)
            );
            $this->page['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'class' =>'form-control',
                'value' =>set_value('phone',$item->phone)
            );
            $this->page['work_hours'] = array(
                'name'  => 'work_hours',
                'id'    => 'work_hours',
                'class' =>'form-control',
                'value' =>set_value('work_hours',$item->work_hours)
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool) $item->status,
                'class'     =>'pull-right',
                'value'     =>1
            );


            $this->page['building_image'] = $this->gm->get_one(array('module_name'=>$this->controller,'group'=>'building','subject_id'=>$id));
            $this->page['enter_image'] = $this->gm->get_one(array('module_name'=>$this->controller,'group'=>'enter','subject_id'=>$id));
            $this->page['office_image'] = $this->gm->get_one(array('module_name'=>$this->controller,'group'=>'office','subject_id'=>$id));

            $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Адреса представительств');
            $this->breadcrumbs[] = array('title'=>'Редактирование');
            $this->render();
        }
        else
        {
            $m = show_error_message('Адрес не найден');
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


}