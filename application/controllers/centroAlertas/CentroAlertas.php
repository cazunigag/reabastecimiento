<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CentroAlertas extends CI_Controller{

	public function __construct() {
		parent:: __construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
	}

	public function index(){
		$this->load->view('CentroAlertas/CentroAlertas');
	}
	public function alertasWMS(){
		$this->load->view('CentroAlertas/WMS/AlertasWMS');
	}
	public function alertasBT(){
		$this->load->view('CentroAlertas/BT/AlertasBT');
	}
	public function alertasPMM(){
		$this->load->view('CentroAlertas/PMM/AlertasPMM');
	}
	public function alertasEIS(){
		$this->load->view('CentroAlertas/EIS/AlertasEIS');
	}
	
}