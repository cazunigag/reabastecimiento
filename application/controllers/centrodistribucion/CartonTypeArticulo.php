<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartonTypeArticulo extends CI_Controller{


	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("CentroDistribucion/CartonType_model");
        $this->load->library("pagination");
        $this->load->library('session');
    }
	public function index(){
		if($this->session->has_userdata('nombre')){
            $this->load->view('CDSource/CartonTypeArticulo');
        }else{
            redirect('', 'refresh');
        }
	}
	public function dataGrid1(){
		echo $this->CartonType_model->dataGrid1();
	}
}