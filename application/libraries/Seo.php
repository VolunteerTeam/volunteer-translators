<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Seo{

    /**
     * CodeIgniter global
     *
     * @var string
     **/
    protected $ci;


    /**
     * __construct
     *
     * @return void
     * @author Ben
     **/
    public function __construct()
    {
        $this->ci =& get_instance();

        $this->ci->load->model('links_model');

    }

    /**
     * УНиверсальный метод для получения заголвка
     */
    public function get_meta($module = 'site_header')
    {
        $this->ci->load->config($module,true);
        $uri = $this->ci->uri->ruri_string();

        if( ($meta_data = $this->ci->links_model->get_title_by_path($uri)) !==false)
        {

            return array(
                'meta_title'=>array('content'=>$meta_data->title),
                'meta'=>array(
                    'keywords'=>array('content'=>$meta_data->keyword),
                    'description'=>array('content'=>$meta_data->description)
                )
            );

        }
        else
        {
            return array(
                'meta_title'=>$this->ci->config->item('meta_title', $module),
                'meta' => $this->ci->config->item('meta', $module)
            );
        }



    }




}