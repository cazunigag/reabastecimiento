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
	public function JiraAPI(){
		$base64_usrpsw = base64_encode("jasilva@ripley.com:tono1963");
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://ripleycl.atlassian.net/rest/api/2/issue/createmeta');
		//curl_setopt($ch, CURLOPT_URL, 'http://localhost:8080/rest/api/2/issue/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		/*curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$base64_usrpsw));

		$arr['project'] = array('id' => '10210');
		$arr['summary'] = "TEST";
		$arr['description'] = "TEST TEST";
		$arr['issuetype'] = array('name' => "Tarea");

		$json_arr['fields'] = $arr;

		$json_string = json_encode($json_arr);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);*/
		$result = curl_exec($ch);
		curl_close($ch);
		var_dump($result);
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
		$codigo = $this->input->post('codigoCITA');
		echo $this->Centroalertas_model->detCodCITA($codigo);
	}
	public function reprocesarCITA(){
		$citas = json_decode($this->input->post('citas'));
		echo $this->Centroalertas_model->reprocesarCITA($citas);
	}
	public function eliminarCITA(){
		$citas = json_decode($this->input->post('citas'));
		echo $this->Centroalertas_model->eliminarCITA($citas);
	}
	public function erroresASN(){
		echo $this->Centroalertas_model->erroresASN();
	}
	public function cantErroresASN(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresASN()));
	}
	public function totASNBajados(){
		echo $this->Centroalertas_model->totASNBajados();
	}
	public function resumenASN(){
		echo $this->Centroalertas_model->resumenASN();
	}
	public function detCodASN(){
		$codigo = $this->input->post('codigoASN');
		echo $this->Centroalertas_model->detCodASN($codigo);
	}
	public function eliminarASN(){
		$asns = json_decode($this->input->post('asns'));
		echo $this->Centroalertas_model->eliminarASN($asns);
	}
	public function reprocesarASN(){
		$asns = json_decode($this->input->post('asns'));
		echo $this->Centroalertas_model->reprocesarASN($asns);
	}
	public function erroresLPN(){
		echo $this->Centroalertas_model->erroresLPN();
	}
	public function cantErroresLPN(){
		echo sizeof(json_decode($this->Centroalertas_model->erroresLPN()));
	}
	public function totLPNBajados(){
		echo $this->Centroalertas_model->totLPNBajados();
	}
	public function resumenLPN(){
		echo $this->Centroalertas_model->resumenLPN();
	}
	public function reprocesarLPN(){
		$lpns = json_decode($this->input->post('lpns'));
		echo $this->Centroalertas_model->reprocesarLPN($lpns);
	}
	public function eliminarLPN(){
		$lpns = json_decode($this->input->post('lpns'));
		echo $this->Centroalertas_model->eliminarLPN($lpns);
	}
}