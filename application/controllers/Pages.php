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
			redirect('reports/produccion');
		} else if ((isset($logged_in) && $logged_in == TRUE) && (isset($user_type) && $user_type == PRODUCTION_USER)) {
			redirect('reports/produccion');
		} else {
			$this->load->view('pages/intro');
		}
	}

	public function home()
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {
			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");

			//$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-d");
		}

		$this->load->helper('time');


		$this->db->select('plantas.planta_nombre as plant, planta_id, 
		COUNT(entry.plant) as count,
		SUM(if(entry.progress <> 3, 1, 0)) AS opened,
		SUM(if(entry.progress = 0, 1, 0)) AS not_assigned,
		SUM(if(entry.progress = 1, 1, 0)) AS assigned,
		SUM(if(entry.progress = 2, 1, 0)) AS released,
		SUM(if(entry.progress = 3, 1, 0)) AS closed,
		SUM( if( (entry.status = 1 AND entry.progress = 2) OR (entry.status = 1 AND entry.progress = 3), 1, 0)) AS rejected,
		SUM( if( (entry.status = 2 AND entry.progress = 2) OR (entry.status = 2 AND entry.progress = 3), 1, 0)) AS accepted,
		SUM( if((entry.status = 3 AND entry.progress = 2) OR (entry.status = 3 AND entry.progress = 3) , 1, 0)) AS waiting');
		$this->db->from('entry');
		$this->db->join('plantas', 'entry.plant = plantas.planta_id');
		$this->db->where("created_at BETWEEN '" . $start_date . " 00:00:00' AND '" . $end_date . " 23:59:59'");
		$this->db->group_by("planta_nombre");

		//$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";

		$data['plants'] = $this->db->get()->result_array();

		$data['title'] = ucfirst('home_production');
		//$data['entries'] = $this->EntryModel->get_pending();
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['sql'] = $this->db->last_query();


		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/home', $data); //loading page and data
		$this->load->view('templates/footer');
	}


	public function home_production()
	{
		$this->session->set_userdata(IS_LOGGED_IN, TRUE);
		$this->session->set_userdata(USER_TYPE, PRODUCTION_USER);
		redirect('/');
	}
}
