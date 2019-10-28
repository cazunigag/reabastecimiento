<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LPNDemora extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->model("LPNDemora/LPNDemora_model");
	}

	public function index(){
		$this->load->view('LPNDemora/LPNDemora');
	}
	public function totalDemoraFecha(){
		echo $this->LPNDemora_model->totalDemoraFecha();
	}
	public function resumenDemoraFecha(){
		$fecha = $this->input->post('fecha');
		echo $this->LPNDemora_model->resumenDemoraFecha($fecha);
	}

}