<?php

class Entries extends CI_Controller{

	public function create()
	{
		$data['parts'] = $this->EntryModel->get_parts();

		$this->form_validation->set_rules('part_no', 'Numero de parte', 'required');
		$this->form_validation->set_rules('lot_no', 'Numero de lote', 'required');
		$this->form_validation->set_rules('qty', 'Cantidad', 'required');
		$this->form_validation->set_rules('plant', 'Planta', 'required');

		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header');
			$this->load->view('entries/create', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->EntryModel->create_entry();

			//session message
			$this->session->set_flashdata('created', 'Se ha guardado la orden y enviada a inspeccion.');

			redirect(base_url() . 'entries/create');
		}
	}







	public function assign($id = NULL)
	{
		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->EntryModel->get_locations();
		$data['users'] = $this->UserModel->get_users_quality();


		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('asignada', 'Usuario', 'required');


		if($this->form_validation->run() === FALSE)
		{

			$this->load->view('templates/header');
			$this->load->view('entries/asign', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->EntryModel->assign_entry();

			//session message
			$this->session->set_flashdata('entrada_asignado', 'Se ha asignado la orden.');

			redirect(base_url() . 'entries/assign/' . $id );
		}
	}





	public function release($id = NULL)
	{
		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->EntryModel->get_locations();


		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('final_qty', 'Cantidad final', 'required');
		$this->form_validation->set_rules('wo_escaneadas', 'Work orders escaneadas', 'required');
		$this->form_validation->set_rules('location', 'Locacion', 'required');
		$this->form_validation->set_rules('fecha_exp', 'Fecha de expiracion', 'required');
		$this->form_validation->set_rules('rev_dibujo', 'Revision de dibujo', 'required');
		$this->form_validation->set_rules('empaque', 'Empaque', 'required');
		$this->form_validation->set_rules('documentos_rev', 'Documentos revisados', 'required');


		if($this->form_validation->run() === FALSE)
		{

			$this->load->view('templates/header');
			$this->load->view('entries/release', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->EntryModel->release_entry();

			//session message
			$this->session->set_flashdata('entrada_liberada', 'Se ha liberado la entrada.');

			redirect(base_url() . 'entries/release/' . $id );
		}
	}







	public function close($id = NULL)
	{
		$data['entry'] = $this->EntryModel->get_single_entry($id);
		$data['locations'] = $this->EntryModel->get_locations();
		$data['users'] = $this->UserModel->get_users_quality();


		$this->form_validation->set_rules('id', 'ID o Folio', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('final_qty', 'Cantidad final', 'required');
		$this->form_validation->set_rules('wo_escaneadas', 'Work orders escaneadas', 'required');
		$this->form_validation->set_rules('fecha_exp', 'Fecha de expiracion', 'required');
		$this->form_validation->set_rules('rev_dibujo', 'Revision de dibujo', 'required');
		$this->form_validation->set_rules('empaque', 'Empaque', 'required');
		$this->form_validation->set_rules('documentos_rev', 'Documentos revisados', 'required');


		if($this->form_validation->run() === FALSE)
		{

			$this->load->view('templates/header');
			$this->load->view('entries/close', $data);
			$this->load->view('templates/footer');
		}
		else
		{

			$this->EntryModel->release_entry();

			//session message
			$this->session->set_flashdata('entrada_liberada', 'Se ha liberado la entrada.');

			redirect(base_url() . 'entries/release/' . $id );
		}
	}



}
