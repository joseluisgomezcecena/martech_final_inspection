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

			$current_date = $current_date->modify('-1 months');
			$start_date = $current_date->format("Y-m-d");
		}

		$this->load->helper('time');

		$closed_orders = $this->EntryModel->get_closed();

		for ($i = 0; $i < count($closed_orders); $i++) {

			$closed_orders[$i]['assigned_elapsed_time'] =  readable_elapsed_time($closed_orders[$i]['asignada_date'], $closed_orders[$i]['created_at']);
			$closed_orders[$i]['released_elapsed_time'] =  readable_elapsed_time($closed_orders[$i]['liberada_date'], $closed_orders[$i]['created_at']);
			$closed_orders[$i]['closed_elapsed_time'] =  readable_elapsed_time($closed_orders[$i]['cerrada_date'], $closed_orders[$i]['created_at']);
		}

		$data['orders'] = $closed_orders;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;


		$this->load->view('templates/header');
		$this->load->view('reports/report_calidad', $data); //loading page and data
		$this->load->view('templates/footer');
	}

	public function detail($id = NULL)
	{
		$this->db->select('entry.*, plantas.planta_nombre as plant_name');
		$this->db->from('entry');
		$this->db->where('id', $id);
		$this->db->join('plantas', 'entry.plant = plantas.planta_id');
		$data['entry'] = $this->db->get()->result_array()[0];

		$this->load->view('templates/header');
		$this->load->view('entries/detail', $data); //loading page and data
		$this->load->view('templates/footer');
	}
}
