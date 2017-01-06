<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Home_Controller {

	public function index()
	{
		$this->load->view('header.inc.html');
		$this->load->view('hospital.html');
		$this->load->view('footer.inc.html');
	}


}
