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

			$data['old']['part_no'] = $this->input->post('part_no') == null ? '' : strtoupper($this->input->post('part_no'));
			$data['old']['lot_no'] = $this->input->post('lot_no') == null ? '' :   strtoupper($this->input->post('lot_no'));
			$data['old']['qty'] = $this->input->post('qty') == null ? '' : $this->input->post('qty');
			$data['old']['plant'] = $this->input->post('plant') == null ? '' : $this->input->post('plant');
			$data['old']['assigned_by'] = $this->input->post('assigned_by') == null ? '' : strtoupper($this->input->post('assigned_by'));

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
		$data['old']['part_no'] = strtoupper($entry_from['part_no']);
		$data['old']['lot_no'] = strtoupper($entry_from['lot_no']);
		$data['old']['plant'] = $entry_from['plant'];
		$data['old']['qty'] = $entry_from['qty'];
		$data['old']['assigned_by'] = strtoupper($entry_from['assigned_by']);

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
		$this->load->helper('time');

		$this->form_validation->set_rules('part_no', 'Numero de parte', 'required');
		$this->form_validation->set_rules('lot_no', 'Numero de lote', 'required');
		$this->form_validation->set_rules('qty', 'Cantidad', 'required|callback_check_is_positive');
		$this->form_validation->set_rules('plant', 'Planta', 'required');
		$this->form_validation->set_rules('from', 'Substituye Orden', 'required');
		$from = $this->input->post('from');

		if ($this->form_validation->run() === TRUE) {


			$current_date_time = new DateTime();
			$this->db->select('status, waiting_start_time, waiting_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", waiting_start_time) as waiting_elapsed_time , rejected_doc_start_time, rejected_doc_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_doc_start_time) as rejected_doc_elapsed_time, rejected_prod_start_time, rejected_prod_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", rejected_prod_start_time) as rejected_prod_elapsed_time, pack_start_time, pack_hours, TIMEDIFF("' . $current_date_time->format(DATETIME_FORMAT) . '", pack_start_time) as pack_elapsed_time');
			$this->db->from('entry');
			$this->db->where('id', $from);
			$entry_row = $this->db->get()->row_array();

			//Si esta en el estatus de waiting y se va a cambiar a otro, vamos a sumar el tiempo de waiting y colocarlo
			$rejected_prod_hours = floatval($entry_row['rejected_prod_hours']);
			$rejected_prod_hours = $rejected_prod_hours +  convert_time_string_to_float($entry_row['rejected_prod_elapsed_time']);


			//$data['rejected_prod_hours'] = $rejected_prod_hours;
			$this->db->set('rejected_prod_hours', $rejected_prod_hours);
			$this->db->set('to_rework', 1);
			$this->db->where('id', $from);
			$this->db->update('entry');



			$this->EntryModel->create_entry();

			$url =  $this->input->post('reload_route') . '?start_date=' . $this->input->post('start_date') . '&end_date=' . $this->input->post('end_date') . '&success_message=' . urldecode('SE HA REMOVIDO DE LA LISTA LA ORDEN RECHAZADA Y SE HA CONFIGURADO UNA NUEVA');
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
		if (!$this->is_logged_in()) {
			redirect('/');
		}

		$from = $this->input->get('from');
		$data = $this->retrieve_data($from);
		$data['title'] = "SOLUCION AL RECHAZO POR DISCREPANCIA";

		if ($this->is_production())
			$data['message'] = "CONFIRME QUE SE HA SOLUCIONADO EL PROBLEMA QUE HA GENERADO EL RECHAZO/DISCREPANCIA DE ESTA ORDEN. AL CONFIRMAR LA SOLUCION SE TURNARÁ A CALIDAD PARA QUE REVISE Y LIBERE LA ORDEN.";
		else
			$data['message'] = "CONFIRME QUE SE HA SOLUCIONADO EL PROBLEMA QUE HA GENERADO EL RECHAZO/DISCREPANCIA DE ESTA ORDEN. AL CONFIRMAR LA SOLUCION SE PUEDE PASAR LIBERAR O CERRAR.";

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

			$url =  $this->input->post('reload_route') . '?start_date=' . $this->input->post('start_date') . '&end_date=' . $this->input->post('end_date') . '&success_message=' . urldecode('SE HA REMOVIDO DE LA LISTA DE ORDENES RECHAZADAS POR DISCREPANCIAS Y AHORA PASA A LAS LISTAS DE CALIDAD POR TRABAJAR.');
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
		} else if (
			$this->input->post('status') == STATUS_REJECTED_BY_PRODUCT
			|| $this->input->post('status') == STATUS_DISCREPANCY
			|| $this->input->post('status') == STATUS_PACK
		) {

			$this->form_validation->set_rules('id', 'ID o Folio', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('final_qty', 'Cantidad final', 'required|callback_check_is_positive');
			$this->form_validation->set_rules('location', 'Locacion', 'required');
			$this->form_validation->set_rules('documentos_rev', 'Documentos revisados', 'required');
			$this->form_validation->set_rules('razon_rechazo', 'Razon del rechazo.', 'required');
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


		if ($this->input->post('has_fecha_exp') == 1) {
			$this->form_validation->set_rules('fecha_exp', 'Fecha de expiracion', 'required');
		}


		if ($this->input->post('status') != STATUS_WAITING) {
			$data['entry'] = $this->EntryModel->get_single_entry($id);
			$quantity = $data['entry']['qty'];
			$final_qty = $this->input->post('final_qty');
			$error_message = NULL;
			if ($final_qty > $quantity) {
				$error_message = strtoupper('La cantidad final no puede ser mayor a la cantidad enviada, por favor verifique la informacion');
				$data['error_message'] = $error_message;
			}
		}


		if ($this->form_validation->run() === FALSE || $error_message != NULL) {
			$data['entry']['status'] = $this->input->post('status');
			$data['entry']['final_qty'] = $this->input->post('final_qty');
			$data['entry']['location'] = strtoupper($this->input->post('location'));
			$data['entry']['wo_escaneadas'] = strtoupper($this->input->post('wo_escaneadas'));
			$data['entry']['has_fecha_exp'] = $this->input->post('has_fecha_exp');
			$data['entry']['fecha_exp'] = $this->input->post('fecha_exp');
			$data['entry']['rev_dibujo'] = strtoupper($this->input->post('rev_dibujo'));
			$data['entry']['empaque'] = strtoupper($this->input->post('empaque'));
			$data['entry']['documentos_rev'] = strtoupper($this->input->post('documentos_rev'));
			$data['entry']['label_zebra_rev'] = strtoupper($this->input->post('label_zebra_rev'));
			$data['entry']['razon_rechazo'] = strtoupper($this->input->post('razon_rechazo'));

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
			$this->session->set_flashdata('liberada', $this->getSaveMessage());

			redirect(base_url() . 'entries/release/' . $id);
		}
	}


	public function getSaveMessage()
	{
		$message = "";
		$status = $this->input->post('status');

		/*
		defined('STATUS_REJECTED_BY_PRODUCT')      or define('STATUS_REJECTED_BY_PRODUCT', 1);
defined('STATUS_DISCREPANCY')      or define('STATUS_DISCREPANCY', 4);
defined('STATUS_ACCEPTED')      or define('STATUS_ACCEPTED', 2);
defined('STATUS_WAITING')      or define('STATUS_WAITING', 3);

		*/
		if ($status == STATUS_DISCREPANCY) {
			$message = strtoupper("Existen discrepancias por subsanar, ahora mismo producción ya ha sido notificado a través del sistema");
		} else if ($status == STATUS_REJECTED_BY_PRODUCT) {
			$message = strtoupper("El producto ha sizo rechazado y es necesario que producción formule una nueva orden");
		} else if ($status == STATUS_WAITING) {
			$message = strtoupper("Se colocó en espera esta orden, retomela lo mas pronto posible en cuanto tenga oportunidad");
		} else if ($status == STATUS_PACK) {
			$message = strtoupper("Se envio para pack del lado de producción, retomela una vez que producción haya regresado el material");
		} else if ($status == STATUS_ACCEPTED) {
			$message = strtoupper("Se ha liberado la orden y esta esperando para ser Cerrada");
		}

		return $message;
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
			$this->form_validation->set_rules('rev_mapics', 'Revision contra Mapics y Cerrada Por', 'required');
		}

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header');
			$this->load->view('entries/close', $data);
			$this->load->view('templates/footer');
		} else {

			if ($this->EntryModel->close_entry() == TRUE) {
				//session message
				$this->session->set_flashdata('cerrada', $this->getSaveCloseMessage());
				redirect(base_url() . 'entries/close/' . $id);
			} else {
				$this->session->set_flashdata('cerrada y Aceptado', 'Se ha cerrado satisfactoriamente la orden.');
				redirect(base_url() . 'reports/calidad');
			}
		}
	}


	public function getSaveCloseMessage()
	{
		$message = "";
		$final_result = $this->input->post('final_result');

		if ($final_result == FINAL_RESULT_DISCREPANCY) {
			$message = strtoupper("Existen discrepancias por subsanar, ahora mismo producción ya ha sido notificado a través del sistema");
		} else if ($final_result == FINAL_RESULT_REJECTED_BY_PRODUCT) {
			$message = strtoupper("El producto ha sizo rechazado y es necesario que producción formule una nueva orden");
		} else if ($final_result == FINAL_RESULT_WAITING) {
			$message = strtoupper("Se colocó en espera esta orden, retomela lo mas pronto posible en cuanto tenga oportunidad");
		} else if ($final_result == FINAL_RESULT_CLOSED) {
			$message = strtoupper("Se ha cerrado la orden con éxito");
		}

		return $message;
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


	function getActionName($progress, $status)
	{
		$btn_title = "";

		if ($progress == PROGRESS_NOT_ASSIGNED) {
			$btn_title = "ASIGNAR";
		} elseif ($progress == PROGRESS_ASSIGNED) {
			$btn_title = "RESULTADO DE INSPECCION";
		} elseif ($progress == PROGRESS_RELEASED) {
			if ($status == STATUS_WAITING || $status == STATUS_VERIFY || $status == STATUS_PACK) {
				$btn_title = "RESULTADO DE INSPECCION";
			} else {
				$btn_title = "RESULTADO DEL CIERRE";
			}
		} elseif ($progress == PROGRESS_CLOSED) {
			$btn_title = "RESULTADO DEL CIERRE";
		}
		return $btn_title;
	}


	function getActionLink($progress, $status, $id)
	{
		$link = "";

		if ($progress == PROGRESS_NOT_ASSIGNED) {
			$link = "entries/assign/{$id}";
		} elseif ($progress == PROGRESS_ASSIGNED) {
			$link = "entries/release/{$id}";
		} elseif ($progress == PROGRESS_RELEASED) {
			if ($status == STATUS_WAITING || $status == STATUS_VERIFY || $status == STATUS_PACK) {
				$link = "entries/release/{$id}";
			} else {
				$link = "entries/close/{$id}";
			}
		} elseif ($progress == PROGRESS_CLOSED) {
			$link = "entries/close/{$id}";
		}

		return $link;
	}

	function getActionColor($progress)
	{
		$color =  "";

		if ($progress == PROGRESS_NOT_ASSIGNED) {
			$color =  "bg-danger";
		} elseif ($progress == PROGRESS_ASSIGNED) {
			$color =  "bg-warning";
		} elseif ($progress == PROGRESS_RELEASED) {
			$color =  "bg-primary";
		} elseif ($progress == PROGRESS_CLOSED) {
			$color =  "bg-success disabled";
		}

		return $color;
	}


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
		TIMEDIFF(NOW(), created_at) as elapsed_time, asignada, has_urgency, location
		FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id 
		WHERE  1 ";

		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		$empQuery .= " AND (";
		$empQuery .= ' progress = ' . PROGRESS_NOT_ASSIGNED;
		$empQuery .= ' OR progress = ' . PROGRESS_ASSIGNED;
		$empQuery .= ' OR ((progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_ACCEPTED . ') OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_WAITING . ' ) OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_PACK . ' )  OR ( progress = ' . PROGRESS_RELEASED . ' AND status = ' . STATUS_VERIFY . ' ) )';
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



			$btn_title = $this->getActionName($row['progress'], $row['status']);
			$link = $this->getActionLink($row['progress'], $row['status'], $row['id']);

			$text = $this->getProgressName($row['progress']);
			$status = $this->getStatusText($row['progress'], $row['status'], $row['final_result']);
			$color = $this->getStatusColor($row['progress'], $row['status'], $row['final_result']);



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
				$urgency = "<span class='badge rounded-pill bg-danger'>URGENTE</span>";
			} else {
				$urgency = "<span class='badge rounded-pill bg-primary'>NORMAL</span>";
			}

			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"elapsed_time" => convert_time_string_to_float($row['elapsed_time']),
				"part_no" => strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => $row['qty'],
				"asignada" => $asignada,
				"planta" => strtoupper($row['plant']),
				"progress" => strtoupper("$text"),
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"btn_id" => $actions,
				"has_urgency" => $urgency,
				"location" => $row['location'],

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
		progress, status, final_result, discrepancia_descr, razon_rechazo, location  FROM entry_accepted INNER JOIN plantas ON entry_accepted.plant = plantas.planta_id WHERE  1 ";

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

			$status = $this->getStatusText($row['progress'], $row['status'], $row['final_result']);
			$color = $this->getStatusColor($row['progress'], $row['status'], $row['final_result']);

			if ($row['progress'] == PROGRESS_RELEASED) {
				$comments = $row['razon_rechazo'];
			} else if ($row['progress'] == PROGRESS_CLOSED) {
				$comments = $row['discrepancia_descr'];
			}

			$estimated =  round(convert_time_string_to_float($row['closed_elapsed_time']) - floatval($row['waiting_hours']) - floatval($row['rejected_doc_hours']), 2);

			$data[] = array(
				"id" => $row['id'],
				"part_no" => strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => $row['qty'],
				"plant" => strtoupper($row['plant']),
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"assigned_elapsed_time" => convert_time_string_to_float($row['assigned_elapsed_time']),
				"released_elapsed_time" =>  convert_time_string_to_float($row['released_elapsed_time']),
				"closed_elapsed_time" =>  convert_time_string_to_float($row['closed_elapsed_time']),
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail_accepted/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>',
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"comments" => $comments,
				"waiting_hours" => $row['waiting_hours'],
				"rejected_doc_hours" => $row['rejected_doc_hours'],
				"estimated" => $estimated,
				"location" => strtoupper($row['location']),
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}


	function getProgressName($progress)
	{
		$text = '';
		if ($progress == PROGRESS_NOT_ASSIGNED) {
			$text =  "0/3 EN ESPERA";
		} elseif ($progress == PROGRESS_ASSIGNED) {
			$text =  "1/3 ASGINADO";
		} elseif ($progress == PROGRESS_RELEASED) {
			$text =  "2/3 EN LIBERACION";
		} elseif ($progress == PROGRESS_CLOSED) {
			$text =  "3/3 EN CIERRE";
		}
		return $text;
	}

	function getStatusText($progress, $status, $final_result)
	{
		$status_str = '';

		if ($progress == PROGRESS_NOT_ASSIGNED) {
			$status_str = 'SIN ASIGNAR';
		} else if ($progress == PROGRESS_ASSIGNED) {
			$status_str = 'ASIGNADO';
		} else if ($progress == PROGRESS_RELEASED) {
			if ($status == STATUS_ACCEPTED) {
				$status_str = 'ACEPTADO';
			} else if ($status == STATUS_REJECTED_BY_PRODUCT) {
				$status_str = 'RECHAZO X PROD';
			} else if ($status == STATUS_DISCREPANCY) {
				$status_str = 'DISCREPANCIA';
			} else if ($status == STATUS_WAITING) {
				$status_str = 'EN ESPERA';
			} else if ($status == STATUS_VERIFY) {
				$status_str = 'DISCREPANCIA RESUELTA';
			} else if ($status == STATUS_PACK) {
				$status_str = 'EN PACK';
			}
		} else if ($progress == PROGRESS_CLOSED) {
			if ($final_result == FINAL_RESULT_CLOSED) {
				$status_str = 'CERRADO';
			} else if ($final_result == FINAL_RESULT_REJECTED_BY_PRODUCT) {
				$status_str = 'RECHAZO X PROD';
			} else if ($final_result == FINAL_RESULT_DISCREPANCY) {
				$status_str = 'DISCREPANCIA';
			} else if ($final_result == FINAL_RESULT_WAITING) {
				$status_str = 'EN ESPERA';
			} else if ($final_result == FINAL_RESULT_VERIFY) {
				$status_str = 'DISCREPANCIA RESUELTA';
			}
		}

		return $status_str;
	}

	function getStatusColor($progress, $status, $final_result)
	{
		$color = '';

		if ($progress == PROGRESS_NOT_ASSIGNED) {
			$color =  "bg-secondary";
		} else if ($progress == PROGRESS_ASSIGNED) {
			$color =  "bg-primary";
		} else if ($progress == PROGRESS_RELEASED) {
			if ($status == STATUS_ACCEPTED) {
				$color =  "bg-success disabled";
			} else if ($status == STATUS_REJECTED_BY_PRODUCT) {
				$color =  "bg-danger";
			} else if ($status == STATUS_DISCREPANCY) {
				$color =  "bg-danger";
			} else if ($status == STATUS_WAITING) {
				$color =  "bg-warning";
			} else if ($status == STATUS_VERIFY) {
				$color =  "bg-secondary";
			} else if ($status == STATUS_PACK) {
				$color =  "bg-warning";
			}
		} else if ($progress == PROGRESS_CLOSED) {
			if ($final_result == FINAL_RESULT_CLOSED) {
				$color =  "bg-success disabled";
			} else if ($final_result == FINAL_RESULT_REJECTED_BY_PRODUCT) {
				$color =  "bg-danger";
			} else if ($final_result == FINAL_RESULT_DISCREPANCY) {
				$color =  "bg-danger";
			} else if ($final_result == FINAL_RESULT_WAITING) {
				$color =  "bg-warning";
			} else if ($final_result == FINAL_RESULT_VERIFY) {
				$color =  "bg-secondary";
			}
		}

		return $color;
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

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, location  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

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

			$btn_title = $this->getActionName($row['progress'], $row['status']);
			$link = $this->getActionLink($row['progress'], $row['status'], $row['id']);
			$color = $this->getActionColor($row['progress']);


			$text = $this->getProgressName($row['progress']);

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT) {
				$action = '<td><a href="' . base_url() . 'entries/rework?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary">Retrabajar</a></td>';
			} else if ($row['final_result'] == FINAL_RESULT_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/rework?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary">Solucionado</a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => $row['qty'],
				"plant" => strtoupper($row['plant']),
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => strtoupper("$text"),
				"razon_rechazo" => strtoupper($row['razon_rechazo']),
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>',
				"action" => $action,
				"location" => strtoupper($row['location']),
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

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status, location  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

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

			$text = $this->getProgressName($row['progress']);

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_REJECTED_BY_PRODUCT || $row['status'] == STATUS_REJECTED_BY_PRODUCT) {
				$action = '<td><a href="' . base_url() . 'entries/rework?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> Nuevo Registro x retrabajo </a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => $row['qty'],
				"plant" => strtoupper($row['plant']),
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => strtoupper("$text"),
				"razon_rechazo" => strtoupper($row['razon_rechazo']),
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>',
				"action" => $action,
				"location" => strtoupper($row['location']),
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

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status, location FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";

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

			$text = $this->getProgressName($row['progress']);

			$action = '';

			if ($row['final_result'] == FINAL_RESULT_DISCREPANCY || $row['status'] == STATUS_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/solved?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> RESUELTO </a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" => strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => strtoupper($row['qty']),
				"plant" => strtoupper($row['plant']),
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => strtoupper("$text"),
				"razon_rechazo" => strtoupper($row['razon_rechazo']),
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>',
				"action" => $action,
				"qty" => strtoupper($row['location']),
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

		$empQuery = "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status, 0 as accepted, location  FROM entry INNER JOIN plantas ON entry.plant = plantas.planta_id WHERE  1 ";
		$plant_id = $this->session->userdata(PLANT_ID);
		if ($plant_id > 0) {
			$empQuery .= " AND plant = " . $plant_id;
		}

		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}

		$empQuery .= " UNION ";

		$empQuery .= "SELECT id, progress, part_no, lot_no, qty, plantas.planta_nombre as plant, created_at, progress, IF(progress = 2, razon_rechazo , discrepancia_descr) as razon_rechazo, to_rework, final_result, status, 1 as accepted, location FROM entry_accepted INNER JOIN plantas ON entry_accepted.plant = plantas.planta_id WHERE  1 ";
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


			$text = $this->getProgressName($row['progress']);
			$status = $this->getStatusText($row['progress'], $row['status'], $row['final_result']);
			$color = $this->getStatusColor($row['progress'], $row['status'], $row['final_result']);


			$action = '';

			if ($row['final_result'] == FINAL_RESULT_DISCREPANCY || $row['status'] == STATUS_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/solved?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> RESUELTO </a></td>';
			}

			$detail =  '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>';
			if ($row['accepted'] == 1) {
				$detail =  '<td><a href="' . base_url() . 'reports/detail_accepted/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>';
			}

			$id = '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>';
			if ($row['accepted'] == 1) {
				$id = '<a href="' . base_url() . 'reports/detail_accepted/' . $row['id'] . '" >' . $row['id'] . '</a>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => $id,
				"part_no" => strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => $row['qty'],
				"plant" => strtoupper($row['plant']),
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => strtoupper("$text"),
				"razon_rechazo" => strtoupper($row['razon_rechazo']),
				"entry_id" => $detail,
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"action" => $action,
				"location" => strtoupper($row['location']),
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


		/*
		$empQuery .= " AND ( 
		(progress = " . PROGRESS_RELEASED . " AND  status = " . STATUS_REJECTED_BY_PRODUCT . ")  
		OR (progress = " . PROGRESS_RELEASED . " AND  status = " . STATUS_DISCREPANCY . ")
		OR (progress = " . PROGRESS_CLOSED . " AND  final_result = " . FINAL_RESULT_REJECTED_BY_PRODUCT . ")
		OR (progress = " . PROGRESS_CLOSED . " AND  final_result = " . FINAL_RESULT_DISCREPANCY . ")
		)";
			*/

		$empQuery .= " AND ( 
			(progress = " . PROGRESS_RELEASED . " AND  status = " . STATUS_REJECTED_BY_PRODUCT . ")  	
			OR (progress = " . PROGRESS_CLOSED . " AND  final_result = " . FINAL_RESULT_REJECTED_BY_PRODUCT . ")
			)";


		if (!($start_date == '' &&  $end_date == '')) {
			$empQuery .= " AND created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'";
		}


		$empRecords = $this->db->query($empQuery)->result_array();

		$data = array();

		foreach ($empRecords as $row) {


			$text = $this->getProgressName($row['progress']);
			$status = $this->getStatusText($row['progress'], $row['status'], $row['final_result']);
			$color = $this->getStatusColor($row['progress'], $row['status'], $row['final_result']);
			$action = '';

			if ($row['final_result'] == FINAL_RESULT_DISCREPANCY || $row['status'] == STATUS_DISCREPANCY) {
				$action = '<td><a href="' . base_url() . 'entries/solved?from=' . $row['id'] . '&reload_route=' . $reload_route . '&start_date=' . $start_date_route . '&end_date=' . $end_date_route . '" class="btn btn-primary"> RESUELTO </a></td>';
			}

			//	"progress" => "<h4><span class='badge $color'>$text</span></h4>",
			$data[] = array(
				"id" => '<a href="' . base_url() . 'reports/detail/' . $row['id'] . '" >' . $row['id'] . '</a>',
				"part_no" =>  strtoupper($row['part_no']),
				"lot_no" => strtoupper($row['lot_no']),
				"qty" => $row['qty'],
				"plant" => strtoupper($row['plant']),
				"created_at" => date_format(new DateTime($row['created_at']), 'm/d/y g:i A'),
				"progress" => strtoupper("$text"),
				"razon_rechazo" => strtoupper($row['razon_rechazo']),
				"entry_id" => '<td><a href="' . base_url() . 'reports/detail/' . $row['id'] . '" class="btn btn-primary">DETALLE</a></td>',
				"status" => "<h4><span class='badge rounded-pill $color'>$status</span></h4>",
				"action" => $action
			);
		}

		$response['data'] = $data;

		echo json_encode($response);
	}
}
