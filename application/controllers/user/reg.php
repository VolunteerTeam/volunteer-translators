<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Reg extends MY_Controller 
{
	function index() 
	{
		$this->load->library('session');
		$cj = $this->session->userdata('mail');

		if($cj == true) {
			header('Location: /user/profile');
		}

		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		$this->form_validation->set_rules('name',       'name',       'required');
		$this->form_validation->set_rules('surname',    'surname',    'required');
		//	$this->form_validation->set_rules('patronymic', 'patronymic', 'required');//не обязательное поле
		
		
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('sex', 'sex', 'required');
		$this->form_validation->set_rules('pret', 'pret', 'required');	
		$this->form_validation->set_rules('dol', 'dol', 'required');
		$this->form_validation->set_rules('tel', 'tel', 'required');	
		$this->form_validation->set_rules('email', 'email', 'is_unique[users.email]');
		$this->form_validation->set_message('is_unique', 'Извините, но данный E-mail занят, выберите другой!');

		if ($this->form_validation->run() == FALSE) {
			$this->render();
		} else {
			$this->load->library('email');
			$secret   = rand(5689,15972);
			$password = $this->input->post('password');
			$adres    = $this->input->post('email');
			
			$name       = $this->input->post('name');
			$surname    = $this->input->post('surname');
			$patronymic = $this->input->post('patronymic');
			
			$sex      = $this->input->post('sex');
			$pret     = $this->input->post('pret');
			$dol      = $this->input->post('dol');
			$tel      = $this->input->post('tel');
			$skype    = $this->input->post('skype');
			$group     = $this->input->post('group');
			$city     = $this->input->post('city');
			$coordinates = $this->input->post('coordinates');
			$calendar = $this->input->post('calendar');
			
			
			$sc['fb_profile'] = $this->input->post('fb_profile');
			$sc['vk_profile'] = $this->input->post('vk_profile');
			$sc['od_profile'] = $this->input->post('od_profile');
			$sc['gp_profile'] = $this->input->post('gp_profile');
			$sc['tw_profile'] = $this->input->post('tw_profile');
			$sc['in_profile'] = $this->input->post('in_profile');
			$sc['lj_profile'] = $this->input->post('lj_profile');
			$sc['li_profile'] = $this->input->post('li_profile');

			$data = array(
				'ip_address' => $_SERVER['REMOTE_ADDR'],
				'password' => md5($password),
				'active'   => 0,
				'email'     => $adres,
				'intro'	   =>$city,
				'secret'	=> $secret,
				'first_name'       => $name,
				'last_name'    => $surname,
				'patro_name' => $patronymic,
				
				'dob' => $calendar,
				
				'sex'      => $sex,
				'about'     => $pret,
				'phone'      => $tel,
				'skype'    => $skype,
				'job_post'     => $dol,
				'coordinates' => $coordinates,
				'group' =>	$group
			);
			
			foreach($sc as $k => $v) {
				$data[$k] = $v;
			}


			$this->load->model('users');
			$this->users->regmail($data);


			$this->email->from('support@perevodov.info', 'Волонтёр');
			$this->email->to($adres); 

			$this->email->subject('Активация аккаунта');
			$this->email->message('Здравствуйте '.$name.', для активация вашего аккаунта перейдите по <a href="http://v2.perevodov.info/user/reg/active/'.$secret.'">ссылке</a>');	

			$this->email->send();

			$this->load->view('front/reg/header');
			$this->load->view('front/reg/regmail');
			$this->load->view('front/reg/footer');
		}
	}
 
	function active($secret) 
	{
		$this->load->model('users');
		$data=$this->users->active($secret);
		if($data) {
			$al = array('secret'=>$secret, 'active'=>'1');
			$this->users->activego($al);

			$this->load->view('front/reg/header');
			$this->load->view('front/reg/active');
			$this->load->view('front/reg/footer');

			redirect('user/auth', 'refresh');
		} else {
			$this->load->view('front/reg/header');
			$this->load->view('front/reg/noactive');
			$this->load->view('front/reg/footer');
		}
	}
}
?>