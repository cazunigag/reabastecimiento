<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SeteoAtributos extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('SeteoAtributos/Seteoatributos_model');
	}

	public function index(){
		$this->load->view('SeteoAtributos/SeteoAtributos');
	}
	public function infoSku(){
		$sku = $this->input->post('sku');
		echo $this->Seteoatributos_model->infoSku($sku);
	}
	public function cboCartonType(){
		echo $this->Seteoatributos_model->cboCartonType();
	}
}