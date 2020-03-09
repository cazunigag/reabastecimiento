<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalendarioPKT extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('CentroAlertas/BT/CalendarioPKT/CalendarioPKT_model');
		$this->load->library('form_validation');
	}

	public function index(){
		$this->load->view('CentroAlertas/BT/CalendarioPKT/CalendarioPKT');
	}

	public function calendar(){
		echo $this->CalendarioPKT_model->calendar();
	}

	public function EstadosPKT(){
		$fecha = $this->input->post('fecha');
		echo $this->CalendarioPKT_model->EstadosPKT($fecha);
	}

	public function DetallePKT(){
		$fecha = $this->input->post('fecha');
		$codigo = $this->input->post('codigo');
		echo $this->CalendarioPKT_model->DetallePKT($fecha, $codigo);
	}
}