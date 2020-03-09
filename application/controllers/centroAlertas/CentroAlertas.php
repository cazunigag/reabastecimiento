<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CentroAlertas extends CI_Controller{

	public function __construct() {
		parent:: __construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('CentroAlertas/CentroAlertas');
        }else{
            redirect('', 'refresh');
        }
		
	}
	public function alertasWMS(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('CentroAlertas/WMS/AlertasWMS');
        }else{
            redirect('', 'refresh');
        }
	}
	public function alertasBT(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('CentroAlertas/BT/AlertasBT');
        }else{
            redirect('', 'refresh');
        }
	}
	public function alertasPMM(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('CentroAlertas/PMM/AlertasPMM');
        }else{
            redirect('', 'refresh');
        }
	}
	public function alertasEIS(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('CentroAlertas/EIS/AlertasEIS');
        }else{
            redirect('', 'refresh');
        }
	}
	
}