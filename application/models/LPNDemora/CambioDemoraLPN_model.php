<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CambioDemoraLPN_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}
	public function read($data){
		$fecha = "";
		$fecha_sistema = "";
		foreach ($data as $key) {
			$sql = "SELECT TO_CHAR(INCUB_DATE,'DD/MM/YYYY') INCUB_DATE FROM CASE_HDR WHERE CASE_NBR = '$key[LPN]'";

			$result = $this->db->query($sql);

			foreach($result->result() as $row){
				if(is_null($row->INCUB_DATE) || $row->INCUB_DATE == ""){
					$fecha_sistema = null;
				}else{
					$fecha_sistema = $row->INCUB_DATE;
				}
				
			}
			if(is_null($key['FECHA']) || $key['FECHA'] == ""){
				if(is_null($fecha_sistema) || $fecha_sistema == ""){
					$datos[] = array(
			            'LPN' =>  $key['LPN'],
			            'TIPO' =>  $key['TIPO'],
			            'FECHA' =>  "",
			            'SYSFECHA' => ""
		            );
				}
				else{
					$datos[] = array(
			            'LPN' =>  $key['LPN'],
			            'TIPO' =>  $key['TIPO'],
			            'FECHA' =>  "",
			            'SYSFECHA' => date("Y-m-d",strtotime(str_replace("/", "-", $fecha_sistema)))
		            );
				}		
			}else{
				if(date("Y-m-d",strtotime(substr($key['FECHA'], 0, 10))) == "1970-01-01"){
					$fecha = "";

				}
				else{
					$fecha = date("Y-m-d",strtotime(substr($key['FECHA'], 0, 10)));
				}
				if(is_null($fecha_sistema) || $fecha_sistema == ""){
					$datos[] = array(
			            'LPN' =>  $key['LPN'],
			            'TIPO' =>  $key['TIPO'],
			            'FECHA' =>  $fecha,
			            'SYSFECHA' => ""
		            );
				}else{
					$datos[] = array(
			            'LPN' =>  $key['LPN'],
			            'TIPO' =>  $key['TIPO'],
			            'FECHA' =>  $fecha,
			            'SYSFECHA' => date("Y-m-d",strtotime(str_replace("/", "-", $fecha_sistema)))
		            );
				}
			}	
		}
		return json_encode($datos);
	}

	public function save($data){
		$sql = "SELECT PMMWMS.LOTE_SEQ_LPN.NEXTVAL FROM DUAL";
		$result = $this->db->query($sql);

		foreach($result->result() as $row){
			$lote = $row->NEXTVAL;
		}
		if(!is_null($data)){
			foreach ($data as $key) {
				$lpn = $key->LPN;
				$tipo = $key->TIPO;
				if($key->FECHA != "" && !is_null($key->FECHA)){
					$fecha_liberacion = "'".date("d/m/Y", strtotime($key->FECHA))."'";
				}else
				{
					$fecha_liberacion = "NULL";
				}
				if($lpn != "" && !is_null($lpn)){
					$sql = "INSERT INTO RDX_LPN_DEMORA (CASE_NBR, SPL_INSTR_CODE_1, INCUB_DATE, CREATE_DATE_TIME, LOTE)
							VALUES('$lpn','$tipo',$fecha_liberacion,SYSDATE,$lote)";
					$resp = $this->db->query($sql);
					if($resp){
			        	$sql = "UPDATE CASE_HDR SET SPL_INSTR_CODE_1 = '$tipo', INCUB_DATE = $fecha_liberacion WHERE CASE_NBR = '$lpn'";
			        	$this->db->query($sql);
			        	if($this->db->affected_rows()>0){
			        		$sql = "UPDATE RDX_LPN_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'PROCESADO OK' WHERE CASE_NBR = '$lpn'";
			        		$this->db->query($sql);
			        	}else{
			        		$sql = "UPDATE RDX_LPN_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'LPN NO ENCONTRADO EN EL SISTEMA' WHERE CASE_NBR = '$lpn'";
			        	}
			        	$this->db->close();      
			        }
			        else{
			        	$this->db->close();
			        	return $this->db->error();
			        }
				}
				else{
					break;
					return 1;
				}
			}
		}
		else{
			return 2;
		}
		return 0;
	}
}