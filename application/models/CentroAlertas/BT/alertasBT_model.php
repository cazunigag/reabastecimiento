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
					  	PKT_HDR PH,
					  	PKT_HDR_INTRNL PHI
					  WHERE 
					  	TRUNC(PH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					  	AND SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
					  	AND PH.MARK_FOR IS NOT NULL
					  	AND PH.PKT_CTRL_NBR = PHI.PKT_CTRL_NBR
					  	AND PHI.STAT_CODE <> 99
					  	AND PHI.STAT_CODE <> 95
					  GROUP BY
					  	PH.MARK_FOR
					  HAVING
					  	COUNT(*) > 1
					) PKT
				WHERE
					PKT.CUD = PH.MARK_FOR
					AND PH.PKT_CTRL_NBR = PHI.PKT_CTRL_NBR
					AND SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
				ORDER BY
					PKT.CUD";
		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$bdwms->close();
			return $data;
		}
		else{
			return $bdwms->error();
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
					  	PKT_HDR PH,
					  	PKT_HDR_INTRNL PHI
					  WHERE 
					  	TRUNC(PH.CREATE_DATE_TIME) = TRUNC(SYSDATE)
					  	AND SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
					  	AND PH.MARK_FOR IS NOT NULL
					  	AND PH.PKT_CTRL_NBR = PHI.PKT_CTRL_NBR
					  	AND PHI.STAT_CODE <> 99
					  	AND PHI.STAT_CODE <> 95
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
			$bdwms->close();
			return $data;
		}
		else{
			return $bdwms->error();
		}
	}
	public function actualizarPKT($pkts){
		$bdwms = $this->load->database("LECLWMPROD", TRUE);
		$datos = "";
		foreach ($pkts as $key) {
			if(next($pkts) == false){
				$datos = $datos.$key->PKT_CTRL_NBR;
			}else{
				$datos = $datos.$key->PKT_CTRL_NBR."','";
			}
		}
		$sql = array("UPDATE PKT_HDR_INTRNL SET STAT_CODE = 99, TOTAL_NBR_OF_UNITS = 0, MOD_DATE_TIME = TRUNC(SYSDATE), USER_ID = 'JASILVA'
					  WHERE PKT_CTRL_NBR IN ('$datos')",
					 "UPDATE PKT_DTL SET PKT_QTY = 0, MOD_DATE_TIME = TRUNC(SYSDATE), USER_ID = 'JASILVA' WHERE PKT_CTRL_NBR IN ('$datos')");
		foreach ($sql as $key) {
			$result = $bdwms->query($key);
				if(!$result){
					break;
					return 1;
				}
		}
		return 0;
	}
	public function PedidosSinStock(){
		$bdwms = $this->load->database("prodWMS", TRUE);

		$sql = "SELECT
					PKT.BODEGA,
					PKT.PKT,
					PKT.PA PALNIFICAION_AUTOMATICA,
					PKT.BATCHNBR,
					CDD.CASE_NBR LPN,
					PKT.OLA,
					PKT.DESC_OLA,
					PKT.SKU,
					IM.SKU_DESC,
					IM.MERCH_TYPE DEPTO,
					SC.CODE_DESC DESCP_DEPTO,
					PKT.CANT,
					PKT.FECHA_OLA,
					PKT.USUARIO,
					NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) DISP_CASE_PICK,
					NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) DISP_ACTIVO,
					NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='W' GROUP BY  CD.SKU_ID),0) WR, 
					NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='P' GROUP BY  CD.SKU_ID),0) PISO, 
					NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0) RACK,     
					NVL((SELECT SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) PP,
					CASE
						WHEN
							NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) = 0 AND
							NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) = 0 AND
							NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA  in('R','P','W') GROUP BY  CD.SKU_ID),0) = 0 AND
							NVL((SELECT SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) = 0
							THEN 'SIN STOCK' 
						WHEN
							NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
							NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
							NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0) > 0
							THEN 'REABASTECER' 
						WHEN
							NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
							NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
							NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA  in('R','P','W') GROUP BY  CD.SKU_ID),0) <= 0 AND
							NVL((SELECT SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) > 0
							THEN 'POR UBICAR' 
						ELSE 
							(SELECT 
							TO_CHAR(SUM(NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0)+
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0)+
								NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA  in('R','P','W') GROUP BY  CD.SKU_ID),0)
								)) TOTAL
							FROM DUAL)
					END TOTAL
				FROM 
				(
					SELECT 
						PH.WHSE BODEGA,
						PH.PKT_CTRL_NBR PKT,
						PH.ADVT_CODE PA,
						PD.BATCH_NBR BATCHNBR, 
						PI.SHIP_WAVE_NBR OLA, 
						SWP.WAVE_DESC DESC_OLA,
						PD.SKU_ID SKU, 
						PD.PKT_QTY CANT, 
						PD.MOD_DATE_TIME FECHA_OLA, 
						PD.USER_ID USUARIO
					FROM 
						PKT_HDR PH, 
						PKT_HDR_INTRNL PI, 
						PKT_DTL PD,
						SHIP_WAVE_PARM SWP
					WHERE 
						PH.PKT_CTRL_NBR = PI.PKT_CTRL_NBR
						AND PH.PKT_CTRL_NBR = PD.PKT_CTRL_NBR
						AND SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
						AND PI.STAT_CODE = '10'
						AND  PI.SHIP_WAVE_NBR=SWP.SHIP_WAVE_NBR(+)
					ORDER BY 
						PI.MOD_DATE_TIME ASC
				)PKT,
				CASE_DTL CDD,
				ITEM_MASTER IM,
				CASE_HDR CH,
				SYS_CODE SC
				WHERE 
						PKT.BATCHNBR=CDD.BATCH_NBR(+)
						AND PKT.SKU=IM.SKU_ID
						AND CDD.CASE_NBR=CH.CASE_NBR(+)
						AND SC.REC_TYPE='B' AND SC.CODE_TYPE='752' AND SC.CODE_ID=IM.MERCH_TYPE";

		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$bdwms->close();
			return $data;
		}
		else{
			return $bdwms->error();
		}
	}
	public function PP($sku){

		$bdwms = $this->load->database("prodWMS", TRUE);

		$sql = "SELECT 
				    CL.CASE_NBR,
				    SC.CODE_DESC,
				    CH.RCVD_DATE FECHA_RECEPCION,
				    LH.DSP_LOCN,
				    CL.INVN_LOCK_CODE,
				    CD.SKU_ID,
				    IM.SKU_DESC,
				    CD.ACTL_QTY,
				    CH.USER_ID
				FROM 
					CASE_LOCK CL ,
					CASE_DTL CD,
					ITEM_MASTER IM,
					CASE_HDR CH,
					LOCN_HDR LH,
					SYS_CODE SC
				WHERE 
				    CL.INVN_LOCK_CODE='PP'
				    AND CL.CASE_NBR=CD.CASE_NBR
				    AND CD.SKU_ID='$sku'
				    AND CD.SKU_ID=IM.SKU_ID
				    AND CD.CASE_NBR=CH.CASE_NBR
				    AND CH.LOCN_ID=LH.LOCN_ID(+)
				    AND CH.STAT_CODE IN(10,30)
					AND SC.REC_TYPE='S' AND SC.CODE_TYPE='509' AND SC.CODE_ID=CH.STAT_CODE";

		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$bdwms->close();
			return $data;
		}
		else{
			return $bdwms->error();
		}
	}

	public function reserva($sku){

		$bdwms = $this->load->database("prodWMS", TRUE);

		$sql = "SELECT 
					CH.CASE_NBR LPN,
					SC.CODE_DESC ESTADO, 
					CH.RCVD_DATE FECHA_RECEPCION,
					LH.DSP_LOCN LOCACION,
					CL.INVN_LOCK_CODE,
					CD.SKU_ID ARTICULO,
					IM.SKU_DESC DESCRIPCION,
					CD.ACTL_QTY CANTIDAD ,
					CH.USER_ID
				FROM 
					CASE_HDR CH,
					CASE_LOCK CL,
					CASE_DTL CD ,
					SYS_CODE SC,
					LOCN_HDR LH,
					ITEM_MASTER IM
				WHERE 
					CH.STAT_CODE=30  
					AND CH.CASE_NBR=CL.CASE_NBR(+)  
					AND CL.INVN_LOCK_CODE IS NULL  
					AND CH.CASE_NBR=CD.CASE_NBR  
					AND CD.SKU_ID='$sku'
					AND SC.REC_TYPE='S' AND SC.CODE_TYPE='509' AND SC.CODE_ID=CH.STAT_CODE
					AND CH.LOCN_ID=LH.LOCN_ID(+)
					AND CD.SKU_ID=IM.SKU_ID
					AND LH.AREA ='R'";

		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$bdwms->close();
			return $data;
		}
		else{
			return $bdwms->error();
		}

	}

	public function soloReabastecer(){

		$bdwms = $this->load->database("prodWMS", TRUE);

		$sql = "SELECT  
				    SOLO_REABASTECER.ART,
				    SOLO_REABASTECER.DESC_ART,
				    CD.ACTL_QTY CANTIDAD,
				    CD.CASE_NBR LPN,
				    SC.CODE_DESC ESTADO_LPN,
				    LH.AREA,
				    LH.DSP_LOCN
				FROM
				(
				SELECT
				    PKT.BODEGA,
				    PKT.PKT,
				    PKT.PA PALNIFICAION_AUTOMATICA,
				    PKT.BATCHNBR,
				    CDD.CASE_NBR LPN,
				    PKT.OLA,
				    PKT.DESC_OLA,
				    PKT.SKU ART,
				    IM.SKU_DESC DESC_ART, 
				    IM.MERCH_TYPE DEPTO,
				    SC.CODE_DESC DESCP_DEPTO,
				    PKT.CANT,
				    PKT.FECHA_OLA,
				    PKT.USUARIO,
				    NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) DISP_CASE_PICK,
				    NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) DISP_ACTIVO,
				    NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0) RESERVA,     
				    NVL((SELECT  SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) PP,
				    CASE
				        WHEN
				            NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) = 0 AND
				            NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) = 0 AND
				            NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0) = 0 AND
				            NVL((SELECT  SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) = 0
				            THEN 'SIN STOCK' 
				        WHEN
				            NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
				            NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
				            NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0) > 0
				            THEN 'REABASTECER' 
				        WHEN
				            NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
				            NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0) <= 0 AND
				            NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0) <= 0 AND
				            NVL((SELECT  SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) > 0
				            THEN 'POR UBICAR' 
				        ELSE 
				            (SELECT 
				            TO_CHAR(SUM(NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0)+
				                NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) > 0),0)+
				                NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.AREA ='R' GROUP BY  CD.SKU_ID),0)
				                )) TOTAL
				            FROM DUAL)
				    END TOTAL
				    FROM 
				    (
				       SELECT 
				           PH.WHSE BODEGA,
				           PH.PKT_CTRL_NBR PKT,
				           PH.ADVT_CODE PA,
				           PD.BATCH_NBR BATCHNBR, 
				           PI.SHIP_WAVE_NBR OLA, 
				           SWP.WAVE_DESC DESC_OLA,
				           PD.SKU_ID SKU, 
				           PD.PKT_QTY CANT, 
				           PD.MOD_DATE_TIME FECHA_OLA, 
				           PD.USER_ID USUARIO
				       FROM 
				           PKT_HDR PH, 
				           PKT_HDR_INTRNL PI, 
				           PKT_DTL PD,
				           SHIP_WAVE_PARM SWP
				       WHERE 
				           PH.PKT_CTRL_NBR = PI.PKT_CTRL_NBR
				           AND PH.PKT_CTRL_NBR = PD.PKT_CTRL_NBR
				           AND SUBSTR(PH.PKT_CTRL_NBR,1,3) = 'BTC'
				           AND PI.STAT_CODE = '10'
				           AND  PI.SHIP_WAVE_NBR=SWP.SHIP_WAVE_NBR(+)
				       ORDER BY 
				           PI.MOD_DATE_TIME ASC
				    )PKT,
				    CASE_DTL CDD,
				    ITEM_MASTER IM,
				    CASE_HDR CH,
				    SYS_CODE SC
				    WHERE 
				         PKT.BATCHNBR=CDD.BATCH_NBR(+)
				         AND PKT.SKU=IM.SKU_ID
				         AND CDD.CASE_NBR=CH.CASE_NBR(+)
				         AND SC.REC_TYPE='B' AND SC.CODE_TYPE='752' AND SC.CODE_ID=IM.MERCH_TYPE
				) SOLO_REABASTECER,
				   CASE_DTL CD ,
				   CASE_HDR CH,
				   SYS_CODE SC,
				   LOCN_HDR LH
				WHERE                       
				   SOLO_REABASTECER.TOTAL='REABASTECER' 
				   AND SOLO_REABASTECER.ART=CD.SKU_ID
				   AND CD.CASE_NBR=CH.CASE_NBR
				   AND CH.STAT_CODE=30
				   AND SC.REC_TYPE='S' AND SC.CODE_TYPE='509' AND SC.CODE_ID=CH.STAT_CODE
				   AND CH.LOCN_ID=LH.LOCN_ID
				   AND LH.AREA ='R'
				ORDER BY
				    SOLO_REABASTECER.ART";

		$result = $bdwms->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$bdwms->close();
			return $data;
		}
		else{
			return $bdwms->error();
		}
	}

	/*public function cargarTabla(){

		$bdwms = $this->load->database("PMMWMS", TRUE);

		$sql = "DELETE FROM CTL_DESCUADRATURA_INV_BT";

		$result = $bdwms->query($sql);
		
		$sql = "SELECT 
					ARTICULO,
					CODIGO_VTA,
					DESCRIPCION,
					SUM(CANTIDAD) CANTIDAD,
					LGBTCK_FUN_DIF_DIS_STK(CODIGO_VTA,10039,NRO_LOC_BOD,0) VENDIBLE
				FROM(
					SELECT 
						P.ARTICULO,
						R.CODIGO_VTA,
						P.DESCRIPCION,
						R.NRO_LOC_BOD,
						SUM(R.CANTIDAD) CANTIDAD
					FROM BIGT_RESERVAS R
					, BIGT_PRODUCTOS P
					WHERE R.COD_MOTIVO = 'R1'
					AND R.CODIGO_VTA = P.CODIGO_VTA
					AND R.NRO_LOC_BOD = 10095
					AND R.JORNADA_DESP='ST'
					AND NOT EXISTS (SELECT 'X' FROM BIGT_DESPACHOS D
									WHERE D.CUD = R.CUD)
					GROUP BY
						P.ARTICULO,
						R.CODIGO_VTA,
						P.DESCRIPCION,
						R.NRO_LOC_BOD
					UNION ALL
					SELECT DISTINCT P.ARTICULO,R.CODIGO_VTA,P.DESCRIPCION, D.NRO_LOC_BOD,SUM(R.CANTIDAD) CANTIDAD
					FROM BIGT_RESERVAS R
					, BIGT_DESPACHOS D
					, BIGT_PRODUCTOS P
					, BIGT_CLIENTE L
					, LGBTCK_MOV_PLA_TRF   PL
					, BIGT_MOTIVO  M
					, BIGT_MOTIVO  M2
					, BIGT_ESTADO_DESPACHO E
					WHERE R.CUD = D.CUD
					AND D.ESTADO_DESPACHO = '0'
					AND NVL(D.COD_MOTIVO,'*') <> '22'
					AND R.CODIGO_VTA = P.CODIGO_VTA
					AND L.RUT_CLIENTE(+) = R.RUT_CLIENTE
					AND D.NRO_LOC_BOD=10095
					AND PL.BTMPT_COC_UNI_DBO_K(+) = D.CUD
					AND PL.BTMPT_NRO_SUC_ORI(+) = D.NRO_LOC_BOD
					AND E.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
					AND M.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
					AND M.COD_MOTIVO(+)           = D.COD_MOTIVO
					AND M2.ESTADO_DESPACHO = 'R'
					AND M2.COD_MOTIVO(+)   = R.COD_MOTIVO
					AND PL.BTMPT_COC_EST_TRF(+) NOT IN ('NU','OL')
					AND R.JORNADA_DESP='ST'
					AND TO_CHAR(D.FECHA_VTA, 'DD/MM/YYYY') = '29/07/2020'
					GROUP BY
						P.ARTICULO,
						R.CODIGO_VTA,
						P.DESCRIPCION,
						D.NRO_LOC_BOD
					UNION ALL
					SELECT DISTINCT P.ARTICULO,R.CODIGO_VTA,P.DESCRIPCION, D.NRO_LOC_BOD,SUM(R.CANTIDAD) CANTIDAD
					FROM BIGT_RESERVAS R
					, BIGT_DESPACHOS D
					, BIGT_PRODUCTOS P
					, BIGT_CLIENTE L
					, LGBTCK_MOV_PLA_TRF   PL
					, BIGT_MOTIVO  M
					, BIGT_MOTIVO  M2
					, BIGT_ESTADO_DESPACHO E
					WHERE R.CUD = D.CUD
					AND D.ESTADO_DESPACHO IN ('1')
					AND D.COD_MOTIVO = '13'
					AND R.CODIGO_VTA = P.CODIGO_VTA
					AND L.RUT_CLIENTE(+) = R.RUT_CLIENTE
					AND D.NRO_LOC_BOD=10095
					AND PL.BTMPT_COC_UNI_DBO_K(+) = D.CUD
					AND PL.BTMPT_NRO_SUC_ORI(+) = D.NRO_LOC_BOD
					AND E.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
					AND M.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
					AND M.COD_MOTIVO(+)           = D.COD_MOTIVO
					AND M2.ESTADO_DESPACHO = 'R'
					AND M2.COD_MOTIVO(+)   = R.COD_MOTIVO
					AND PL.BTMPT_COC_EST_TRF(+) NOT IN ('NU','OL')
					AND R.JORNADA_DESP='ST'
					AND TO_CHAR(D.FECHA_VTA, 'DD/MM/YYYY') = '29/07/2020'
					GROUP BY
						P.ARTICULO,
						R.CODIGO_VTA,
						P.DESCRIPCION,
						D.NRO_LOC_BOD
				)
				GROUP BY
					ARTICULO,
					CODIGO_VTA,
					DESCRIPCION,
					LGBTCK_FUN_DIF_DIS_STK(CODIGO_VTA,10039,NRO_LOC_BOD,0)";

		$result = $this->db->query($sql);

		foreach ($result->result() as $key) {
			$sql = "INSERT INTO CTL_DESCUADRATURA_INV_BT(
						ARTICULO_CORTO,
						ARTICULO_LARGO,
						DESCRIPCION_ARTICULO,
						CANTIDAD,
						NOMBRE_CLIENTE) 
					VALUES(
						'$key->ARTICULO',
						'$key->CODIGO_VTA',
						'$key->DESCRIPCION',
						$key->CANTIDAD,
						'$key->VENDIBLE'
										)";
			
			$result = $bdwms->query($sql);
		}

		return 1;

	}*/

	public function cargarTabla(){

		$bdwms = $this->load->database("PMMWMS", TRUE);

		$sql = "DELETE FROM CTL_DESCUADRATURA_INV_BT";

		$result = $bdwms->query($sql);

		
		$sql = "SELECT 
					R.CUD,
					P.ARTICULO,
					R.CODIGO_VTA,
					P.DESCRIPCION,
					R.CANTIDAD,
					R.NRO_LOC_BOD,
					R.SUCURSAL_DESP,
					LGBTCK_FUN_DIF_DIS_STK(R.CODIGO_VTA,10039,R.NRO_LOC_BOD,0) VENDIBLE,
					R.FECHA_DESP,
					null FECHA_VTA,
					null FCHPLN,
					null ESTADO,
					R.COD_MOTIVO MOTIVO
				FROM BIGT_RESERVAS R
				, BIGT_PRODUCTOS P
				WHERE R.COD_MOTIVO = 'R1'
				AND R.CODIGO_VTA = P.CODIGO_VTA
				AND R.NRO_LOC_BOD = 10095
				AND NOT EXISTS (SELECT 'X' FROM BIGT_DESPACHOS D
								WHERE D.CUD = R.CUD)
				UNION ALL
				SELECT DISTINCT R.CUD,P.ARTICULO,R.CODIGO_VTA,P.DESCRIPCION,R.CANTIDAD,R.NRO_LOC_BOD
					, R.SUCURSAL_DESP
					, LGBTCK_FUN_DIF_DIS_STK(R.CODIGO_VTA,10039,D.NRO_LOC_BOD,0) VENDIBLE
					, D.FECHA_DESP
					, D.FECHA_VTA
					, PL.BTMPT_FCH_PLA_SUG FCHPLN
					, D.ESTADO_DESPACHO||'-'||E.DESCRIPCION_ESTADO ESTADO
					, D.COD_MOTIVO||'-'||M.DESCRIPCION_MOTIVO MOTIVO
				FROM BIGT_RESERVAS R
				, BIGT_DESPACHOS D
				, BIGT_PRODUCTOS P
				, BIGT_CLIENTE L
				, LGBTCK_MOV_PLA_TRF   PL
				, BIGT_MOTIVO  M
				, BIGT_MOTIVO  M2
				, BIGT_ESTADO_DESPACHO E
				WHERE R.CUD = D.CUD
				AND D.ESTADO_DESPACHO = '0'
				AND NVL(D.COD_MOTIVO,'*') <> '22'
				AND R.CODIGO_VTA = P.CODIGO_VTA
				AND L.RUT_CLIENTE(+) = R.RUT_CLIENTE
				AND D.NRO_LOC_BOD=10095
				AND PL.BTMPT_COC_UNI_DBO_K(+) = D.CUD
				AND PL.BTMPT_NRO_SUC_ORI(+) = D.NRO_LOC_BOD
				AND E.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
				AND M.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
				AND M.COD_MOTIVO(+)           = D.COD_MOTIVO
				AND M2.ESTADO_DESPACHO = 'R'
				AND M2.COD_MOTIVO(+)   = R.COD_MOTIVO
				AND PL.BTMPT_COC_EST_TRF(+) NOT IN ('NU','OL')
				--AND R.CUD IN ('0390050001440967600101')
				AND D.FECHA_VTA >= TO_DATE('01/01/2020', 'DD/MM/YYYY')
				UNION ALL
				SELECT DISTINCT R.CUD,P.ARTICULO,R.CODIGO_VTA,P.DESCRIPCION,R.CANTIDAD,R.NRO_LOC_BOD
					, R.SUCURSAL_DESP
					, LGBTCK_FUN_DIF_DIS_STK(R.CODIGO_VTA,10039,D.NRO_LOC_BOD,0) VENDIBLE
					, D.FECHA_DESP
					, D.FECHA_VTA
					, PL.BTMPT_FCH_PLA_SUG FCHPLN
					, D.ESTADO_DESPACHO||'-'||E.DESCRIPCION_ESTADO ESTADO
					, D.COD_MOTIVO||'-'||M.DESCRIPCION_MOTIVO MOTIVO
				FROM BIGT_RESERVAS R
				, BIGT_DESPACHOS D
				, BIGT_PRODUCTOS P
				, BIGT_CLIENTE L
				, LGBTCK_MOV_PLA_TRF   PL
				, BIGT_MOTIVO  M
				, BIGT_MOTIVO  M2
				, BIGT_ESTADO_DESPACHO E
				WHERE R.CUD = D.CUD
					AND D.ESTADO_DESPACHO IN ('1')
					AND D.COD_MOTIVO = '13'
					AND R.CODIGO_VTA = P.CODIGO_VTA
					AND L.RUT_CLIENTE(+) = R.RUT_CLIENTE
					AND D.NRO_LOC_BOD=10095
					AND PL.BTMPT_COC_UNI_DBO_K(+) = D.CUD
					AND PL.BTMPT_NRO_SUC_ORI(+) = D.NRO_LOC_BOD
					AND E.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
					AND M.ESTADO_DESPACHO(+)      = D.ESTADO_DESPACHO
					AND M.COD_MOTIVO(+)           = D.COD_MOTIVO
					AND M2.ESTADO_DESPACHO = 'R'
					AND M2.COD_MOTIVO(+)   = R.COD_MOTIVO
					AND PL.BTMPT_COC_EST_TRF(+) NOT IN ('NU','OL')
					AND D.FECHA_VTA >= TO_DATE('01/01/2020', 'DD/MM/YYYY')";

						$result = $this->db->query($sql);

						foreach ($result->result() as $key) {
							$sql = "INSERT INTO CTL_DESCUADRATURA_INV_BT(
										CUD,
										ARTICULO_CORTO,
										ARTICULO_LARGO,
										DESCRIPCION_ARTICULO,
										CANTIDAD,
										SUCURSAL_STOCK,
										SUCURSAL_DESPACHO,
										NOMBRE_CLIENTE,
										DIRECCION_DESPACHO,
										FECHA_VENTA,
										FECHA_PLANIFICACION,
										ESTADO,
										MOTIVO) 
									VALUES(
										'$key->CUD',
										'$key->ARTICULO',
										'$key->CODIGO_VTA',
										'$key->DESCRIPCION',
										$key->CANTIDAD,
										'$key->NRO_LOC_BOD',
										'$key->SUCURSAL_DESP',
										'$key->VENDIBLE',
										'$key->FECHA_DESP',
										'$key->FECHA_VTA',
										'$key->FCHPLN',
										'$key->ESTADO',
										'$key->MOTIVO'
									)";
							
							$result = $bdwms->query($sql);
		}

		return 1;

	}

	public function readPSSBT(){
		
		//$llenarTabla = $this->cargarTabla();
		$llenarTabla = 1;
		$data = array();

		$bdwms = $this->load->database("PMMWMS", TRUE);

		if($llenarTabla == 1){
			$sql = "SELECT 
						PKT.CUD,
						PKT.SKU,
						PKT.SKU_DESC,
						PKT.CANT,
						PKT.SUC_STOCK,
						PKT.SUC_DESP,
						PKT.FECHAVTA,
						PKT.FECHA_PLAN,
						PKT.ESTADO,
						PKT.MOTIVO,
						NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  > 0),0) DISP_CASE_PICK, 
						NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0) DISP_ACTIVO,
						NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD, LOCN_HDR LH WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU AND  CH.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS ='R' GROUP BY  CD.SKU_ID),0) RESERVA,
						NVL((SELECT  SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) PP,
						CASE
							WHEN
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY)- SUM(PLD.TO_BE_PIKD_QTY) > 0),0) = 0 AND
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0) = 0 AND
								NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU GROUP BY  CD.SKU_ID),0) = 0 AND
								NVL((SELECT  SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) = 0
								THEN 'SIN STOCK' 
							WHEN
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0) <= 0 AND
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY)- SUM(PLD.TO_BE_PIKD_QTY) > 0),0) <= 0 AND
								NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU GROUP BY  CD.SKU_ID),0) > 0
								THEN 'REABASTECER' 
							WHEN
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0) <= 0 AND
								NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0) <= 0 AND
								NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU GROUP BY  CD.SKU_ID),0) <= 0 AND
								NVL((SELECT  SUM(CD.ACTL_QTY) FROM CASE_DTL CD,CASE_HDR CH,CASE_LOCK CL WHERE  CD.CASE_NBR=CH.CASE_NBR AND CH.STAT_CODE IN(10,30) AND CH.CASE_NBR=CL.CASE_NBR AND CL.INVN_LOCK_CODE='PP' AND CD.SKU_ID=PKT.SKU GROUP BY CD.SKU_ID),0) > 0
								THEN 'POR UBICAR' 
							ELSE 
								(SELECT 
									TO_CHAR(SUM(NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS='C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0)+
									NVL((SELECT SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY)  FROM PICK_LOCN_DTL PLD, LOCN_HDR LH WHERE PLD.SKU_ID=PKT.SKU AND PLD.LOCN_ID=LH.LOCN_ID AND LH.LOCN_CLASS<>'C' GROUP BY PLD.SKU_ID HAVING SUM(PLD.ACTL_INVN_QTY) - SUM(PLD.TO_BE_PIKD_QTY) > 0),0)+
									NVL((SELECT SUM(CD.ACTL_QTY) CANTIDAD FROM CASE_HDR CH,CASE_LOCK CL,CASE_DTL CD WHERE CH.STAT_CODE=30  AND CH.CASE_NBR=CL.CASE_NBR(+)  AND CL.INVN_LOCK_CODE IS NULL  AND  CH.CASE_NBR=CD.CASE_NBR  AND CD.SKU_ID=PKT.SKU GROUP BY  CD.SKU_ID),0)
									)) TOTAL
								FROM DUAL)
						END TOTAL
					FROM
					(
						SELECT  
							BT.CUD CUD,
							RTRIM(BT.ARTICULO_CORTO) SKU,
							BT.DESCRIPCION_ARTICULO SKU_DESC,
							BT.CANTIDAD CANT,
							BT.SUCURSAL_STOCK SUC_STOCK,
							BT.SUCURSAL_DESPACHO SUC_DESP,
							BT.FECHA_VENTA FECHAVTA,
							BT.FECHA_PLANIFICACION FECHA_PLAN,
							BT.ESTADO ESTADO,
							BT.MOTIVO MOTIVO 
						FROM 
							CTL_DESCUADRATURA_INV_BT BT
					)PKT";

			$result = $bdwms->query($sql);
			if($result || $result != null){
				$data = json_encode($result->result());
				$bdwms->close();
				return $data;
			}
			else{
				return $bdwms->error();
			}				
		}else{
			return $data;
		}
	}
}	