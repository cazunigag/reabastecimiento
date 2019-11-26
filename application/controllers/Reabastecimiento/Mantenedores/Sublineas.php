<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sublineas extends CI_Controller{

	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("Reabastecimiento/Mantenedores/Sublineas_model");
        $this->load->library("pagination");
    }
	public function index()
	{
		$this->load->view('Reabastecimiento/Mantenedores/Sublineas');
	}
	public function obtenerTabla(){
		echo $this->Sublineas_model->obtenerTabla();
	}
	public function guardarCambios(){
		$changes = json_decode($this->input->post('changes'));
		echo $this->Sublineas_model->guardarCambios($changes);
	}
}