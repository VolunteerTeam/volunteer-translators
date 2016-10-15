<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MY_Controller 
{
	function index() 
	{
		$this->load->library('session');
		$this->session->unset_userdata('mail');

		header('Location: /');
	}
}
