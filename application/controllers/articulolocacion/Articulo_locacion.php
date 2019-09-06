<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articulo_locacion extends CI_Controller{

	

	public function index()
	{
		$this->load->helper('url');
		$this->load->model('artlocacion_model');
		$this->load->view('articulolocacion');
	}
	public function insertarArtLocacion(){
        $tempData = $this->input->post('models');
        $json = json_decode($tempData, true);
        $this->load->model('artlocacion_model');
        $resp = $this->artlocacion_model->insertarArtLocacion($json);
        echo json_encode($resp);

    }
    public function readArtLocacion(){
    	$this->load->model('artlocacion_model');
        echo $this->artlocacion_model->readArtLocacion();
    }
    public function filtrarDatos(){
        $fecIni = $this->input->post('fecIni');
        $fecFin = $this->input->post('fecFin');
        $this->load->model('artlocacion_model');
        echo $this->artlocacion_model->filtrarDatos($fecIni, $fecFin);
        

    }
}