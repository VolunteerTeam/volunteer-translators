<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Form.php');

class Orders extends MY_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('orders_model');
	}

	function load_view($content,$data=array()){
		$sources = array();
		$sources['js'] = array(
			'/js/vendor/jquery-ui.min.js',
			'/js/vendor/bootstrap/moment.min.js',
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

	function index() {
		if($this->ion_auth->logged_in()){
			$user_id = $this->ion_auth->get_user_id();
			$user_groups = $this->ion_auth->get_users_groups($user_id)->result();
			if(empty($user_groups)) {
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Выберите Вашу роль, чтобы иметь возможность просматривать Заказы.</div>');
				redirect("user/profile");
			}
			$data = array();
			$data["languages"] = $this->orders_model->getLanguages();
			$this->load_view('front/users/orders',$data);
		} else {
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Войдите, чтобы иметь возможность просматривать Заказы.</div>');
			redirect("user/auth");
		}
	}

	function getOrders() {
		//Get records from database
		$this->input->get("jtSorting") ? $jtSorting = $this->input->get("jtSorting") : $jtSorting = "o.created_on DESC";
		$this->input->get("jtStartIndex") ? $jtStartIndex = $this->input->get("jtStartIndex") : $jtStartIndex = 0;
		$this->input->get("jtPageSize") ? $jtPageSize = $this->input->get("jtPageSize") : $jtPageSize = 10;
		$orders = $this->orders_model->getOrdersPortion($jtSorting,$jtStartIndex,$jtPageSize);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $this->orders_model->getOrdersCount();
		$jTableResult['Records'] = $orders;
		echo json_encode($jTableResult);
	}

	function createOrder(){
		$this->form_validation->set_rules('purpose', 'Purpose', 'trim|required|min_length[3]|max_length[500]|xss_clean');
		$this->form_validation->set_rules('receiver', 'Receiver', 'trim|required|min_length[3]|max_length[500]|xss_clean');

		$this->form_validation->set_message('required', 'поле обязательно для заполнения');
		$this->form_validation->set_message('min_length', 'поле должно быть не короче 3 символов');
		$this->form_validation->set_message('max_length', 'поле должно быть не больше 500 символов');

		$json_data = array();
		$json_data["errors"] = array();

		if ($this->form_validation->run() == FALSE) {
			$json_data["errors"]['purpose'] = form_error('purpose');
			$json_data["errors"]['receiver'] = form_error('receiver');
		} else {
			date_default_timezone_set("Europe/London");
			$user_id = $this->ion_auth->get_user_id();
			$data = array(
				'purpose' => $this->input->post('purpose'),
				'receiver' => $this->input->post('receiver'),
				'client_user_id' => $user_id,
				'created_on' => date("Y-m-d H:i:s"),
				'language_in' => $this->input->post("language_in"),
				'language_out' => $this->input->post("language_out"),
			);

			$uploaddir = './images/users/' . $user_id . "/";
			if (!file_exists($uploaddir)) {
				mkdir($uploaddir, 0777, true);
			}
			if($_FILES["photo_origin"] && $_FILES["photo_origin"]["name"]){
				$tmp_file = $_FILES["photo_origin"]["tmp_name"];
				$info = pathinfo($_FILES["photo_origin"]["name"]);
				$ext = $info['extension']; // get the extension of the file
				$newname = md5(uniqid(rand(), true));

				move_uploaded_file($tmp_file, $uploaddir.$newname.".".$ext);
				$data['photo'] = '/images/users/' . $user_id . "/".$newname.".".$ext;

				$img = $this->input->post('photo');
				if ($img != NULL) {
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$dt = base64_decode($img);

					$imgname = $newname . "_thumb.png";
					$uploadfile = $uploaddir . basename($imgname);
					file_put_contents($uploadfile, $dt);
					$data['photo_thumb'] = '/images/users/' . $user_id . "/".$imgname;
				}
			}
			$order = $this->orders_model->create($data);

			if($order) {
				// записать так же по таблицам файлы для перевода
				$uploaddir = './uploads/users/' . $user_id . "/";
				if (!file_exists($uploaddir)) {
					mkdir($uploaddir, 0777, true);
				}
				if($_FILES["files"] && $_FILES["files"]["name"][0]){
					foreach($_FILES["files"]["name"] as $key => $value) {
						$tmp_file = $_FILES["files"]["tmp_name"][$key];
						$info = pathinfo($value);
						$ext = $info['extension']; // get the extension of the file
						$newname = md5(uniqid(rand(), true)).".".$ext;

						$target = $uploaddir.$newname;
						move_uploaded_file($tmp_file, $target);
						$data = array(
							'order_id' => $order,
							'name_in' => $_FILES['files']['name'][$key],
							'file_in' => $target,
							'created_on' => date("Y-m-d H:i:s"),
						);
						$translation = $this->orders_model->addTranslation($data);
						if(!$translation) {
							$json_data["errors"]['create'] = "Один из файлов для перевода не был сохранён";
						}
					}
				}
				$json_data["order"] = $this->orders_model->getOrder($order);

				// отправить на почту письмо, что был создан заказ
				$this->load->library('email');
				$email_data = array(
					"subject" => 'Создан заказ №'.$order,
					"message" => '<p>Тестирование сервиса создания заказов на сайте Волонтёры переводов. Для просмотра заказа пройдите по ссылке <a href="'.$this->config->base_url().'user/orders/'.$order.'">'.$this->config->base_url().'user/orders/'.$order.'</a></p>',
					"from_email" => 'system@perevodov.info',
					"to" => 'volontery@perevodov.info',
					"reply_to" => 'volontery@perevodov.info',
				);
				$this->sendEmail($email_data);

				$user_email = $this->users_model->getUserEmail($user_id);
				if($user_email) {
					$email_data["to"] = $this->users_model->getUserEmail($user_id);
					$this->sendEmail($email_data);
				}
			} else {
				$json_data["errors"]['create'] = "Ошибка сохранения заказа";
			}
		}

		echo json_encode($json_data);
	}

	function show($id){
		$order = $this->orders_model->getOrder($id);
		if($order){
			$data["order"] = $order[0];

			$sources['js'] = array(
				'/js/vendor/bootstrap/moment.min.js',
				'/js/vendor/lightbox.js'
			);
			$sources['css'] = array(
				'/css/vendor/lightbox/lightbox.css'
			);
			$this->load->view('front/common/header',$sources);
			$this->load->view('front/users/order',$data);
			$this->load->view('front/common/footer');
		} else {
			show_404();
		}
	}

	function update(){
		$order_id = $this->input->post("order_id");
		$user_id = $this->ion_auth->get_user_id();

		$this->db->trans_start();
		$query = $this->db->query("SELECT * FROM orders WHERE id='".$order_id."' FOR UPDATE");
		$order = $query->row();

		$user_groups = $this->users_model->getUserGroupsId($user_id);

		if($order->manager_user_id && !(in_array("1", $user_groups) || in_array("3", $user_groups))){
			// если пользователь имеет только роль Заказчик и пока он редактировал, заказ уже был принят в работу Менеджером, мы отталкиваем его попытку отредактировать заказ
			$this->db->trans_complete();
			$manager_email = $this->users_model->getUserEmail($order->manager_user_id);
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Извините, Ваш заказ только что был принят в работу менеджером <a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>. Если Вам нужно что-то изменить в Заказе, свяжитесь с ним <a href="mailto:'.$manager_email.'">'.$manager_email.'</a></div>');
			redirect('user/orders/'.$order_id);
		} else {
			$client_user_id = $this->input->post("client_user_id");
			$data = array(
				'purpose' => $this->input->post('purpose'),
				'receiver' => $this->input->post('receiver'),
				'language_in' => $this->input->post("language_in"),
				'language_out' => $this->input->post("language_out"),
			);
			$uploaddir = './images/users/' . $client_user_id . "/";
			if (!file_exists($uploaddir)) {
				mkdir($uploaddir, 0777, true);
			}
			if($_FILES["photo_origin"] && $_FILES["photo_origin"]["name"]){
				$tmp_file = $_FILES["photo_origin"]["tmp_name"];
				$info = pathinfo($_FILES["photo_origin"]["name"]);
				$ext = $info['extension']; // get the extension of the file
				$newname = md5(uniqid(rand(), true));

				move_uploaded_file($tmp_file, $uploaddir.$newname.".".$ext);
				$data['photo'] = '/images/users/' . $user_id . "/".$newname.".".$ext;

				$img = $this->input->post('photo');
				if ($img != NULL) {
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$dt = base64_decode($img);

					$imgname = $newname . "_thumb.png";
					$uploadfile = $uploaddir . basename($imgname);
					file_put_contents($uploadfile, $dt);
					$data['photo_thumb'] = '/images/users/' . $client_user_id . "/".$imgname;
				}
			}
			$this->orders_model->update($data, $order_id);

			// записать так же по таблицам файлы для перевода
			$uploaddir = './uploads/users/' . $user_id . "/";
			if (!file_exists($uploaddir)) {
				mkdir($uploaddir, 0777, true);
			}
			if($_FILES["files"] && $_FILES["files"]["name"][0]){
				foreach($_FILES["files"]["name"] as $key => $value) {
					$tmp_file = $_FILES["files"]["tmp_name"][$key];
					$info = pathinfo($value);
					$ext = $info['extension']; // get the extension of the file
					$newname = md5(uniqid(rand(), true)).".".$ext;

					$target = $uploaddir.$newname;
					move_uploaded_file($tmp_file, $target);
					date_default_timezone_set("Europe/London");
					$data = array(
						'order_id' => $order_id,
						'name_in' => $_FILES['files']['name'][$key],
						'file_in' => $target,
						'created_on' => date("Y-m-d H:i:s"),
					);
					$translation = $this->orders_model->addTranslation($data);
				}
			}
			$this->db->trans_complete();

			if($this->input->post("translations")){
				$this->load->library('email');
				foreach($this->input->post("translations") as $key => $value){
					$this->db->trans_start();
					$query = $this->db->query("SELECT * FROM translations WHERE id='".$key."' FOR UPDATE");
					$translation = $query->row();
					$data = array();
					if(isset($value["name_in"])) $data["name_in"] = $value["name_in"].".".$value["name_in_ext"];
					if(isset($value["name_out"])) $data["name_out"] = $value["name_out"].".".$value["name_out_ext"];
					if(isset($value["volume_in"])) $data["volume_in"] = $value["volume_in"];
					if(isset($value["volume_out"])) $data["volume_out"] = $value["volume_out"];

					// Если файл уже взят в работу переводчиком, переназначить его на другого переводчика нельзя
					if($translation->date_in && $translation->translator_user_id != $value["translator_user_id"]){
						$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Извините, файл "'.$value["name_in"].".".$value["name_in_ext"].'" уже взят в работу переводчиком.</div>');
					} else {
						$data['translator_user_id'] = $value["translator_user_id"];
					}
					$this->orders_model->updateTranslation($data, $key);
					$this->db->trans_complete();

					if($data['translator_user_id']) {
						// Отправляем уведомление на почту Переводчику
						$translator_email = $this->users_model->getUserEmail($data['translator_user_id']);
						if($translator_email) {
							$email_data = array(
								"subject" => 'Заказ №'.$translation->order_id.'. Вам назначен файл для перевода',
								"from_email" => 'system@perevodov.info',
								"to" => $translator_email,
								"reply_to" => 'volontery@perevodov.info',
								"message" => '<p>Тестирование сервиса заказов на сайте Волонтёры переводов. Вам был назначен файл для перевода (заказ №'.$translation->order_id.')! Чтобы взять файл в работу, пройдите по ссылке <a href="'.$this->config->base_url().'user/translations">'.$this->config->base_url().'user/translations</a></p>',
							);
							$this->sendEmail($email_data);
						}
					}
				}
			}

			redirect('user/orders/edit/'.$order_id);
		}
	}

	function delete($id){
		$json_data = array();

		$this->db->trans_start();
		$query = $this->db->query("SELECT * FROM orders WHERE id='".$id."' FOR UPDATE");
		$order = $query->row();
		if($order->manager_user_id && !$this->ion_auth->is_admin()){
			$this->db->trans_complete();
			$manager_email = $this->users_model->getUserEmail($order->manager_user_id);
			$json_data["error"] = '<div class="alert alert-danger text-center">Извините, у заказа №'.$id.' уже есть менеджер <a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>, поэтому Вы не можете его удалить. Свяжитесь с менеджером <a href="mailto:'.$manager_email.'">'.$manager_email.'</a></div>';
		} else {
			$this->orders_model->delete($id);
			$this->db->trans_complete();
			// ОТПРАВИТЬ ПИСЬМО НА ПОЧТУ ОБ УДАЛЕНИИ ЗАКАЗА ЗА МЕНЕДЖЕРОМ!!!
		}
		echo json_encode($json_data);
	}

	function deleteTranslation($id){
		$json_data = array();

		$this->db->trans_start();
		$query = $this->db->query("SELECT * FROM translations WHERE id='".$id."' FOR UPDATE");
		$translation = $query->row();
		if($translation){
			$this->orders_model->deleteTranslation($id);
			$this->db->trans_complete();
		}
		$json_data["translation_id"] = $id;
		echo json_encode($json_data);
	}

	function edit($id){
		if($this->ion_auth->logged_in()){
			$order = $this->orders_model->getOrder($id);

			if($order){
				$data["order"] = $order[0];

				// если пользователь имеет только роль Заказчик или роль Менеджер (но это не его заказ), его нужно редиректить на страницу просмотра заказа
				$user_id = $this->ion_auth->get_user_id();
				$user_groups = $this->users_model->getUserGroupsId($user_id);
				if($data["order"]->manager_user_id && (!(in_array("1", $user_groups) || in_array("3", $user_groups)) || (in_array("3", $user_groups) && $user_id != $data["order"]->manager_user_id))){
					$manager_email = $this->users_model->getUserEmail($data["order"]->manager_user_id);
					$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Извините, Ваш заказ был принят в работу менеджером <a href="/user/profile/'.$data["order"]->manager_user_id.'">'.$this->users_model->getUserName($data["order"]->manager_user_id).'</a>. Если Вам нужно что-то изменить в Заказе, свяжитесь с ним <a href="mailto:'.$manager_email.'">'.$manager_email.'</a></div>');
					redirect('user/orders/'.$id);
				}

				$data["languages"] = $this->orders_model->getLanguages();
				$data["managers"] = $this->users_model->getUsersByGroup(3);
				$data["translators"] = $this->users_model->getUsersByGroup(4);

				$sources['js'] = array(
					'/js/vendor/bootstrap/moment.min.js',
					'/js/cropit/dist/jquery.cropit.min.js',
				);
				$sources['css'] = array(
					'/css/vendor/cropit.css'
				);
				$this->load->view('front/common/header',$sources);
				$this->load->view('front/users/order_edit',$data);
				$this->load->view('front/common/footer');
			} else {
				show_404();
			}
		} else {
			$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Войдите, чтобы иметь возможность редактировать Заказы.</div>');
			redirect("user/auth");
		}
	}

	function addFileOut(){
		$translation_id = intval($this->input->post("translation_id"));
		$json_data = array();

		$user_id = $this->ion_auth->get_user_id();
		$uploaddir = './uploads/users/' . $user_id . "/";
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir, 0777, true);
		}

		$translation = $this->orders_model->getTranslation($translation_id);
		$translation->date_out ? $status = "изменён" : $status = "выполнен";

		if($translation_id && $_FILES["file_out"] && $_FILES["file_out"]["name"]){
			$tmp_file = $_FILES["file_out"]["tmp_name"];
			$info = pathinfo($_FILES["file_out"]["name"]);
			$ext = $info['extension']; // get the extension of the file
			$newname = md5(uniqid(rand(), true)).".".$ext;

			$target = $uploaddir.$newname;
			move_uploaded_file($tmp_file, $target);
			$data = array(
				'name_out' => $_FILES['file_out']['name'],
				'file_out' => $target,
				'date_out' => date("Y-m-d H:i:s")
			);
			$this->orders_model->updateTranslation($data,$translation_id);

			// Отправляем уведомление на почту Менеджеру
			$manager_id = $this->orders_model->getManager($translation_id);
			$manager_email = $this->users_model->getUserEmail($manager_id);

			if($manager_email) {
				$this->load->library('email');
				$email_data = array(
					"subject" => 'Заказ №'.$translation->order_id.'. Добавлен файл перевода',
					"from_email" => 'system@perevodov.info',
					"to" => $manager_email,
					"reply_to" => 'volontery@perevodov.info',
					"message" => '<p>Тестирование сервиса заказов на сайте Волонтёры переводов. По заказу №'.$translation->order_id.' к файлу оригинала "'.$translation->name_in.'" '.$status.' файл перевода "'.$data['name_out'].'"! Для просмотра заказа пройдите по ссылке <a href="'.$this->config->base_url().'user/orders/'.$translation->order_id.'">'.$this->config->base_url().'user/orders/'.$translation->order_id.'</a></p>',
				);
				$this->sendEmail($email_data);
			}

			$json_data["name_out"] = explode(".".$ext,$_FILES['file_out']['name'])[0];
			$json_data["ext"] = $ext;
			$json_data["file_out"] = $target;
			$json_data["translation_id"] = $translation_id;
		}
		echo json_encode($json_data);
	}

	function changeStatus(){
		$translation_id = $this->input->post("translation_id");
		$json_data = array();
		$json_data["translation_id"] = $translation_id;

		$this->db->trans_start();
		$query = $this->db->query("SELECT * FROM translations WHERE id='".$translation_id."' FOR UPDATE");
		$translation = $query->row();
		if($translation->translator_user_id != $this->ion_auth->get_user_id()){
			$json_data["error"] = '<div class="alert alert-danger text-center">Извините, этот файл назначен другому переводчику.</div>';
		} else {
			$data = array();
			date_default_timezone_set("Europe/London");
			$data["date_in"] = date("Y-m-d H:i:s");
			$this->orders_model->updateTranslation($data,$translation_id);
		}
		$this->db->trans_complete();
		echo json_encode($json_data);
	}

	function changeOrderStatus(){
		$order_id = intval($this->input->post("order_id"));

		$this->load->library('email');
		$email_data = array(
			"subject" => 'Статус заказа №'.$order_id.' изменился',
			"from_email" => 'system@perevodov.info',
			"to" => 'volontery@perevodov.info',
			"reply_to" => 'volontery@perevodov.info',
		);

		$data = array();
		$json_data = array();
		date_default_timezone_set("Europe/London");
		$client_id = $this->orders_model->getClientId($order_id);
		$client_email = $this->users_model->getUserEmail($client_id);

		switch($this->input->post("order_status")){
			case "done":
				$data["date_out"] = date("Y-m-d H:i:s");
				$this->orders_model->update($data,$order_id);
				$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Заказ успешно закрыт.</div>');

				// Отправляем уведомление на почту
				$email_data["message"] = '<p>Тестирование сервиса заказов на сайте Волонтёры переводов. Заказ выполнен! Для просмотра заказа пройдите по ссылке <a href="'.$this->config->base_url().'user/orders/'.$order_id.'">'.$this->config->base_url().'user/orders/'.$order_id.'</a></p>';
				$this->sendEmail($email_data);
				if($client_email) {
					$email_data["to"] = $this->users_model->getUserEmail($client_id);
					$this->sendEmail($email_data);
				}

				redirect('user/orders/'.$order_id);
				break;
			case "in_process":
				$order_id = $this->input->post("order_id");
				$this->db->trans_start();
				$query = $this->db->query("SELECT * FROM orders WHERE id='".$order_id."' FOR UPDATE");
				$order = $query->row();
				if(!$order){
					$this->db->trans_complete();
					$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Заказ не найден в базе данных. Вероятно, его только что удалили.</div>');
					redirect('user/orders');
				} else if($order->manager_user_id){
					$this->db->trans_complete();
					$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Извините, этот заказ только что был взят в работу менеджером <a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>.</div>');
					redirect('user/orders/'.$order_id);} else {
					$user_id = $this->ion_auth->get_user_id();
					$data["date_in"] = date("Y-m-d H:i:s");
					$data["manager_user_id"] = $this->ion_auth->get_user_id();
					$this->orders_model->update($data,$order_id);
					$json_data["manager"] = '<a href="/user/profile/'.$user_id.'">'.$this->users_model->getUserName($user_id).'</a>';
					$this->db->trans_complete();
					$this->session->set_flashdata('msg','<div class="alert alert-success text-center">Заказ был принят Вами в работу. Теперь Вы можете редактировать его.</div>');

					// Отправляем уведомление на почту
					$email_data["message"] = '<p>Тестирование сервиса заказов на сайте Волонтёры переводов. Заказ принят в работу! Для просмотра заказа пройдите по ссылке <a href="'.$this->config->base_url().'user/orders/'.$order_id.'">'.$this->config->base_url().'user/orders/'.$order_id.'</a></p>';
					$this->sendEmail($email_data);
					if($client_email) {
						$email_data["to"] = $this->users_model->getUserEmail($client_id);
						$this->sendEmail($email_data);
					}

					redirect('user/orders/edit/'.$order_id);
				}
				break;
		}
	}

	function sendEmail($data){
		$result = $this->email
				->from($data["from_email"])
				->reply_to($data["reply_to"])
				->to($data["to"])
				->subject($data["subject"])
				->message($data["message"])
				->send();
	}

}
