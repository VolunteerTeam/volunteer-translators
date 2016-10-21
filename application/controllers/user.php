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
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^\+7\-\d{3}\-\d{3}\-\d{2}\-\d{2}$/]');

        $this->form_validation->set_message('regex_match', 'поле должно быть заполнено в формате +7-ххх-ххх-хх-хх');
        $this->form_validation->set_message('required', 'поле обязательно для заполнения');
        $this->form_validation->set_message('valid_email', 'поле должно содержать правильный адрес электронной почты');
        $this->form_validation->set_message('matches', 'поле "Пароль" должно соответствовать значению в поле "Повторите пароль"');

        //validate form input
        if ($this->form_validation->run() == FALSE)
        {
            // fails

            $sources = array();
            $sources['js'] = array('/js/vendor/bootstrap/moment.min.js','/js/vendor/bootstrap/bootstrap-datetimepicker.min.js');
            $sources['css'] = array('/css/vendor/bootstrap/bootstrap-datetimepicker.min.css');

            $this->load->view('front/common/header',$sources);
            $this->load->view('front/users/reg_form');
            $this->load->view('front/common/footer');
        }
        else
        {
            //insert the user registration details into database
            $secret = sha1(rand(2589,195568).$this->input->post('email'));
            var_dump($secret);
            exit;
            $data = array(
                'fname' => $this->input->post('fname'),
                'lname' => $this->input->post('lname'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password')
            );
            // insert form data into database
            if ($this->users_model->insertUser($data))
            {
                // send email
                if ($this->users_model->sendEmail($this->input->post('email')))
                {
                    // successfully sent mail
                    $this->session->set_flashdata('msg','<div class="alert alert-success text-center">You are Successfully Registered! Please confirm the mail sent to your Email-ID!!!</div>');
                    redirect('user/register');
                }
                else
                {
                    // error
                    $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
                    redirect('user/register');
                }
            }
            else
            {
                // error
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Oops! Error.  Please try again later!!!</div>');
                redirect('user/register');
            }
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
}