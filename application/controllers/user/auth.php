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
			$this->form_validation->set_rules('password', '������', 'required|xss_clean');

			$remember = (bool) $this->input->post('remember');
			$provider = $this->input->get('provider');
			$data = array();

			require_once APPPATH."config/social.php";
			require_once APPPATH.'third_party/SocialAuther/autoload.php';
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
							$user = $this->users_model->createSocialUser($auther);
							if(empty($user)){
								$data['msg'] = '<div class="alert alert-danger text-center">�������� ���������. ��������� ������ ��� ������ ������. ���������� ����� �����.</div>';
							}
						}
						if($this->ion_auth->login($user['email'],$user['salt'].$user['email'])){
							$this->session->set_flashdata('msg', $this->ion_auth->messages());
							redirect('user/profile');
						} else {
							$data['msg'] = $this->ion_auth->errors();
						}
					}
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