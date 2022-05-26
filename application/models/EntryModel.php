<?php

class EntryModel extends CI_Model{

	public function __construct()
	{
		$this->load->database();
	}

	public function get_plants()
	{
		$query = $this->db->get('plantas');
		return $query->result_array();
	}


	public function get_parts()
	{
		$query = $this->db->get('activeparts');
		return $query->result_array();
	}





	public function get_pending()
	{
		$query = $this->db->get_where('entry', "progress < 3 ");
		return $query->result_array();
	}



	public function get_closed()
	{
		$query = $this->db->get_where('entry', "progress = 3 ");
		return $query->result_array();
	}



	public function get_single_entry($id)
	{
		$query = $this->db->get_where('entry', array("id"=>$id));
		return $query->row_array();
	}



	public function create_entry()
	{
		$parcial = $this->input->post('parcial');
		$reinspeccion = $this->input->post('reinspeccion');
		$ficticio = $this->input->post('ficticio');
		$discrepancia = $this->input->post('discrepancia');

		$parcial = (isset($parcial) ? 1 : 0);
		$reinspeccion = (isset($reinspeccion) ? 1 : 0);
		$ficticio = (isset($ficticio) ? 1 : 0);
		$discrepancia = (isset($discrepancia) ? 1 : 0);

		$data = array(
			'part_no'=>$this->input->post('part_no'),
			'lot_no'=>$this->input->post('lot_no'),
			'qty'=>$this->input->post('qty'),
			'plant'=>$this->input->post('plant'),
			'parcial'=>$parcial,
			'reinspeccion'=>$reinspeccion,
			'ficticio'=>$ficticio,
			'discrepancia'=>$discrepancia,
		);

		return $this->db->insert('entry', $data);
	}




	public function assign_entry()
	{

		$id = $this->input->post('id');
		$progress = 1;
		$assign_date = date("Y-m-d H:i:s");

		$data = array(
			'progress'=>$progress,
			'asignada'=>$this->input->post('asignada'),
			'asignada_date'=>$assign_date,
		);

		return $this->db->update('entry', $data, array("id"=>$id));
	}




	public function release_entry()
	{

		$id = $this->input->post('id');
		$progress = 2;
		$release_date = date("Y-m-d H:i:s");

		$data = array(
			'progress'=>$progress,
			'status'=>$this->input->post('status'),
			'final_qty'=>$this->input->post('final_qty'),
			'location'=>$this->input->post('location'),
			'wo_escaneadas'=>$this->input->post('wo_escaneadas'),
			'has_fecha_exp'=>$this->input->post('has_fecha_exp'),
			'fecha_exp'=>$this->input->post('fecha_exp'),
			'rev_dibujo'=>$this->input->post('rev_dibujo'),
			'empaque'=>$this->input->post('empaque'),
			'documentos_rev'=>$this->input->post('documentos_rev'),
			'razon_rechazo'=>$this->input->post('razon_rechazo'),
			'liberada_date'=>$release_date,
		);

		return $this->db->update('entry', $data, array("id"=>$id));
	}





	public function close_entry()
	{

		$id = $this->input->post('id');
		$progress = 3;
		$cerrada_date = date("Y-m-d H:i:s");

		$data = array(
			'progress'=>$progress,
			'status'=>$this->input->post('status'),
			'rev_mapics'=>$this->input->post('rev_mapics'),
			'cerrada_por'=>$this->input->post('cerrada_por'),
			'cerrada_date'=>$cerrada_date,
		);

		return $this->db->update('entry', $data, array("id"=>$id));
	}


}
