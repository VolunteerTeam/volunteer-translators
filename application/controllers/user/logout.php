<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MY_Controller 
{
	function index() 
	{
		$this->ion_auth->logout();
		redirect("user/auth");
//		$this->load->library('session');
//		$this->session->unset_userdata('mail');
//
//		header('Location: /');
	}
}
