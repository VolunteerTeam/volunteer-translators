<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('form_validation', 'email'));
        $this->load->database();
        $this->load->model('users_model');
    }

    function check_coordinates() {
        return $this->input->post('latlng') ? TRUE : FALSE;
    }

    function group_required($str) {
        if(!$str){
            $this->form_validation->set_message('group_required', 'выберите Вашу роль');
            return false;
        }
        return true;
    }

    function agreement($str) {
        if(!$str){
            $this->form_validation->set_message('agreement', 'для регистрации на портале Вы должны принять условия Пользовательского соглашения');
            return false;
        }
        return true;
    }

    function social_required(){
        if( !$this->input->post('vk_profile') &&
            !$this->input->post('fb_profile') &&
            !$this->input->post('od_profile') &&
            !$this->input->post('tw_profile') &&
            !$this->input->post('li_profile') &&
            !$this->input->post('lj_profile') &&
            !$this->input->post('gp_profile') &&
            !$this->input->post('in_profile')){

            $this->form_validation->set_message('social_required', 'Пожалуйста, укажите хотя бы один профиль из соцсетей:');
            return false;
        }
        return true;
    }

    function valid_fb_profile($str){
        if(!$str || preg_match('/(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[?\w\-]*\/)?(?:profile.php\?id=(?=\d.*))?([\w\-]*)?/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_fb_profile', 'неверно указан URL к профилю');
            return false;
        }
    }

    function valid_vk_profile($str){
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?vk\.com\/(\w|\d)+?\/?$/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_vk_profile', 'неверно указан URL к профилю');
            return false;
        }
    }

    function valid_od_profile($str){
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?ok\.ru\/(\w|\d)+?\/?$/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_od_profile', 'неверно указан URL к профилю');
            return false;
        }
    }

    function valid_gp_profile($str){
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?plus\.google\.com\/.+$/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_gp_profile', 'неверно указан URL к профилю');
            return false;
        }
    }

    function valid_tw_profile($str){
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?twitter\.com\/(\w|\d)+?\/?$/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_tw_profile', 'неверно указан URL к профилю');
            return false;
        }
    }

    function valid_in_profile($str){
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?instagram\.com\/(\w|\d)+?\/?$/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_in_profile', 'неверно указан URL к профилю');
            return false;
        }
    }

    function valid_lj_profile($str){
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?\w+\.livejournal\.com(\/)?$/',$str)){
            return true;
        } else {
            $this->form_validation->set_message('valid_lj_profile', 'неверно указан URL к профилю');
            return false;
        }
    }
}