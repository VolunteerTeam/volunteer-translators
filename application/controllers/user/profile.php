<?php
class Profile extends MY_Controller {
    
    function index()
    {
		$this->load->library('session');
		$cj=$this->session->userdata('email');

		if($cj==false) {
			header('Location: /user/auth');
		}
		
		$this->load->model('users');
		
		$data['data'] = $this->users->profile($cj);

		$this->load->library('session');
		$cj = $this->session->userdata('mail');
		
		$header_param = array();
		
		$header_param['is_auth'] = false;
		if($cj == true) {
			$header_param['is_auth'] = true;
			$header_param['NameAndSename'] = $data['data'][0]['first_name'].' '.$data['data'][0]['last_name'];
		}
		
		$this->load->view('front/common/header',  $header_param);
		$this->load->view('front/reg/profile', $data);
		$this->load->view('front/common/footer');
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