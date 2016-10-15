<?php

class Users extends CP_Controller {

    const PER_PAGE = 100;
    public $page = array();

    function __construct()
    {
        $this->access = array(
            'index'         =>array('admin','moderator'),
            'search'        =>array('admin','moderator'),
            'add'           =>array('admin','moderator'),
            'edit'          =>array('admin','moderator'),
            'sorting'       =>array('admin','moderator'),
            'filter'        =>array('admin','moderator'),
            'remove'        =>array('admin'),
            'toggle_status' =>array('admin','moderator'),
            'groups'        =>array('admin','moderator'),
            'add_group'     =>array('admin'),
            'edit_group'    =>array('admin'),
            'remove_group'  =>array('admin'),
        );
        parent::__construct();
        $this->load->model('seo_model','seo');
        $this->page['js']['footer'][] = array('src'=>'/js/cpanel/chosen/chosen.jquery.min.js');
        $this->page['css'][] = array('src'=>'/js/cpanel/chosen/chosen.min.css');
    }

    function index()
    {
        $this->load->model('users_model');
        $where = array();
        $filter = $this->session->userdata('filter');
        if(!empty($filter))
        {
            $where = $filter;
        }

        $sorting = $this->session->userdata('sorting');
        if(!empty($sorting))
        {
            $this->db->order_by($sorting[0],$sorting[1]);
        }

        $this->page['message'] =  $this->session->flashdata('message');
        $this->page['users']   = $this->users_model->get_list(array(),1000,0);
        foreach($this->page['users'] as $k=>$user)
        {
            $this->page['users'][$k]->users_group =  $this->ion_auth->get_users_groups($user->id)->result();
        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Пользователи');

        $this->render();
    }

    function search()
    {
        $this->load->model('users_model');
        $q = $this->input->post('q',true);
        $this->page['query']  = $q;
        if(($config['total_rows']= $this->users_model->search_total($q)  )!=0)
        {
            $this->load->library('pagination');

            $config['base_url'] = base_url('cpanel/users/search');

            $config['cur_tag_open'] = ' <a class="current" href="#">';
            $config['cur_tag_close'] = '</a>';
            $config['per_page'] = self::PER_PAGE;
            $config['uri_segment'] = 4;

            $offset = $this->uri->segment(4,0);

            $this->pagination->initialize($config);

            $this->page['pagination'] = $this->pagination->create_links();
            $this->page['users']   = $this->users_model->search_list($q,self::PER_PAGE,$offset);
            foreach($this->page['users'] as $k=>$user)
            {
                $this->page['users'][$k]->users_group =  $this->ion_auth->get_users_groups($user->id)->result();
            }

        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Пользователи');
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller.'/search'),'title'=>'Пользователи - результаты поиска');

        $this->page['message'] =  $this->session->flashdata('message');

        $this->render();

    }

    function filter()
    {
        $filters = $this->input->post('filter',true);
        $f_array = array();
        foreach ($filters as $f => $v) {
            if($v!='' && $v!='all')
            {
                $f_array[$f]=$v;
            }

            if($v=='all')
            {
                $this->session->unset_userdata('filter');

            }

        }

        if(!empty($f_array))
        {
            $this->session->set_userdata('filter',$f_array);
        }


        if($this->input->post('do_reset'))
        {
            $this->session->unset_userdata('filter');
        }

        redirect($this->side.'/'.$this->controller);
    }

    function sorting()
    {
        $sort = $this->input->post('sorting',true);
        $s_array = array();

        if(!empty($sort)){
            $s_array = explode("__",$sort);
        }



        if(!empty($s_array))
        {
            $this->session->set_userdata('sorting',$s_array);
        }


        if($this->input->post('do_reset'))
        {
            $this->session->unset_userdata('sorting');
        }

        redirect($this->side.'/'.$this->controller);
    }

