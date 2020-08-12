<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CambioDemoraCarton_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database('PMMWMS');
		
	}

	public function read($data){
		$estado_inicial = "";
		$fecha = "";
		foreach ($data as $key) {
			$sql = "SELECT STAT_CODE ESTADO_INICIAL FROM CARTON_HDR WHERE CARTON_NBR = '$key[CARTON]'";

			$result = $this->db->query($sql);

			foreach($result->result() as $row){
				if(is_null($row->ESTADO_INICIAL) || $row->ESTADO_INICIAL == ""){
					$estado_inicial = null;
				}else{
					$estado_inicial = $row->ESTADO_INICIAL;
				}
			}

			if($estado_inicial == 20 || $estado_inicial == 21){
				if($estado_inicial != $key['ESTADO']){
					if(is_null($key['FECHA']) || $key['FECHA'] == ""){
						$datos[] = array(
							'CARTON' =>  $key['CARTON'],
							'ESTADO_INICIAL' =>  $estado_inicial,
							'ESTADO_FINAL' =>  $key['ESTADO'],
							'FECHA' => ""
						);
					}else{
						if(date("Y-m-d",strtotime(str_replace("/", "-",$key['FECHA']))) == "1970-01-01"){
							$fecha = "";
						}else{
							$fecha = date("Y-m-d",strtotime(str_replace("/", "-",$key['FECHA'])));
						}
						$datos[] = array(
							'CARTON' =>  $key['CARTON'],
							'ESTADO_INICIAL' =>  $estado_inicial,
							'ESTADO_FINAL' =>  $key['ESTADO'],
							'FECHA' => $fecha
						);
					}
				}
			}
		}
		return json_encode($datos);
	}

	public function saveStat21_20($carton, $estado_inicial, $estado_final, $fecha_liberacion){
		$sql = "SELECT PMMWMS.LOTE_SEQ_LPN.NEXTVAL FROM DUAL";
		$result = $this->db->query($sql);

		foreach($result->result() as $row){
			$lote = $row->NEXTVAL;
		}
		
		if($carton != "" && !is_null($carton)){
			$sql = "INSERT INTO RDX_CARTON_DEMORA (CARTON_NBR, ESTADO_INICIAL, ESTADO_FINAL, LAST_FROZN_DATE_TIME, CREATE_DATE_TIME, LOTE)
					VALUES('$carton',$estado_inicial,$estado_final,$fecha_liberacion,SYSDATE,$lote)";
			$resp = $this->db->query($sql);
			if($resp){
				$sql = "DELETE FROM CARTON_LOCK 
						WHERE CARTON_NBR = (SELECT CARTON_NBR FROM CARTON_HDR WHERE STAT_CODE = 21 
											AND CARTON_NBR = '$carton') 
						AND INVN_LOCK_CODE = 'DM'";
				$this->db->query($sql);
	        	if($this->db->affected_rows()>0){
	        		$sql = "SELECT * FROM EVENT_MESSAGE WHERE EVENT_KEY = '$carton'";
	        		$result1 = $this->db->query($sql);
	        		if(sizeof($result1->result()) > 0){
	        			$sql = "UPDATE EVENT_MESSAGE SET STAT_CODE = 20 WHERE STAT_CODE = 11 
	        															AND EVENT_KEY = (SELECT CARTON_NBR FROM CARTON_HDR WHERE STAT_CODE = 21 
																							AND CARTON_NBR = '$carton')";
						$this->db->query($sql);
	        		}else{
	        			$nextval = $this->getSequenceNextval();
	        			$sql = "INSERT INTO EVENT_MESSAGE(
	        						EVENT_MESSAGE_ID,
	        						EVENT_ID,
	        						EVENT_KEY,
	        						WHSE,
	        						VALIDATE_KEY,
	        						NBR_OF_RETRY,
	        						STAT_CODE,
	        						ERROR_SEQ_NBR,
	        						CREATE_DATE_TIME,
	        						MOD_DATE_TIME,
	        						USER_ID,
	        						CL_MESSAGE_ID,
	        						SCHEMA_ID,
	        						ELS_ACTVTY_CODE,
	        						CD_MASTER_ID)
	        					VALUES(
	        						$nextval,
	        						6180,
	        						'$carton',
	        						'095',
	        						NULL,
	        						1,
	        						20,
	        						0,
	        						SYSDATE,
	        						SYSDATE,
        							'RBRAVO208',
        							NULL,
        							NULL,
        							'001',
        							1001)";
        				$resp = $this->db->query($sql);
	        		}
	        	}
	        	$sql = "UPDATE CARTON_HDR SET STAT_CODE = 20, LAST_FROZN_DATE_TIME = NULL WHERE STAT_CODE = 21 AND CARTON_NBR = '$carton'";
	        	$this->db->query($sql);
	        	if($this->db->affected_rows()>0){
	        		$sql = "UPDATE RDX_CARTON_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'PROCESADO OK' WHERE CARTON_NBR = '$carton'";
	        		$this->db->query($sql);
	        	}else{
	        		$sql = "UPDATE RDX_CARTON_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'CARTON NO ENCONTRADO EN EL SISTEMA' WHERE CARTON_NBR = '$carton'";
	        	}
			}
		}else{
			return 2;
		}
		return 0;
		
	}
	public function saveStat20_21($carton, $estado_inicial, $estado_final, $fecha_liberacion){
		$sql = "SELECT PMMWMS.LOTE_SEQ_LPN.NEXTVAL FROM DUAL";
		$result = $this->db->query($sql);

		foreach($result->result() as $row){
			$lote = $row->NEXTVAL;
		}
		
		if($carton != "" && !is_null($carton)){
			if($fecha_liberacion == "" || $fecha_liberacion == "NULL"){
				return 3;
			}
			else{
				$sql = "INSERT INTO RDX_CARTON_DEMORA (CARTON_NBR, ESTADO_INICIAL, ESTADO_FINAL, LAST_FROZN_DATE_TIME, CREATE_DATE_TIME, LOTE)
						VALUES('$carton',$estado_inicial,$estado_final,$fecha_liberacion,SYSDATE,$lote)";
				$resp = $this->db->query($sql);
				if($resp){
					$sql = "SELECT * FROM CARTON_LOCK WHERE CARTON_NBR = '$carton'";
					$resp = $this->db->query($sql);
					if(sizeof($resp->result())<0){
						$sql = "INSERT INTO CARTON_LOCK(
									CARTON_NBR,
									INVN_LOCK_CODE,
									CREATE_DATE_TIME,
									MOD_DATE_TIME,
									USER_ID
								) VALUES(
									'$carton',
									'DM',
									SYSDATE,
									SYSDATE,
									'RBRAVO208'
								)";
						$this->db->query($sql);
					}
	        		$sql = "SELECT * FROM EVENT_MESSAGE WHERE EVENT_KEY = '$carton'";
	        		$result1 = $this->db->query($sql);
	        		if(sizeof($result1->result()) > 0){
	        			$sql = "UPDATE EVENT_MESSAGE SET STAT_CODE = 21 WHERE STAT_CODE = 20 
	        															AND EVENT_KEY = (SELECT CARTON_NBR FROM CARTON_HDR WHERE STAT_CODE = 20 
																							AND CARTON_NBR = '$carton')";
						$this->db->query($sql);
	        		}else{
	        			$nextval = $this->getSequenceNextval();
	        			$sql = "INSERT INTO EVENT_MESSAGE(
	        						EVENT_MESSAGE_ID,
	        						EVENT_ID,
	        						EVENT_KEY,
	        						WHSE,
	        						VALIDATE_KEY,
	        						NBR_OF_RETRY,
	        						STAT_CODE,
	        						ERROR_SEQ_NBR,
	        						CREATE_DATE_TIME,
	        						MOD_DATE_TIME,
	        						USER_ID,
	        						CL_MESSAGE_ID,
	        						SCHEMA_ID,
	        						ELS_ACTVTY_CODE,
	        						CD_MASTER_ID)
	        					VALUES(
	        						$nextval,
	        						6180,
	        						'$carton',
	        						'095',
	        						NULL,
	        						1,
	        						21,
	        						0,
	        						SYSDATE,
	        						SYSDATE,
	        						'RBRAVO208',
	    							NULL,
	    							NULL,
	    							'001',
	    							1001)";
	    				$resp = $this->db->query($sql);
	        		}
		       
		        	$sql = "UPDATE CARTON_HDR SET STAT_CODE = 21, LAST_FROZN_DATE_TIME = $fecha_liberacion WHERE STAT_CODE = 20 AND CARTON_NBR = '$carton'";
		        	$this->db->query($sql);
		        	if($this->db->affected_rows()>0){
		        		$sql = "UPDATE RDX_CARTON_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'PROCESADO OK' WHERE CARTON_NBR = '$carton'";
		        		$this->db->query($sql);
		        	}else{
		        		$sql = "UPDATE RDX_CARTON_DEMORA SET PROC_DATE_TIME = SYSDATE, MESSAGE = 'CARTON NO ENCONTRADO EN EL SISTEMA' WHERE CARTON_NBR = '$carton'";
		        	}
				}
			}	
		}
		else{
			return 2;
		}
		return 0;
		
	}

	public function getSequenceNextval(){
		$nextval = 0;
		$sql = "SELECT EVENT_MESSAGE_SEQ.NEXTVAL FROM DUAL";
		$result = $this->db->query($sql);

		foreach($result->result() as $row){
			$event_message_seq = $row->NEXTVAL;
		}

		$sql = "SELECT * FROM EVENT_MESSAGE WHERE EVENT_MESSAGE_ID = $event_message_seq";
		$result = $this->db->query($sql);
		if(sizeof($result->result()) > 0){
			$nextval = $this->getSequenceNextval();
		}else{
			$nextval = $event_message_seq;
		}
		return $nextval;
	}
}