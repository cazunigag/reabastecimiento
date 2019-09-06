<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CentroAlertas extends CI_Controller{

	public function __construct() {
		parent:: __construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('Centroalertas_model');
		$this->load->library('form_validation');
	}

	public function index(){
		$this->load->view('CentroAlertas/CentroAlertas');
	}
	public function erroresPKT(){
		echo $this->Centroalertas_model->erroresPKT();
	}
	public function cantErroresPKT(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresPKT()));
	}
	public function totPKTBajados(){
		echo $this->Centroalertas_model->totPKTBajados();
	}
	public function resumenPKT(){
		echo $this->Centroalertas_model->resumenPKT();
	}
	public function cantErroresPO(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresPO()));
	}
	public function erroresPO(){
		echo $this->Centroalertas_model->erroresPO();
	}
	public function totPOBajados(){
		echo $this->Centroalertas_model->totPOBajados();
	}
	public function erroresBRCD(){
		echo $this->Centroalertas_model->erroresBRCD();
	}
	public function cantErroresBRCD(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresBRCD()));
	}
	public function totBRCDBajados(){
		echo $this->Centroalertas_model->totBRCDBajados();
	}
	public function erroresART(){
		echo $this->Centroalertas_model->erroresART();
	}
	public function cantErroresART(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresART()));
	}
	public function totoARTMod(){
		echo $this->Centroalertas_model->totoARTMod();
	}
	public function reprocesarPKT(){
		$pkts = json_decode($this->input->post('pkts'));
		echo $this->Centroalertas_model->reprocesarPKT($pkts);
	}
	public function eliminarPKT(){
		$pkts = json_decode($this->input->post('pkts'));
		echo $this->Centroalertas_model->eliminarPKT($pkts);
	}
	public function reprocesarPO(){
		$pos = json_decode($this->input->post('pos'));
		echo $this->Centroalertas_model->reprocesarPO($pos);
	}
	public function eliminarPO(){
		$pos = json_decode($this->input->post('pos'));
		echo $this->Centroalertas_model->eliminarPO($pos);
	}
	public function reprocesarBRCD(){
		$brcds = json_decode($this->input->post('brcds'));
		echo $this->Centroalertas_model->reprocesarBRCD($brcds);
	}
	public function eliminarBRCD(){
		$brcds = json_decode($this->input->post('brcds'));
		echo $this->Centroalertas_model->eliminarBRCD($brcds);
	}
	public function reprocesarART(){
		$arts = json_decode($this->input->post('arts'));
		echo $this->Centroalertas_model->reprocesarART($arts);
	}
	public function eliminarART(){
		$arts = json_decode($this->input->post('arts'));
		echo $this->Centroalertas_model->eliminarART($arts);
	}
	public function resumenOLA(){
		echo $this->Centroalertas_model->resumenOLA();
	}
	public function totOLA(){
		echo $this->Centroalertas_model->totOLA();
	}
	public function erroresOLA(){
		echo $this->Centroalertas_model->erroresOLA();
	}
	public function cantErroresOLA(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresOLA()));
	}
	public function erroresCITA(){
		echo $this->Centroalertas_model->erroresCITA();
	}
	public function cantErroresCITA(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresCITA()));
	}
	public function totCITASBajadas(){
		echo $this->Centroalertas_model->totCITASBajadas();
	}
	public function resumenCITA(){
		echo $this->Centroalertas_model->resumenCITA();
	}
	public function detCodCITA(){
		$codigo = $this->input->post('codigo');
		echo $this->Centroalertas_model->detCodCITA($codigo);
	}
}