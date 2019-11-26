<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CartonType extends CI_Controller{


	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("CentroDistribucion/CartonType_model");
        $this->load->library("pagination");
    }
	public function index(){
		$this->load->view('CDSource/CartonType');
	}
	public function dataGrid1(){
		echo $this->CartonType_model->dataGrid1();
	}
}