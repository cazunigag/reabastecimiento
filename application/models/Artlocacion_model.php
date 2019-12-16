<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artlocacion_model extends CI_Model{

	public function __construct(){
		$this->load->database();


	}

	public function insertarArtLocacion($json){

		$sql = "SELECT PMMWMS.LOTE_SEQ.NEXTVAL FROM DUAL";
		$result = $this->db->query($sql);

		foreach($result->result() as $row){
			$lote = $row->NEXTVAL;
		} 

		if (!is_null($json)) {
		    foreach ($json as $columna) {
		        $Locacion = $columna["Locacion"];
		        $Articulo = $columna["Articulo"];
		        $Minimo = $columna["Minimo"];
		        $Maximo = $columna["Maximo"];

		        $sql = "INSERT INTO PMMWMS.RDX_REABASTECIMIENTO(dsp_locn, sku_id, min_invn_qty, max_invn_qty, create_date_time,lote) VALUES ('$Locacion','$Articulo',$Minimo,$Maximo,SYSDATE,$lote)";
		        $resp = $this->db->query($sql);
		        if($resp){
		        	$sql = "BEGIN PMMWMS.RDX_REABASTECIMIENTO_PR(); END;";
		        	$this->db->query($sql);
		        	$this->db->close();
		            
		        }
		        else{
		        	$this->db->close();
		        	return $this->db->error();
		        }
		    }
		    $this->db->close();
		    return 0;

		}
		else{
			$this->db->close();
			return 1;
		}
	}

	public function readArtLocacion(){
		$sql = "SELECT DSP_LOCN, SKU_ID, MIN_INVN_QTY, MAX_INVN_QTY, PROCESS_DATE_TIME, MESSAGE FROM PMMWMS.RDX_REABASTECIMIENTO WHERE LOTE = (SELECT MAX(LOTE) FROM PMMWMS.RDX_REABASTECIMIENTO)";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$json = json_encode($result->result());
			$this->db->close();
			return $json;
		}
		else{
			return $this->db->error();
		}
		
	}

	public function filtrarDatos($fecIni, $fecFin){

		$sql = "SELECT DSP_LOCN, SKU_ID, MIN_INVN_QTY, MAX_INVN_QTY, PROCESS_DATE_TIME, MESSAGE FROM PMMWMS.RDX_REABASTECIMIENTO WHERE TRUNC(PROCESS_DATE_TIME) BETWEEN TO_DATE('$fecIni', 'DD/MM/YY') AND TO_DATE('$fecFin', 'DD/MM/YY')";
		$result = $this->db->query($sql);
		if($result || $result != null){
			$json = json_encode($result->result());
			$this->db->close();
			return $json;
		}
		else{
			return $this->db->error();
		}
	}
}