    /**
     *
     */
    function add()
    {

        //$this->form_validation->set_rules('username', 'Логин', 'required|xss_clean');
        $this->form_validation->set_rules('dob', 'Дата рождения', 'xss_clean');
        $this->form_validation->set_rules('sex', 'Пол', 'xss_clean');
        $this->form_validation->set_rules('coordinates', 'Координаты', 'xss_clean');
        $this->form_validation->set_rules('first_name', 'Имя', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Фамилия', 'required|xss_clean');
        $this->form_validation->set_rules('u_email', 'Email', 'xss_clean');
        $this->form_validation->set_rules('patro_name', 'Отчество', 'xss_clean');
        $this->form_validation->set_rules('job_post', 'Должность', 'xss_clean');
        $this->form_validation->set_rules('intro', 'Представление', 'xss_clean');
        $this->form_validation->set_rules('phone', 'Контактный телефон', 'xss_clean');
        $this->form_validation->set_rules('skype', 'Skype', 'xss_clean');
        $this->form_validation->set_rules('vk_profile', 'vk_profile', 'xss_clean');
        $this->form_validation->set_rules('fb_profile', 'fb_profile', 'xss_clean');
        $this->form_validation->set_rules('od_profile', 'od_profile', 'xss_clean');
        $this->form_validation->set_rules('tw_profile', 'tw_profile', 'xss_clean');
        $this->form_validation->set_rules('li_profile', 'li_profile', 'xss_clean');
        $this->form_validation->set_rules('gp_profile', 'gp_profile', 'xss_clean');
        $this->form_validation->set_rules('in_profile', 'in_profile', 'xss_clean');
        $this->form_validation->set_rules('lj_profile', 'lj_profile', 'xss_clean');


        $groups = $this->input->post('groups');
        $flat_groups = array();
        if(!empty($groups))
        {
            foreach($groups as $group){
                array_push($flat_groups,$group);
            }
        }

        if(in_array(2,$flat_groups) or in_array(1,$flat_groups)){
            $this->form_validation->set_rules('u_password', 'Пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[u_password_confirm]');
            $this->form_validation->set_rules('u_password_confirm', 'Повтор пароля', 'required');
            $password = $this->input->post('u_password');
        }
        else
        {
            $password = rand(8,8);
        }


        $username = $this->input->post('username');
        $email    = strtolower($this->input->post('u_email'));
        $additional_data = array(
            'username'    => $this->input->post('username'),
            'first_name'  => $this->input->post('first_name'),
            'last_name'   => $this->input->post('last_name'),
            'patro_name'  => $this->input->post('patro_name'),
            'job_post'    => $this->input->post('job_post'),
            'dob'         => $this->input->post('dob'),
            'sex'         => $this->input->post('sex'),
            'coordinates' => $this->input->post('coordinates'),
            'intro'       => $this->input->post('intro'),
            'phone'       => $this->input->post('phone'),
            'skype'       => $this->input->post('skype'),
            'vk_profile'  => $this->input->post('vk_profile'),
            'fb_profile'  => $this->input->post('fb_profile'),
            'od_profile'  => $this->input->post('od_profile'),
            'tw_profile'  => $this->input->post('tw_profile'),
            'li_profile'  => $this->input->post('li_profile'),
            'gp_profile'  => $this->input->post('gp_profile'),
            'in_profile'  => $this->input->post('in_profile'),
            'lj_profile'  => $this->input->post('lj_profile'),
        );

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && ($id=@$this->ion_auth->register($username, $password, $email, $additional_data,$groups))!==false )
        {
            $options   = array(
                'create_thumb'          =>TRUE,
                'width'                 =>200,
                'height'                =>200,
                'big_resize'            =>FALSE,
                'big_resize_advanced'   =>TRUE,
                'max_side_size'         =>800,
                'crop'                  =>FALSE,
            );
            $this->image_process('avatar',$options,$this->controller,'avatar',$id);
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect($this->side.'/'.$this->controller.'/index');
        }
        else
        {

            $this->page['groups'] = $this->ion_auth->groups()->result_array();
            $this->page['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->page['username'] = array(
                'name'  => 'username',
                'id'    => 'username',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('username')
            );

            $this->page['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('first_name')
            );
            
            $this->page['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('last_name')
            );

            $this->page['patro_name'] = array(
                'name'  => 'patro_name',
                'id'    => 'patro_name',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('patro_name')
            );

            $this->page['email'] = array(
                'name'  => 'u_email',
                'id'    => 'email',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('u_email')
            );

            $this->page['job_post'] = array(
                'name'  => 'job_post',
                'id'    => 'job_post',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('job_post')
            );
            
            $this->page['intro'] = array(
                'name'  => 'intro',
                'id'    => 'intro',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('intro')
            );
            
            $this->page['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('phone')
            );

            $this->page['skype'] = array(
                'name'  => 'skype',
                'id'    => 'skype',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('skype')
            );

            $this->page['sex'] = array(
                'name'  => 'sex',
                'id'    => 'sex',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('sex')
            );

            $this->page['coordinates'] = array(
                'name'  => 'coordinates',
                'id'    => 'coordinates',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('coordinates')
            );

            $this->page['dob'] = array(
                'name'  => 'dob',
                'id'    => 'datepicker',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' =>set_value('dob')
            );

            $this->page['vk_profile'] = array(
                'name'  => 'vk_profile',
                'id'    => 'vk_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('vk_profile')
            );

            $this->page['fb_profile'] = array(
                'name'  => 'fb_profile',
                'id'    => 'fb_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('fb_profile')
            );

            $this->page['od_profile'] = array(
                'name'  => 'od_profile',
                'id'    => 'od_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('od_profile')
            );

            $this->page['tw_profile'] = array(
                'name'  => 'tw_profile',
                'id'    => 'tw_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('tw_profile')
            );

            $this->page['li_profile'] = array(
                'name'  => 'li_profile',
                'id'    => 'li_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('li_profile')
            );

            $this->page['gp_profile'] = array(
                'name'  => 'gp_profile',
                'id'    => 'gp_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('gp_profile')
            );

            $this->page['in_profile'] = array(
                'name'  => 'in_profile',
                'id'    => 'in_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('in_profile')
            );

            $this->page['lj_profile'] = array(
                'name'  => 'lj_profile',
                'id'    => 'lj_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('lj_profile')
            );

            $this->page['password'] = array(
                'name' => 'u_password',
                'id'   => 'password',
                'class'=>'form-control',
                'type' => 'text'
            );
            $this->page['password_confirm'] = array(
                'name' => 'u_password_confirm',
                'id'   => 'password_confirm',
                'class'=>'form-control',
                'type' => 'text'
            );

        }

        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Пользователи');
        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->page['js']['footer'][] = array('src'=>'/js/cpanel/chosen/chosen.jquery.min.js');
        $this->page['css'][] = array('src'=>'/js/cpanel/chosen/chosen.min.css');

        $this->render();

    }

function edit($id)
    {

        $this->form_validation->set_rules('seo[url]', 'Логин', 'xss_clean');
        $this->form_validation->set_rules('dob', 'Дата рождения', 'xss_clean');
        $this->form_validation->set_rules('sex', 'Пол', 'xss_clean');
        $this->form_validation->set_rules('coordinates', 'Координаты', 'xss_clean');
        $this->form_validation->set_rules('first_name', 'Имя', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Фамилия', 'required|xss_clean');
        $this->form_validation->set_rules('patro_name', 'Отчество', 'xss_clean');
        $this->form_validation->set_rules('job_post', 'Должность', 'xss_clean');
        $this->form_validation->set_rules('intro', 'Представление', 'xss_clean');
        $this->form_validation->set_rules('phone', 'Контактный телефон', 'xss_clean');
        $this->form_validation->set_rules('skype', 'Skype', 'xss_clean');
        $this->form_validation->set_rules('vk_profile', 'vk_profile', 'xss_clean');
        $this->form_validation->set_rules('fb_profile', 'fb_profile', 'xss_clean');
        $this->form_validation->set_rules('od_profile', 'od_profile', 'xss_clean');
        $this->form_validation->set_rules('tw_profile', 'tw_profile', 'xss_clean');
        $this->form_validation->set_rules('li_profile', 'li_profile', 'xss_clean');
        $this->form_validation->set_rules('gp_profile', 'gp_profile', 'xss_clean');
        $this->form_validation->set_rules('in_profile', 'in_profile', 'xss_clean');
        $this->form_validation->set_rules('lj_profile', 'lj_profile', 'xss_clean');

        $user = $this->ion_auth->user($id)->row();

        $seo2 = array(
            'seo[url]'      => $this->input->post('username')
        );

        $data = array(
            'first_name'    => $this->input->post('first_name'),
            'last_name'     => $this->input->post('last_name'),
            'patro_name'    => $this->input->post('patro_name'),
            'phone'         => $this->input->post('phone'),
            'email'         => $this->input->post('u_email'),
            'job_post'      => $this->input->post('job_post'),
            'username'      => $this->input->post('username'),
            'intro'         => $this->input->post('intro'),
            'skype'         => $this->input->post('skype'),
            'dob'           => date('Y-m-d',strtotime($this->input->post('dob'))),
            'sex'           => $this->input->post('sex'),
            'coordinates'   => $this->input->post('coordinates'),            
            'vk_profile'    => $this->input->post('vk_profile'),
            'fb_profile'    => $this->input->post('fb_profile'),
            'od_profile'    => $this->input->post('od_profile'),
            'tw_profile'    => $this->input->post('tw_profile'),
            'li_profile'    => $this->input->post('li_profile'),
            'gp_profile'    => $this->input->post('gp_profile'),
            'in_profile'    => $this->input->post('in_profile'),
            'lj_profile'    => $this->input->post('lj_profile')
        );

        //update the password if it was posted
        if ($this->input->post('new_password'))
        {
            $this->form_validation->set_rules('new_password', 'Пароль', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', 'Повтор пароля', 'required');

            $data['password'] = $this->input->post('new_password');
        }

        if($this->input->post('uploadfile') != NULL) {
                $img = $this->input->post('uploadfile');
                $img = str_replace('data:image/png;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $dt = base64_decode($img);

                $uploaddir = './images/';
                $img_ganerate_name = md5(uniqid(rand(),true));
                $imgname = $img_ganerate_name.".png";
                $uploadfile = $uploaddir . basename($imgname);  
                file_put_contents($uploadfile, $dt);
                $data['avatar'] = "/images/" . $imgname;
        }

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && $this->ion_auth->update($user->id, $data))
        {
            //$this->seo_manipulation($seo2,$id,'detail','users');
            
            if($this->seo->get_one(array('action'=>'detail','subject_id'=>$id,'module_name'=>'users')))
            {
                $this->seo->update(array('action'=>'detail','subject_id'=>$id,'module_name'=>'users'),array('url'=>$this->input->post('username')));
            }
            else
            {
                $this->seo->create(array('url'=>$this->input->post('username'),'action'=>'detail','subject_id'=>$id,'module_name'=>'users'));
            }
            
            
            if ($this->ion_auth->is_admin())
            {
                //Update the groups user belongs to
                $groups = $this->input->post('groups');
                $this->ion_auth->remove_from_group('', $id);
                if (isset($groups) && !empty($groups)) {
                    foreach ($groups as $grp) {
                        $this->ion_auth->add_to_group($grp, $id);
                    }

                }
            }

            $this->session->set_flashdata('message', show_success_message('Обновление прошло успешно'));
            redirect('cpanel/'.$this->controller.'/edit/'.$id);
        }
        else
        {
            $user   = $this->ion_auth->user($id)->row();

            $this->page['groups'] = $this->ion_auth->groups()->result_array();
            $current_groups =  $this->ion_auth->get_users_groups($id)->result();

            $this->page['current_groups'] = array();
            if(!empty($current_groups))
            {
                foreach ($current_groups as $g) {
                    $this->page['current_groups'][$g->id] = $g->id;
                }
            }

            $this->page['avatar_image'] = $user->avatar;

            $this->page['message'] = validation_errors() ? validation_errors(): $this->session->flashdata('message');

            $seo_data = $this->seo->get_one(array('module_name'=>'users','subject_id'=>$id));

            $this->page['user_id'] = $id;
            
            $this->page['username'] = array(
                'name'  => 'username',
                'id'    => 'username',
                'type'  => 'text',
                'class'=>'form-control',
                'required'=>false,
                'value'=>set_value('susername',!empty($seo_data) ? $seo_data->url :'')
            );            
            
            $this->page['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'class'=>'form-control',
                'required'=>true,
                'value' => set_value('first_name', $user->first_name),
            );
            
            $this->page['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'class'=>'form-control',
                'required'=>true,
                'value' => set_value('last_name', $user->last_name),
            );

            $this->page['email'] = array(
                'name'  => 'u_email',
                'id'    => 'email',
                'type'  => 'text',
                'class' =>'form-control',
                'value' => set_value('u_email', $user->email)
            );

            $this->page['job_post'] = array(
                'name'  => 'job_post',
                'id'    => 'job_post',
                'type'  => 'text',
                'class' => 'form-control required',
                'value' => set_value('job_post', $user->job_post)
            );

            $this->page['intro'] = array(
                'name'  => 'intro',
                'id'    => 'intro',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('intro', $user->intro)
            );
            
            $this->page['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'class'=>'form-control required',

                'value'=>set_value('phone', $user->phone)
            );

            $this->page['patro_name'] = array(
                'name'  => 'patro_name',
                'id'    => 'patro_name',
                'type'  => 'text',
                'class'=>'form-control',

                'value'=>set_value('patro_name', $user->patro_name)
            );

            $this->page['skype'] = array(
                'name'  => 'skype',
                'id'    => 'skype',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('skype', $user->skype)
            );

            $this->page['sex'] = array(
                'name'  => 'sex',
                'id'    => 'sex',
                'type'  => 'text',
                'class'=>'form-control required',
                'value'=>set_value('sex', $user->sex)
            );

            $this->page['coordinates'] = array(
                'name'  => 'coordinates',
                'id'    => 'coordinates',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('coordinates', $user->coordinates)
            );

            $this->page['dob'] = array(
                'name'  => 'dob',
                'id'    => 'datepicker',
                'type'  => 'text',
                'class' => 'form-control required',
                'value' => set_value('dob', date('d.m.Y',strtotime($user->dob)))
            );


            $this->page['vk_profile'] = array(
                'name'  => 'vk_profile',
                'id'    => 'vk_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('vk_profile', $user->vk_profile)
            );

            $this->page['fb_profile'] = array(
                'name'  => 'fb_profile',
                'id'    => 'fb_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('fb_profile', $user->fb_profile)
            );

            $this->page['od_profile'] = array(
                'name'  => 'od_profile',
                'id'    => 'od_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('od_profile', $user->od_profile)
            );

            $this->page['tw_profile'] = array(
                'name'  => 'tw_profile',
                'id'    => 'tw_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('tw_profile', $user->tw_profile)
            );

            $this->page['li_profile'] = array(
                'name'  => 'li_profile',
                'id'    => 'li_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('li_profile', $user->li_profile)
            );

            $this->page['lj_profile'] = array(
                'name'  => 'lj_profile',
                'id'    => 'lj_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('lj_profile', $user->lj_profile)
            );

            $this->page['gp_profile'] = array(
                'name'  => 'gp_profile',
                'id'    => 'gp_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('gp_profile', $user->gp_profile)
            );

            $this->page['in_profile'] = array(
                'name'  => 'in_profile',
                'id'    => 'in_profile',
                'type'  => 'text',
                'class'=>'form-control',
                'value'=>set_value('in_profile',$user->in_profile)
            );

            $this->page['new_password'] = array(
                'name' => 'new_password',
                'id'   => 'new_password',
                'class'=>'form-control',
                'type' => 'text',
                'value'=>set_value('new_password')
            );
            $this->page['password_confirm'] = array(
                'name' => 'password_confirm',
                'id'   => 'password_confirm',
                'class'=>'form-control',
                'type' => 'text',
                'value'=>set_value('password_confirm')
            );
        }
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Пользователи');
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller.'/'.$this->method),'title'=>'Редактирование');


        $this->render();
    }

