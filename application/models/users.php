<?php

class users extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function regmail($data)
	{
		$this->db->insert('users', $data);
	}
	
	function activego($al)
	{
		$this->db->where('secret',$al['secret']);
		$this->db->update('users',$al);
	}
	
	function active($secret)
	{
		$query = $this->db->query('SELECT secret FROM users WHERE secret='.$secret.'');
		return $query->row_array();
	}
	
	function profile($cj)
	{
		$query = $this->db->query('SELECT * FROM users WHERE email="'.$cj.'"');
		return $query->result_array();
	}
	
	function save($data)
	{
		$this->db->where('email', $data['email']);
		return $this->db->update('users',$data);
	}

	function emailexits($email) {
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('email', $email);
		return $this->db->get();
	}

	function setForgot($uid, $email, $md5) {
		$this->db->delete('forgot_password', array('email' => $email));

		$data = ['user_id' => $uid, 'email' => $email, 'md5' => $md5];
		return $this->db->insert('forgot_password', $data);
	}

	function checkForgot($id, $md5) {
		$this->db->select('*');
		$this->db->limit(1);
		$this->db->where('user_id', $id);
		$this->db->where('md5', $md5);
		return $this->db->get('forgot_password');
	}

	function updatePassword($id, $password) {
		$this->db->where('id', $id);
		$this->db->update('users', $data = ['password' => md5($password)]);
	}
}

