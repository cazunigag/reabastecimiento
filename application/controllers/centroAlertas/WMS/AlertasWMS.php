<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlertasWMS extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('CentroAlertas/WMS/alertasWMS_model');
		$this->load->library('form_validation');
	}
	public function JiraAPI(){
		$base64_usrpsw = base64_encode("jasilva@ripley.com:tono1963");
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://ripleycl.atlassian.net/rest/api/2/issue/');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Basic '.$base64_usrpsw));

		$arr['project'] = array('key' => 'PBW');
		$arr['summary'] = "TEST";
		$arr['description'] = "TEST TEST";
		$arr['issuetype'] = array('name' => "Tarea");

		$json_arr['fields'] = $arr;

		$json_string = json_encode($json_arr);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_string);
		$result = curl_exec($ch);
		curl_close($ch);
		var_dump($result);
	}
	public function erroresPKT(){
		echo $this->alertasWMS_model->erroresPKT();
	}
	public function cantErroresPKT(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresPKT()));
	}
	public function totPKTBajados(){
		echo $this->alertasWMS_model->totPKTBajados();
	}
	public function resumenPKT(){
		echo $this->alertasWMS_model->resumenPKT();
	}
	public function cantErroresPO(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresPO()));
	}
	public function erroresPO(){
		echo $this->alertasWMS_model->erroresPO();
	}
	public function totPOBajados(){
		echo $this->alertasWMS_model->totPOBajados();
	}
	public function erroresBRCD(){
		echo $this->alertasWMS_model->erroresBRCD();
	}
	public function cantErroresBRCD(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresBRCD()));
	}
	public function totBRCDBajados(){
		echo $this->alertasWMS_model->totBRCDBajados();
	}
	public function erroresART(){
		echo $this->alertasWMS_model->erroresART();
	}
	public function cantErroresART(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresART()));
	}
	public function totoARTMod(){
		echo $this->alertasWMS_model->totoARTMod();
	}
	public function reprocesarPKT(){
		$pkts = json_decode($this->input->post('pkts'));
		echo $this->alertasWMS_model->reprocesarPKT($pkts);
	}
	public function eliminarPKT(){
		$pkts = json_decode($this->input->post('pkts'));
		echo $this->alertasWMS_model->eliminarPKT($pkts);
	}
	public function reprocesarPO(){
		$pos = json_decode($this->input->post('pos'));
		echo $this->alertasWMS_model->reprocesarPO($pos);
	}
	public function eliminarPO(){
		$pos = json_decode($this->input->post('pos'));
		echo $this->alertasWMS_model->eliminarPO($pos);
	}
	public function reprocesarBRCD(){
		$brcds = json_decode($this->input->post('brcds'));
		echo $this->alertasWMS_model->reprocesarBRCD($brcds);
	}
	public function eliminarBRCD(){
		$brcds = json_decode($this->input->post('brcds'));
		echo $this->alertasWMS_model->eliminarBRCD($brcds);
	}
	public function reprocesarART(){
		$arts = json_decode($this->input->post('arts'));
		echo $this->alertasWMS_model->reprocesarART($arts);
	}
	public function eliminarART(){
		$arts = json_decode($this->input->post('arts'));
		echo $this->alertasWMS_model->eliminarART($arts);
	}
	public function resumenOLA(){
		echo $this->alertasWMS_model->resumenOLA();
	}
	public function totOLA(){
		echo $this->alertasWMS_model->totOLA();
	}
	public function erroresOLA(){
		echo $this->alertasWMS_model->erroresOLA();
	}
	public function cantErroresOLA(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresOLA()));
	}
	public function erroresCITA(){
		echo $this->alertasWMS_model->erroresCITA();
	}
	public function cantErroresCITA(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresCITA()));
	}
	public function totCITASBajadas(){
		echo $this->alertasWMS_model->totCITASBajadas();
	}
	public function resumenCITA(){
		echo $this->alertasWMS_model->resumenCITA();
	}
	public function detCodCITA(){
		$codigo = $this->input->post('codigoCITA');
		echo $this->alertasWMS_model->detCodCITA($codigo);
	}
	public function reprocesarCITA(){
		$citas = json_decode($this->input->post('citas'));
		echo $this->alertasWMS_model->reprocesarCITA($citas);
	}
	public function eliminarCITA(){
		$citas = json_decode($this->input->post('citas'));
		echo $this->alertasWMS_model->eliminarCITA($citas);
	}
	public function erroresASN(){
		echo $this->alertasWMS_model->erroresASN();
	}
	public function cantErroresASN(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresASN()));
	}
	public function totASNBajados(){
		echo $this->alertasWMS_model->totASNBajados();
	}
	public function resumenASN(){
		echo $this->alertasWMS_model->resumenASN();
	}
	public function detCodASN(){
		$codigo = $this->input->post('codigoASN');
		echo $this->alertasWMS_model->detCodASN($codigo);
	}
	public function eliminarASN(){
		$asns = json_decode($this->input->post('asns'));
		echo $this->alertasWMS_model->eliminarASN($asns);
	}
	public function reprocesarASN(){
		$asns = json_decode($this->input->post('asns'));
		echo $this->alertasWMS_model->reprocesarASN($asns);
	}
	public function erroresLPN(){
		echo $this->alertasWMS_model->erroresLPN();
	}
	public function cantErroresLPN(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresLPN()));
	}
	public function totLPNBajados(){
		echo $this->alertasWMS_model->totLPNBajados();
	}
	public function resumenLPN(){
		echo $this->alertasWMS_model->resumenLPN();
	}
	public function reprocesarLPN(){
		$lpns = json_decode($this->input->post('lpns'));
		echo $this->alertasWMS_model->reprocesarLPN($lpns);
	}
	public function eliminarLPN(){
		$lpns = json_decode($this->input->post('lpns'));
		echo $this->alertasWMS_model->eliminarLPN($lpns);
	}
	public function erroresDISTRO(){
		echo $this->alertasWMS_model->erroresDISTRO();
	}
	public function cantErroresDISTRO(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresDISTRO()));
	}
	public function reprocesarDISTRO(){
		$distros = json_decode($this->input->post('distros'));
		echo $this->alertasWMS_model->reprocesarDISTRO($distros);
	}
	public function eliminarDISTRO(){
		$distros = json_decode($this->input->post('distros'));
		echo $this->alertasWMS_model->eliminarDISTRO($distros);
	}
	public function erroresCARGA(){
		echo $this->alertasWMS_model->erroresCARGA();
	}
	public function cantErroresCARGA(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresCARGA()));
	}
	public function totCARGASEnviadas(){
		echo $this->alertasWMS_model->totCARGASEnviadas();
	}
	public function resumenCARGA(){
		echo $this->alertasWMS_model->resumenCARGA();
	}
	public function reporcesarCARGA(){
		$proceso = 0;
		$cargas = json_decode($this->input->post('cargas'));

		foreach ($cargas as $key) {
			if($key->STAT_CODE == '60'){
				$proceso = $this->alertasWMS_model->reporcesarCARGA60($key->LOAD_NBR);
			}
			elseif ($key->STAT_CODE == '79') {
				$proceso = $this->alertasWMS_model->reporcesarCARGA79($key->LOAD_NBR, $key->TRLR_NBR);
			}
			elseif ($key->STAT_CODE == '70') {
				$proceso = $this->alertasWMS_model->reporcesarCARGA79($key->LOAD_NBR, $key->TRLR_NBR);
			}
			else{
				$proceso = 1;
			}
			if($proceso > 0){
				break;
				return $proceso;
			}
		}
		return $proceso;
	}
	public function erroresFASN(){
		echo $this->alertasWMS_model->erroresFASN();
	}
	public function cantErroresFASN(){
		echo sizeof(json_decode($this->alertasWMS_model->erroresFASN()));
	}
	public function reporcesarFASN(){
		$fasns = json_decode($this->input->post('fasns'));
		echo $this->alertasWMS_model->reporcesarFASN($fasns);
	}
	public function verificarOC(){
		$pos = json_decode($this->input->post('pos'));
		echo $this->alertasWMS_model->verificarOC($pos);
	}
	public function verificarLPN(){
		$caseL = json_decode($this->input->post('lpns'));
		echo $this->alertasWMS_model->verificarLPN($caseL);
	}
	public function verificarASN(){
		$asns = json_decode($this->input->post('asns'));
		echo $this->alertasWMS_model->verificarASN($asns);
	}
	public function unidadesEnviadasASN(){
		$asns = json_decode($this->input->post('asns'));
		echo $this->alertasWMS_model->unidadesEnviadasASN($asns);
	}
	public function pasillosSinWorkGroup(){
		echo $this->alertasWMS_model->pasillosSinWorkGroup();
	}
	public function cantPasillosSinWorkGroup(){
		echo sizeof(json_decode($this->alertasWMS_model->pasillosSinWorkGroup()));
	}
}