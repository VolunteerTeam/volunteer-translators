<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot extends My_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('users');
	}

 	function index() {
    	$this->load->view('front/reg/header');
		$this->load->view('front/reg/forgot');
		$this->load->view('front/reg/footer');
	}

	function send() {
		$error = '';
		$success = 0;

		if($this->input->post('email') != NULL) {

			$emailExits = $this->users->emailexits($this->input->post('email'));

			if ($emailExits->num_rows() == 1) {
				$md5 = md5($this->input->post('email'));

				$arr = $emailExits->result_array();
				$this->users->setForgot($arr[0]['id'], $this->input->post('email'), $md5);

					$html = "<html><body>";
					$html .= "Для изменения пароля Вашей учетной записи, перейдите по ссылке ";
					$html .= '<a href="http://v2.perevodov.info/user/forgot/restore/' . $arr[0]['id'] . '/'. $md5 . '">http://v2.perevodov.info/user/forgot/restore/' . $arr[0]['id'] . '/'. $md5 . '</a>';
					$html .= "</body></html>";

					$this->load->library('email');
					$this->email->from('support@v2.perevodov.info', 'Волонтеры');
					$this->email->to($this->input->post('email')); 
					$this->email->subject('Восстановление пароля');
					$this->email->message($html);	
					$this->email->send();

					$error = "Ссылка на восстановление пароля отправлена Вам на email.";
					$success = 1;
			} else {
				$error = "Пользователя с таким email не существует.";
			}
		} else {
			$error = "Не указан email адрес.";
		}

		$this->load->view('front/reg/header', $data = ['error' => $error, 'success' => $success]);
		$this->load->view('front/reg/forgot');
		$this->load->view('front/reg/footer');
	}

	function restore($id, $md5) {
		$error = '';
		$success = 1;
		$entry = 0;

		if($this->users->checkForgot($id, $md5)->num_rows() == 1) {
			$entry = 1;
		} else {
			$error = "Неверные данные";
		}

		if($this->input->post('newpassword') != NULL) {
			$np = $this->input->post('newpassword');
			$rnp = $this->input->post('renewpassword');

			if($np == $rnp) {
				$this->users->updatePassword($id, $np);
				$error = 'Пароль успешно изменен!';
				$entry = 0;
			} else {
				$error = "Введенные Вами пароли не совпадают!";
			}
		}

		$this->load->view('front/reg/header');
		$this->load->view('front/reg/forgot', $data = ['error' => $error, 'success' => $success, 'entry' => $entry]);
		$this->load->view('front/reg/footer');
	}
}


?>