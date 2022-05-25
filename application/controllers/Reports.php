<?php

class Reports extends CI_Controller{

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

		$data['orders'] = $this->EntryModel->get_closed();

		$this->load->view('templates/header');
		$this->load->view('reports/report_calidad', $data); //loading page and data
		$this->load->view('templates/footer');
	}

}
