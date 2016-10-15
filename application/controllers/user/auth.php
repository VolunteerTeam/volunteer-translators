<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends My_Controller {

	function index() {
		$this->load->library('session');
		$cj=$this->session->userdata('mail');

		if($cj==true) {
			header('Location: /user/profile');         
		}

		$this->load->view('front/reg/header');

		$this->load->view('front/reg/auth');
		$this->load->view('front/reg/footer');
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