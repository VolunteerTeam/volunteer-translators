<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MY_Controller
{
	function index() {
		$this->load_view('front/users/orders');
	}

	function load_view($content,$data=array()){
		$sources = array();
		$sources['js'] = array(
			'/js/vendor/jquery-ui.min.js'
		);
		$sources['css'] = array();

		$this->load->view('front/common/header',$sources);
		$this->load->view($content,$data);
		$this->load->view('front/common/footer');
	}
}