    /**
     * @param $id
     */
    function remove($id)
    {
        $this->ion_auth->delete_user($id);
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect($this->side.'/'.$this->controller.'/index');
    }

    /**
     *
     */
    function toggle_status()
    {
        $user_id = $this->input->post('id',true);

        $user_data = $this->ion_auth->user($user_id)->row();
        if(!empty($user_id) && !empty($user_data))
        {

            if($user_data->active == 1)
            {
                $result = $this->db->update('users',array('active'=>0),array('id'=>$user_id));
                //$response = array('')

            }
            else{
                $result = $this->db->update('users',array('active'=>1),array('id'=>$user_id));

            }

            echo json_encode($result);
        }


    }

    /**
     *
     */
    function groups()
    {
        $this->page['message'] =  $this->session->flashdata('message');
        $this->page['groups']   = $this->ion_auth->groups()->result();
        $this->breadcrumbs[] = array('url'=>base_url($this->side.'/'.$this->controller),'title'=>'Группы');
        $this->render();
    }


    /**
     *
     */
    function add_group()
    {

        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description')))
        {
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect($this->side.'/'.$this->controller.'/groups');
        }
        else
        {
            $this->page['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->page['group_name'] = array(
                'name'  => 'group_name',
                'id'    => 'group_name',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' => set_value('group_name')
            );
            $this->page['description'] = array(
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' => set_value('description')
            );

        }

        $this->breadcrumbs[] = array('title'=>'Добавление');
        $this->render();

    }


    /**
     * @param $id
     */
    function edit_group($id)
    {

        $group = $this->ion_auth->group($id)->row();
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');
        $this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

        //Если была отправлена форма и валидация прошла
        if($this->input->post('do') && $this->form_validation->run() && $this->ion_auth->update_group($group->id,$this->input->post('group_name'), $this->input->post('description')))
        {
            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect('cpanel/'.$this->controller.'/edit_group/'.$id);
        }
        else
        {
            $this->page['message'] =  (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->page['group_id'] = $id;
            $this->page['group_name'] = array(
                'name'  => 'group_name',
                'id'    => 'group_name',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' => set_value('group_name',$group->name)
            );
            $this->page['description'] = array(
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'class' =>'form-control required',
                'value' => set_value('description',$group->description)
            );
        }
        $this->breadcrumbs[] = array('title'=>'Редактирование');
        $this->render();
    }


    function remove_group($id){
        $this->ion_auth->delete_group($id);
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect($this->side.'/'.$this->controller.'/groups');
    }


}