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
        echo $this->asignacionpedido_model->getSku();
	}
	public function actualizarSKUS(){
		$tempData = $this->input->post('skus');
        $json = json_decode($tempData, true);
        $resp = $this->asignacionpedido_model->actualizarSKUS($json);
        echo json_encode($resp);

	}
}