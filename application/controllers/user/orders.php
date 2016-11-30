<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Form.php');

class Orders extends MY_Form
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('orders_model');
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
			$data = array(
				'purpose' => $this->input->post('purpose'),
				'receiver' => $this->input->post('receiver'),
				'client_user_id' => $this->ion_auth->get_user_id(),
				'created_on' => date("Y-m-d H:i:s"),
				'language_in' => $this->input->post("language_in"),
				'language_out' => $this->input->post("language_out"),
			);

			$uploaddir = './images/users/' . $this->ion_auth->get_user_id() . "/";
			if (!file_exists($uploaddir)) {
				mkdir($uploaddir, 0777, true);
			}
			if($_FILES["photo_origin"] && $_FILES["photo_origin"]["name"]){
				$tmp_file = $_FILES["photo_origin"]["tmp_name"];
				$info = pathinfo($_FILES["photo_origin"]["name"]);
				$ext = $info['extension']; // get the extension of the file
				$newname = md5(uniqid(rand(), true));

				$data['photo'] = $uploaddir.$newname.".".$ext;
				move_uploaded_file($tmp_file, $data['photo']);

				$img = $this->input->post('photo');
				if ($img != NULL) {
					$img = str_replace('data:image/png;base64,', '', $img);
					$img = str_replace(' ', '+', $img);
					$dt = base64_decode($img);

					$imgname = $newname . "_thumb.png";
					$uploadfile = $uploaddir . basename($imgname);
					file_put_contents($uploadfile, $dt);
				}
			}
			$order = $this->orders_model->create($data);

			if($order) {
				// записать так же по таблицам файлы для перевода
				$uploaddir = './uploads/users/' . $this->ion_auth->get_user_id() . "/";
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
}
