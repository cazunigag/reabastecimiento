<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Depto308 extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('CentroAlertas/BT/alertasBT_model');
		$this->load->library('form_validation');
	}
	public function index(){
		$this->load->view('Depto308/Seteo308');
	}
}