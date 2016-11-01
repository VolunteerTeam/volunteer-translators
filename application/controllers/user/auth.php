<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends My_Controller {

	function index() {
		$this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
		$this->form_validation->set_rules('password', 'Пароль', 'required|xss_clean');

		$remember = (bool) $this->input->post('remember');

		if($this->input->post('do_login')
			&& $this->form_validation->run()
			&& $this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
		{
			$this->session->set_flashdata('msg', $this->ion_auth->messages());
			redirect('user/profile');
		} else {
			if($this->ion_auth->errors()) {
				$data['msg'] = $this->ion_auth->errors();
			}
			$this->load->view('front/common/header');
			if(isset($data)) {
				$this->load->view('front/reg/auth',$data);
			} else {
				$this->load->view('front/reg/auth');
			}
			$this->load->view('front/common/footer');
		}
	}

	function ajax() {
	    $this->load->library('session');
	 
		$login = $_POST['mail'];

		$password = md5($_POST['password']);

		$this->data['result'] = $query_check_user = $this->db->query("SELECT * FROM users WHERE email= '".$login."' and password = '".$password."' and active = '1'");

	 	$userdata = $query_check_user->row_array(); 

		if (@$userdata['email'] == $login and @$userdata['password'] == $password) { 
			$authdata = array('mail' => $login, 'login' => true );
			
			$this->session->set_userdata($authdata); 

			$data['success'] = true;
		} else {   
			$data['success'] = false;
		}

		echo json_encode($data);
	}

	public function log() {
		$this->load->library('session');

		$data =  $this->session->userdata('mail');
		$array_items = array('mail' => $data, 'logged_in' => 'logged_in');

		$this->session->unset_userdata($array_items);

		header('Location: /');
	}

}


?>