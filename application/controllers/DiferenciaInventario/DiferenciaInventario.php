<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiferenciaInventario extends CI_Controller{

	public function __construct() {
        parent:: __construct();

        $this->load->model("DiferenciaInventario/DiferenciaInventario_model");
        $this->load->library('session');
    }
	public function index()
	{
		if($this->session->has_userdata('nombre')){
			$modulos = $this->session->userdata('modulos');
			$count = 0;
			foreach ($modulos as $key) {
				if($key->MENU_ID == '17'){
					$this->load->view('DiferenciaInventario/DiferenciaInventario');
					break;
				}
			}
	        
	    }else{
	        redirect('', 'refresh');
	    }
	}

	public function read(){
		echo $this->DiferenciaInventario_model->read();
	}

	public function detalleDiffPMM(){
		$fecha = date("d/m/Y", strtotime(str_replace("-", "/", $this->input->post('fecha'))));
		echo $this->DiferenciaInventario_model->detalleDiffPMM($fecha);
	}
	public function detalleDiffWMS(){
		$fecha = date("d/m/Y", strtotime(str_replace("-", "/", $this->input->post('fecha'))));
		echo $this->DiferenciaInventario_model->detalleDiffWMS($fecha);
	}
}