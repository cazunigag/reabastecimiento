<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class alertasWMS_model extends CI_Model{

	public function __construct(){
		$this->load->database('prodWMS');
		$db2 = $this->load->database('LECLWMPROD');

	}
	public function erroresPKT(){
		$sql="SELECT A.PKT_CTRL_NBR, B.MSG AS MSG_HDR, C.SIZE_DESC, D.MSG AS MSG_DTL FROM INPT_PKT_HDR A, MSG_LOG B, INPT_PKT_DTL C, MSG_LOG D
			  WHERE TO_cHAR(A.ERROR_SEQ_NBR)=B.REF_VALUE_1(+) AND A.PKT_CTRL_NBR = C.PKT_CTRL_NBR(+) AND TO_cHAR(C.ERROR_SEQ_NBR)=D.REF_VALUE_1(+)
			  AND (A.ERROR_SEQ_NBR > 0 OR C.ERROR_SEQ_NBR > 0 OR A.PROC_STAT_CODE > 0 OR C.PROC_STAT_CODE > 0)";

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
	public function totPKTBajados(){
		$sql="SELECT COUNT(*) AS TOT FROM PKT_HDR_INTRNL PHI WHERE SUBSTR(PHI.PKT_CTRL_NBR,1,3)='BTC' AND TRUNC(PHI.CREATE_DATE_TIME)=TRUNC(SYSDATE)";

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
	public function resumenPKT(){
		$sql="SELECT PHI.STAT_CODE, SC.CODE_DESC AS DESC_ESTADO, COUNT(*) AS TOTAL FROM PKT_HDR_INTRNL PHI, SYS_CODE SC
			  WHERE SUBSTR(PHI.PKT_CTRL_NBR,1,3) = 'BTC' AND TRUNC(PHI.CREATE_DATE_TIME) = TRUNC(SYSDATE) AND SC.REC_TYPE = 'S'
			  AND SC.CODE_TYPE = '501' AND TO_CHAR(PHI.STAT_CODE) = SC.CODE_ID GROUP BY PHI.STAT_CODE, SC.CODE_DESC ORDER BY PHI.STAT_CODE";

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
	public function erroresPO(){
		$sql="SELECT A.PO_NBR, B.MSG AS MSG_HDR, C.SIZE_DESC, D.MSG AS MSG_DTL FROM INPT_PO_HDR A, MSG_LOG B, INPT_PO_DTL C, MSG_LOG D
			  WHERE TO_CHAR(A.ERROR_SEQ_NBR)=B.REF_VALUE_1(+) AND A.PO_NBR = C.PO_NBR(+) AND TO_CHAR(C.ERROR_SEQ_NBR)=D.REF_VALUE_1(+)
			  AND (A.ERROR_SEQ_NBR > 0 OR C.ERROR_SEQ_NBR > 0 OR A.PROC_STAT_CODE > 0 OR C.PROC_STAT_CODE > 0)";

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
	public function totPOBajados(){
		$sql="SELECT COUNT(*) AS TOT FROM PO_HDR WHERE TRUNC(CREATE_DATE_TIME) = TRUNC(SYSDATE)";

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
	public function erroresBRCD(){
		$sql="SELECT A.VENDOR_BRCD, A.SKU_BRCD, A.CREATE_DATE_TIME, B.MSG FROM INPT_XREF A, MSG_LOG B WHERE A.ERROR_SEQ_NBR > 0
			  AND TO_CHAR(A.ERROR_SEQ_NBR)= B.REF_VALUE_1(+)";

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
	public function totBRCDBajados(){
		$sql="SELECT COUNT(*) AS TOT FROM XREF WHERE TRUNC(CREATE_DATE_TIME) = TRUNC(SYSDATE)";

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
	public function erroresART(){
		$sql="SELECT A.SKU_ID, B.MSG AS MSG_WAREHOUSE, D.MSG AS MSG_HEADER, A.CREATE_DATE_TIME FROM INPT_ITEM_WHSE_MASTER A, MSG_LOG B, 
			  INPT_ITEM_MASTER C, MSG_LOG D WHERE A.ERROR_SEQ_NBR<>0 AND TO_cHAR(A.ERROR_SEQ_NBR) = B.REF_VALUE_1(+) AND 
			  A.SKU_ID = C.SKU_ID(+) AND TO_CHAR(C.ERROR_SEQ_NBR) = D.REF_VALUE_1(+)";

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
	public function totoARTMod(){
		$sql="SELECT COUNT(*) FROM ITEM_MASTER WHERE TRUNC(CREATE_DATE_TIME) = TRUNC(SYSDATE) OR TRUNC(MOD_DATE_TIME) =  TRUNC(SYSDATE)";
		
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
	public function reprocesarPKT($pkts){
		foreach ($pkts as $key) {
			$sqlDTL="UPDATE INPT_PKT_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE= 0 WHERE PKT_CTRL_NBR = '$key->PKT_CTRL_NBR'";
			$sqlHDR="UPDATE INPT_PKT_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE= 0 WHERE PKT_CTRL_NBR = '$key->PKT_CTRL_NBR'";

			$resultDTL = $this->db->query($sqlDTL);
			$resultHDR = $this->db->query($sqlHDR);

			if(!$resultHDR && !$resultDTL){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function eliminarPKT($pkts){
		foreach ($pkts as $key) {
			$sqlDTL="DELETE FROM INPT_PKT_DTL WHERE PKT_CTRL_NBR = '$key->PKT_CTRL_NBR'";
			$sqlHDR="DELETE FROM INPT_PKT_HDR WHERE PKT_CTRL_NBR = '$key->PKT_CTRL_NBR'";

			$resultDTL = $this->db->query($sqlDTL);
			$resultHDR = $this->db->query($sqlHDR);

			if(!$resultHDR && !$resultDTL){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function reprocesarPO($pos){
		foreach ($pos as $key) {
			$sqlDTL="UPDATE INPT_PO_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE PO_NBR = '$key->PO_NBR'";
			$sqlHDR="UPDATE INPT_PO_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE PO_NBR = '$key->PO_NBR'";

			$resultDTL = $this->db->query($sqlDTL);
			$resultHDR = $this->db->query($sqlHDR);

			if(!$resultHDR && !$resultDTL){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function eliminarPO($pos){
		foreach ($pos as $key) {
			$sqlDTL="DELETE FROM INPT_PO_DTL WHERE PO_NBR = '$key->PO_NBR'";
			$sqlHDR="DELETE FROM INPT_PO_HDR WHERE PO_NBR = '$key->PO_NBR'";

			$resultDTL = $this->db->query($sqlDTL);
			$resultHDR = $this->db->query($sqlHDR);

			if(!$resultHDR && !$resultDTL){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function reprocesarBRCD($brcds){
		foreach ($brcds as $key) {
			$sql="UPDATE INPT_XREF SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE VENDOR_BRCD = '$key->VENDOR_BRCD' AND SKU_BRCD = '$key->SKU_BRCD'";

			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function eliminarBRCD($brcds){
		foreach ($brcds as $key) {
			$sql="DELETE FROM INPT_XREF WHERE VENDOR_BRCD = '$key->VENDOR_BRCD' AND SKU_BRCD = '$key->SKU_BRCD'";

			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function reprocesarART($arts){
		foreach ($arts as $key) {
			$sqlDTL="UPDATE INPT_ITEM_WHSE_MASTER SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SKU_ID = '$key->SKU_ID'";
			$sqlHDR="UPDATE INPT_ITEM_MASTER SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SKU_ID = '$key->SKU_ID'";

			$resultDTL = $this->db->query($sqlDTL);
			$resultHDR = $this->db->query($sqlHDR);

			if(!$resultHDR && !$resultDTL){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function eliminarART($arts){
		foreach ($arts as $key) {
			$sqlDTL="DELETE FROM INPT_ITEM_WHSE_MASTER WHERE SKU_ID = '$key->SKU_ID'";
			$sqlHDR="DELETE FROM INPT_ITEM_MASTER WHERE SKU_ID = '$key->SKU_ID'";

			$resultDTL = $this->db->query($sqlDTL);
			$resultHDR = $this->db->query($sqlHDR);

			if(!$resultHDR && !$resultDTL){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function resumenOLA(){
		$sql = "SELECT 
					OLA.NUMERO_OLA,
					OLA.DESC_OLA,
					OLA.ESTADO,
					OLA.DESC_ESTADO,
					OLA.INICIO,
					OLA.TERMINO,
					CASE
						WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))
						ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))
					END ||':'||
					CASE
						WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))
						ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))
					END ||':'||
					CASE
						WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))
						ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))
					END AS DIFERENCIA_HHMMSS
				FROM
					(
					SELECT
						WP.WAVE_NBR AS NUMERO_OLA,
						WP.WAVE_DESC AS DESC_OLA,
						WP.WAVE_STAT_CODE AS ESTADO,
						SC.CODE_DESC AS DESC_ESTADO,
						TO_CHAR(MIN(ML.LOG_DATE_TIME), 'DD/MM/YYYY HH24:MI:SS') AS INICIO,
						TO_CHAR(MAX(ML.LOG_DATE_TIME), 'DD/MM/YYYY HH24:MI:SS') AS TERMINO
					FROM
						WAVE_PARM WP,
						SYS_CODE SC,
						MSG_LOG ML
					WHERE 
						TRUNC(WP.CREATE_DATE_TIME) = TRUNC(SYSDATE)
						AND SC.REC_TYPE = 'S' AND SC.CODE_TYPE = '595' AND WP.WAVE_STAT_CODE = SC.CODE_ID
						AND WP.WAVE_NBR = ML.REF_VALUE_1
					GROUP BY
						WP.WAVE_NBR,
						WP.WAVE_DESC,
						WP.WAVE_STAT_CODE,
						SC.CODE_DESC
					) OLA
				ORDER BY
					OLA.NUMERO_OLA DESC";

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
	public function totOLA(){
		$sql = "SELECT COUNT(*) AS TOT FROM WAVE_PARM WP WHERE TRUNC(WP.CREATE_DATE_TIME) = TRUNC(SYSDATE)";

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
	public function erroresOLA(){
		$sql = "SELECT 
					OLA.NUMERO_OLA,
					OLA.DESC_OLA,
					OLA.ESTADO,
					OLA.DESC_ESTADO,
					OLA.INICIO,
					OLA.TERMINO,
					CASE
						WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))
						ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))
					END ||':'||
					CASE
						WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))
						ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))
					END ||':'||
					CASE
						WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))
						ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))
					END AS DIFERENCIA_HHMMSS
				FROM
					(
					SELECT
						WP.WAVE_NBR AS NUMERO_OLA,
						WP.WAVE_DESC AS DESC_OLA,
						WP.WAVE_STAT_CODE AS ESTADO,
						SC.CODE_DESC AS DESC_ESTADO,
						TO_CHAR(MIN(ML.LOG_DATE_TIME), 'DD/MM/YYYY HH24:MI:SS') AS INICIO,
						TO_CHAR(MAX(ML.LOG_DATE_TIME), 'DD/MM/YYYY HH24:MI:SS') AS TERMINO
					FROM
						WAVE_PARM WP,
						SYS_CODE SC,
						MSG_LOG ML
					WHERE 
						TRUNC(WP.CREATE_DATE_TIME) = TRUNC(SYSDATE)
						AND SC.REC_TYPE = 'S' AND SC.CODE_TYPE = '595' AND WP.WAVE_STAT_CODE = SC.CODE_ID
						AND WP.WAVE_NBR = ML.REF_VALUE_1
					GROUP BY
						WP.WAVE_NBR,
						WP.WAVE_DESC,
						WP.WAVE_STAT_CODE,
						SC.CODE_DESC
					) OLA
				WHERE
					(OLA.ESTADO >= 37 AND OLA.ESTADO <= 48)
					OR
					(OLA.ESTADO < 50 AND (TO_DATE(OLA.TERMINO, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(OLA.INICIO, 'DD/MM/YYYY HH24:MI:SS')) > .013796296)	
				ORDER BY
					OLA.NUMERO_OLA DESC";

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
	public function erroresCITA(){
		$sql = "SELECT A.APPT_NBR, A.SHPMT_NBR, A.CREATE_DATE_TIME, B.MSG FROM INPT_APPT_SCHED A, MSG_LOG B WHERE A.ERROR_SEQ_NBR > 0 
				AND TO_CHAR(A.ERROR_SEQ_NBR) = B.REF_VALUE_1(+)";

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
	public function totCITASBajadas(){
		$sql = "SELECT COUNT(*) AS TOT FROM APPT_SCHED WHERE TRUNC(CREATE_DATE_TIME) = TRUNC(SYSDATE)";

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
	public function resumenCITA(){
		$sql = "SELECT APPT.STAT_CODE, SC.SHORT_DESC, COUNT(*) AS CANTIDAD_CITAS FROM APPT_SCHED APPT, SYS_CODE SC 
				WHERE TRUNC(APPT.CREATE_DATE_TIME) = TRUNC(SYSDATE) AND SC.REC_TYPE = 'S' AND SC.CODE_TYPE = '628' 
				AND TO_CHAR(APPT.STAT_CODE) = TO_CHAR(SC.CODE_ID)
				GROUP BY APPT.STAT_CODE, SC.SHORT_DESC ORDER BY APPT.STAT_CODE";

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
	public function detCodCITA($codigo){
		$sql = "SELECT
					APPT.APPT_NBR,
					APPT.SHPMT_NBR,
					APPT.STAT_CODE,
					SC.SHORT_DESC,
					TO_CHAR(APPT.CREATE_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS CREATE_DATE_TIME
				FROM 
					APPT_SCHED APPT,
					SYS_CODE SC
				WHERE 
					TRUNC(APPT.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					AND SC.REC_TYPE = 'S'
					AND SC.CODE_TYPE = '628'
					AND TO_CHAR(APPT.STAT_CODE) = TO_CHAR(SC.CODE_ID)
					AND APPT.STAT_CODE = '$codigo'";

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
	public function reprocesarCITA($citas){
				 
		foreach ($citas as $key) {	
			$sql =  array(
						"UPDATE INPT_APPT_SCHED SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE APPT_NBR = '$key->APPT_NBR'",
						"UPDATE INPT_ASN_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE REF_FIELD_1 = '$key->APPT_NBR'",
						"UPDATE INPT_ASN_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR IN (SELECT SHPMT_NBR
						 FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR')",
						"UPDATE INPT_CASE_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE ORIG_SHPMT_NBR IN (SELECT SHPMT_NBR FROM
						 INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR')",
						"UPDATE INPT_CASE_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE CASE_NBR IN (SELECT CASE_NBR
						 FROM INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN (SELECT SHPMT_NBR FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR'))",
						"UPDATE INPT_CASE_LOCK SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR
						 WHERE ORIG_SHPMT_NBR IN(SELECT SHPMT_NBR FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR'))",
				 		"UPDATE INPT_STORE_DISTRO SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR IN (SELECT SHPMT_NBR FROM INPT_ASN_HDR
						 WHERE REF_FIELD_1 = '$key->APPT_NBR')"
			);		 	
			foreach ($sql as $key2) {
				$result = $this->db->query($key2);

				if(!$result){
					break;
					return 1;
				}	 	
			}
		}
		return 0;
	}
	public function eliminarCITA($citas){
		foreach ($citas as $key) {
			$sql =  array(
						"DELETE INPT_CASE_LOCK WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN ( SELECT SHPMT_NBR
					 	 FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR'))",
						"DELETE INPT_CASE_DTL WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN (SELECT SHPMT_NBR
					 	 FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR'))",
			 			"DELETE INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN (SELECT SHPMT_NBR FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR')",
						"DELETE INPT_ASN_DTL WHERE SHPMT_NBR IN (SELECT SHPMT_NBR FROM INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR')",
			 			"DELETE INPT_ASN_HDR WHERE REF_FIELD_1 = '$key->APPT_NBR'",
			 			"DELETE INPT_APPT_SCHED WHERE  APPT_NBR = '$key->APPT_NBR'"
			);
			 				
			foreach($sql as $key2){
				$result = $this->db->query($key2);

				if(!$result){
					break;
					return 1;
				}	 	
			}		 
 
		}
		return 0;
	}

	public function erroresASN(){
		$sql = "SELECT
					A.SHPMT_NBR,
					A.REF_FIELD_1,
					B.MSG AS MSG_SHPMT,
					C.SIZE_DESC,
					C.PO_NBR,
					D.MSG AS MSG_SKU
				FROM 
					INPT_ASN_HDR A,
					MSG_LOG B,
					INPT_ASN_DTL C,
					MSG_LOG D
				WHERE
					TO_cHAR(A.ERROR_SEQ_NBR) = B.REF_VALUE_1(+)
					AND A.SHPMT_NBR = C.SHPMT_NBR(+)
					AND TO_cHAR(C.ERROR_SEQ_NBR) = D.REF_VALUE_1(+)
					AND (A.ERROR_SEQ_NBR > 0 OR C.ERROR_SEQ_NBR > 0 OR A.PROC_STAT_CODE > 0 OR C.PROC_STAT_CODE > 0)";

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
	public function totASNBajados(){
		$sql = "SELECT COUNT(*) AS TOT FROM ASN_HDR WHERE TRUNC(CREATE_DATE_TIME) = TRUNC(SYSDATE)";

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
	public function resumenASN(){
		$sql = "SELECT
					AH.STAT_CODE,
					SC.CODE_DESC,
					COUNT(*) AS ASNS
				FROM
					ASN_HDR AH,
					SYS_CODE SC
				WHERE
					TRUNC(AH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					AND  SC.REC_TYPE = 'S'
					AND  SC.CODE_TYPE = '564'
					AND TO_CHAR(AH.STAT_CODE) = TO_cHAR(SC.CODE_ID)
				GROUP BY
					AH.STAT_CODE,
					SC.CODE_DESC
				ORDER BY
					AH.STAT_CODE";

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
	public function detCodASN($codigo){
		$sql = "SELECT
					AH.SHPMT_NBR,
					AH.REF_FIELD_1,
					AH.STAT_CODE,
					SC.CODE_DESC
				FROM
					ASN_HDR AH,
					SYS_CODE SC
				WHERE
					TRUNC(AH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					AND  SC.REC_TYPE = 'S'
					AND  SC.CODE_TYPE = '564'
					AND TO_CHAR(AH.STAT_CODE) = TO_cHAR(SC.CODE_ID)
					AND AH.STAT_CODE = '$codigo'
				ORDER BY
					AH.STAT_CODE";

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
	public function reprocesarASN($asns){
		foreach ($asns as $key) {
			$sql = array(
					"UPDATE INPT_APPT_SCHED SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'",
					"UPDATE INPT_ASN_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'",
					"UPDATE INPT_ASN_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'",
					"UPDATE INPT_CASE_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE ORIG_SHPMT_NBR = '$key->SHPMT_NBR'",
					"UPDATE INPT_CASE_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR
					 WHERE ORIG_SHPMT_NBR = '$key->SHPMT_NBR')",
					"UPDATE INPT_CASE_LOCK SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR
					 WHERE ORIG_SHPMT_NBR = '$key->SHPMT_NBR')",
					"UPDATE INPT_STORE_DISTRO SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'"
			);		
			foreach ($sql as $key2) {

				$result = $this->db->query($key2);

				if(!$result){
					break;
					return 1;
				}	 	
			}		 
		}
		return 0;
	}
	public function eliminarASN($asns){
		foreach ($asns as $key) {
			$sql = array(
					"DELETE FROM INPT_CASE_LOCK WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN ('$key->SHPMT_NBR'))",
					"DELETE FROM INPT_CASE_DTL WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN ('$key->SHPMT_NBR'))",
					"DELETE FROM INPT_CASE_HDR WHERE ORIG_SHPMT_NBR IN ('$key->SHPMT_NBR')",
					"DELETE FROM INPT_ASN_HDR WHERE SHPMT_NBR IN ('$key->SHPMT_NBR')",
					"DELETE FROM INPT_ASN_DTL WHERE SHPMT_NBR IN ('$key->SHPMT_NBR')",
					"DELETE FROM INPT_APPT_SCHED WHERE SHPMT_NBR IN ('$key->SHPMT_NBR')",
					"DELETE FROM INPT_STORE_DISTRO WHERE SHPMT_NBR IN ('$key->SHPMT_NBR')"
			);
			foreach ($sql as $key2) {
				$result = $this->db->query($key2);

				if(!$result){
					break;
					return 1;
				}	 	
			}		 
		}
		return 0;
	}
	public function erroresLPN(){
		$sql = "SELECT A.CASE_NBR, A.ORIG_SHPMT_NBR, B.MSG AS MSG_LPN, C.SIZE_DESC, D.MSG AS MSG_SKU 
				FROM INPT_CASE_HDR A, MSG_LOG B, INPT_CASE_DTL C, MSG_LOG D 
		   		WHERE TO_CHAR(A.ERROR_SEQ_NBR) = B.REF_VALUE_1(+) AND A.CASE_NBR = C.CASE_NBR(+) AND TO_CHAR(C.ERROR_SEQ_NBR) = D.REF_VALUE_1(+)
		   		AND (A.ERROR_SEQ_NBR > 0 OR C.ERROR_SEQ_NBR > 0 OR A.PROC_STAT_CODE > 0 OR C.PROC_STAT_CODE > 0)";

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
	public function totLPNBajados(){
		$sql = "SELECT COUNT(*) AS TOT FROM CASE_HDR WHERE TRUNC(CREATE_DATE_TIME) = TRUNC(SYSDATE)";

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
	public function resumenLPN(){
		$sql = "SELECT
					CH.STAT_CODE,
					SC.CODE_DESC,
					COUNT(*) AS CANTDAD_LPN
				FROM
					CASE_HDR CH,
					SYS_CODE SC
				WHERE 
					TRUNC(CH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					AND SC.REC_TYPE = 'S'
					AND SC.CODE_TYPE = '509'
					AND TO_CHAR(CH.STAT_CODE) = TO_CHAR(SC.CODE_ID)
				GROUP BY
					CH.STAT_CODE,
					SC.CODE_DESC
				ORDER BY
					CH.STAT_CODE";

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
	public function reprocesarLPN($lpns){
		foreach ($lpns as $key) {
			$sql = array(
					"UPDATE INPT_APPT_SCHED SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR
					 WHERE CASE_NBR = '$key->CASE_NBR')",
					"UPDATE INPT_ASN_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR
					 WHERE CASE_NBR = '$key->CASE_NBR')",
					"UPDATE INPT_ASN_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR
					 WHERE CASE_NBR = '$key->CASE_NBR')",
					"UPDATE INPT_CASE_HDR SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE ORIG_SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR
					 WHERE CASE_NBR = '$key->CASE_NBR')",
					"UPDATE INPT_CASE_DTL SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR
					 WHERE ORIG_SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR WHERE CASE_NBR = '$key->CASE_NBR'))",
					"UPDATE INPT_CASE_LOCK SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE CASE_NBR IN (SELECT CASE_NBR FROM INPT_CASE_HDR
					 WHERE ORIG_SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR WHERE CASE_NBR = '$key->CASE_NBR'))",
					"UPDATE INPT_STORE_DISTRO SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE SHPMT_NBR IN (SELECT ORIG_SHPMT_NBR FROM INPT_CASE_HDR
					 WHERE CASE_NBR = '$key->CASE_NBR')" 
			);

		
			foreach ($sql as $key2) {
				$result = $this->db->query($key2);
				if(!$result){
					break;
					return 1;
				}	 	
			}
		}
		return 0;
	}
	public function eliminarLPN($lpns){
		foreach ($lpns as $key) {
			$sql = array(
					"DELETE FROM INPT_CASE_DTL WHERE CASE_NBR = '$key->CASE_NBR'",
					"DELETE FROM INPT_CASE_LOCK WHERE CASE_NBR = '$key->CASE_NBR'",
					"DELETE FROM INPT_CASE_HDR WHERE CASE_NBR = '$key->CASE_NBR'",
					"DELETE FROM INPT_ASN_DTL WHERE SHPMT_NBR = '$key->ORIG_SHPMT_NBR'",
					"DELETE FROM INPT_ASN_HDR WHERE SHPMT_NBR = '$key->ORIG_SHPMT_NBR'",
					"DELETE FROM INPT_STORE_DISTRO WHERE CASE_NBR = '$key->CASE_NBR'"
			);

			foreach ($sql as $key2) {
				$result = $this->db->query($key2);
				if(!$result){
					break 2;
					return 1;
				}	 	
			}
		}
		return 0;
	}
	public function erroresDISTRO(){
		$sql = "SELECT
					A.DISTRO_NBR,
					A.SIZE_DESC,
					A.CREATE_DATE_TIME,
					B.MSG
				FROM
					INPT_STORE_DISTRO A,
					MSG_LOG B
				WHERE
					A.ERROR_SEQ_NBR > 0
					AND TO_CHAR(A.ERROR_SEQ_NBR) = B.REF_VALUE_1(+)";

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
	public function reprocesarDISTRO($distros){
		foreach ($distros as $key) {
			$sql = "UPDATE INPT_STORE_DISTRO SET ERROR_SEQ_NBR = 0, PROC_STAT_CODE = 0 WHERE DISTRO_NBR = '$key->DISTRO_NBR'";

			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function eliminarDISTRO($distros){
		foreach ($distros as $key) {
			$sql = "DELETE FROM INPT_STORE_DISTRO WHERE DISTRO_NBR = '$key->DISTRO_NBR'";

			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function erroresCARGA(){
		$errCarga = array();
		date_default_timezone_set("America/Santiago");
		$systemdate=date('d/m/Y H:i:s');
		$sql = "SELECT
					LOAD_NBR,
					STAT_CODE,
					SC.CODE_DESC,
					TRLR_NBR,
					TO_CHAR(OL.MOD_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS FEC_MOD
				FROM
					OUTBD_LOAD OL,
					SYS_CODE SC
				WHERE
					STAT_CODE IN (60,79,70)
					AND SC.REC_TYPE = 'S'
					AND SC.CODE_TYPE = '843'
					AND TO_CHAR(OL.STAT_CODE) = TO_CHAR(SC.CODE_ID)
					--AND TRUNC(OL.MOD_DATE_TIME) = TRUNC(SYSDATE)";

		$result = $this->db->query($sql);
		if($result->num_rows()>0 ){
			//var_dump($result->num_rows());
			foreach ($result->result() as $key) {
				$fechaMod=$key->FEC_MOD;
				if(substr($systemdate,0,10) == substr($fechaMod,0,10)){
					if(substr($systemdate,11,2) == substr($fechaMod,11,2)){
						if((substr($systemdate,14,2) - substr($fechaMod,14,2)) >= 20){
							array_push($errCarga, $key);
						}
					}elseif ((substr($systemdate,11,2) - substr($fechaMod,11,2)) >= 1){
						if(((60 - substr($fechaMod,14,2)) + substr($systemdate,14,2)) >= 20){
							array_push($errCarga, $key);
						}
					}
				}elseif ((substr($systemdate,6,4) - substr($fechaMod,6,4)) > 0) {
					array_push($errCarga, $key);
				}elseif ((substr($systemdate,3,2) - substr($fechaMod,3,2)) > 0) {
					array_push($errCarga, $key);
				}elseif ((substr($systemdate,0,2) - substr($fechaMod,0,2)) > 0) {
					array_push($errCarga, $key);
				}

				/*if(substr($systemdate,1,10) == substr($fechaMod,1,10)){
					if(substr($systemdate,11,2) == substr($fechaMod,11,2)){
						if((substr($systemdate,14,2) - substr($fechaMod,14,2)) >= 20){
							array_push($errCarga, $key);
						}
					}elseif ((substr($systemdate,11,2) - substr($fechaMod,11,2)) >= 1){
						if(((60 - substr($fechaMod,14,2)) + substr($systemdate,14,2)) >= 20){
							array_push($errCarga, $key);
						}
					}
				}elseif ((substr($systemdate,5,2) - substr($fechaMod,5,2)) >= 1) {
					array_push($errCarga, $key);
				}elseif ((substr($systemdate,3,2) - substr($fechaMod,3,2)) >= 1) {
					array_push($errCarga, $key);
				}elseif ((substr($systemdate,1,2) - substr($fechaMod,1,2)) >= 1) {
					array_push($errCarga, $key);
				}*/
			}
		}
		$data = json_encode($errCarga);
		$this->db->close();
		return $data;	
	}
	public function resumenCARGA(){
		$sql = "SELECT
					OL.STAT_CODE,
					SC.CODE_DESC,
					COUNT(*) AS CANTIDAD_CARGAS
				FROM
					OUTBD_LOAD OL,
					SYS_CODE SC
				WHERE
					TRUNC(OL.MOD_DATE_TIME) = TRUNC(SYSDATE)
					AND SC.REC_TYPE = 'S'
					AND SC.CODE_TYPE = '843'
					AND TO_cHAR(OL.STAT_CODE) = TO_CHAR(SC.CODE_ID)
				GROUP BY
					OL.STAT_CODE,
					SC.CODE_DESC
				ORDER BY
					OL.STAT_CODE";

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
	public function totCARGASEnviadas(){
		$sql = "SELECT COUNT(*) AS TOT FROM OUTBD_LOAD OL WHERE TRUNC(OL.MOD_DATE_TIME) = TRUNC(SYSDATE) AND OL.STAT_CODE = 80";

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
	public function reporcesarCARGA60($carga){
		$db2 = $this->load->database('LECLWMPROD', TRUE);
		$sql = "UPDATE OUTBD_LOAD SET STAT_CODE = 40 WHERE LOAD_NBR = '$carga'";

		$result = $db2->query($sql);
		if(!$result){
			return 1;
		}else{
			return 0;
		}
	}
	public function reporcesarCARGA79($carga, $patente){
		$db2 = $this->load->database('LECLWMPROD', TRUE);
		$sql = array(
			"UPDATE OUTBD_LOAD SET STAT_CODE = 40 WHERE LOAD_NBR = '$carga'",
			"UPDATE CARTON_HDR SET STAT_CODE = 50 WHERE LOAD_NBR = '$carga' AND STAT_CODE > 20",
			"UPDATE PKT_HDR_INTRNL SET STAT_CODE 70 WHERE PKT_CTRL_NBR IN (SELECT PKT_CTRL_NBR FROM CARTON_HDR WHERE LOAD_NBR = '$carga'
			 AND PKT_CTRL_NBR NOT LIKE 'PER%'",
			"UPDATE OUTBD_LOAD SET TRLR_NBR = '$patente', FIRST_LOAD_DATE_TIME = SYSDATE, LAST_LOAD_DATE_TIME = SYSDATE WHERE LOAD_NBR = '$carga'"
		);

		foreach ($sql as $key) {
			$result = $db2->query($key);
				if(!$result){
					break;
					return 1;
				}
		}
		return 0;
	}
	public function erroresFASN(){
		$sql = "SELECT
					AH.SHPMT_NBR,
					AH.STAT_CODE,
					TO_CHAR(AH.CREATE_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS FECHA_CREACION,
					TO_CHAR(AH.MOD_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS FECHA_MOD,
					TO_CHAR(AH.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS FECHA_VERIFICACION,
					AH.MANIF_NBR,
					AH.REP_NAME,
					AD.PO_NBR
				FROM
					ASN_HDR AH,
					ASN_DTL AD
				WHERE
					AH.STAT_CODE = 70
					AND AH.SHPMT_NBR = AD.SHPMT_NBR
				GROUP BY
					AH.SHPMT_NBR,
					AH.STAT_CODE,
					TO_CHAR(AH.CREATE_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'),
					TO_CHAR(AH.MOD_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'),
					TO_CHAR(AH.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'),
					AH.MANIF_NBR,
					AH.REP_NAME,
					AD.PO_NBR";

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
	public function reporcesarFASN($fasns){
		$db2 = $this->load->database('LECLWMPROD', TRUE);
		foreach ($fasns as $key) {
			$sql = "UPDATE ASN_HDR SET STAT_CODE = 90, MOD_DATE_TIME = SYSDATE, USER_ID = 'JASILVA' WHERE SHPMT_NBR = '$key->SHPMT_NBR' AND STAT_CODE = 70";

			$result = $db2->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function verificarOC($pos){
		$ocs = "";
		foreach ($pos as $key) {
			if(next($pos) == false){
				$ocs = $ocs.$key->PO_NBR;
			}else{
				$ocs = $ocs.$key->PO_NBR."','";
			}
		}
		$sql = "SELECT
					A.PO_NBR,
					A.MOD_DATE_TIME,
					A.STAT_CODE,
					B.CODE_DESC
				FROM
					PO_HDR A,
					SYS_CODE B
				WHERE 
					A.PO_NBR IN ('$ocs')
					AND A.STAT_CODE = B.CODE_ID
					AND B.REC_TYPE = 'S'
					AND B.CODE_TYPE = '123'";

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
	public function verificarLPN($caseL){
		$lpns = "";
		foreach ($caseL as $key) {
			if(next($caseL) == false){
				$lpns = $lpns.$key->CASE_NBR;
			}else{
				$lpns = $lpns.$key->CASE_NBR."','";
			}
		}
		$sql = "SELECT
				    A.CASE_NBR LPN,
				    A.MOD_DATE_TIME FEC_MODIFICACION,
				    A.STAT_CODE,
				    B.CODE_DESC
				FROM
				    CASE_HDR A,
				    SYS_CODE B
				WHERE
				    A.CASE_NBR IN ('$lpns')
				    AND A.STAT_CODE = B.CODE_ID
				    AND B.REC_TYPE = 'S'
				    AND B.CODE_TYPE = '509'";

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
	public function verificarASN($asns){
		$shpmts = "";
		foreach ($asns as $key) {
			if(next($asns) == false){
				$shpmts = $shpmts.$key->SHPMT_NBR;
			}else{
				$shpmts = $shpmts.$key->SHPMT_NBR."','";
			}
		}
		$sql = "SELECT
				    A.SHPMT_NBR ASN,
				    A.MOD_DATE_TIME FEC_MODIFICACION,
				    A.STAT_CODE,
				    B.CODE_DESC
				FROM
				    ASN_HDR A,
				    SYS_CODE B
				WHERE
				    A.SHPMT_NBR IN ('$shpmts')
				    AND A.STAT_CODE = B.CODE_ID
				    AND B.REC_TYPE = 'S'
				    AND B.CODE_TYPE = '564'";

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
	public function unidadesEnviadasASN($asns){
		$shpmts = "";
		foreach ($asns as $key) {
			if(next($asns) == false){
				$shpmts = $shpmts.$key->SHPMT_NBR;
			}else{
				$shpmts = $shpmts.$key->SHPMT_NBR."','";
			}
		}
		$sql = "SELECT
					A.SHPMT_NBR,
					A.UNITS_SHPD,
					B.AD_UNITS_SHPD
				FROM
					INPT_ASN_HDR A,
					(SELECT
						C.SHPMT_NBR,
						SUM(C.UNITS_SHPD) AD_UNITS_SHPD
					 FROM
					 	INPT_ASN_DTL C
					 GROUP BY
					 	C.SHPMT_NBR) B
				WHERE
					A.SHPMT_NBR IN ('$shpmts')
					AND A.SHPMT_NBR = B.SHPMT_NBR";

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
	public function pasillosSinWorkGroup(){
		$sql = "SELECT
				    SUBSTR(LOCN_BRCD,1,4) PASILLO,
				    COUNT(*) CANTIDAD_UBICACIONES
				FROM
				    LOCN_HDR
				WHERE
				    (WORK_GRP IS NULL OR WORK_AREA IS NULL)
				    AND AREA = 'R'
				GROUP BY 
				    SUBSTR(LOCN_BRCD,1,4)
				ORDER BY 1";

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
	public function invnNeedTypeFaltantes(){
		$resultado = array();
		$sql = "SELECT DISTINCT
					X.*
				FROM
					(SELECT
						A.CURR_WORK_GRP,
						A.CURR_WORK_AREA,
						SUM((CASE
								WHEN A.INVN_NEED_TYPE = 2 THEN 1 ELSE 0
							 END)) SUM_2,
						SUM((CASE
								WHEN A.INVN_NEED_TYPE = 53 THEN 1 ELSE 0
							 END)) SUM_53
					 FROM
					 	INT_PATH_DEFN A
					 WHERE
					 	A.INVN_NEED_TYPE IN (2,53)
					 	AND CURR_WORK_GRP IS NOT NULL
					 	AND CURR_WORK_AREA IS NOT NULL
					 GROUP BY
					 	A.CURR_WORK_GRP, A.CURR_WORK_AREA) X
				WHERE 
					X.SUM_2 = 0
					OR X.SUM_53 = 0";

		$result = $this->db->query($sql);

		
		if($result || $result != null){
			foreach ($result->result() as $key) {
				if($key->SUM_2 == 0){
					array_push($resultado, array("CURR_WORK_GRP" => $key->CURR_WORK_GRP,
									   "CURR_WORK_AREA" => $key->CURR_WORK_AREA,
									   "FALTANTE" => "FALTA TIPO DE NECESIDAD DE INVENTARIO 2"));
				}
				if($key->SUM_53 == 0){
					array_push($resultado, array("CURR_WORK_GRP" => $key->CURR_WORK_GRP,
									   "CURR_WORK_AREA" => $key->CURR_WORK_AREA,
									   "FALTANTE" => "FALTA TIPO DE NECESIDAD DE INVENTARIO 53"));
				}
			}
			return json_encode($resultado);
		}
		else{
			return $this->db->error();
		}
	}
}	
