<?php

class EntryModel extends CI_Model
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
		$query = $this->db->get_where('entry', array("id" => $id));
		return $query->row_array();
	}



	public function create_entry()
	{
		$parcial = $this->input->post('parcial');
		$reinspeccion = $this->input->post('reinspeccion');
		$ficticio = $this->input->post('ficticio');
		$rm = $this->input->post('rm');
		$from = $this->input->post('from');
		$has_urgency = $this->input->post('has_urgency');

		$parcial = (isset($parcial) ? 1 : 0);
		$reinspeccion = (isset($reinspeccion) ? 1 : 0);
		$ficticio = (isset($ficticio) ? 1 : 0);
		$rm = (isset($rm) ? 1 : 0);
		$substitutes_to = (isset($from) ? $from : NULL);
		$has_urgency = (isset($has_urgency) ? 1 : 0);



		$data = array(
			'part_no' =>  strtoupper($this->input->post('part_no')),
			'lot_no' => strtoupper($this->input->post('lot_no')),
			'qty' => $this->input->post('qty'),
			'plant' => $this->input->post('plant'),
			'parcial' => $parcial,
			'reinspeccion' => $reinspeccion,
			'ficticio' => $ficticio,
			'rm' => $rm,
			'substitutes_to' => $substitutes_to,
			'has_urgency' => $has_urgency,
			'assigned_by' => strtoupper($this->input->post('assigned_by')),
		);

		return $this->db->insert('entry', $data);
	}


	public function solve_entry()
	{
		$this->load->helper('time');

		$progress = $this->input->post('progress');

		/*$parcial = $this->input->post('parcial');
		$reinspeccion = $this->input->post('reinspeccion');
		$ficticio = $this->input->post('ficticio');
		$discrepancia = $this->input->post('discrepancia');*/
		$from = $this->input->post('from');

		/*$parcial = (isset($parcial) ? 1 : 0);
		$reinspeccion = (isset($reinspeccion) ? 1 : 0);
		$ficticio = (isset($ficticio) ? 1 : 0);
		$discrepancia = (isset($discrepancia) ? 1 : 0);
*/
		$data = array(
			/*
			'parcial' => $parcial,
			'reinspeccion' => $reinspeccion,
			'ficticio' => $ficticio,
			'discrepancia' => $discrepancia,
			*/);

		$current_date_time = new DateTime();

		if ($progress == PROGRESS_RELEASED) {
			$status_to_set = STATUS_VERIFY;
			$data['status'] = $status_to_set;

			/*codigo para salvar el tiempo*/
			$this->db->select('status, waiting_start_time, waiting_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", waiting_start_time) as waiting_elapsed_time , rejected_doc_start_time, rejected_doc_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_doc_start_time) as rejected_doc_elapsed_time, rejected_prod_start_time, rejected_prod_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_prod_start_time) as rejected_prod_elapsed_time');
			$this->db->from('entry');
			$this->db->where('id', $from);
			$entry_row = $this->db->get()->row_array();

			if ($entry_row['status'] == STATUS_DISCREPANCY && $status_to_set != STATUS_DISCREPANCY) {
				//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
				$rejected_doc_hours = floatval($entry_row['rejected_doc_hours']);
				$rejected_doc_hours = $rejected_doc_hours +  convert_time_string_to_float($entry_row['rejected_doc_elapsed_time']);
				$data['rejected_doc_hours'] = $rejected_doc_hours;
			}
		} else if ($progress == PROGRESS_CLOSED) {
			$status_to_set = FINAL_RESULT_VERIFY;
			$data['final_result'] = $status_to_set;

			/*codigo para salvar el tiempo*/
			$this->db->select('final_result, waiting_start_time, waiting_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", waiting_start_time) as waiting_elapsed_time , rejected_doc_start_time, rejected_doc_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_doc_start_time) as rejected_doc_elapsed_time, rejected_prod_start_time, rejected_prod_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_prod_start_time) as rejected_prod_elapsed_time');
			$this->db->from('entry');
			$this->db->where('id', $from);
			$entry_row = $this->db->get()->row_array();

			if ($entry_row['final_result'] == FINAL_RESULT_DISCREPANCY && $status_to_set != FINAL_RESULT_DISCREPANCY) {
				//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
				$rejected_doc_hours = floatval($entry_row['rejected_doc_hours']);
				$rejected_doc_hours = $rejected_doc_hours +  convert_time_string_to_float($entry_row['rejected_doc_elapsed_time']);
				$data['rejected_doc_hours'] = $rejected_doc_hours;
			}
		}

		$this->db->where('id', $from);
		return $this->db->update('entry', $data);
	}




	public function assign_entry()
	{
		$id = $this->input->post('id');
		$progress = 1;
		$assign_date = date("Y-m-d H:i:s");

		$data = array(
			'progress' => $progress,
			'asignada' => $this->input->post('asignada'),
			'status' => STATUS_ASSIGNED,
			'asignada_date' => $assign_date,
		);

		return $this->db->update('entry', $data, array("id" => $id));
	}

	public function reassign_entry()
	{
		$id = $this->input->post('id');
		$assign_date = date("Y-m-d H:i:s");

		$data = array(
			'asignada' => $this->input->post('asignada'),
			'asignada_date' => $assign_date,
		);

		return $this->db->update('entry', $data, array("id" => $id));
	}




	public function release_entry()
	{
		$this->load->helper('time');
		$id = $this->input->post('id');
		$progress = 2;
		$release_date = date("Y-m-d H:i:s");
		$current_date_time = new DateTime();

		//echo json_encode($entry_row);
		$status_to_set = $this->input->post('status');
		$data = array(
			'progress' => $progress,
			'liberada_date' => $release_date,
			'status' => $status_to_set,
			'final_qty' => $this->input->post('final_qty'),
			'location' => strtoupper($this->input->post('location')),
			'wo_escaneadas' => strtoupper($this->input->post('wo_escaneadas')),
			'has_fecha_exp' => $this->input->post('has_fecha_exp'),
			'fecha_exp' => $this->input->post('fecha_exp'),
			'rev_dibujo' => strtoupper($this->input->post('rev_dibujo')),
			'empaque' => strtoupper($this->input->post('empaque')),
			'documentos_rev' => strtoupper($this->input->post('documentos_rev')),
			'label_zebra_rev' => strtoupper($this->input->post('label_zebra_rev')),
			'razon_rechazo' => strtoupper($this->input->post('razon_rechazo')),
		);

		if ($status_to_set == STATUS_DISCREPANCY) {
			$data['discrepancia'] = 1;
		}

		/* CODIGO PARA GRABAR LOS TIEMPOS*/
		//TIMEDIFF('2014-02-17 12:10:08', '2014-02-16 12:10:08')
		$this->db->select('status, waiting_start_time, waiting_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", waiting_start_time) as waiting_elapsed_time , rejected_doc_start_time, rejected_doc_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_doc_start_time) as rejected_doc_elapsed_time, rejected_prod_start_time, rejected_prod_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_prod_start_time) as rejected_prod_elapsed_time, pack_start_time, pack_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", pack_start_time) as pack_elapsed_time');
		$this->db->from('entry');
		$this->db->where('id', $id);
		$entry_row = $this->db->get()->row_array();

		//Si se va a colocar en el estado de waiting
		if ($entry_row['status'] != STATUS_WAITING && $status_to_set == STATUS_WAITING) {
			//Save the waiting_start_time
			$data['waiting_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}

		if ($entry_row['status'] != STATUS_REJECTED_BY_PRODUCT && $status_to_set == STATUS_REJECTED_BY_PRODUCT) {
			//Save the waiting_rejected_time
			$data['rejected_prod_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}

		if ($entry_row['status'] != STATUS_DISCREPANCY && $status_to_set == STATUS_DISCREPANCY) {
			//Save the waiting_rejected_time
			$data['rejected_doc_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}

		if ($entry_row['status'] != STATUS_PACK && $status_to_set == STATUS_PACK) {
			//Save the pack time
			$data['pack_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}


		if ($entry_row['status'] == STATUS_WAITING && $status_to_set != STATUS_WAITING) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$waiting_hours = floatval($entry_row['waiting_hours']);
			$waiting_hours = $waiting_hours +  convert_time_string_to_float($entry_row['waiting_elapsed_time']);
			$data['waiting_hours'] = $waiting_hours;
		}

		if ($entry_row['status'] == STATUS_REJECTED_BY_PRODUCT && $status_to_set != STATUS_REJECTED_BY_PRODUCT) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$rejected_prod_hours = floatval($entry_row['rejected_prod_hours']);
			$rejected_prod_hours = $rejected_prod_hours +  convert_time_string_to_float($entry_row['rejected_prod_elapsed_time']);
			$data['rejected_prod_hours'] = $rejected_prod_hours;
		}

		if ($entry_row['status'] == STATUS_DISCREPANCY && $status_to_set != STATUS_DISCREPANCY) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$rejected_doc_hours = floatval($entry_row['rejected_doc_hours']);
			$rejected_doc_hours = $rejected_doc_hours +  convert_time_string_to_float($entry_row['rejected_doc_elapsed_time']);
			$data['rejected_doc_hours'] = $rejected_doc_hours;
		}

		if ($entry_row['status'] == STATUS_PACK && $status_to_set != STATUS_PACK) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$pack_hours = floatval($entry_row['pack_hours']);
			$pack_hours = $pack_hours +  convert_time_string_to_float($entry_row['pack_elapsed_time']);
			$data['pack_hours'] = $pack_hours;
		}

		return $this->db->update('entry', $data, array("id" => $id));
	}





	public function close_entry()
	{
		$this->load->helper('time');

		$id = $this->input->post('id');
		$cerrada_date = date("Y-m-d H:i:s");
		$discrepancia_descr = strtoupper($this->input->post('discrepancia_descr'));
		$status_to_set = $this->input->post('final_result');

		$final_result = $this->input->post('final_result');

		//discrepancia_descr
		$data = array(
			'progress' => PROGRESS_CLOSED,
			'final_result' => $final_result,
			'rev_mapics' => strtoupper($this->input->post('rev_mapics')),
			'cerrada_date' => $cerrada_date,
			'discrepancia_descr' =>  $discrepancia_descr,
		);

		if ($status_to_set == FINAL_RESULT_DISCREPANCY) {
			$data['discrepancia'] = 1;
		}


		/* CODIGO PARA GRABAR LOS TIEMPOS*/
		//TIMEDIFF('2014-02-17 12:10:08', '2014-02-16 12:10:08')
		$current_date_time = new DateTime();
		$this->db->select('final_result, waiting_start_time, waiting_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", waiting_start_time) as waiting_elapsed_time , rejected_doc_start_time, rejected_doc_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_doc_start_time) as rejected_doc_elapsed_time, rejected_prod_start_time, rejected_prod_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_prod_start_time) as rejected_prod_elapsed_time');
		$this->db->from('entry');
		$this->db->where('id', $id);
		$entry_row = $this->db->get()->row_array();

		//Si se va a colocar en el estado de waiting
		if ($entry_row['final_result'] != FINAL_RESULT_WAITING && $status_to_set == FINAL_RESULT_WAITING) {
			//Save the waiting_start_time
			$data['waiting_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}

		if ($entry_row['final_result'] != FINAL_RESULT_REJECTED_BY_PRODUCT && $status_to_set == FINAL_RESULT_REJECTED_BY_PRODUCT) {
			//Save the waiting_rejected_time
			$data['rejected_prod_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}

		if ($entry_row['final_result'] != FINAL_RESULT_DISCREPANCY && $status_to_set == FINAL_RESULT_DISCREPANCY) {
			//Save the waiting_rejected_time
			$data['rejected_doc_start_time'] = $current_date_time->format(DATETIME_FORMAT);
		}


		if ($entry_row['final_result'] == FINAL_RESULT_WAITING && $status_to_set != FINAL_RESULT_WAITING) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$waiting_hours = floatval($entry_row['waiting_hours']);
			$waiting_hours = $waiting_hours +  convert_time_string_to_float($entry_row['waiting_elapsed_time']);
			$data['waiting_hours'] = $waiting_hours;
		}

		if ($entry_row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT && $status_to_set != FINAL_RESULT_REJECTED_BY_PRODUCT) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$rejected_prod_hours = floatval($entry_row['rejected_prod_hours']);
			$rejected_prod_hours = $rejected_prod_hours +  convert_time_string_to_float($entry_row['rejected_prod_elapsed_time']);
			$data['rejected_prod_hours'] = $rejected_prod_hours;
		}

		if ($entry_row['final_result'] == FINAL_RESULT_DISCREPANCY && $status_to_set != FINAL_RESULT_DISCREPANCY) {
			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$rejected_doc_hours = floatval($entry_row['rejected_doc_hours']);
			$rejected_doc_hours = $rejected_doc_hours +  convert_time_string_to_float($entry_row['rejected_doc_elapsed_time']);
			$data['rejected_doc_hours'] = $rejected_doc_hours;
		}

		$this->db->update('entry', $data, array("id" => $id));


		if ($final_result == FINAL_RESULT_CLOSED) {

			//insert in another table
			$this->db->select('*');
			$this->db->from('entry');
			$this->db->where('id', $id);
			$data = $this->db->get()->row_array();
			$this->db->insert('entry_accepted', $data);

			//delete from entry
			$this->db->where('id', $id);
			$this->db->delete('entry');
			return FALSE;
		}

		return TRUE;
	}
}
