<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot extends My_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('users');
	}

 	function index() {
    	$this->load->view('front/common/header');
		$this->load->view('front/reg/forgot');
		$this->load->view('front/common/footer');
	}

	function send() {
		$error = '';
		$msg = '';
		$success = 0;

		if($this->input->post('email') != NULL) {

			$emailExits = $this->users->emailexits($this->input->post('email'));

			if ($emailExits->num_rows() == 1) {
				$md5 = md5($this->input->post('email'));

				$arr = $emailExits->result_array();
				$this->users->setForgot($arr[0]['id'], $this->input->post('email'), $md5);

					$html = "<html><body>";
					$html .= "Для изменения пароля Вашей учетной записи, перейдите по ссылке ";
					$html .= '<a href="'.$this->config->base_url().'user/forgot/restore/' . $arr[0]['id'] . '/'. $md5 . '">'.$this->config->base_url().'user/forgot/restore/' . $arr[0]['id'] . '/'. $md5 . '</a>';
					$html .= "</body></html>";

					$this->load->library('email');
					$this->email->from('support@v2.perevodov.info', 'Волонтеры переводов');
					$this->email->to($this->input->post('email')); 
					$this->email->subject('Восстановление пароля');
					$this->email->message($html);	
					$this->email->send();

					$msg = "Ссылка на восстановление пароля отправлена Вам на email.";
					$success = 1;
			} else {
				$error = "Пользователя с таким email не существует.";
			}
		} else {
			$error = "Не указан email адрес.";
		}

		$this->load->view('front/common/header', $data = ['error' => $error, 'msg' => $msg, 'success' => $success]);
		$this->load->view('front/reg/forgot');
		$this->load->view('front/common/footer');
	}

	function restore($id, $md5) {
		$msg = '';
		$error = '';
		$success = 1;
		$entry = 0;

		if($this->users->checkForgot($id, $md5)->num_rows() == 1) {
			$entry = 1;
		} else {
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Неверные данные. Попробуйте восстановить пароль ещё раз.</div>');
			redirect("user/forgot");
		}

		if($this->input->post('newpassword') != NULL) {
			$np = $this->input->post('newpassword');
			$rnp = $this->input->post('renewpassword');

			if($np == $rnp) {
				$this->users->updatePassword($id, $np);
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Пароль успешно изменен! Можете войти в свой личный кабинет.</div>');
				$entry = 0;
				redirect("user/auth");
			} else {
				$error = "Введенные Вами пароли не совпадают!";
			}
		}

		$this->load->view('front/common/header');
		$this->load->view('front/reg/forgot', $data = ['error' => $error, 'msg' => $msg, 'success' => $success, 'entry' => $entry]);
		$this->load->view('front/common/footer');
	}
}


?>