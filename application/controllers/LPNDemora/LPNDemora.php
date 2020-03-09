<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LPNDemora extends CI_Controller{

	public function __construct(){

		parent::__construct();
		$this->load->model("LPNDemora/LPNDemora_model");
		$this->load->library('session');
	}

	public function index(){
		if($this->session->has_userdata('nombre')){
	        $this->load->view('LPNDemora/LPNDemora');
	    }else{
	        redirect('', 'refresh');
	    }
	}
	public function totalDemoraFecha(){
		echo $this->LPNDemora_model->totalDemoraFecha();
	}
	public function resumenDemoraFecha(){
		$fecha = $this->input->post('fecha');
		echo $this->LPNDemora_model->resumenDemoraFecha($fecha);
	}

}