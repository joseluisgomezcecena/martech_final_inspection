<?php

include_once('BaseController.php');

class Pages extends BaseController
{

	public function default()
	{
		$this->load->helper('url');

		$logged_in = $this->session->userdata(IS_LOGGED_IN);
		$user_type = $this->session->userdata(USER_TYPE);

		if ((isset($logged_in) && $logged_in == TRUE) && (isset($user_type) && $user_type == QUALITY_USER)) {
			redirect('pages/home');
		} else if ((isset($logged_in) && $logged_in == TRUE) && (isset($user_type) && $user_type == PRODUCTION_USER)) {
			redirect('pages/home');
		} else {
			$this->load->view('pages/intro');
		}
	}

	public function home()
	{
		if (!$this->is_logged_in()) {
			redirect('/');
		}

		$data['title'] = ucfirst('home_production');
		$data['entries'] = $this->EntryModel->get_pending();
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/home_production', $data); //loading page and data
		$this->load->view('templates/footer');
	}


	public function home_production()
	{
		$this->session->set_userdata(IS_LOGGED_IN, TRUE);
		$this->session->set_userdata(USER_TYPE, PRODUCTION_USER);
		redirect('/');
	}
}
