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
		$data['production_users'] = $this->UserModel->get_users_production();

		$this->form_validation->set_rules('part_no', 'Numero de parte', 'required');
		$this->form_validation->set_rules('lot_no', 'Numero de lote', 'required');
		$this->form_validation->set_rules('qty', 'Cantidad', 'required|callback_check_is_positive');
		$this->form_validation->set_rules('plant', 'Planta', 'required');
		$this->form_validation->set_rules('assigned_by', 'Supervisor o Guía', 'required');

		if ($this->form_validation->run() === FALSE) {

			$data['old']['part_no'] = $this->input->post('part_no') == null ? '' : $this->input->post('part_no');
			$data['old']['lot_no'] = $this->input->post('lot_no') == null ? '' : $this->input->post('lot_no');
			$data['old']['qty'] = $this->input->post('qty') == null ? '' : $this->input->post('qty');
			$data['old']['plant'] = $this->input->post('plant') == null ? '' : $this->input->post('plant');
			$data['old']['assigned_by'] = $this->input->post('assigned_by') == null ? '' : $this->input->post('assigned_by');

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



	public function retrieve_data($from)
	{
		$this->db->select('id, created_at, part_no, lot_no, plant, qty, parcial, reinspeccion, ficticio, progress, assigned_by');
		$this->db->from('entry');
		$this->db->where('id', $from);
		$entry_from = $this->db->get()->result_array()[0];

		$message = 'Se va a remover de la lista de rechazados la orden con folio ' . $entry_from['id'] . ', de fecha ' . $entry_from['created_at'] . ' y se va a generar una nueva orden para inspeccion';

		$data['parts'] = $this->EntryModel->get_parts();
		$data['plantas'] = $this->LocationModel->get_plants();
		$data['message'] = $message;

		$data['from'] = $from;
		$data['old']['part_no'] = $entry_from['part_no'];
		$data['old']['lot_no'] = $entry_from['lot_no'];
		$data['old']['plant'] = $entry_from['plant'];
		$data['old']['qty'] = $entry_from['qty'];
		$data['old']['assigned_by'] = $entry_from['assigned_by'];

		$data['parcial'] = $entry_from['parcial'];
		$data['reinspeccion'] = $entry_from['reinspeccion'];
		$data['ficticio'] = $entry_from['ficticio'];
		$data['progress'] = $entry_from['progress'];

		$data['reload_route'] = $this->input->get('reload_route');
		$data['start_date'] = $this->input->get('start_date');
		$data['end_date'] = $this->input->get('end_date');

		return $data;
	}

	public function rework()
	{
		if (!($this->is_logged_in() && $this->is_production())) {
			redirect('/');
		}

		$from = $this->input->get('from');
		$data = $this->retrieve_data($from);

		$this->load->view('templates/header');
		$this->load->view('entries/rework', $data);
		$this->load->view('templates/footer');
	}

	public function rework_save()
	{
		$this->form_validation->set_rules('part_no', 'Numero de parte', 'required');
		$this->form_validation->set_rules('lot_no', 'Numero de lote', 'required');
		$this->form_validation->set_rules('qty', 'Cantidad', 'required|callback_check_is_positive');
		$this->form_validation->set_rules('plant', 'Planta', 'required');
		$this->form_validation->set_rules('from', 'Substituye Orden', 'required');
		$from = $this->input->post('from');

		if ($this->form_validation->run() === TRUE) {

			//No se necesita porque se realizaria una nueva orden y esta ya no vale....
			//actualizar los tiempos de los final_result porque se va a crear otra orden
			/*$current_date_time = new DateTime();
			$this->db->select('final_result, waiting_start_time, waiting_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", waiting_start_time) as waiting_elapsed_time , rejected_doc_start_time, rejected_doc_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_doc_start_time) as rejected_doc_elapsed_time, rejected_prod_start_time, rejected_prod_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_prod_start_time) as rejected_prod_elapsed_time');
			$this->db->from('entry');
			$this->db->where('id', $from);
			$entry_row = $this->db->get()->row_array();
			if ($entry_row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
				//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
				$rejected_prod_hours = floatval($entry_row['rejected_prod_hours']);
				$rejected_prod_hours = $rejected_prod_hours +  convert_time_string_to_float($entry_row['rejected_prod_elapsed_time']);
				//$data['rejected_prod_hours'] = $rejected_prod_hours;
				$this->db->set('rejected_prod_hours', $rejected_prod_hours);
			}
			*/

			//'to_rework'
			$this->db->set('to_rework', 1);
			$this->db->where('id', $from);
			$this->db->update('entry');

			$this->EntryModel->create_entry();

			$url =  $this->input->post('reload_route') . '?start_date=' . $this->input->post('start_date') . '&end_date=' . $this->input->post('end_date') . '&success_message=' . urldecode('Se removió de la lista la orden rechazada y se configuró una Nueva');
			//After 
			redirect($url);
		} else {
			//An error ocurred
			$data = $this->retrieve_data($from);

			$this->load->view('templates/header');
			$this->load->view('entries/rework', $data);
			$this->load->view('templates/footer');
		}
	}



	public function solved()
	{
		if (!($this->is_logged_in() && $this->is_production())) {
			redirect('/');
		}

		$from = $this->input->get('from');
		$data = $this->retrieve_data($from);
		$data['title'] = "SOLUCION AL RECHAZO POR DISCREPANCIA";

		$data['message'] = "Confirme que se ha solucionado el problema que generó el rechazo/discrepancia de esta orden. Al confirmar la solución se turnara a calidad para que revise y libere la orden.";

		$this->load->view('templates/header');
		$this->load->view('entries/solved', $data);
		$this->load->view('templates/footer');
	}


	public function solved_save()
	{
		$this->form_validation->set_rules('part_no', 'Numero de parte', 'required');
		$this->form_validation->set_rules('lot_no', 'Numero de lote', 'required');

		//$this->form_validation->set_rules('qty', 'Cantidad', 'required|callback_check_is_positive');
		//$this->form_validation->set_rules('plant', 'Planta', 'required');
		$this->form_validation->set_rules('from', 'Substituye Orden', 'required');
		$from = $this->input->post('from');


		//echo json_encode($this->input->post());

		if ($this->form_validation->run() === TRUE) {
			$this->EntryModel->solve_entry();

			$url =  $this->input->post('reload_route') . '?start_date=' . $this->input->post('start_date') . '&end_date=' . $this->input->post('end_date') . '&success_message=' . urldecode('Se removió de la lista de ordenes rechazadas por discrepancias y ahora pasa a las listas de calidad por trabajar.');
			//After 
			redirect($url);
		} else {
			//An error ocurred
			$data = $this->retrieve_data($from);

			$this->load->view('templates/header');
			$this->load->view('entries/solved', $data);
			$this->load->view('templates/footer');
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
		$data['reload_route'] = 'reports/produccion';


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




	public function reassign($id = NULL)
	{

		if (!($this->is_logged_in() && $this->is_quality())) {
			redirect('/');
		}

		//asignar
		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->LocationModel->get_locations();
		$data['plants'] = $this->LocationModel->get_plants();
		$data['users'] = $this->UserModel->get_users_quality();
		$data['reload_route'] = 'reports/produccion';


		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('asignada', 'Usuario', 'required');


		if ($this->form_validation->run() === FALSE) {

			$this->load->view('templates/header');
			$this->load->view('entries/reasign', $data);
			$this->load->view('templates/footer');
		} else {

			$this->EntryModel->reassign_entry();
			//session message
			$this->session->set_flashdata('assigned', 'Se ha reasignado la orden.');
			redirect(base_url() . 'entries/reassign/' . $id);
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
		$data['location'] = $this->LocationModel->get_location($data['entry']['location']);
		$data['plants'] =  $this->LocationModel->get_plants();
		$data['quality_users'] = $this->UserModel->get_users_quality();

		$data['reload_route'] = 'reports/produccion';


		$this->load->view('templates/header');
		$this->load->view('entries/release', $data);
		$this->load->view('templates/footer');
	}


	public function release_save($id = NULL)
	{
		//liberar
		if (!($this->is_logged_in() && $this->is_quality())) {
			redirect('/');
		}


		if ($this->input->post('status') == STATUS_WAITING) {
			$this->form_validation->set_rules('id', 'ID o Folio', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');

			//Si trae la cantidad validarla
			if ($this->input->post('final_qty') != null)
				$this->form_validation->set_rules('final_qty', 'Cantidad final', 'required|callback_check_is_positive');
		} else {
			$this->form_validation->set_rules('id', 'ID o Folio', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('final_qty', 'Cantidad final', 'required|callback_check_is_positive');
			$this->form_validation->set_rules('wo_escaneadas', 'Work orders escaneadas', 'required');
			$this->form_validation->set_rules('location', 'Locacion', 'required');
			$this->form_validation->set_rules('rev_dibujo', 'Revision de dibujo', 'required');
			$this->form_validation->set_rules('empaque', 'Empaque', 'required');
			$this->form_validation->set_rules('documentos_rev', 'Documentos revisados', 'required');
			$this->form_validation->set_rules('has_fecha_exp', 'Fecha de expiracion si o no', 'required');
		}


		if ($this->input->post('status') == 1) {
			$this->form_validation->set_rules('razon_rechazo', 'Razon del rechazo.', 'required');
		}

		if ($this->input->post('has_fecha_exp') == 1) {
			$this->form_validation->set_rules('fecha_exp', 'Fecha de expiracion', 'required');
		}

		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$quantity = $data['entry']['qty'];
		$final_qty = $this->input->post('final_qty');


		$error_message = NULL;
		if ($final_qty > $quantity) {
			$error_message = 'La cantidad final no puede ser mayor a la cantidad enviada, por favor verifique la informacion';
			$data['error_message'] = $error_message;
		}


		if ($this->form_validation->run() === FALSE || $error_message != NULL) {
			$data['entry']['status'] = $this->input->post('status');
			$data['entry']['final_qty'] = $this->input->post('final_qty');
			$data['entry']['location'] = $this->input->post('location');
			$data['entry']['wo_escaneadas'] = $this->input->post('wo_escaneadas');
			$data['entry']['has_fecha_exp'] = $this->input->post('has_fecha_exp');
			$data['entry']['fecha_exp'] = $this->input->post('fecha_exp');
			$data['entry']['rev_dibujo'] = $this->input->post('rev_dibujo');
			$data['entry']['empaque'] = $this->input->post('empaque');
			$data['entry']['documentos_rev'] = $this->input->post('documentos_rev');
			$data['entry']['razon_rechazo'] = $this->input->post('razon_rechazo');

			$data['locations'] = $this->LocationModel->get_locations();
			$data['location'] = $this->LocationModel->get_location($data['entry']['location']);
			$data['plants'] =  $this->LocationModel->get_plants();
			$data['quality_users'] = $this->UserModel->get_users_quality();
			$data['reload_route'] = 'reports/produccion';

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
		$data['location'] = $this->LocationModel->get_location($data['entry']['location']);
		$data['users'] = $this->UserModel->get_users_quality();


		if ($this->input->post('final_result') == FINAL_RESULT_WAITING) {
			$this->form_validation->set_rules('id', 'ID o Folio', 'required');
			$this->form_validation->set_rules('final_result', 'Resultado', 'required');
		} else {
			$this->form_validation->set_rules('id', 'ID o Folio', 'required');
			$this->form_validation->set_rules('final_result', 'Resultado', 'required');
			$this->form_validation->set_rules('cerrada_por', 'Cerrada por', 'required');
			$this->form_validation->set_rules('rev_mapics', 'Revision contra Mapics', 'required');
		}

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header');
			$this->load->view('entries/close', $data);
			$this->load->view('templates/footer');
		} else {

			if ($this->EntryModel->close_entry() == TRUE) {
				//session message
				$this->session->set_flashdata('cerrada', 'Se ha cerrado la entrada.');
				redirect(base_url() . 'entries/close/' . $id);
			} else {
				$this->session->set_flashdata('cerrada y Aceptado', 'Se ha cerrado satisfactoriamente la orden.');
				redirect(base_url() . 'reports/calidad');
			}
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


	/*function api_get_entries($apply_filter_not_closed = FALSE)
	{
		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		## Fetch records
		//$empQuery = "SELECT * FROM entry
		//	WHERE  1  " . $searchQuery . " ORDER BY " . $columnName . "  " . $columnSortOrder . " LIMIT " . $row . " , " . $rowperpage;
		$empQuery = "SELECT id, created_at, part_no, lot_no, qty, plantas.planta_nombre as plant, progress FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";
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
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
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
		echo json_encode($response);
	}*/


	function api_entries_opened()
	{
		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		## Fetch records
		//$empQuery = "SELECT * FROM entry
		//	WHERE  1  " . $searchQuery . " ORDER BY " . $columnName . "  " . $columnSortOrder . " LIMIT " . $row . " , " . $rowperpage;
		$empQuery = "SELECT id, created_at, part_no, lot_no, qty, plantas.planta_nombre as plant, progress, status, final_result,
		TIMEDIFF(NOW(), created_at) as elapsed_time, asignada, has_urgency
		FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		$empQuery .= " AND (";
		$empQuery .= ' progress = ' . PROGRESS_NOT_ASSIGNED;
		$empQuery .= ' OR progress = ' . PROGRESS_ASSIGNED;
		$empQuery .= ' OR ((progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_ACCEPTED . ') OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_WAITING . ' ) OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_VERIFY . ' ) )';
		$empQuery .= ' OR ((progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_WAITING . ') OR (progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_VERIFY . ') )';
		$empQuery .= " )";


		if ($start_date != '' && $end_date == '') {
			//Solo fecha desde
			$empQuery .= " AND created_at > '" . $start_date . "'";
		} else if ($start_date == '' && $end_date != '') {
			//Solo fecha hasta
			$empQuery .= " AND created_at < '" . $end_date . "'";
		} else if ($start_date != '' && $end_date != '') {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			//progress
			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$btn_title = "Asignar";
				$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				$btn_title = "Liberar";
				$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {

				$text =  "2/3 Liberado";

				if ($row['status'] == STATUS_WAITING) {
					$btn_title = "Liberar";
					$link = "entries/release/{$row['id']}";
				} else {
					$btn_title = "Cerrar";
					$link = "entries/close/{$row['id']}";
				}
			} elseif ($row['progress'] == PROGRESS_CLOSED) {

				$text =  "3/3 Orden Cerrada";
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
			}


			$status = '';
			$color = '';

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$status = 'Sin asignar';
				$color =  "bg-secondary";
			} else if ($row['progress'] == PROGRESS_ASSIGNED) {
				$status = 'Asignado';
				$color =  "bg-primary";
			} else if ($row['progress'] == PROGRESS_RELEASED) {
				if ($row['status'] == STATUS_ACCEPTED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['status'] == STATUS_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				} else if ($row['status'] == STATUS_VERIFY) {
					$status = 'Por Verificar';
					$color =  "bg-danger";
				}
			} else if ($row['progress'] == PROGRESS_CLOSED) {
				if ($row['final_result'] == FINAL_RESULT_CLOSED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				} else if ($row['final_result'] == FINAL_RESULT_VERIFY) {
					$status = 'Por Verificar';
					$color =  "bg-danger";
				}
			}

			$link = base_url() . $link;


			$actions = "<div class='btn-group'>";
			$actions .= "<a href='$link' class='btn btn-primary'>$btn_title</a>";
			$actions .= " </div>";

			if ($row['asignada'] != '') {
				if ($this->session->userdata(USER_TYPE) == QUALITY_USER && $this->session->userdata(LEVEL_NAME) == 'Supervisor') {
					//Si es el supervisor entonces se puede reasignar...
					$asignada = '<a href="' . base_url() . 'entries/reassign/' . $row['id'] . '" class="btn btn-primary bg-secondary" >' . $row['asignada'] . '</a>';
				} else {
					$asignada = $row['asignada'];
				}
			} else {
				$asignada = "";
			}


			$urgency = '';

			if ($row['has_urgency'] == 1) {
				$urgency = "<span class='badge rounded-pill bg-danger'>Urgente</span>";
			} else {
				$urgency = "<span class='badge rounded-pill bg-primary'>Normal</span>";
			}

			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"elapsed_time" => convert_time_string_to_float($row['elapsed_time']),
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"asignada" => $asignada,
				"planta" => $row['plant'],
				"progress" => "$text",
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"btn_id" => $actions,
				"has_urgency" => $urgency,
			);
		}

		## Response
		$response['data'] = $data;
		echo json_encode($response);
	}



	function api_entries_closed()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, TIMEDIFF(asignada_date, created_at) as assigned_elapsed_time,
		TIMEDIFF(liberada_date, created_at) as released_elapsed_time,
		TIMEDIFF(cerrada_date, created_at) as closed_elapsed_time, 
		waiting_hours,
		rejected_doc_hours,
		progress, status, final_result, discrepancia_descr, razon_rechazo  FROM entry_accepted INNER JOIN plantas ON entry_accepted.plant = plantas.planta_id WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}

		/*
		$empQuery .= " AND NOT (";
		$empQuery .= ' progress = ' . PROGRESS_NOT_ASSIGNED;
		$empQuery .= ' OR progress = ' . PROGRESS_ASSIGNED;
		$empQuery .= ' OR ((progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_ACCEPTED . ') OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_WAITING . ' ) OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_VERIFY . ' ) )';
		$empQuery .= ' OR ((progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_WAITING . ') OR (progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_VERIFY . ') )';
		$empQuery .= " )";
		*/

		$empQuery .= " AND  (";
		$empQuery .= 'progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_CLOSED;
		$empQuery .= " )";


		$empRecords = $this->db->query($empQuery)->result_array();
		$data = array();

		foreach ($empRecords as $row) {

			$status = '';
			$color = '';

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$status = 'Sin asignar';
				$color =  "bg-secondary";
			} else if ($row['progress'] == PROGRESS_ASSIGNED) {
				$status = 'Asignado';
				$color =  "bg-primary";
			} else if ($row['progress'] == PROGRESS_RELEASED) {
				if ($row['status'] == STATUS_ACCEPTED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['status'] == STATUS_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				}
				$comments = $row['razon_rechazo'];
			} else if ($row['progress'] == PROGRESS_CLOSED) {
				if ($row['final_result'] == FINAL_RESULT_CLOSED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				}
				$comments = $row['discrepancia_descr'];
			}

			$estimated =  round(convert_time_string_to_float($row['closed_elapsed_time']) - floatval($row['waiting_hours']) - floatval($row['rejected_doc_hours']), 2);

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
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail_accepted/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>',
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"comments" => $comments,
				"waiting_hours" => $row['waiting_hours'],
				"rejected_doc_hours" => $row['rejected_doc_hours'],
				"estimated" => $estimated,
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}


	function api_entries_rejected()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		$start_date_route = $this->input->get('start_date');
		$end_date_route = $this->input->get('end_date');
		$reload_route = $this->input->get('reload_route');

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		$empQuery .= ' AND ((progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_REJECTED_BY_PRODUCT . ') OR (progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_DISCREPANCY . ') OR (progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_REJECTED_BY_PRODUCT . ') OR (progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_DISCREPANCY . ') )';
		$empQuery .= ' AND to_rework = 0';

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$btn_title = "Asignar";
				$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				$color =  "bg-danger";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				$btn_title = "Liberar";
				$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado";
				$color =  "bg-warning";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado";
				$color =  "bg-primary";
			} elseif ($row['progress'] == PROGRESS_CLOSED) {
				$btn_title = "Cerrar";
				$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				$color =  "bg-success disabled";
			}


			$action = '';

			if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
				$action = '<td><a href="' . base_url() . 'entries/rework?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary">Retrabajar</a></td>';
			} else if ($row['final_result'] == FINAL_RESULT_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/rework?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary">Solucionado</a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"plant" => $row['plant'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => "$text",
				"razon_rechazo" => $row['razon_rechazo'],
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>',
				"action" => $action
			);
		}



		$response['data'] = $data;

		echo json_encode($response);
	}



	function api_entries_rejected_by_product()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		$start_date_route = $this->input->get('start_date');
		$end_date_route = $this->input->get('end_date');
		$reload_route = $this->input->get('reload_route');

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		$empQuery .= ' AND ((progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_REJECTED_BY_PRODUCT . ') OR (progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_REJECTED_BY_PRODUCT . ') )';
		$empQuery .= ' AND to_rework = 0';

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				//$btn_title = "Asignar";
				//$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				//$color =  "bg-danger";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				//$btn_title = "Liberar";
				//$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado";
				//$color =  "bg-warning";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado";
				//$color =  "bg-primary";
			} elseif ($row['progress'] == PROGRESS_CLOSED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				//$color =  "bg-success disabled";
			}

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT || $row['status'] == STATUS_REJECTED_BY_PRODUCT) {
				$action = '<td><a href="' . base_url() . 'entries/rework?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> Nuevo Registro x retrabajo </a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"plant" => $row['plant'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => "$text",
				"razon_rechazo" => $row['razon_rechazo'],
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>',
				"action" => $action
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}



	function api_entries_rejected_by_document()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		$start_date_route = $this->input->get('start_date');
		$end_date_route = $this->input->get('end_date');
		$reload_route = $this->input->get('reload_route');

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		$empQuery .= ' AND ((progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_DISCREPANCY . ') OR (progress = ' . PROGRESS_CLOSED . ' AND final_result = ' . FINAL_RESULT_DISCREPANCY . ') )';
		$empQuery .= ' AND to_rework = 0';

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				//$btn_title = "Asignar";
				//$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				//$color =  "bg-danger";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				//$btn_title = "Liberar";
				//$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado";
				//$color =  "bg-warning";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado";
				//$color =  "bg-primary";
			} elseif ($row['progress'] == PROGRESS_CLOSED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				//$color =  "bg-success disabled";
			}

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_DISCREPANCY || $row['status'] == STATUS_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/solved?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> Resuelto </a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"plant" => $row['plant'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => "$text",
				"razon_rechazo" => $row['razon_rechazo'],
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>',
				"action" => $action
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}



	function api_entries_quality_all()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		$start_date_route = $this->input->get('start_date');
		$end_date_route = $this->input->get('end_date');
		$reload_route = $this->input->get('reload_route');

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status, 0 as accepted  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";
		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}

		$empQuery .= " UNION ";

		$empQuery .= "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status, 1 as accepted FROM entry_accepted INNER JOIN plantas ON entry_accepted.plant = plantas.planta_id WHERE  1 ";
		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}

		$empQuery .= " ORDER BY id";


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				//$btn_title = "Asignar";
				//$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				//$color =  "bg-danger";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				//$btn_title = "Liberar";
				//$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado";
				//$color =  "bg-warning";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado";
				//$color =  "bg-primary";
			} elseif ($row['progress'] == PROGRESS_CLOSED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				//$color =  "bg-success disabled";
			}


			$status = '';
			$color = '';

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$status = 'Sin asignar';
				$color =  "bg-secondary";
			} else if ($row['progress'] == PROGRESS_ASSIGNED) {
				$status = 'Asignado';
				$color =  "bg-primary";
			} else if ($row['progress'] == PROGRESS_RELEASED) {
				if ($row['status'] == STATUS_ACCEPTED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['status'] == STATUS_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				} else if ($row['status'] == STATUS_VERIFY) {
					$status = 'Por Verificar';
					$color =  "bg-danger";
				}
			} else if ($row['progress'] == PROGRESS_CLOSED) {
				if ($row['final_result'] == FINAL_RESULT_CLOSED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				} else if ($row['final_result'] == FINAL_RESULT_VERIFY) {
					$status = 'Por Verificar';
					$color =  "bg-danger";
				}
			}

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_DISCREPANCY || $row['status'] == STATUS_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/solved?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> Resuelto </a></td>';
			}

			$detail =  '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>';
			if ($row['accepted'] == 1) {
				$detail =  '<td><a href="' . base_url() . 'reports/detail_accepted/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>';
			}

			$id = '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>';
			if ($row['accepted'] == 1) {
				$id = '<a href="' . base_url() . 'reports/detail_accepted/' . $row['id'] . '" >' . $row['id'] . '</a>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => $id,
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"plant" => $row['plant'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => "$text",
				"razon_rechazo" => $row['razon_rechazo'],
				"entry_id" => $detail,
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"action" => $action
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}



	function api_entries_quality_rejected()
	{

		$this->load->helper('time');

		$start_date = $this->input->get('start_date');
		if ($start_date != '') $start_date .= ' 00:00:00';

		$end_date = $this->input->get('end_date');
		if ($end_date != '') $end_date .= ' 23:59:59';

		$start_date_route = $this->input->get('start_date');
		$end_date_route = $this->input->get('end_date');
		$reload_route = $this->input->get('reload_route');

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}



		$empQuery .= " AND ( 
		(progress = " . PROGRESS_RELEASED . " AND  status = " . STATUS_REJECTED_BY_PRODUCT . ")  
		OR (progress = " . PROGRESS_RELEASED . " AND  status = " . STATUS_DISCREPANCY . ")
		OR (progress = " . PROGRESS_CLOSED . " AND  final_result = " . FINAL_RESULT_REJECTED_BY_PRODUCT . ")
		OR (progress = " . PROGRESS_CLOSED . " AND  final_result = " . FINAL_RESULT_DISCREPANCY . ")
		)";

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				//$btn_title = "Asignar";
				//$link = "entries/assign/{$row['id']}";
				$text =  "0/3 En espera";
				//$color =  "bg-danger";
			} elseif ($row['progress'] == PROGRESS_ASSIGNED) {
				//$btn_title = "Liberar";
				//$link = "entries/release/{$row['id']}";
				$text =  "1/3 Asignado";
				//$color =  "bg-warning";
			} elseif ($row['progress'] == PROGRESS_RELEASED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "2/3 Liberado";
				//$color =  "bg-primary";
			} elseif ($row['progress'] == PROGRESS_CLOSED) {
				//$btn_title = "Cerrar";
				//$link = "entries/close/{$row['id']}";
				$text =  "3/3 Orden Cerrada";
				//$color =  "bg-success disabled";
			}


			$status = '';
			$color = '';

			if ($row['progress'] == PROGRESS_NOT_ASSIGNED) {
				$status = 'Sin asignar';
				$color =  "bg-secondary";
			} else if ($row['progress'] == PROGRESS_ASSIGNED) {
				$status = 'Asignado';
				$color =  "bg-primary";
			} else if ($row['progress'] == PROGRESS_RELEASED) {
				if ($row['status'] == STATUS_ACCEPTED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['status'] == STATUS_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['status'] == STATUS_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				} else if ($row['status'] == STATUS_VERIFY) {
					$status = 'Por Verificar';
					$color =  "bg-danger";
				}
			} else if ($row['progress'] == PROGRESS_CLOSED) {
				if ($row['final_result'] == FINAL_RESULT_CLOSED) {
					$status = 'Aceptado';
					$color =  "bg-success disabled";
				} else if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
					$status = 'Rechazo x Prod';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_DISCREPANCY) {
					$status = 'Discrepancia';
					$color =  "bg-danger";
				} else if ($row['final_result'] == FINAL_RESULT_WAITING) {
					$status = 'En espera';
					$color =  "bg-warning";
				} else if ($row['final_result'] == FINAL_RESULT_VERIFY) {
					$status = 'Por Verificar';
					$color =  "bg-danger";
				}
			}

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_DISCREPANCY || $row['status'] == STATUS_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/solved?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> Resuelto </a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => $row['part_no'],
				"lot_no" => $row['lot_no'],
				"qty" => $row['qty'],
				"plant" => $row['plant'],
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => "$text",
				"razon_rechazo" => $row['razon_rechazo'],
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">Detalle</a></td>',
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"action" => $action
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}
}
