<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Auth extends My_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
	}

	function index() {

		if($this->ion_auth->logged_in()){
			redirect("user/profile");
		} else {
			$this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
			$this->form_validation->set_rules('password', 'Пароль', 'required|xss_clean');

			$remember = (bool) $this->input->post('remember');
			$provider = $this->input->get('provider');
			$data = array();

			require_once "/application/config/social.php";
			require_once '/application/third_party/SocialAuther/autoload.php';
			$adapters = array();
			foreach ($adapterConfigs as $adapter => $settings) {
				$class = 'SocialAuther\Adapter\\' . ucfirst($adapter);
				$adapters[$adapter] = new $class($settings);
				$data[$adapter] = $adapters[$adapter]->getAuthUrl();
			}

			if($provider) {
				if(array_key_exists($provider, $adapters)) {
					$auther = new SocialAuther\SocialAuther($adapters[$provider]);
					if ($auther->authenticate()) {
						$user = $this->users_model->socialUserExists($auther->getSocialId(),$provider);
						if(empty($user)){
							if(!$this->users_model->createSocialUser($auther)){
								// show error
							}
						}
//						if($this->ion_auth->loginSocialUser($user->id)){
//							redirect('user/profile');
//						}

//					$result = mysql_query(
//						"SELECT *  FROM `users` WHERE `provider` = '{$auther->getProvider()}' AND `social_id` = '{$auther->getSocialId()}' LIMIT 1"
//					);
//
//					$record = mysql_fetch_array($result);
//					if (!$record) {
//						$values = array(
//							$auther->getProvider(),
//							$auther->getSocialId(),
//							$auther->getName(),
//							$auther->getEmail(),
//							$auther->getSocialPage(),
//							$auther->getSex(),
//							date('Y-m-d', strtotime($auther->getBirthday())),
//							$auther->getAvatar()
//						);
//
//						$query = "INSERT INTO `users` (`provider`, `social_id`, `name`, `email`, `social_page`, `sex`, `birthday`, `avatar`) VALUES ('";
//						$query .= implode("', '", $values) . "')";
//						$result = mysql_query($query);
//					} else {
//						$userFromDb = new stdClass();
//						$userFromDb->provider   = $record['provider'];
//						$userFromDb->socialId   = $record['social_id'];
//						$userFromDb->name       = $record['name'];
//						$userFromDb->email      = $record['email'];
//						$userFromDb->socialPage = $record['social_page'];
//						$userFromDb->sex        = $record['sex'];
//						$userFromDb->birthday   = date('m.d.Y', strtotime($record['birthday']));
//						$userFromDb->avatar     = $record['avatar'];
//					}
//
//					$user = new stdClass();
//					$user->provider   = $auther->getProvider();
//					$user->socialId   = $auther->getSocialId();
//					$user->name       = $auther->getName();
//					$user->email      = $auther->getEmail();
//					$user->socialPage = $auther->getSocialPage();
//					$user->sex        = $auther->getSex();
//					$user->birthday   = $auther->getBirthday();
//					$user->avatar     = $auther->getAvatar();
//
//					if (isset($userFromDb) && $userFromDb != $user) {
//						$idToUpdate = $record['id'];
//						$birthday = date('Y-m-d', strtotime($user->birthday));
//
//						mysql_query(
//							"UPDATE `users` SET " .
//							"`social_id` = '{$user->socialId}', `name` = '{$user->name}', `email` = '{$user->email}', " .
//							"`social_page` = '{$user->socialPage}', `sex` = '{$user->sex}', " .
//							"`birthday` = '{$birthday}', `avatar` = '$user->avatar' " .
//							"WHERE `id`='{$idToUpdate}'"
//						);
//					}
//
//					$_SESSION['user'] = $user;
					}

					echo "not";
				}
			} else if($this->input->post('do_login')
				&& $this->form_validation->run()
				&& $this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
			{
				$this->session->set_flashdata('msg', $this->ion_auth->messages());
				redirect('user/profile');
			}
			if($this->ion_auth->errors()) {
				$data['msg'] = $this->ion_auth->errors();
			}
			$this->load->view('front/common/header');
			$this->load->view('front/reg/auth',$data);
			$this->load->view('front/common/footer');
		}
	}

	function ajax() {
	    $this->load->library('session');
	 
		$login = $_POST['mail'];

		$password = md5($_POST['password']);

		$this->data['result'] = $query_check_user = $this->db->query("SELECT * FROM users WHERE email= '".$login."' and password = '".$password."' and active = '1'");

	 	$userdata = $query_check_user->row_array(); 

		if (@$userdata['email'] == $login and @$userdata['password'] == $password) { 
			$authdata = array('mail' => $login, 'login' => true );
			
			$this->session->set_userdata($authdata); 

			$data['success'] = true;
		} else {   
			$data['success'] = false;
		}

		echo json_encode($data);
	}

	public function log() {
		$this->load->library('session');

		$data =  $this->session->userdata('mail');
		$array_items = array('mail' => $data, 'logged_in' => 'logged_in');

		$this->session->unset_userdata($array_items);

		header('Location: /');
	}

}


?>