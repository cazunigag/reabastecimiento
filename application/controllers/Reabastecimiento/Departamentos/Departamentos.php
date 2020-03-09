<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Departamentos extends CI_Controller{

	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("Reabastecimiento/Departamentos/Departamentos_model");
        $this->load->library("pagination");
        $this->load->library('session');
    }
	public function index()
	{
		if($this->session->has_userdata('nombre')){
	        $data['deptos'] = $this->Departamentos_model->listDepartamentos();
			$this->load->view('Reabastecimiento/Departamentos/Departamentos', $data);
	    }else{
	       redirect('', 'refresh');
	    }
	}
	public function selectDepto(){
		$depto = $this->input->post('depto');
		echo $this->Departamentos_model->selectDepto($depto);
	}
	public function pasillosPutwy(){
		$data = json_decode($this->input->post('data'));
		echo $this->Departamentos_model->pasillosPutwy($data);
	}
	public function availableLocn(){
		$pasillo = $this->input->post('aisle');
		echo $this->Departamentos_model->availableLocn($pasillo);
	}
	public function configurar(){
		$data = json_decode($this->input->post('data'));
		echo $this->Departamentos_model->configurar($data);
	}
}