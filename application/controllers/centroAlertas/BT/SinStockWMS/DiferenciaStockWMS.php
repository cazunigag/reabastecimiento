<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiferenciaStockWMS extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->model('CentroAlertas/BT/SinStockWMS/DiferenciaStockWMS_model');
		$this->load->library('form_validation');
	}

	public function index(){
		$this->load->view('CentroAlertas/BT/SinStockWMS/DiferenciaStockWMS');
	}

	public function read(){
		echo $this->DiferenciaStockWMS_model->read();
	}

	public function detalleBloqueo(){
		$sku = $this->input->post('sku');
		echo $this->DiferenciaStockWMS_model->detalleBloqueo($sku);
	}
}