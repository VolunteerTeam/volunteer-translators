<?php
/**
 * Created by PhpStorm.
 * User: Елена
 * Date: 18.10.2016
 * Time: 11:05
 */

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('session', 'form_validation', 'email'));
        $this->load->database();
        $this->load->model('users_model');
    }

    function index()
    {
        $this->register();
    }

    function register()
    {
        //set validation rules
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
        $this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|md5');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        $this->form_validation->set_rules('about', 'About', 'trim|required|xss_clean');
        $this->form_validation->set_rules('job_post', 'Job Post', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'required');
        $this->form_validation->set_rules('sex', 'Sex', 'required');
        $this->form_validation->set_rules('group', 'Date of Birth', 'callback_group_required');
        $this->form_validation->set_rules('city', 'City', 'required|callback_check_coordinates');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^\+7\-\d{3}\-\d{3}\-\d{2}\-\d{2}$/]');
        $this->form_validation->set_rules('soc_profiles', 'Social profiles', 'callback_social_required');
        $this->form_validation->set_rules('fb_profile', 'FB profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('vk_profile', 'VK profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('od_profile', 'OD profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('gp_profile', 'GP profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('tw_profile', 'TW profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('in_profile', 'IN profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('lj_profile', 'LJ profile', 'callback_valid_fb_profile');
        $this->form_validation->set_rules('li_profile', 'LI profile', 'callback_valid_fb_profile');

        $this->form_validation->set_message('regex_match', 'поле должно быть заполнено в формате +7-ххх-ххх-хх-хх');
        $this->form_validation->set_message('required', 'поле обязательно для заполнения');
        $this->form_validation->set_message('valid_email', 'поле должно содержать правильный адрес электронной почты');
        $this->form_validation->set_message('matches', 'поле "Пароль" должно соответствовать значению в поле "Повторите пароль"');
        $this->form_validation->set_message('alpha', 'поле должно содержать только буквы');
        $this->form_validation->set_message('check_coordinates', 'Вы не указали действительные координаты');
        $this->form_validation->set_message('is_unique', 'такой E-mail уже есть в базе');

        //validate form input
        if ($this->form_validation->run() == FALSE)
        {
            // fails

            $sources = array();
            $sources['js'] = array(
                                '/js/vendor/jquery-ui.min.js',
                                '/js/vendor/bootstrap/moment.min.js',
                                '/js/vendor/bootstrap/locale/ru.js',
                                '/js/vendor/bootstrap/bootstrap-datetimepicker.min.js',
                                'https://maps.google.com/maps/api/js?key=AIzaSyAcZF9a4bTTl7oT77NFJ3dozmSZNuISgA0&language=ru'
                             );
            $sources['css'] = array('/css/vendor/bootstrap/bootstrap-datetimepicker.min.css');

            $this->load->view('front/common/header',$sources);
            $this->load->view('front/users/reg_form');
            $this->load->view('front/common/footer');
        }
        else
        {
            $this->load->view('front/common/header');
            $this->load->view('front/users/dummy');
            $this->load->view('front/common/footer');
            //insert the user registration details into database
//            $salt = rand(2589,195568);
//            $secret = sha1($salt.$this->input->post('email'));
//            $password = sha1($salt.$this->input->post('password'));
//            $city = $this->users_model->getCityId();
//
//            $data = array(
//                'secret_key' => $secret,
//                'ip_address' => $_SERVER['REMOTE_ADDR'],
//                'password' => $password,
//                'salt' => $salt,
//                'email' => $this->input->post('email'),
//                'first_name' => $this->input->post('first_name'),
//                'last_name' => $this->input->post('last_name'),
//                'patro_name' => $this->input->post('patro_name'),
//                'phone' => $this->input->post('phone'),
//                'job_post' => $this->input->post('job_post'),
//                'skype' => $this->input->post('skype'),
//                'vk_profile' => $this->input->post('vk_profile'),
//                'fb_profile' => $this->input->post('fb_profile'),
//                'od_profile' => $this->input->post('od_profile'),
//                'tw_profile' => $this->input->post('tw_profile'),
//                'li_profile' => $this->input->post('li_profile'),
//                'lj_profile' => $this->input->post('lj_profile'),
//                'gp_profile' => $this->input->post('gp_profile'),
//                'in_profile' => $this->input->post('in_profile'),
//                'dob' => $this->input->post('dob'),
//                'sex_type' => $this->input->post('sex'),
//                'coordinates' => $this->input->post('coordinates'),
//                'about' => $this->input->post('about'),
//                'group_id' => $this->input->post('group'),
//                'city' => $city
//            );
//            // insert form data into database
//            if ($this->users_model->insertUser($data))
//            {
//                // send email
//                if ($this->users_model->sendEmail($this->input->post('email')))
//                {
//                    // successfully sent mail
//                    $this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Registered! Please confirm the mail sent to your Email-ID!!!</div>');
//                    redirect('user/register');
//                }
//                else
//                {
//                    // error
//                    $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
//                    redirect('user/register');
//                }
//            }
//            else
//            {
//                // error
//                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
//                redirect('user/register');
//            }
        }
    }

    function verify($hash=NULL)
    {
        if ($this->users_model->verifyEmailID($hash))
        {
            $this->session->set_flashdata('verify_msg','<div class="alert alert-success text-center">Your Email Address is successfully verified! Please login to access your account!</div>');
            redirect('user/register');
        }
        else
        {
            $this->session->set_flashdata('verify_msg','<div class="alert alert-danger text-center">Sorry! There is error verifying your Email Address!</div>');
            redirect('user/register');
        }
    }

    // Функции валидации (в будущем надо вынести в отдельный класс)

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
        if(!$str || preg_match('/^(http:\/\/|https:\/\/)?(www\.)?plus\.google\.com\/(\w|\d)+?\/?$/',$str)){
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