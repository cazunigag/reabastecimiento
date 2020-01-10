<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asignacion_pedido extends CI_Controller{

	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("asignacionpedido_model");
        $this->load->library("pagination");
    }
	public function index()
	{
		$this->load->view('asignacionpedido');
	}

	public function getAsignaciones(){
        echo $this->asignacionpedido_model->getAsignaciones();
	}
	public function getSku(){
		sh2_connect('10.0.150.13', 23);
        //echo $this->asignacionpedido_model->getSku();
	}
	public function actualizarSKUS(){
		ssh2_connect('10.0.150.13', 23);

	}
	//10.0.150.13  user rpyop  pass xxx 
}