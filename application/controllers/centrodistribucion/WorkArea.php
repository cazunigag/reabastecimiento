<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WorkArea extends CI_Controller{


	public function __construct() {
        parent:: __construct();
        $this->load->helper("url");
        $this->load->model("CentroDistribucion/WorkArea_model");
        $this->load->library("pagination");
        $this->load->library('session');
    }

    public function index(){
    	$this->load->view('CDSource/WorkAreaPasillo');
    }

    public function gridWorkArea(){
    	echo json_encode($this->WorkArea_model->gridWorkArea());
    }

    public function listWorkArea(){
    	$workgroup = $this->input->post('workgroup');
    	echo json_encode($this->WorkArea_model->listWorkArea($workgroup));
    }

    public function listWorkGroup(){
    	echo json_encode($this->WorkArea_model->listWorkGroup());
    }

    public function actualizarWorkArea(){
    	$workArea = $this->input->post('workarea');
    	$workGroup = $this->input->post('workgroup');
    	$pasillo = $this->input->post('pasillo');
    	echo json_encode($this->WorkArea_model->actualizarWorkArea($pasillo, $workArea, $workGroup));
    }
}

