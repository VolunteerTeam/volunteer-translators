<?php

class CP_Controller extends CI_Controller {
    public  $breadcrumbs = array();

    public $access = array();
    function __construct()
    {

        parent::__construct();
        $this->load->model('seo_model','seo');
        $this->load->spark('debug-toolbar');

        $this->load->library('Translit');

        if(!$this->input->is_ajax_request())
        {
            //$this->output->enable_profiler(true);
        }

        if (!$this->ion_auth->logged_in())
        {
            redirect('login/index');
        }


        $this->user = $this->ion_auth->user()->row();

        $this->load->config('cpanel_header',true);


        $this->page['js']       =  $this->config->item('js','cpanel_header');
        $this->page['css']      =  $this->config->item('css','cpanel_header');

        $this->page['meta_title']    =  $this->config->item('meta_title','cpanel_header');
        $this->page['meta']     =  $this->config->item('meta','cpanel_header');



        $this->page['inline_js']       =  $this->config->item('inline_js','admin_header');


        //Основной шаблон
        $this->page['tpl'] = 'dashboard';
        $this->side = 'cpanel';

        $this->content_path = FCPATH.'/'.CONTENT_DIR.'/';

        $this->controller = $this->router->fetch_class();
        $this->method = $this->router->fetch_method();

        $this->upload_config = array(
            'upload_path'   =>$this->content_path,
            'allowed_types' =>'*',
            'encrypt_name'  =>true,
            'max_size'      =>10240,
            'max_width'     => '10000',
            'max_height'    => '10000'
        );
        $this->breadcrumbs[] = array('url'=>base_url($this->side),'title'=>'Главная');


        $this->form_validation->set_error_delimiters('<div class="alert alert-danger" role="alert">', '</div>');


        $this->load->model('gallery_model','gm');

        if(!$this->ion_auth->in_group($this->access[$this->method]))
        {
            $this->session->set_flashdata('message',show_error_message('У вас недостаточно прав'));
            redirect($this->side.'/errors/access_denied');
        }

    }


    /**
     * Метод отвечает за вывод информации, попутно подключая служебные js и css файлы
     */
    function render()
    {
        if(file_exists(FCPATH.'/js/cpanel/'.$this->controller.'.js'))
        {
            $this->page['js']['footer'][] = array('src'=>'/js/cpanel/'.$this->controller.'.js');
        }

        if(file_exists(FCPATH.'/css/cpanel/'.$this->controller.'.css'))
        {
            $this->page['css'][] = array('src'=>'/css/cpanel/'.$this->controller.'.css');
        }

        $this->page['content']['action_dir'] = $this->controller;
        $this->page['content']['action_tpl'] = $this->method;
        $this->page['inline_js'][] = '$("#'.$this->method.'").addClass(\'hover\');';
        $this->load->view('cpanel',  $this->page);
    }


