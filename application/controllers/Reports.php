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



}
