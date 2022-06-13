<?php

class  LocationModel extends  CI_Model
{
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


	public function get_locations()
	{
		$query = $this->db->get('locations');
		return $query->result_array();
	}

	public function get_location($id)
	{
		$this->db->select('*');
		$this->db->from('locations');
		$this->db->where('location_id', $id);

		$result = $this->db->get()->result_array();

		if (count($result) > 0)
			return $result[0]['location_name'];
		else
			return '';
	}
}