    /*
      * Функция для манипуляции изображениями
      *
      */
    function image_manipulation($param,$field = 'files')
    {

        $config = $param['config'];


        $this->load->library('upload', $config);

        $upload_result = $this->upload->multi_upload_pro($field,$config);
        $data['upload_data']  = $upload_result['multi_data'];
        //Если ничего не загружали
        if(!$data['upload_data'])
        {
            return $data['multi_error'];
        }

        //Если необходимо уменьшить избражения
        if(isset($param['big_resize']) && $param['big_resize']!== FALSE)
        {
            //Запускаем цикл, в котором вызываем функцию уменьшения
            $config['image_library'] = 'gd2'; // выбираем библиотеку
            $config['width']	= (isset($param['big_width'])) ? $param['big_width'] : '1280';
            $config['height']	= (isset($param['big_height'])) ? $param['big_height'] : '800';

            $config['master_dim']='auto';
            $this->load->library('image_lib', $config); // загружаем библиотеку

            foreach($data['upload_data'] as $key)
            {
                $config['source_image']	= $param['config']['upload_path'].$key['file_name'];
                $config['new_image']	= $param['config']['upload_path'].$key['file_name'];

                $this->image_lib->initialize($config);
                $this->image_lib->resize(); // и вызываем функцию

            }

        }

        //Если необходимо уменьшить избражения с сохранением пропорций
        if(isset($param['big_resize_advanced']) && $param['big_resize_advanced']!== FALSE)
        {

            $config = array();
            $config['image_library'] = 'gd2'; // выбираем библиотеку


            $this->load->library('image_lib', $config); // загружаем библиотеку

            $do_resize = false;
            foreach($data['upload_data'] as $k=> $key)
            {
                //Запускаем цикл, в котором вызываем функцию уменьшения
                $image_info = getimagesize($param['config']['upload_path'].$key['file_name']);
                if($image_info[0]>$image_info[1])
                {


                    $prop =  round($image_info[0]/$image_info[1],2);

                    //альбомная ориентация
                    if($image_info[0]>$param['max_side_size'])
                    {
                        $config['width']	 = $param['max_side_size']; // и задаем размеры
                        $config['height']	 = round($param['max_side_size']/$prop);

                        $do_resize = true;
                    }

                }
                if($image_info[1]>$image_info[0])
                {
                    $prop =  round($image_info[0]/$image_info[1],2);
                    //вертикальная ориентация
                    if($image_info[1]>$param['max_side_size'])
                    {
                        $config['height']	 = $param['max_side_size']; // и задаем размеры
                        $config['width']	 = round($param['max_side_size']*$prop);
                        $do_resize = true;
                    }

                }
                if($image_info[0]==$image_info[1])
                {
                    $config['master_dim'] = 'auto';
                    if($image_info[0]>$param['max_side_size'])
                    {
                        $config['height']	 = $param['max_side_size']; // и задаем размеры

                        $config['width']	 = $param['max_side_size']; // и задаем размеры
                        $do_resize = true;
                    }

                }


                $config['source_image']	= $param['config']['upload_path'].$key['file_name'];
                $config['new_image']	= $param['config']['upload_path'].$key['file_name'];

                //$config['maintain_ratio'] = TRUE;

                if($do_resize)
                {
                    $this->image_lib->initialize($config);


                    if(!$this->image_lib->resize())
                    {
                        echo $this->image_lib->display_errors();

                    }

                    $data['upload_data'][$k]['big_resize_data']['width'] = $config['width'];
                    $data['upload_data'][$k]['big_resize_data']['height'] = $config['height'];

                }

            }

        }

        //Если необходим ресайз
        if(isset($param['create_thumb']) && $param['create_thumb']!== FALSE)
        {

            //Запускаем цикл, в котором вызываем функцию уменьшения и перемещения

            $resize_config = array();
            $resize_config['image_library'] = 'gd2'; // выбираем библиотеку
            $resize_config['width']	 = $param['width']; // и задаем размеры
            $resize_config['height']	 = $param['height']; // и задаем размеры
            $config['master_dim']='auto';
            $this->load->library('image_lib', $resize_config); // загружаем библиотеку

            foreach($data['upload_data'] as $key)
            {
                $resize_config['source_image']	= $param['config']['upload_path'].$key['file_name'];

                $resize_config['new_image'] = $param['config']['upload_path'].$key['raw_name'].'_thumb'.$key['file_ext'];
                $this->image_lib->initialize($resize_config);
                if ( ! $this->image_lib->resize())
                {
                    echo $this->image_lib->display_errors();

                }

            }
        }

        if(isset($param['crop']) && $param['crop']!== FALSE)
        {

            //Запускаем цикл, в котором вызываем функцию уменьшения и перемещения

            if(isset($param['crop_auto']) AND $param['crop_auto']!==false)
            {
                $crop_config = array();
                $crop_config['maintain_ratio'] = false;
                $crop_config['image_library'] = 'gd2'; // выбираем библиотеку


                $this->load->library('image_lib', $crop_config); // загружаем библиотеку

                foreach($data['upload_data'] as $k=> $key)
                {


                    $image_info = getimagesize($param['config']['upload_path'].$key['file_name']);


                    if($param['crop_width']==$param['crop_height'])
                    {
                        if($image_info[0]>$image_info[1])
                        {
                            $crop_config['width']	 = $image_info[1]; // и задаем размеры
                            $crop_config['height']	 = $image_info[1]; // и задаем размеры
                            $crop_config['x_axis']	 = round( ($image_info[0]-$image_info[1])/2); // и задаем размеры
                            $crop_config['y_axis']	 = 0; // и задаем размеры




                        }
                        elseif($image_info[0]<$image_info[1])
                        {
                            $crop_config['width']	 = $image_info[0]; // и задаем размеры
                            $crop_config['height']	 = $image_info[0]; // и задаем размеры
                            $crop_config['y_axis']	 = round( ($image_info[1]-$image_info[0])/2); // и задаем размеры
                            $crop_config['x_axis']	 = 0; // и задаем размеры

                        }
                        else
                        {
                            $crop_config['width']	 = $image_info[0]; // и задаем размеры
                            $crop_config['height']	 = $image_info[1]; // и задаем размеры
                            $crop_config['y_axis']	 = 0; // и задаем размеры
                            $crop_config['x_axis']	 = 0; // и задаем размеры

                        }
                    }
                    elseif($param['crop_width']>$param['crop_height'])
                    { //Другой размер

                        $ratio = round($param['crop_width']/$param['crop_height'],4);

                        //Если оригинал альбомный
                        if($image_info[0]>$image_info[1])
                        {
                            $image_ratio = round($image_info[0]/$image_info[1],4);

                            //Смотрим на пропорции
                            if($image_ratio > $ratio){

                                $crop_config['width']	 = round($image_info[1]*$ratio); // и задаем размеры
                                $crop_config['height']	 = $image_info[1];
                                $crop_config['x_axis']	 = round( ($image_info[0]-round($image_info[1]*$ratio))/2); // и задаем размеры
                                $crop_config['y_axis']	 = 0; // и задаем размеры


                            } else if($image_ratio <  $ratio){
                                $crop_config['width']	 = $image_info[0]; // и задаем размеры
                                $crop_config['height']	 = round($image_info[0]/$ratio); // и задаем размеры
                                $crop_config['x_axis']	 = 0; // и задаем размеры
                                $crop_config['y_axis']	 = round( ($image_info[1]-round($image_info[0]/$ratio))/2); // и задаем размеры
                            }

                        }
                        elseif($image_info[0]<$image_info[1])
                        {
                            $image_ratio = round($image_info[1]/$image_info[0],4);
                            //Смотрим на пропорции

                            $crop_config['width']	 = $image_info[0]; // и задаем размеры
                            $crop_config['height']	 = round($image_info[0]/$ratio);
                            $crop_config['x_axis']	 = 0; // и задаем размеры
                            $crop_config['y_axis']	 = round( ($image_info[1]-round($image_info[0]/$ratio))/2);  // и задаем размеры



                        }
                        else
                        {
                            $crop_config['width']	 = $image_info[0]; // и задаем размеры
                            $crop_config['height']	 = round($image_info[0]/$ratio); // и задаем размеры
                            $crop_config['x_axis']	 = 0; // и задаем размеры
                            $crop_config['y_axis']	 = round( ($image_info[1]-round($image_info[0]/$ratio))/2); // и задаем размеры


                        }
                    }



                    $data['upload_data'][$k]['crop_data'] = $crop_config;
                    $crop_config['source_image']	= $param['config']['upload_path'].$key['file_name'];

                    $crop_config['new_image'] = $param['config']['upload_path'].$key['raw_name'].'_thumb'.$key['file_ext'];
                    $this->image_lib->initialize($crop_config);
                    if ( ! $this->image_lib->crop())
                    {
                        echo $this->image_lib->display_errors();

                    }

                    //Необходимо также сжать кропанное
                    $resize_cropped_config['width']         = $param['crop_width'];
                    $resize_cropped_config['height']        = $param['crop_height'];
                    $resize_cropped_config['source_image']	= $param['config']['upload_path'].$key['raw_name'].'_thumb'.$key['file_ext'];
                    $resize_cropped_config['new_image']	    = $param['config']['upload_path'].$key['raw_name'].'_thumb'.$key['file_ext'];

                    $this->image_lib->initialize($resize_cropped_config);
                    $this->image_lib->resize(); // и вызываем функцию


                }





            }
            else
            {
                $crop_config = array();
                $crop_config['maintain_ratio'] = false;
                $crop_config['image_library'] = 'gd2'; // выбираем библиотеку
                $crop_config['width']	 = $param['crop_width']; // и задаем размеры
                $crop_config['height']	 = $param['crop_height']; // и задаем размеры
                $crop_config['x_axis']	 = $param['x_axis']; // и задаем размеры
                $crop_config['y_axis']	 = $param['y_axis']; // и задаем размеры

                $this->load->library('image_lib', $crop_config); // загружаем библиотеку

                foreach($data['upload_data'] as $k=> $key)
                {
                    $crop_config['source_image']	= $param['config']['upload_path'].$key['file_name'];

                    $crop_config['new_image'] = $param['config']['upload_path'].$key['raw_name'].'_thumb'.$key['file_ext'];

                    $data['upload_data'][$k]['crop_data'] = $crop_config;
                    $this->image_lib->initialize($crop_config);
                    if ( ! $this->image_lib->crop())
                    {
                        echo $this->image_lib->display_errors();

                    }

                }

            }

        }
        return $data['upload_data'];

    }



