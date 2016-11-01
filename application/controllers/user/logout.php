<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MY_Controller 
{
	function index() 
	{
		$this->ion_auth->logout();
		redirect("user/auth");
	}
}
