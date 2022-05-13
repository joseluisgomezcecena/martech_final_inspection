<?php

class Pages extends CI_Controller
{
	public function view($page = 'home')
	{
		if(!file_exists(APPPATH . 'views/pages/' . $page . '.php'))
		{
			show_404();
		}

		$data['title'] = ucfirst($page);

		$data['entries'] = $this->EntryModel->get_pending();



		//load header, page & footer
		$this->load->view('templates/header');
		$this->load->view('pages/' . $page, $data); //loading page and data
		$this->load->view('templates/footer');
	}
}
