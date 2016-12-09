<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Form.php');

class Translations extends MY_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('translations_model');
	}

	function load_view($content,$data=array()){
		$sources = array();
		$sources['js'] = array(
			'/js/vendor/jquery-ui.min.js',
			'/js/vendor/bootstrap/moment.min.js',
			'/js/vendor/jtable.2.4.0/jquery.jtable.js',
			'/js/vendor/jtable.2.4.0/localization/jquery.jtable.ru.js',
		);
		$sources['css'] = array(
			'/js/vendor/jtable.2.4.0/themes/metro/purple/jtable.min.css',
		);

		$this->load->view('front/common/header',$sources);
		$this->load->view($content,$data);
		$this->load->view('front/common/footer');
	}

	function index() {
		if($this->ion_auth->logged_in()){
			$user_id = $this->ion_auth->get_user_id();
			$user_groups = $this->users_model->getUserGroupsId($user_id);
			// Только админы и переводчики могут смотреть страницу Переводов
			if(in_array("1", $user_groups) || in_array("4", $user_groups)){
				$this->load_view('front/users/translations');
			} else {
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">У вас нет прав просматривать страницу Переводов.</div>');
				redirect('user/orders');
			}
		} else {
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Войдите, чтобы иметь возможность редактировать Переводы.</div>');
			redirect("user/auth");
		}
	}

	function getList() {

		//Get records from database
		$this->input->get("jtSorting") ? $jtSorting = $this->input->get("jtSorting") : $jtSorting = "t.created_on DESC";
		$this->input->get("jtStartIndex") ? $jtStartIndex = $this->input->get("jtStartIndex") : $jtStartIndex = 0;
		$this->input->get("jtPageSize") ? $jtPageSize = $this->input->get("jtPageSize") : $jtPageSize = 10;
		$orders = $this->translations_model->getTranslationsPortion($jtSorting,$jtStartIndex,$jtPageSize,$this->input->get("id"));

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $this->translations_model->getTranslationsCount();
		$jTableResult['Records'] = $orders;
		echo json_encode($jTableResult);
	}

}
