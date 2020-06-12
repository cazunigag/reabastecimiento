<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PKTCancelados extends CI_Controller{

	public function __construct() {
        parent:: __construct();

        $this->load->model("DiferenciaInventario/PKTCancelados/PKTCancelados_model");
        $this->load->library('session');
    }
	public function index()
	{
		if($this->session->has_userdata('nombre')){
	
			$this->load->view('DiferenciaInventario/PKTCancelados/PKTCancelados');
		}
	}

	public function read(){
		echo $this->PKTCancelados_model->read();
	}

	public function detalle(){
		$fecha = str_replace('"', '', $this->input->post('fecha'));
		echo $this->PKTCancelados_model->detalle($fecha);
	}
}