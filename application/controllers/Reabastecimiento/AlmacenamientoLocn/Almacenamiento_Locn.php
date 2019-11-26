<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Almacenamiento_Locn extends CI_Controller{

	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("Reabastecimiento/Almacenamiento_Locn/AlmacenamientoLocn_model");
        $this->load->library("pagination");
    }
	public function index()
	{
		$this->load->view('Reabastecimiento/AlmacenamientoLocn/AlmacenamientoLocn');
	}
	public function info(){
		echo $this->AlmacenamientoLocn_model->info();
	}
}