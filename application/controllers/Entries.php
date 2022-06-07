<?php

include_once('BaseController.php');

class Entries extends BaseController
{

	public function create()
	{
		if (!($this->is_logged_in() && $this->is_production())) {
			redirect('/');
		}

		$data['parts'] = $this->EntryModel->get_parts();
		$data['plantas'] = $this->LocationModel->get_plants();

		$this->form_validation->set_rules('part_no', 'Numero de parte', 'required');
		$this->form_validation->set_rules('lot_no', 'Numero de lote', 'required');
		$this->form_validation->set_rules('qty', 'Cantidad', 'required|callback_check_is_positive');
		$this->form_validation->set_rules('plant', 'Planta', 'required');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header');
			$this->load->view('entries/create', $data);
			$this->load->view('templates/footer');
		} else {

			$this->EntryModel->create_entry();

			//session message
			$this->session->set_flashdata(
				'created',
				'Se ha guardado la orden y enviada a inspeccion.'
			);

			redirect(base_url() . 'entries/create');
		}
	}




	public function assign($id = NULL)
	{

		if (!($this->is_logged_in() && $this->is_quality())) {
			redirect('/');
		}

		//asignar

		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->LocationModel->get_locations();
		$data['plants'] = $this->LocationModel->get_plants();
		$data['users'] = $this->UserModel->get_users_quality();


		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('asignada', 'Usuario', 'required');


		if ($this->form_validation->run() === FALSE) {

			$this->load->view('templates/header');
			$this->load->view('entries/asign', $data);
			$this->load->view('templates/footer');
		} else {

			$this->EntryModel->assign_entry();
			//session message
			$this->session->set_flashdata('assigned', 'Se ha asignado la orden.');
			redirect(base_url() . 'entries/assign/' . $id);
		}
	}





	public function release($id = NULL)
	{
		//liberar
		if (!($this->is_logged_in() && $this->is_quality())) {
			redirect('/');
		}

		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->LocationModel->get_locations();
		$data['plants'] =  $this->LocationModel->get_plants();

		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('final_qty', 'Cantidad final', 'required|callback_check_is_positive');
		$this->form_validation->set_rules('wo_escaneadas', 'Work orders escaneadas', 'required');
		$this->form_validation->set_rules('location', 'Locacion', 'required');
		$this->form_validation->set_rules('rev_dibujo', 'Revision de dibujo', 'required');
		$this->form_validation->set_rules('empaque', 'Empaque', 'required');
		$this->form_validation->set_rules('documentos_rev', 'Documentos revisados', 'required');
		$this->form_validation->set_rules('has_fecha_exp', 'Fecha de expiracion si o no', 'required');



		if ($this->input->post('status') == 2) {
			$this->form_validation->set_rules('razon_rechazo', 'Razon del rechazo.', 'required');
		}

		if ($this->input->post('has_fecha_exp') == 1) {
			$this->form_validation->set_rules('fecha_exp', 'Fecha de expiracion', 'required');
		}

		$quantity = $data['entry']['qty'];
		$final_qty = $this->input->post('final_qty');
		$error_message = NULL;
		if ($final_qty > $quantity) {
			$error_message = 'La cantidad final no puede ser mayor a la cantidad enviada, por favor verifique la informacion';
			$data['error_message'] = $error_message;
		}

		if ($this->form_validation->run() === FALSE || $error_message != NULL) {

			$this->load->view('templates/header');
			$this->load->view('entries/release', $data);
			$this->load->view('templates/footer');
		} else {

			$this->EntryModel->release_entry();

			//session message
			$this->session->set_flashdata('liberada', 'Se ha liberado la entrada.');

			redirect(base_url() . 'entries/release/' . $id);
		}
	}







	public function close($id = NULL)
	{
		if (!($this->is_logged_in() && $this->is_quality())) {
			redirect('/');
		}


		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->LocationModel->get_locations();
		$data['plants'] = $this->LocationModel->get_plants();
		$data['users'] = $this->UserModel->get_users_quality();


		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('final_result', 'Resultado', 'required');
		$this->form_validation->set_rules('cerrada_por', 'Cerrada por', 'required');
		$this->form_validation->set_rules('rev_mapics', 'Revision contra Mapics', 'required');


		if ($this->form_validation->run() === FALSE) {

			$this->load->view('templates/header');
			$this->load->view('entries/close', $data);
			$this->load->view('templates/footer');
		} else {

			$this->EntryModel->close_entry();

			//session message
			$this->session->set_flashdata('cerrada', 'Se ha cerrado la entrada.');

			redirect(base_url() . 'entries/release/' . $id);
		}
	}





	/**************  custom validation ***************/

	function check_is_positive($qty)
	{
		$this->form_validation->set_message('check_is_positive', 'El campo de cantidad debe ser un numero mayor que 0.');

		if ($qty < 0) {
			return false;
		} else {
			return true;
		}
	}


	function api_get_entries($apply_filter_not_closed = FALSE)
	{


		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';



		## Fetch records
		//$empQuery = "SELECT * FROM entry
		//	WHERE  1  " . $searchQuery . " ORDER BY " . $columnName . "  " . $columnSortOrder . " LIMIT " . $row . " , " . $rowperpage;
		$empQuery = "SELECT * FROM entry WHERE  1 ";
		if ($apply_filter_not_closed == TRUE) {
			$empQuery .= ' AND (progress < ' . PROGRESS_CLOSED . ') ';
		}
		$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		//$empQuery .=  $searchQuery . " ORDER BY " . $columnName . "  " . $columnSortOrder . " LIMIT " . $row . " , " . $rowperpage;

		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			//progress
			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$btn_title = "Asignar";
				$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				$color =  "bg-danger";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				$btn_title = "Liberar";
				$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado en espera a Liberar";
				$color =  "bg-warning";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado en espera a Cerrar";
				$color =  "bg-primary";
			} elseif ($row['progress'] == PROGRESS_CLOSED) {
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				$color =  "bg-success disabled";
			}

			$link = base_url() . $link;

			$data[] = array(
				"id" => $row['id'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"planta" => $row['plant'],
				"progress" => "<h4><span class='badge $color'>$text</span></h4>",
				"btn_id" => "<a href='$link' class='btn btn-primary'>$btn_title</a>"
			);
		}

		## Response
		$response['data'] = $data;
		//$response['start_date'] = $start_date;
		//$response['end_date'] = $end_date;

		echo json_encode($response);
	}


	function api_entries_not_closed()
	{
		return $this->api_get_entries(TRUE);
	}



	function api_entries_closed()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';


		$sql = 'SELECT count(*) AS allcount from entry';
		$sql .= ' WHERE progress = ' . PROGRESS_CLOSED;

		if (!($start_date == '' &&  $end_date == '')) {
			$sql .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}

		$query = $this->db->query($sql);
		$records = $query->result_array();
		$totalRecords = $records[0]['allcount'];
		$totalRecordwithFilter = $records[0]['allcount'];



		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, TIMEDIFF(asignada_date, created_at) as assigned_elapsed_time,
		TIMEDIFF(liberada_date, created_at) as released_elapsed_time,
		TIMEDIFF(cerrada_date, created_at) as closed_elapsed_time  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";
		$empQuery .= ' AND (progress = ' . PROGRESS_CLOSED . ') ';

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}

		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {


			$data[] = array(
				"id" => $row['id'],
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"plant" => $row['plant'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"assigned_elapsed_time" => convert_time_string_to_float($row['assigned_elapsed_time']),
				"released_elapsed_time" =>  convert_time_string_to_float($row['released_elapsed_time']),
				"closed_elapsed_time" =>  convert_time_string_to_float($row['closed_elapsed_time']),
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>',
			);
		}



		$response['data'] = $data;

		echo json_encode($response);
	}
}
