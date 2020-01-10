<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CambioDemoraLOCN_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}
	public function read($data){
		$fecha = "";
		foreach ($data as $key) {
			$sql = "SELECT TO_CHAR(LAST_FROZN_DATE_TIME,'DD/MM/YYYY') LAST_FROZN_DATE_TIME FROM LOCN_HDR WHERE DSP_LOCN = '$key[LOCN]'";

			$result = $this->db->query($sql);

			foreach($result->result() as $row){
				if(is_null($row->LAST_FROZN_DATE_TIME) || $row->LAST_FROZN_DATE_TIME == ""){
					$fecha_sistema = null;
				}else{
					$fecha_sistema = $row->LAST_FROZN_DATE_TIME;
				}
				
			}
			if(is_null($key['FECHA']) || $key['FECHA'] == ""){
				if(is_null($fecha_sistema) || $fecha_sistema == ""){
					$datos[] = array(
			            'LOCN' =>  $key['LOCN'],
			            'FECHA' =>  "",
			            'SYSFECHA' => ""
		            );
				}
				else{
					$datos[] = array(
			            'LOCN' =>  $key['LOCN'],
			            'FECHA' =>  "",
			            'SYSFECHA' => date("Y-m-d",strtotime(str_replace("/", "-", $fecha_sistema)))
		            );
				}		
			}else{
				if(date("Y-m-d",strtotime(substr($key['FECHA'], 0, 10))) == "1970-01-01"){
					$fecha = null;

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
				$locn = $key->LOCN;
				if($key->FECHA != "" && !is_null($key->FECHA)){
					$fecha_liberacion = "'".date("d/m/Y", strtotime($key->FECHA))."'";
				}else
				{
					$fecha_liberacion = "NULL";
				}
				if($locn != "" && !is_null($locn)){
					$sql = "INSERT INTO RDX_LOCN_DEMORA (DSP_LOCN, LAST_FROZN_DATE_TIME, CREATE_DATE_TIME, LOTE)
							VALUES('$locn',$fecha_liberacion,SYSDATE,$lote)";
					$resp = $this->db->query($sql);
					if($resp){
			        	$sql = "UPDATE LOCN_HDR SET LAST_FROZN_DATE_TIME = $fecha_liberacion WHERE DSP_LOCN = '$locn'";
			        	$this->db->query($sql);
			        	$sql = "UPDATE CARTON_HDR SET LAST_FROZN_DATE_TIME = $fecha_liberacion WHERE CURR_LOCN_ID = 
			        			(SELECT LOCN_ID FROM LOCN_HDR WHERE DSP_LOCN = '$locn')";
			        	$this->db->query($sql);
			        	if($this->db->affected_rows()>0){
			        		$sql = "UPDATE RDX_LOCN_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'PROCESADO OK' WHERE DSP_LOCN = '$locn'";
			        		$this->db->query($sql);
			        	}else{
			        		$sql = "UPDATE RDX_LOCN_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'LOCACION NO ENCONTRADA EN EL SISTEMA' WHERE DSP_LOCN = '$locn'";
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
	public function detalleLocns($data){
		$locns = "";
		foreach ($data as $key) {
			if(next($data) == false){
				$locns = $locns.$key->LOCN;
			}else{
				$locns = $locns.$key->LOCN."','";
			}
		}
		$sql = "SELECT
				    A.DSP_LOCN,
				    A.LAST_FROZN_DATE_TIME FECHA_LIBERACION_ACTUAL_LOCN,
				    B.CARTON_NBR,
				    B.LAST_FROZN_DATE_TIME FECHA_LIBERACION_ACTUAL_CARTON,
				    B.STAT_CODE,
				    C.CODE_DESC
				FROM
				    LOCN_HDR A,
				    CARTON_HDR B,
				    SYS_CODE C
				WHERE
				    B.CURR_LOCN_ID = A.LOCN_ID
				    AND A.DSP_LOCN IN ('$locns')
				    AND B.STAT_CODE = C.CODE_ID
				    AND C.REC_TYPE = 'S'
				    AND C.CODE_TYPE = '502'";

		$result = $this->db->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}
}