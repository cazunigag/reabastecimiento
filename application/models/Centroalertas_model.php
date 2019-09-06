<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Centroalertas_model extends CI_Model{

	public function __construct(){
		$this->load->database('prodWMS');


	}
	public function erroresPKT(){
		$sql="SELECT A.PKT_CTRL_NBR, B.MSG AS MSG_HDR, C.SIZE_DESC, D.MSG AS MSG_DTL FROM INPT_PKT_HDR A, MSG_LOG B, INPT_PKT_DTL C, MSG_LOG D
			  WHERE TO_cHAR(A.ERROR_SEQ_NBR)=B.REF_VALUE_1(+) AND A.PKT_CTRL_NBR = C.PKT_CTRL_NBR(+) AND TO_cHAR(C.ERROR_SEQ_NBR)=D.REF_VALUE_1(+)
			  AND (A.ERROR_SEQ_NBR>0 OR C.ERROR_SEQ_NBR>0)";

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
			  AND (A.ERROR_SEQ_NBR>0 OR C.ERROR_SEQ_NBR>0)";

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
				AND TO_CHAR(A.ERROR_SEQ_NBR) = B.REF_VALUE_1";

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
					AND TO_CHAR(APPT.STAT_CODE = TO_CHAR(SC.CODE_ID)
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
}
