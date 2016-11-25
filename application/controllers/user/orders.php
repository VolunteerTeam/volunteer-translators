<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Form.php');

class Orders extends MY_Form
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
		$this->form_validation->set_rules('purpose', 'Purpose', 'trim|required|min_length[3]|max_length[500]|xss_clean');
		$this->form_validation->set_rules('receiver', 'Receiver', 'trim|required|min_length[3]|max_length[500]|xss_clean');
		$this->form_validation->set_rules('files', 'Files', 'callback_file_required|callback_file_size['.$_FILES.',5]'); // размер файла указывается в МБ

		$this->form_validation->set_message('required', 'поле обязательно для заполнения');
		$this->form_validation->set_message('min_length', 'поле должно быть не короче 3 символов');
		$this->form_validation->set_message('max_length', 'поле должно быть не больше 500 символов');

		$json_data = array();

		if ($this->form_validation->run() == FALSE) {
			$json_data["errors"] = array();
			$json_data["errors"]['purpose'] = form_error('purpose');
			$json_data["errors"]['receiver'] = form_error('receiver');
			$json_data["errors"]['files'] = form_error('files');
//			$json_data["errors"]['purpose'] = form_error('purpose');
		}
		echo json_encode($json_data);
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
