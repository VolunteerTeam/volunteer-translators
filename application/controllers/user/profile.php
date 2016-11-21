<?php
require_once(APPPATH.'core/MY_Form.php');

class Profile extends MY_Form {

	public function __construct()
	{
		parent::__construct();
	}

	function load_view($content,$custom_data=array()){
		$data['user'] = $this->ion_auth->user()->result_array()[0];
		$data['user']['city'] = $this->users_model->getCity($data['user']['city']);
		$data['user']['groups'] = $this->ion_auth->get_users_groups()->result_array();

		$data['js'] = array(
			'/js/vendor/jquery-ui.min.js',
			'/js/vendor/bootstrap/moment.min.js',
			'/js/vendor/bootstrap/locale/ru.js',
			'/js/cropit/dist/jquery.cropit.min.js',
			'/js/vendor/bootstrap/bootstrap-datetimepicker.min.js',
			'https://maps.google.com/maps/api/js?key=AIzaSyAcZF9a4bTTl7oT77NFJ3dozmSZNuISgA0&language=ru',
		);
		$data['css'] = array(
			'/css/vendor/bootstrap/bootstrap-datetimepicker.min.css',
			'/css/vendor/cropit.css'
		);

		$this->load->view('front/common/header', $data);
		$this->load->view($content,$custom_data);
		$this->load->view('front/common/footer');
	}
    
    function index()
    {
		if($this->ion_auth->logged_in()){
			$this->load_view('front/reg/profile');
		} else {
			redirect("user/auth");
		}

	}
		
	function save()
	{
		$user = $this->ion_auth->user()->row();

		//set validation rules
		if (!$user->email_confirm) {
			$this->form_validation->set_rules('email', 'Email ID', 'trim|required|valid_email|is_unique[users.email]');
		}
		if (empty($this->ion_auth->get_users_groups()->result_array())) {
			$this->form_validation->set_rules('group', 'Group', 'callback_group_required');
		}
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|alpha|min_length[3]|max_length[30]|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|matches[cpassword]|md5');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim');
		$this->form_validation->set_rules('about', 'About', 'trim|required|xss_clean');
		$this->form_validation->set_rules('job_post', 'Job Post', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dob', 'Date of Birth', 'required');
		$this->form_validation->set_rules('sex', 'Sex', 'required');
		$this->form_validation->set_rules('city', 'City', 'required|callback_check_coordinates');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|required|regex_match[/^\+7\-\d{3}\-\d{3}\-\d{2}\-\d{2}$/]');
		$this->form_validation->set_rules('soc_profiles', 'Social profiles', 'callback_social_required');
		$this->form_validation->set_rules('fb_profile', 'FB profile', 'callback_valid_fb_profile');
		$this->form_validation->set_rules('vk_profile', 'VK profile', 'callback_valid_vk_profile');
		$this->form_validation->set_rules('od_profile', 'OD profile', 'callback_valid_od_profile');
		$this->form_validation->set_rules('gp_profile', 'GP profile', 'callback_valid_gp_profile');
		$this->form_validation->set_rules('tw_profile', 'TW profile', 'callback_valid_tw_profile');
		$this->form_validation->set_rules('in_profile', 'IN profile', 'callback_valid_in_profile');
		$this->form_validation->set_rules('lj_profile', 'LJ profile', 'callback_valid_lj_profile');
		$this->form_validation->set_rules('li_profile', 'LI profile', 'callback_valid_li_profile');

		$this->form_validation->set_message('regex_match', 'поле должно быть заполнено в формате +7-ххх-ххх-хх-хх');
		$this->form_validation->set_message('required', 'поле обязательно для заполнения');
		$this->form_validation->set_message('valid_email', 'поле должно содержать правильный адрес электронной почты');
		$this->form_validation->set_message('matches', 'поле "Пароль" должно соответствовать значению в поле "Повторите пароль"');
		$this->form_validation->set_message('alpha', 'поле должно содержать только буквы');
		$this->form_validation->set_message('check_coordinates', 'Вы не указали действительные координаты');
		$this->form_validation->set_message('is_unique', 'такой E-mail уже есть в базе');

		//validate form input
		if ($this->form_validation->run() == FALSE) {
			// fails
			$this->load_view('front/reg/profile');
		} else {
			//insert the user profile details into database
			$city = $this->users_model->getCityId();
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'patro_name' => $this->input->post('patro_name'),
				'about' => $this->input->post('about'),
				'job_post' => $this->input->post('job_post'),
				'dob' => date('Y-m-d', strtotime($this->input->post('dob'))),
				'sex_type' => $this->input->post('sex'),
				'city' => $city,
				'phone' => $this->input->post('phone'),
				'skype' => $this->input->post('skype'),
				'fb_profile' => $this->input->post('fb_profile'),
				'vk_profile' => $this->input->post('vk_profile'),
				'od_profile' => $this->input->post('od_profile'),
				'gp_profile' => $this->input->post('gp_profile'),
				'tw_profile' => $this->input->post('tw_profile'),
				'in_profile' => $this->input->post('in_profile'),
				'lj_profile' => $this->input->post('lj_profile'),
				'li_profile' => $this->input->post('li_profile')
			);
			$img = $this->input->post('uploadfile');
			if ($img != NULL) {
				$img = str_replace('data:image/png;base64,', '', $img);
				$img = str_replace(' ', '+', $img);
				$dt = base64_decode($img);

				$uploaddir = './images/users/' . $user->id . "/";
				if (!file_exists($uploaddir)) {
					mkdir($uploaddir, 0777, true);
				}
				$img_generate_name = md5(uniqid(rand(), true));
				$imgname = $img_generate_name . ".png";
				$uploadfile = $uploaddir . basename($imgname);
				file_put_contents($uploadfile, $dt);
				$data['avatar'] = '/images/users/' . $user->id . "/" . $imgname;
			}
			if ($this->input->post('password')) $data['password'] = $this->input->post('password');
			if ($this->input->post('email')) {
				$data['email'] = $this->input->post('email');
			}
			var_dump($data);
			exit;
			// insert form data into database
			$user_id = $this->users_model->update_user($user->id, $data);
			if ($this->input->post('group')) $this->users_model->setGroup($user_id, $this->input->post('group'));

			//send verification email to user's email id
			if ($this->input->post('email')) {
				$this->load->library('email');
				$subject = 'Подтверждение адреса E-mail';
				$message = '<p>Здравствуйте, ' . $this->input->post('first_name') . '!</p>В Вашем личном кабинете на сайте https://v2.perevodov.info/ был изменён адрес E-mail. Для подтверждения нового адреса перейдите по <a href="' . $this->config->base_url() . 'user/activate?s=' . $secret . '&email=' . $this->input->post('email') . '">ссылке</a>. Если Вы не имеете отношения к сайту Волонтёры переводов и это письмо является ошибкой, можете не обращать внимания.';
				$from_email = 'system@perevodov.info';

				$result = $this->email
					->from($from_email)
					->reply_to('volontery@perevodov.info')
					->to($this->input->post('email'))
					->subject($subject)
					->message($message)
					->send();
				if ($result) {
					// successfully sent mail
					$this->load_view('front/reg/profile', array('status' => 'ok'));
				} else {
					$this->users_model->remove_user($user_id);
					$data['email_msg'] = '<div class="alert alert-danger text-center">Письмо на Вашу электронную почту не было отправлено. Проверьте, пожалуйста, ещё раз правильность указания E-mail или попробуйте позже.</div>';
					$this->load_view('front/reg/profile', $data);
				}
			}
			$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Ваш профиль успешно обновлён.</div>');
			redirect("user/profile");
		}
	}
}     
?>