<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recepcion extends CI_Controller{

	public function __construct() {
		parent:: __construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->model('AseguramientoCalidad/Recepcion/Recepcion_model');
	}
	public function read(){
		echo $this->Recepcion_model->read();
	}

	public function detalle(){
		$asns = json_decode($this->input->post('data'));
		echo $this->Recepcion_model->detalle($asns);
	}

	public function reprocesar(){
		$asns = json_decode($this->input->post('data'));
		echo $this->Recepcion_model->reprocesar($asns);
	}
	public function detalleErrInterfaz(){
		$asns = json_decode($this->input->post('data'));
		echo $this->Recepcion_model->detalleErrInterfaz($asns);
	}
}