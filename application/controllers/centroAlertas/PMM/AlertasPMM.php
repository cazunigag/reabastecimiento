<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlertasPMM extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('CentroAlertas/PMM/alertasPMM_model');
		$this->load->library('form_validation');
	}
	public function cantDifPMMWMS(){
		echo sizeof(json_decode($this->alertasPMM_model->diferenciasPMMWMS()));
	}
	public function diferenciasPMMWMS(){
		echo $this->alertasPMM_model->diferenciasPMMWMS();
	}
}