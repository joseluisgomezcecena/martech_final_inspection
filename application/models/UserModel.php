<?php

class UserModel extends CI_Model{

	public function __construct()
	{
		$this->load->database();
	}

	public function register($encrypted_pwd, $mail, $user_department_id, $user_martech_number, $user_level_id)
	{
		$data = array(
			'user_name'=>$this->input->post('user_name'),
			'user_email'=>$mail,
			'user_lastname'=>" ",
			'user_department_id'=>$user_department_id,
			'user_password'=>$encrypted_pwd,
			'user_martech_number'=>$user_martech_number,
			//'user_level_id'=>0
		);

		return $this->db->insert('users', $data);
	}


	public function edit_profile($post_image, $uploaded, $user_id, $encrypted_pwd)
	{
		$id = $user_id;

		if($uploaded== 0)
		{
			$data = array(
				'name'=>$this->input->post('name'),
				'email'=>$this->input->post('email'),
				'username'=>$this->input->post('username'),
				'phone'=>$this->input->post('phone'),
				'password'=>$encrypted_pwd,
				'bio'=>$this->input->post('bio'),
			);
		}
		else
		{
			$data = array(
				'name'=>$this->input->post('name'),
				'email'=>$this->input->post('email'),
				'username'=>$this->input->post('username'),
				'phone'=>$this->input->post('phone'),
				'password'=>$encrypted_pwd,
				'bio'=>$this->input->post('bio'),
				'profile_image'=>$post_image,
			);
		}

		return $this->db->update('users', $data, array('id'=>$id));


	}


	public function login($username, $password)
	{
		$this->db->where('username', $username);
		$query = $this->db->get('users');
		$result = $query->row_array();

		if (!empty($result) && password_verify($password, $result['password'])) {
			return $result;
		} else {
			return false;
		}
	}


	public function check_username_exists($username)
	{
		$query = $this->db->get_where('users', array('user_name' => $username));

		if(empty($query->row_array()))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	public function check_email_exists($email)
	{
		$query = $this->db->get_where('users', array('user_email' => $email));

		if(empty($query->row_array()))
		{
			return true;
		}
		else
		{
			return false;
		}
	}



	public function get_user($id)
	{
		$query = $this->db->get_where('users', array('user_id'=>$id));
		return $query->row_array();
	}


	public function get_users()
	{
		$query = $this->db->get('users');
		return $query->row_array();
	}


	public function get_users_quality()
	{
		//otra db
		$authdb = $this->load->database('authdb', TRUE);

		$query = $authdb->get_where('users', array('user_department_id'=>3));
		return $query->result_array();
	}

}
