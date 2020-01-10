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
	public function aisles(){
		echo $this->AlmacenamientoLocn_model->aisles();
	}
	public function putwy_types(){
		echo $this->AlmacenamientoLocn_model->putwy_types();
	}
	public function locn_class(){
		echo $this->AlmacenamientoLocn_model->locn_class();
	}
	public function update(){
		$updated = json_decode($this->input->post('updated'));
		$old = json_decode($this->input->post('old'));
		echo $this->AlmacenamientoLocn_model->update($updated, $old);
	}
	public function create(){
		$created = json_decode($this->input->post('created'));
		echo $this->AlmacenamientoLocn_model->create($created);
	}
	public function delete(){
		$destroyed = json_decode($this->input->post('destroyed'));
		echo $this->AlmacenamientoLocn_model->delete($destroyed);
	}
}