<?php

class Users extends CI_Controller{

	public function register()
	{
		$data['title'] = 'Registro de inspeccion final';


		$this->form_validation->set_rules('user_name', 'Usuario o firma martech', 'required|callback_check_username_exists');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('users/register', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			//Encrypt password
			$encrypted_pwd = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

			//form email
			$mail = $this->input->post('user_name') . "@martechmedical.com";

			//department id
			$user_department_id = 5;

			//user_martech_number
			$user_martech_number = $this->input->post('user_martech_number');

			//user_level_id
			$user_level_id = 0;
			

			$this->UserModel->register($encrypted_pwd, $mail, $user_department_id, $user_martech_number, $user_level_id);


			//session message
			$this->session->set_flashdata('user_registered', 'You can now login.');

			redirect(base_url() . 'users/register');
		}

	}



	public function login()
	{

		$data['title'] = 'Login Here!';

		$this->form_validation->set_rules('username', 'User Name', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('users/login', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			//Encrypt password
			$username = $this->input->post('username');
			$password = $this->input->post('password');


			$user_id = $this->UserModel->login($username, $password);

			if($user_id)
			{
				//die("Success!");

				//create session
				$user_data = array(
					'user_id'=>$user_id,
					'user_name'=>$username,
					'logged_in'=>true,
				);

				$this->session->set_userdata($user_data);


				//session message
				$this->session->set_flashdata('login_success', 'You are now logged in.');
				redirect(base_url() . 'posts');
			}
			else
			{
				//session message
				$this->session->set_flashdata('login_failed', 'Incorrect username or password.');
				redirect(base_url() . 'users/login');
			}
		}


	}



	public function logout()
	{
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('user_id');
		$this->session->unset_userdata('user_name');

		//session message
		$this->session->set_flashdata('user_logged_out', 'You have logged out.');
		redirect(base_url() . 'users/login');
	}


	public function profile()
	{
		$data['title'] = 'Profile';

		$user_array  = $this->session->userdata('user_id');
		$user_id = $user_array['id'];

		$data['user'] = $this->UserModel->get_user($user_id);

		//validation style
		$this->form_validation->set_error_delimiters(
			'<div class="alert alert_danger mb-5"><strong class="uppercase">Error: </strong>',
			'<button type="button" class="dismiss la la-times" data-dismiss="alert"></button></div>'
		);


		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('username', 'User Name', 'required|callback_check_username_exists');
		$this->form_validation->set_rules('email', 'E-Mail', 'required|valid_email|callback_check_email_exists');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('phone', 'Mobile Number', 'callback_check_phone_exists');
		$this->form_validation->set_rules('password2', 'Password Confirmation', 'matches[password]');


		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('users/profile', $data);
			$this->load->view('templates/footer');
		}
		else
		{
			//file uploads
			$config['upload_path'] = './assets/uploads/users';
			$config['allowed_types'] = 'jpg|jpeg|png|gif';
			$config['max_size'] = '2048';
			$config['max_width'] = '2500';
			$config['max_height'] = '2500';

			$this->load->library('upload', $config);

			if(!$this->upload->do_upload())
			{
				$errors = array('error'=>$this->upload->display_errors());
				//$post_image = 'noimage.jpg';
				$uploaded = 0;
			}
			else
			{
				$data = array('upload_data'=>$this->upload->data());
				$post_image = $_FILES['userfile']['name'];
				$uploaded = 1;
			}

			//encrypting passwords
			$encrypted_pwd = password_hash($this->input->post('password'), PASSWORD_DEFAULT);

			$this->UserModel->edit_profile($post_image, $uploaded, $user_id, $encrypted_pwd);
			redirect(base_url() . 'posts');

		}


	}


	public function get_user($id)
	{
		$user_array  = $this->session->userdata('user_id');
		$id = $user_array['id'];
		$data['user'] = $this->UserModel->get_user($id);
	}


	function check_username_exists($username)
	{
		$this->form_validation->set_message('check_username_exists','That username is taken. Please choose a different one.');

		if($this->UserModel->check_username_exists($username))
		{
			return true;
		}
		else
		{
			return false;
		}
	}



	function check_email_exists($email)
	{
		$this->form_validation->set_message('check_email_exists','That email is taken. Please choose a different one.');

		if($this->UserModel->check_email_exists($email))
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	function check_phone_exists($phone)
	{
		$this->form_validation->set_message('check_phone_exists','That phone number is taken. Please choose a different one.');

		if($this->UserModel->check_phone_exists($phone))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}
