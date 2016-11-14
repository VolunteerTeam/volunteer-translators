<?php
class Profile extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
//		$this->load->helper(array('form','url'));
//		$this->load->library(array('form_validation', 'email'));
//		$this->load->database();
		$this->load->model('users_model');
	}
    
    function index()
    {
		if($this->ion_auth->logged_in()){
			$data['user'] = $this->ion_auth->user()->result_array()[0];
			$data['user']['city'] = $this->users_model->getCity($data['user']['city']);
			$data['user']['groups'] = $this->ion_auth->get_users_groups()->result_array();

			$data['js'] = array(
				'/js/vendor/jquery-ui.min.js',
				'/js/vendor/bootstrap/moment.min.js',
				'/js/vendor/bootstrap/locale/ru.js',
				'/js/vendor/bootstrap/bootstrap-datetimepicker.min.js',
				'https://maps.google.com/maps/api/js?key=AIzaSyAcZF9a4bTTl7oT77NFJ3dozmSZNuISgA0&language=ru',
				'/js/cropit/dist/jquery.cropit.min.js'
			);
			$data['css'] = array('/css/vendor/bootstrap/bootstrap-datetimepicker.min.css');

			$this->load->view('front/common/header', $data);
			$this->load->view('front/reg/profile');
			$this->load->view('front/common/footer');
		} else {
			redirect("user/auth");
		}

	}
		
	function save()
	{
		$this->load->library('session');

		$name       = $this->input->post('name');
		$surname    = $this->input->post('surname');
		$patronymic = $this->input->post('patronymic');
		$coordinates = $this->input->post('coordinates');
		$sex=$this->input->post('sex');
		//$day=$this->input->post('day');
		$pret=$this->input->post('pret');
		$dol=$this->input->post('dol');
		
		$tel=$this->input->post('tel');
		$skype=$this->input->post('skype');
		$grou=$this->input->post('grou');
		$city=$this->input->post('city');
		
		$calendar = $this->input->post('calendar');
		
		$sc    = $this->input->post('sc');
		$email    = $this->input->post('email');
		
		$this->load->model('users');

		//$imageinfo = @getimagesize($_FILES['uploadfile']['tmp_name']);

		$data=array(
				'email'=>$email,
				'sex'=>$sex,
				'about'=>$pret,
				//'day'=>$day,
				'job_post'=>$dol,
				'phone'=>$tel,
				'skype'=>$skype,
				'group'=>$grou,
				'intro'=>$city,
				'coordinates' => $coordinates,
				'first_name'       => $name,
				'last_name'    => $surname,
				'patro_name' => $patronymic,
				'dob' => $calendar,
		);

		if($this->input->post('newpassword') != NULL) {
			$data['password'] = md5($this->input->post('newpassword'));
		}

		if($this->input->post('uploadfile') != NULL) {
		    $img = $this->input->post('uploadfile');
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			$dt = base64_decode($img);

			$uploaddir = './images/';
			$img_ganerate_name = md5(uniqid(rand(),true));
			$imgname = $img_ganerate_name.".png";
			$uploadfile = $uploaddir . basename($imgname);	
			file_put_contents($uploadfile, $dt);
			$data['avatar'] = "/images/" . $imgname;
		}


		//if($imageinfo) {
		//	$uploaddir = './images/';
		//	$img_ganerate_name = md5(uniqid(rand(),true));
		//	$imgname = $img_ganerate_name.".png";
		//	$uploadfile = $uploaddir . basename($imgname);	
		//	move_uploaded_file($_FILES['uploadfile']['tmp_name'], $uploadfile);
		//	$lb='/images/'.$imgname;	
					

		//	$data['avatar']=$lb;
		//}


		foreach($sc as $k => $v){
				$data[$k] = $v;
			}
		$result = $this->users->save($data);
		
		header('Location: /user/profile');
	}
}     
?>