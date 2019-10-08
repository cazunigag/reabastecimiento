<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class alertasBT_model extends CI_Model{

	public function __construct(){
		$this->load->database('BTPROD');
	}

	public function sinProcesarSDI(){
		$sql = "SELECT COUNT(1) AS CANTIDAD FROM LGBTCK_SDI_REC_DBO S WHERE S.BTSRC_FCH_PCS_REG IS NULL";

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
	public function malEnviadosBT(){
		$bdwms = $this->load->database("prodWMS", TRUE);
		$sql = "SELECT
					PH.PKT_CTRL_NBR PKT,
					PH.MARK_FOR CUD,
					PH.SHIPTO_ADDR_2 DIRECCION,
					PH.RTE_ID RUTA,
					PH.CUST_DEPT JORNADA,
					PH.SHIPTO_CITY COMUNA,
					TO_CHAR(PH.CREATE_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') FECHA_CREACION
				FROM
					PKT_HDR PH
				WHERE
					SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
					AND TRUNC(PH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					AND (PH.SHIPTO_ADDR_2 IS NULL OR PH.SHIPTO_ADDR_3 IS NULL OR PH.RTE_ID IS NULL OR PH.CUST_DEPT IS NULL 
						 OR PH.SHIPTO_CITY LIKE 'RET CLI%')";

		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}				 
	}
	public function pickTicketDuplicados(){
		$bdwms = $this->load->database("prodWMS", TRUE);
		$sql = "SELECT
					PKT.CUD,
					PH.PKT_CTRL_NBR,
					PHI.STAT_CODE,
					TO_CHAR(PH.CREATE_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') FECHA_CREACION
				FROM
					PKT_HDR PH,
					PKT_HDR_INTRNL PHI,
					( SELECT
						PH.MARK_FOR CUD,
						COUNT(*)
					  FROM
					  	PKT_HDR PH
					  WHERE 
					  	--TRUNC(PH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					  	 SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
					  	AND PH.MARK_FOR IS NOT NULL
					  GROUP BY
					  	PH.MARK_FOR
					  HAVING
					  	COUNT(*) > 1
					) PKT
				WHERE
					PKT.CUD = PH.MARK_FOR
					AND PH.PKT_CTRL_NBR = PHI.PKT_CTRL_NBR
				ORDER BY
					PKT.CUD";
		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}
	public function cantPickTicketDuplicados(){
		$bdwms = $this->load->database("prodWMS", TRUE);
		$sql = "SELECT COUNT(DISTINCT CUD) CANTIDAD FROM (SELECT
					PKT.CUD,
					PH.PKT_CTRL_NBR,
					PHI.STAT_CODE,
					TO_CHAR(PH.CREATE_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') FECHA_CREACION
				FROM
					PKT_HDR PH,
					PKT_HDR_INTRNL PHI,
					( SELECT
						PH.MARK_FOR CUD,
						COUNT(*)
					  FROM
					  	PKT_HDR PH
					  WHERE 
					  	--TRUNC(PH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					  	 SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
					  	AND PH.MARK_FOR IS NOT NULL
					  GROUP BY
					  	PH.MARK_FOR
					  HAVING
					  	COUNT(*) > 1
					) PKT
				WHERE
					PKT.CUD = PH.MARK_FOR
					AND PH.PKT_CTRL_NBR = PHI.PKT_CTRL_NBR
				ORDER BY
					PKT.CUD)";
		$result = $bdwms->query($sql);
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