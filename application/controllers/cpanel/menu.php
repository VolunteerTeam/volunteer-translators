<?php


class Menu extends CP_Controller {

    const PER_PAGE = 50;
    public $page = array();

    function __construct()
    {
        $this->access = array(
            'index'=>array('admin','manager'),
            'add'   =>array('admin','manager'),
            'edit'  =>array('admin','manager'),
            'remove'=>array('admin','manager'),
            'toggle_status'=>array('admin','manager')
        );

        parent::__construct();
        $this->load->model('menu_model','model');
    }


    function index()
    {
        $where = array('pid'=>0);
        $this->page['items']   = $this->model->get_list($where,1000,0,'position','asc');
        if(!empty($this->page['items']))
        {
            foreach($this->page['items'] as $k=>$v)
            {
                $this->page['items'][$k]->sub_menu = $this->model->get_list(array('pid'=>$v->id),1000,0,'position','asc');
            }
        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Меню');
        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();
    }


    function add($pid = 0)
    {
        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('url', 'Ссылка', 'required');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run())
        {

            $item = array(
                'title'     =>$this->input->post('title'),
                'url'       =>$this->input->post('url'),
                'pid'       =>$this->input->post('pid'),
                'status'    =>$this->input->post('status'),
                'position'    =>$this->input->post('position'),
            );
            $id = $this->model->create($item);

            $m =  ($id) ? show_success_message('Создание прошло успешно') : show_error_message('Создание невозможно');

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller);
        }
        else
        {

            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');


            $pre_options = $this->model->get_list(array('pid'=>0));
            $options = array('0'=>'Меню верхнего уровня');
            if(!empty($pre_options))
            {
                foreach($pre_options as $row)
                {
                    $options[$row->id] = $row->title;

                }
            }

            $this->page['content']['pid'] = array(
                'options'  =>$options ,
                'default'   =>set_value('pid'),
                'additional'=>' id = "pid" class="form-control" ',
            );

            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('title')
            );

            $this->page['translit_title'] = array(
                'name'  => 'translit_title',
                'id'    => 'translit_title',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('translit_title')
            );

            $this->page['url'] = array(
                'name'  => 'url',
                'id'    => 'url',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('url')
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)set_value('status'),
                'class'     =>'pull-right',
                'value'     =>1
            );

            $this->page['position'] = array(
                'name'      => 'position',
                'id'        => 'position',
                'class' =>'form-control',
                'value'     =>set_value('position',0)
            );


        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Меню');
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();
    }

    function edit($id)
    {
        $this->form_validation->set_rules('title', 'Заголовок', 'required|xss_clean');
        $this->form_validation->set_rules('url', 'Ссылка', 'required');


        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run())
        {
            $item = array(
                'title'     =>$this->input->post('title'),
                'url'       =>$this->input->post('url'),
                'pid'       =>$this->input->post('pid'),
                'status'    =>$this->input->post('status'),
                'position'    =>$this->input->post('position'),
            );
            $id = $this->model->update(array('id'=>$id),$item);

            $m =  ($id) ? show_success_message('Обновление прошло успешно') : show_error_message('Обновление невозможно');

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller);
        }
        elseif( ($item = $this->page['item']  = $this->model->get_one(array('id'=>$id)))!==false )
        {

            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');


            $pre_options = $this->model->get_list(array('pid'=>0));
            $options = array('0'=>'Меню верхнего уровня');
            if(!empty($pre_options))
            {
                foreach($pre_options as $row)
                {
                    $options[$row->id] = $row->title;

                }
            }

            $this->page['content']['pid'] = array(
                'options'  =>$options ,
                'default'   =>set_value('pid',$item->pid),
                'additional'=>' id = "pid" class="form-control" ',
            );

            $this->page['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('title',$item->title)
            );


            $this->page['url'] = array(
                'name'  => 'url',
                'id'    => 'url',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('url',$item->url)
            );

            $this->page['status'] = array(
                'name'      => 'status',
                'id'        => 'publish',
                'checked'   =>(bool)set_value('status',$item->status),
                'class'     =>'pull-right',
                'value'     =>1
            );
            $this->page['position'] = array(
                'name'      => 'position',
                'id'        => 'position',
                'class' =>'form-control',
                'value'     =>set_value('position',$item->position)
            );


        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Меню');
        $this->breadcrumbs[] = array('title'=>'Редактирование');
        $this->render();

    }

    function remove($id)
    {

        $result = $this->model->delete(array('id'=>$id));

        $m = ($result) ?  show_success_message('Элемент удален') :  show_error_message('Невозможно удалить элемент') ;

        if($this->input->is_ajax_request())
        {
            $data = array('result'=>$result,'message'=>$m);
            echo json_encode($data);
        }
        else
        {
            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller.'/index');

        }
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

}