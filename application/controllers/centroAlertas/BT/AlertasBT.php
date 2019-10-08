<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlertasBT extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('CentroAlertas/BT/alertasBT_model');
		$this->load->library('form_validation');
	}
	public function sinProcesarSDI(){
		echo $this->alertasBT_model->sinProcesarSDI();
	}
	public function cantSinProcesarSDI(){
		$value = 0;
		$cantidad = json_decode($this->alertasBT_model->sinProcesarSDI());
		foreach ($cantidad as $key){
			if($key->CANTIDAD >= 1){

				break;
				$value = 1;
			}
		}
		echo $value;
	}
	public function malEnviadosBT(){
		echo $this->alertasBT_model->malEnviadosBT();
	}
	public function cantMalEnviadosBT(){
		echo sizeof(json_decode($this->alertasBT_model->malEnviadosBT()));
	}
	public function pickTicketDuplicados(){
		echo $this->alertasBT_model->pickTicketDuplicados();
	}
	public function cantPickTicketDuplicados(){
		echo $this->alertasBT_model->cantPickTicketDuplicados();
	}
}