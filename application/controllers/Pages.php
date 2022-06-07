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
		//if (!$this->is_logged_in()) {
		//	redirect('/');
		//}


		/*if (true) {
			echo "yea";
			return;
		}*/


		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {
			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");

			$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-d");
		}

		$this->load->helper('time');


		$this->db->select('plantas.planta_nombre as plant, COUNT(entry.plant) as pending');
		$this->db->from('entry');
		$this->db->join('plantas', 'entry.plant = plantas.planta_id');
		$this->db->where('progress <>', '' . PROGRESS_CLOSED);
		$this->db->where('status <>', '' . STATUS_REJECTED);

		$this->db->where("created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'");

		$this->db->group_by("entry.plant");

		//$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";

		$data['plants'] = $this->db->get()->result_array();

		$data['title'] = ucfirst('home_production');
		$data['entries'] = $this->EntryModel->get_pending();
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;



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
