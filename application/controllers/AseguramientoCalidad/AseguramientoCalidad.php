<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AseguramientoCalidad extends CI_Controller{

	public function __construct() {
		parent:: __construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('AseguramientoCalidad/AseguramientoCalidad');
        }else{
            redirect('', 'refresh');
        }
		
	}
	public function recepcion(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('AseguramientoCalidad/Recepcion/Recepcion');
        }else{
            redirect('', 'refresh');
        }
	}
}