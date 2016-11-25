<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends MY_Controller
{
	function index() {
		if($this->ion_auth->logged_in()){
			$this->load_view('front/users/orders');
		} else {
			redirect("user/auth");
		}
	}

	function getOrders() {
		//Get records from database
		$this->input->get("jtSorting") ? $jtSorting = $this->input->get("jtSorting") : $jtSorting = "first_name ASC";
		$this->input->get("jtStartIndex") ? $jtStartIndex = $this->input->get("jtStartIndex") : $jtStartIndex = 0;
		$this->input->get("jtPageSize") ? $jtPageSize = $this->input->get("jtPageSize") : $jtPageSize = 10;
		$sql = "SELECT id, first_name FROM users ORDER BY ".$jtSorting." LIMIT ".$jtStartIndex.",".$jtPageSize;
		$query = $this->db->query($sql);
		$rows = $query->result();

		$queryCount = $this->db->get("users");
		$totalCount = $queryCount->num_rows();

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $totalCount;
		$jTableResult['Records'] = $rows;
		echo json_encode($jTableResult);
	}

	function createOrder(){
		var_dump($this->input->post());
		var_dump($_FILES);
		exit;
	}

	function load_view($content,$data=array()){
		$sources = array();
		$sources['js'] = array(
			'/js/vendor/jquery-ui.min.js',
			'/js/vendor/jtable.2.4.0/jquery.jtable.js',
			'/js/vendor/jtable.2.4.0/localization/jquery.jtable.ru.js',
			'/js/cropit/dist/jquery.cropit.min.js',
		);
		$sources['css'] = array(
			'/js/vendor/jtable.2.4.0/themes/metro/purple/jtable.min.css',
			'/css/vendor/cropit.css'
		);

		$this->load->view('front/common/header',$sources);
		$this->load->view($content,$data);
		$this->load->view('front/common/footer');
	}
}