    function file_manipulation($param,$field = 'attach')
    {

        $this->load->library('upload', $param);

        $upload_result = $this->upload->multi_upload_pro($field,$param);
        if ( ! $upload_result['multi_data'])
        {
            return array('result'=>false,'message'=>$upload_result['multi_error']);

        }
        else
        {
            return array('result'=>true,'upload_data'=>$upload_result['multi_data']);

        }

    }

    /**
     * Упрощаем работу с изображениями
     * @param string $image_field_name - имя поля, в котором передается файл
     * @param array $options - опции ресайза
     * @param string $module_name - название модуля
     * @param string $group - название группы ( аватар или бэкграунд или сладйер )
     * @param int $subject_id - id к которому привязываем
     * @return bool
     */
    function image_process($image_field_name  = 'files',$options = array(),$module_name = 'module',$group = '',$subject_id = 0,$recrop = false)
    {


        //Если пришли только данные от кроппера - режем и ресайзим
        if($recrop)
        {
            $x = $this->input->post('x');
            $x2 = $this->input->post('x2');
            $y = $this->input->post('y');
            $y2 = $this->input->post('y2');
            $w = $this->input->post('w');
            $h = $this->input->post('h');
            if(($image_data = $this->gm->get_one(array('subject_id'=>$subject_id,'module_name'=>$module_name,'group'=>$group)))!=false)
            {
                $image = array();
                $image['cropped_x']     = $x;
                $image['cropped_x2']    = $x2;
                $image['cropped_y']     = $y;
                $image['cropped_y2']    = $y2;

                $crop_width =  $image['cropped_x2'] - $image['cropped_x'];
                $crop_height =  $image['cropped_y2'] - $image['cropped_y'];

                $path = $this->content_path;
                $crop_config = array();
                $crop_config['maintain_ratio'] = false;
                $crop_config['image_library'] = 'gd2'; // выбираем библиотеку
                $crop_config['width']	 = $crop_width; // и задаем размеры
                $crop_config['height']	 = $crop_height; // и задаем размеры
                $crop_config['x_axis']	 = $image['cropped_x']; // и задаем размеры
                $crop_config['y_axis']	 = $image['cropped_y']; // и задаем размеры



                $this->load->library('image_lib', $crop_config); // загружаем библиотеку

                $crop_config['source_image']	= $path.$image_data->filename;

                $crop_config['new_image'] = $path.$image_data->cropped_filename;
                $this->image_lib->initialize($crop_config);
                if ( ! $this->image_lib->crop())
                {
                    echo $this->image_lib->display_errors();

                }


                $resize_config = array();
                $resize_config['source_image'] = $path.$image_data->cropped_filename;
                $resize_config['image_library'] = 'gd2';
                $resize_config['width']   = $w;
                $resize_config['height']  = $h;


                $resize_config['new_image']	= $path.$image_data->cropped_filename;

                $this->image_lib->initialize($resize_config);

                $this->image_lib->resize(); // и вызываем функцию


                $this->gm->update_gallery_item($image_data->id,$image);

            }



        }

        //Работаем с изображениями
        if(isset($_FILES[$image_field_name]) AND $_FILES[$image_field_name]['name'][0] !='')
        {
            $param = $options;

            $param['config'] = $this->upload_config;
            //Ищем старое - удаляем

            if(($image_data = $this->gm->get_one(array('subject_id'=>$subject_id,'module_name'=>$module_name,'group'=>$group)))!=false)
            {

                $this->gm->delete_gallery_item(array('id'=>$image_data->id));

            }

            $output_data = $this->image_manipulation($param,$image_field_name);


            $total = count($output_data);

            if($total>0)
            {
                $output_ftxt = $this->input->post('ftxt');
                $i = 0;
                foreach($output_data as $key)
                {
                    $newrow = array(
                        'category'      =>0,
                        'module_name'   =>$module_name,
                        'group'         =>$group,
                        'subject_id'    =>$subject_id,
                        'filename'      =>$key['file_name'],
                        'cropped_filename'=>$key['raw_name'].'_thumb'.$key['file_ext'],
                        'description'     =>$output_ftxt['ru'][$i],
                        'status'        =>1,
                        'cropped_x'     =>isset($key['crop_data']) ?  $key['crop_data']['x_axis']:0,
                        'cropped_x2'    =>isset($key['crop_data'])? $key['crop_data']['width']:0,
                        'cropped_y'     =>isset($key['crop_data'])? $key['crop_data']['y_axis']:0,
                        'cropped_y2'    =>isset($key['crop_data']) ? $key['crop_data']['height'] :0,
                        'width'         =>$key['image_width'],
                        'height'        =>$key['image_height']
                    );
                    $this->gm->create($newrow);
                    $i++;
                }

                return true;

            }



        }

        return;
    }



    function seo_manipulation($item,$subject_id,$action = null,$module_name = null)
    {
        //Сохраняем сео инфо
        $seo = $this->input->post('seo');

        $seo['title'] = empty($seo['title']) ? $item['title'] : $seo['title'];
        $seo['url']     = empty($seo['url']) ? $this->translit->convert($item['title']) : $seo['url'];
        $seo['module_name'] = empty($module_name) ? $this->controller : $module_name;
        $seo['subject_id'] = $subject_id;
        if(!empty($action))
        {
            $seo['action'] = $action;
        }

        if($this->seo->get_one(array('subject_id'=>$subject_id,'module_name'=>$seo['module_name'])))
        {
            $this->seo->update(array('subject_id'=>$subject_id,'module_name'=>$seo['module_name']),$seo);
        }
        else
        {
            $this->seo->create($seo);
        }

    }

}