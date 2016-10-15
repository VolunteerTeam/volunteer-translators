<?php

class MY_Upload extends CI_Upload
{

    /**
     * Загружает массив файлов на сервер
     *
     * @access	public
     * @return	array
     */

    function multi_upload($field='userfield',$config)
    {
        if (!empty($_FILES[$field]))
        {
            $data=array();
            foreach ($_FILES[$field]['name'] AS $index => $val)
            {
                if(!empty($_FILES[$field]['name'][$index])) {
                    foreach ($_FILES[$field] AS $key => $val_arr)
                    {
                        $_FILES[$field.$index][$key] = $val_arr[$index];
                    }
                    self::initialize($config);

                    self::do_upload($field.$index);
                    $data[] =    self::data();


                }
            }
            unset($_FILES[$field]);

            return $data;
        }
    }



    function multi_upload_pro($field='userfield',$config)
    {
        if (!empty($_FILES[$field]))
        {
            $multi_error = array();
            $multi_data = array();
            $data=array();
            foreach ($_FILES[$field]['name'] AS $index => $val)
            {
                if(!empty($_FILES[$field]['name'][$index])) {
                    foreach ($_FILES[$field] AS $key => $val_arr)
                    {
                        $_FILES[$field.$index][$key] = $val_arr[$index];
                    }
                    self::initialize($config);

                    //self::do_upload($field.$index);

                    if(self::do_upload($field.$index))
                    {
                        $multi_data[$index]=self::data();
                    }
                    else
                    {
                        $multi_error[$index] = self::display_errors();
                    }




                }
            }
            unset($_FILES[$field]);

            $data['multi_error'] = $multi_error;
            $data['multi_data']  = $multi_data;


            return $data;
        }
    }

}

?>