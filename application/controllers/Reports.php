<?php

class Reports extends CI_Controller
{

	public function index()
	{
		$data['asistencias'] = $this->ReportModel->get_asistencia();
		$data['movimientos'] = $this->ReportModel->get_movimientos();
		$data['extras'] = $this->ReportModel->get_extras();

		$this->load->view('templates/header');
		$this->load->view('reports/index', $data); //loading page and data
		$this->load->view('templates/footer');
	}



	//ordenes cerradas
	public function reporte_calidad()
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {
			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");

			//$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-01");
		}

		$this->load->helper('time');

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['reload_route'] = 'reports/calidad';


		$this->load->view('templates/header');
		$this->load->view('reports/report_calidad', $data); //loading page and data
		$this->load->view('templates/footer');
	}

	public function reporte_produccion()
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {

			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");

			//$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-01");
		}

		$this->load->helper('time');


		$data['title'] = ucfirst('home_production');
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['reload_route'] = 'reports/produccion';
		$data['success_message'] = $this->input->get('success_message');


		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/home_production', $data); //loading page and data
		$this->load->view('templates/footer');
	}


	public function rejected_by_product()
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {

			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");

			//$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-01");
		}

		$this->load->helper('time');


		$data['title'] = 'RECHAZADAS POR PRODUCTO';
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['reload_route'] = 'production/rejected_by_product';
		$data['success_message'] = $this->input->get('success_message');


		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/production_rejected_by_product', $data); //loading page and data
		$this->load->view('templates/footer');
	}


	public function rejected_by_document()
	{
		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {

			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");

			//$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-01");
		}

		$this->load->helper('time');


		$data['title'] = 'DISCREPANCIAS';
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;

		if ($this->session->userdata(USER_TYPE) == PRODUCTION_USER)
			$data['reload_route'] = 'production/rejected_by_document';
		else
			$data['reload_route'] = 'quality/rejected_by_document';


		$data['success_message'] = $this->input->get('success_message');


		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/production_rejected_by_document', $data); //loading page and data
		$this->load->view('templates/footer');
	}

	public function all_entries()
	{

		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {

			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");
			$start_date = $current_date->format("Y-m-01");
		}

		$this->load->helper('time');


		$data['title'] = 'TODAS LAS ORDENES';
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['reload_route'] = 'production/all_entries';
		$data['success_message'] = $this->input->get('success_message');


		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/quality_all_entries', $data); //loading page and data
		$this->load->view('templates/footer');
	}



	public function all_rejected()
	{

		$start_date = $this->input->get('start_date');
		$end_date = $this->input->get('end_date');

		if ($start_date == null && $end_date == null) {

			$current_date = new DateTime();
			$end_date = $current_date->format("Y-m-d");
			$start_date = $current_date->format("Y-m-01");
		}

		$this->load->helper('time');


		$data['title'] = 'ORDENES RECHAZADAS';
		$data['user_type'] = $this->session->userdata(USER_TYPE);

		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['reload_route'] = 'production/all_rejected';
		$data['success_message'] = $this->input->get('success_message');


		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/quality_all_rejected', $data); //loading page and data
		$this->load->view('templates/footer');
	}



	public function detail($id = NULL)
	{
		$this->db->select('entry.*, plantas.planta_nombre as plant_name');
		$this->db->from('entry');
		$this->db->where('id', $id);
		$this->db->join('plantas', 'entry.plant = plantas.planta_id');
		$data['entry'] = $this->db->get()->result_array()[0];
		$data['table_for_deletion'] = 'entry';

		$this->load->view('templates/header');
		$this->load->view('entries/detail', $data); //loading page and data
		$this->load->view('templates/footer');
	}

	public function detail_accepted($id = NULL)
	{
		$this->db->select('entry_accepted.*, plantas.planta_nombre as plant_name');
		$this->db->from('entry_accepted');
		$this->db->where('id', $id);
		$this->db->join('plantas', 'entry_accepted.plant = plantas.planta_id');
		$data['entry'] = $this->db->get()->result_array()[0];
		$data['table_for_deletion'] = 'entry_accepted';

		$this->load->view('templates/header');
		$this->load->view('entries/detail', $data); //loading page and data
		$this->load->view('templates/footer');
	}


	public function delete_entry()
	{
		$table = $this->input->post('table_for_deletion');
		$id = $this->input->post('id');

		$this->db->where('id', $id);
		$this->db->delete($table);

		$data['result'] = 'ok';
		echo json_encode($data);
	}
}
