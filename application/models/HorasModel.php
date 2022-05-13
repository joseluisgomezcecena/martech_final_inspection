<?php

class HorasModel extends CI_Model{

	public function __construct()
	{
		$this->load->database();
	}

	public function get_plants()
	{
		$query = $this->db->get('plantas');
		return $query->result_array();
	}

	public function get_sites($postData)
	{
		$response = array();

		$this->db->select('linea_id, linea_nombre');
		$this->db->where('planta_id', $postData['plant_id']);
		$q = $this->db->get('lineas');
		$response = $q->result_array();

		return $response;
	}


	public function create_asistencia()
	{
		$data = array(
			'asistencia_fecha'=>$this->input->post('asistencia_fecha'),
			'asistencia_turno'=>$this->input->post('asistencia_turno'),
			'asistencia_planta'=>$this->input->post('asistencia_planta'),
			'asistencia_linea'=>$this->input->post('asistencia_linea'),
			'asistencia_operadores'=>$this->input->post('asistencia_operadores')
		);

		return $this->db->insert('asistencia', $data);
	}



	public function create_movimiento()
	{
		$data = array(
			'movimientos_fecha'=>$this->input->post('movimiento_fecha'),
			'movimientos_turno'=>$this->input->post('movimiento_turno'),
			'movimientos_planta_origen'=>$this->input->post('movimiento_planta_origen'),
			'movimientos_planta_destino'=>$this->input->post('movimiento_planta_destino'),
			'movimientos_linea_origen'=>$this->input->post('movimiento_linea_origen'),
			'movimientos_linea_destino'=>$this->input->post('movimiento_linea_destino'),
			'movimientos_operadores'=>$this->input->post('movimiento_operadores'),
			'movimientos_horas'=>$this->input->post('movimiento_horas'),
		);

		return $this->db->insert('movimientos', $data);
	}


	public function create_tiempo_extra()
	{
		$data = array(
			'te_fecha'=>$this->input->post('te_fecha'),
			'te_turno'=>$this->input->post('te_turno'),
			'te_planta'=>$this->input->post('te_planta'),
			'te_linea'=>$this->input->post('te_linea'),
			'te_operadores'=>$this->input->post('te_operadores'),
			'te_horas'=>$this->input->post('te_horas'),
		);

		return $this->db->insert('tiempo_extra', $data);
	}

}
