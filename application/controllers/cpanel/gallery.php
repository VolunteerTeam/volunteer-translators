<?php
class Gallery extends CP_Controller{

    function __construct()
    {
        $this->access = array(
            'toggle_status'=>array('admin','manager'),
            'update_list'=>array('admin','manager'),
            'remove_gallery_item'=>array('admin')
        );

        parent::__construct();

    }

    function toggle_status()
    {
        $id = $this->input->post('id',true);
        if( ($data = $this->gm->get_one(array('id'=>$id)))!=false )
        {
            $result = ($data->status == 1) ? $this->gm->update(array('id'=>$id),array('status'=>0)) : $this->gm->update(array('id'=>$id),array('active'=>1));
            echo json_encode($result);
        }
    }


    function update_list()
    {

        $data = $this->input->post('new_data',true);

        foreach($data as $position=>$id)
        {

            $this->gm->update(array('id'=>$id),array('position'=>$position));

        }

        echo json_encode(array('result'=>true));

    }

    function remove_gallery_item($id,$module_name = '',$callback_value = 1,$method = 'edit')
    {

        $id = $this->gm->delete_gallery_item(array('id'=>$id));
        $m = ($id) ?show_success_message('Удаление прошло успешно') : show_error_message('удаление невозможно');
        $this->session->set_flashdata('message',$m);
        redirect('cpanel/'.$module_name.'/'.$method.'/'.$callback_value);

    }

}