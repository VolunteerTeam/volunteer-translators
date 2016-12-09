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
			$data = array();
			$data["languages"] = $this->orders_model->getLanguages();
			$this->load_view('front/users/orders',$data);
		} else {
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

				$data['photo'] = '/images/users/' . $user_id . "/".$newname.".".$ext;
				move_uploaded_file($tmp_file, $data['photo']);

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
						);
						$translation = $this->orders_model->addTranslation($data);
						if(!$translation) {
							$json_data["errors"]['create'] = "Один из файлов для перевода не был сохранён";
						}
					}
				}
				$json_data["order"] = $this->orders_model->getOrder($order);
				// отправить на почту письмо, что был создан заказ
//				$this->load->library('email');
//				$subject = 'Создан заказ №'.$order;
//				$message = 'Тестирование сервиса создания заказов на сайте Волонтёры переводов.';
//				$from_email = 'system@perevodov.info';
//
//				$result = $this->email
//					->from($from_email)
//					->reply_to('volontery@perevodov.info')
//					->to('volontery@perevodov.info')
//					->subject($subject)
//					->message($message)
//					->send();
//
//				if (!$result) {
//					$json_data["errors"]['sendEmail'] = "Письмо о заказе не было отправлено";
//				}
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
				'/js/vendor/bootstrap/moment.min.js'
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

				$data['photo'] = '/images/users/' . $client_user_id . "/".$newname.".".$ext;
				move_uploaded_file($tmp_file, $data['photo']);

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
					$data = array(
						'order_id' => $order_id,
						'name_in' => $_FILES['files']['name'][$key],
						'file_in' => $target,
					);
					$translation = $this->orders_model->addTranslation($data);
				}
			}

			if($this->input->post("translations")){
				foreach($this->input->post("translations") as $key => $value){
					$data = array();
					$data = array(
						'name_in' => $value["name_in"].".".$value["name_in_ext"],
						'name_out' => $value["name_out"].".".$value["name_out_ext"],
						'volume_in' => $value["volume_in"],
						'volume_out' => $value["volume_out"],
						'translator_user_id' => $value["translator_user_id"],
					);
					$this->orders_model->updateTranslation($data, $key);
				}
			}
			$this->db->trans_complete();
			redirect('user/orders/edit/'.$order_id);
		}
	}

	function delete($id){
		$json_data = array();

		$this->db->trans_start();
		$query = $this->db->query("SELECT * FROM orders WHERE id='".$id."' FOR UPDATE");
		$order = $query->row();
		if($order->manager_user_id){
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
		$order = $this->orders_model->getOrder($id);

		if($order){
			$data["order"] = $order[0];

			// если пользователь имеет только роль Заказчик, его нужно редиректить на страницу просмотра заказа
			$user_id = $this->ion_auth->get_user_id();
			$user_groups = $this->users_model->getUserGroupsId($user_id);
			if($data["order"]->manager_user_id && !(in_array("1", $user_groups) || in_array("3", $user_groups))){
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
	}

	function addFileOut(){
		$translation_id = intval($this->input->post("translation_id"));
		$json_data = array();

		$user_id = $this->ion_auth->get_user_id();
		$uploaddir = './uploads/users/' . $user_id . "/";
		if (!file_exists($uploaddir)) {
			mkdir($uploaddir, 0777, true);
		}

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

			$json_data["name_out"] = explode(".".$ext,$_FILES['file_out']['name'])[0];
			$json_data["ext"] = $ext;
			$json_data["file_out"] = $target;
			$json_data["translation_id"] = $translation_id;
		}
		echo json_encode($json_data);
	}

	function changeStatus(){
		$translation_id = intval($this->input->post("translation_id"));

		$data = array();
		date_default_timezone_set("Europe/London");
		$data["date_in"] = date("Y-m-d H:i:s");
		$this->orders_model->updateTranslation($data,$translation_id);

		echo json_encode(array("translation_id" => $translation_id));
	}

	function changeOrderStatus(){
		$order_id = intval($this->input->post("order_id"));

		$data = array();
		$json_data = array();
		date_default_timezone_set("Europe/London");
		switch($this->input->post("order_status")){
			case "done":
				$data["date_out"] = date("Y-m-d H:i:s");
				$this->orders_model->update($data,$order_id);
				break;
			case "in_process":
				$order_id = $this->input->post("order_id");
				$this->db->trans_start();
				$query = $this->db->query("SELECT * FROM orders WHERE id='".$order_id."' FOR UPDATE");
				$order = $query->row();
				if(!$order){
					$json_data["error"] = '<div class="alert alert-danger text-center">Заказ не найден в базе данных. Вероятно, его только что удалили.</div>';
				} else if($order->manager_user_id){
					$json_data["manager"] = '<a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>';
					$json_data["error"] = '<div class="alert alert-danger text-center">Извините, этот заказ только что был взят в работу менеджером <a href="/user/profile/'.$order->manager_user_id.'">'.$this->users_model->getUserName($order->manager_user_id).'</a>.</div>';
				} else {
					$user_id = $this->ion_auth->get_user_id();
					$data["date_in"] = date("Y-m-d H:i:s");
					$data["manager_user_id"] = $this->ion_auth->get_user_id();
					$this->orders_model->update($data,$order_id);
					$json_data["manager"] = '<a href="/user/profile/'.$user_id.'">'.$this->users_model->getUserName($user_id).'</a>';
					// ОТПРАВИТЬ ПИСЬМО НА ПОЧТУ О ЗАКРЕПЛЕНИИ ЗАКАЗА ЗА МЕНЕДЖЕРОМ!!!
				}
				$this->db->trans_complete();
				break;
		}

		echo json_encode($json_data);
	}

}
