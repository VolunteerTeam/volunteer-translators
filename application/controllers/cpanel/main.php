<?php


class Main extends CP_Controller {

    function __construct()
    {
        $this->access = array(
            'index'=>array('admin','manager'),
        );

        parent::__construct();

        $this->load->model('main_model','model');


    }

    function index($id = 1)
    {

        $this->form_validation->set_rules('phone', 'Телефон', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && ($old_item = $this->model->get_one(array('id'=>$id)) )!=false)
        {

            $item = array(
                'phone'             =>$this->input->post('phone'),
                'email'      =>$this->input->post('email'),
                'socials'            =>$this->input->post('socials'),

            );
            $result = $this->model->update(array('id'=>$id),$item);
            if($result)
            {

                $m = show_success_message('Обновление прошло успешно');
            }
            else
            {
                $m = show_error_message('Обновление невозможно');
            }

            //Отправляем обратно и сообщаем об успешной операции
            $this->session->set_flashdata('message',$m);
            redirect('cpanel/'.$this->controller);


        }
        elseif( ($this->page['item'] = $item = $this->model->get_one(array('id'=>1)) )!=false )
        {


            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');


            $this->page['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('phone',$item->phone)
            );

            $this->page['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'text',
                'class' =>'form-control',
                'required'=>true,
                'value' =>set_value('email',$item->email)
            );

            $this->render();
        }


    }

}