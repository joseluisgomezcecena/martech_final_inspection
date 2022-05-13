<?php

class Forms extends CI_Controller{

	public function create()
	{
		$data['title'] = 'Registro de eficiencia';
		$data['plantas'] = $this->HorasModel->get_plants();

		$this->form_validation->set_rules('asistencia_fecha', 'Fecha de asistencia', 'required');
		$this->form_validation->set_rules('asistencia_turno', 'Turno de asistencia', 'required');
		$this->form_validation->set_rules('asistencia_planta', 'Planta', 'required');
		$this->form_validation->set_rules('asistencia_linea', 'Linea', 'required');
		$this->form_validation->set_rules('asistencia_operadores', 'Operadores', 'required');

		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('forms/create', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->HorasModel->create_asistencia();

			//session message
			$this->session->set_flashdata('asistencia', 'Se ha guardado la asistencia.');

			redirect(base_url() . 'forms/create');
		}

	}



	public function create_movimiento()
	{
		$data['title'] = 'Registro de eficiencia';
		$data['plantas'] = $this->HorasModel->get_plants();

		$this->form_validation->set_rules('movimiento_fecha', 'Fecha de asistencia', 'required');
		$this->form_validation->set_rules('movimiento_turno', 'Turno de asistencia', 'required');
		$this->form_validation->set_rules('movimiento_planta_origen', 'Planta', 'required');
		$this->form_validation->set_rules('movimiento_planta_destino', 'Planta', 'required');
		$this->form_validation->set_rules('movimiento_linea_origen', 'Linea', 'required');
		$this->form_validation->set_rules('movimiento_linea_destino', 'Linea', 'required');
		$this->form_validation->set_rules('movimiento_operadores', 'Operadores', 'required');
		$this->form_validation->set_rules('movimiento_horas', 'Horas del movimiento', 'required');


		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('forms/create', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->HorasModel->create_movimiento();

			//session message
			$this->session->set_flashdata('movimiento', 'Se ha guardado el movimiento.');

			redirect(base_url() . 'forms/create');
		}

	}




	public function create_tiempo_extra()
	{
		$data['title'] = 'Registro de eficiencia';
		$data['plantas'] = $this->HorasModel->get_plants();

		$this->form_validation->set_rules('te_fecha', 'Fecha de tiempo extra', 'required');
		$this->form_validation->set_rules('te_turno', 'Turno de tiempo extra', 'required');
		$this->form_validation->set_rules('te_planta', 'Planta', 'required');
		$this->form_validation->set_rules('te_linea', 'Linea', 'required');
		$this->form_validation->set_rules('te_operadores', 'Operadores', 'required');
		$this->form_validation->set_rules('te_horas', 'Horas de tiempo extra', 'required');


		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('forms/create', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->HorasModel->create_tiempo_extra();

			//session message
			$this->session->set_flashdata('tiempo', 'Se ha guardado el tiempo extra.');

			redirect(base_url() . 'forms/create');
		}

	}


	public function get_sites()
	{
		// POST data
		$postData = $this->input->post();

		// load model
		$this->load->model('HorasModel');

		// get data
		$data = $this->HorasModel->get_sites($postData);
		echo json_encode($data);
	}


}
