<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlertasEIS extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('CentroAlertas/EIS/alertasEIS_model');
	}

	public function msgEIS(){
		echo $this->alertasEIS_model->msgEIS();
	}
	public function cantErrEIS(){
		$mensajes = json_decode($this->alertasEIS_model->msgEIS());
		$errores = 0;
		foreach ($mensajes as $key) {
			if($key->ESTADO == 'FALLIDO'){
				$errores += $key->TOTAL_MSG;
			}
		}
		echo $errores;
	}
	public function resumenEIS(){
		$endpoint = $this->input->post('endpoint');
		echo $this->alertasEIS_model->resumenEIS($endpoint);
	}
}