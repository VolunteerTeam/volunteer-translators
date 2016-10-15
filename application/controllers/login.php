<?php


class Login extends CI_Controller {


    function __construct()
    {
        parent::__construct();

        $this->load->config('cpanel_header',true);
        $this->page['js']       =  $this->config->item('js','cpanel_header');
        $this->page['css']      =  $this->config->item('css','cpanel_header');
        $this->page['title']    =  $this->config->item('title','cpanel_header');
        $this->page['meta']     =  $this->config->item('meta','cpanel_header');
        $this->page['inline_js']       =  $this->config->item('inline_js','admin_header');

    }


    function index()
    {

        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Пароль', 'required|xss_clean');

        $remember = (bool) $this->input->post('remember');
        if($this->input->post('do_login')
            && $this->form_validation->run()
            && $this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
        {

                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('cpanel/main');
        }
        else
        {
            $ion_error = $this->ion_auth->errors();
            $this->page['message'] =  validation_errors() ? validation_errors() : !empty($ion_error)? $ion_error : $this->session->flashdata('message');

            $this->page['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'email',
                'class' =>'form-control required',
                'value' => set_value('email'),
                'placeholder'=>'ваш email'
            );
            $this->page['password'] = array(
                'name'  => 'password',
                'id'    => 'password',
                'type'  => 'password',
                'class' =>'form-control required',
                'value' =>'',
                'placeholder'=>'ваш пароль'
            );

            $this->page['remember'] = array(
                'name'  => 'remember',
                'id'    => 'remember',
                'checked'=>set_checkbox('remember',true),
                'value' =>'1',

            );
        }

        $this->page['js'][] = array('src'=>'/js/cpanel/login.js');
        $this->load->view('login/index',  $this->page);
    }

    function logout()
    {
        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('login');
    }

    function restore()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|xss_clean|callback_email_check');


        if($this->input->post('do_restore')
            && $this->form_validation->run()
            && $this->ion_auth->forgotten_password($this->input->post('email',true))
           )
        {

            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect('login');
        }
        else
        {
            $this->page['message'] = $this->ion_auth->errors()? $this->ion_auth->errors() : validation_errors() ? validation_errors(): $this->session->flashdata('message');

            $this->page['email'] = array(
                'name'  => 'email',
                'id'    => 'email',
                'type'  => 'email',
                'class' =>'form-control required',
                'value' => set_value('email'),
                'placeholder'=>'ваш email'
            );

        }

        $this->page['js'][] = array('src'=>'/js/cpanel/login.js');
        $this->load->view('login/restore',  $this->page);
    }

    //reset password - final step for forgotten password
    public function reset_password($code = NULL)
    {
        if (!$code)
        {
            $this->ion_auth->set_message('forgot_password_unsuccessful');
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("login/restore");
        }

        $user = $this->ion_auth->forgotten_password_check($code);


        if ($user)
        {
            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false)
            {
                //display the form

                //set the flash data error message if there is one
                $this->page['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');


                $this->page['new_password'] = array(
                    'name' => 'new',
                    'id'   => 'new',
                    'type' => 'password',
                    'class'=>'form-control required'
                );
                $this->page['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id'   => 'new_confirm',
                    'type' => 'password',
                    'class'=>'form-control required'
                );
                $this->page['user_id'] = array(
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                );
                $this->page['js'][] = array('src'=>'/js/cpanel/login.js');
                $this->load->view('login/reset',  $this->page);
            }
            else
            {
                // do we have a valid request?
                if ($user->id != $this->input->post('user_id'))
                {
                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);
                    $this->ion_auth->set_message('forgot_password_unsuccessful');
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect("login/restore");
                }
                else
                {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};
                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change)
                    {
                        //if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        $this->logout();
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('login/reset_password/' . $code);
                    }
                }
            }
        }
        else
        {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("login/restore");
        }
    }


    public function email_check($str)
    {
        $identity = $this->ion_auth->where('email', strtolower($str))->users()->row();
        if (empty($identity))
        {
            $this->form_validation->set_message('email_check', 'В базе отсутствует пользователь с таким email');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}