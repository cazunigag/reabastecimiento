<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PKTCancelados_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database('PMMWMS');
		
	}

	public function read(){

		$sqlsrv = $this->load->database('SQLSRV', true);

		$data = array();

		$lgbtck_aux_trs_dbo = 'LGBTCK_AUX_TRS_DBO@L$ODBMS_ADM_POLLC1';
		$bigt_productos = 'BIGT_PRODUCTOS@L$ODBMS_ADM_POLLC1';

		$ticketAuris = 0;
		$porcentaje = "";

		$sql = "SELECT A.BTATB_FCH_VTA,
				       A.TOTAL_PKT_CREADOS_BT,
				       B.TOTAL_PKT_CANCELADOS_WMS
				  FROM (SELECT TRUNC(X.BTATB_FCH_VTA) BTATB_FCH_VTA,
				               COUNT(*) TOTAL_PKT_CREADOS_BT
				          FROM $lgbtck_aux_trs_dbo X,
				               $bigt_productos     P
				         WHERE P.ARTICULO = X.BTATB_COC_PRD AND
				               TRUNC(X.BTATB_FCH_ENV_MSJ) >= TRUNC(SYSDATE) - 30
				         GROUP BY TRUNC(X.BTATB_FCH_VTA)) A,
				       (SELECT TRUNC(PKT_HDR.MOD_DATE_TIME) MOD_DATE_TIME,
				               COUNT(*) TOTAL_PKT_CANCELADOS_WMS
				          FROM PKT_HDR_INTRNL,
				               PKT_HDR,
				               PKT_DTL
				         WHERE PKT_HDR.PKT_CTRL_NBR = PKT_HDR_INTRNL.PKT_CTRL_NBR AND
				               PKT_HDR_INTRNL.PKT_CTRL_NBR = PKT_DTL.PKT_CTRL_NBR AND
				               PKT_HDR.ORD_TYPE IN ('I', 'B') AND
				               PKT_HDR_INTRNL.STAT_CODE = '99' AND
				               TRUNC(PKT_HDR.MOD_DATE_TIME) >= TRUNC(SYSDATE) - 30
				         GROUP BY TRUNC(PKT_HDR.MOD_DATE_TIME)) B
				 WHERE A.BTATB_FCH_VTA = B.MOD_DATE_TIME
				 ORDER BY A.BTATB_FCH_VTA DESC";

		$result = $this->db->query($sql);
		/*foreach ($result->result() as $key) {

			$porcentaje = 0;

			$sql = "SELECT 
					   A.MARK_FOR CUD
					FROM 
					   PKT_HDR A,
					   PKT_HDR_INTRNL B
					WHERE 
					   A.PKT_CTRL_NBR = B.PKT_CTRL_NBR
					   AND A.ORD_TYPE IN ('I', 'B')
					   AND STAT_CODE = 99
					   AND TRUNC(B.MOD_DATE_TIME) = '$key->BTATB_FCH_VTA'";

			$result2 = $this->db->query($sql);

			foreach ($result2->result() as $cuds) {
				$sql = "SELECT 
							NROTICKET 
						FROM 
							[dbo].[AURIS_ABIERTOS] 
						WHERE 
							TRIM(CUD) = '$cuds->CUD' 
							OR TRIM(CUD1) = '$cuds->CUD' 
							OR TRIM(CUD2) = '$cuds->CUD'
							AND DESCMOTIVO2 = 'Sin Stock'";

				$resultAbiertos = $sqlsrv->query($sql);
				$ticketAbierto = $resultAbiertos->result();
				$sql = "SELECT 
							NROTICKET 
						FROM 
							[dbo].[AURIS_HIST] 
						WHERE 
							TRIM(CUD) = '$cuds->CUD' 
							OR TRIM(CUD1) = '$cuds->CUD' 
							OR TRIM(CUD2) = '$cuds->CUD'
							AND DESCMOTIVO2 = 'Sin Stock'";

				$resultHistoricos = $sqlsrv->query($sql);
				$ticketHistorico = $resultHistoricos->result();

				if(sizeof($ticketHistorico) > 0 || sizeof($ticketAbierto) > 0){
					$ticketAuris ++;
				}
			}

			$porcentaje = $ticketAuris/$key->TOTAL_PKT_CANCELADOS_WMS*100;

			array_push($data, array(
				"BTATB_FCH_VTA" => $key->BTATB_FCH_VTA,
				"TOTAL_PKT_CREADOS_BT" => $key->TOTAL_PKT_CREADOS_BT,
				"TOTAL_PKT_CANCELADOS_WMS" => $key->TOTAL_PKT_CANCELADOS_WMS,
				"CANTIDAD_RECLAMOS" => $ticketAuris,
				"PORCENTAJE" => $porcentaje
			));
		}*/
		if($result || $result != null){
			$data = json_encode($result->result());
			$this->db->close();
			return $data;
		}
		else{
			return $this->db->error();
		}
	}

	public function detalle($fecha){
		$sqlsrv = $this->load->database('SQLSRV', true);

		$lgbtck_aux_trs_dbo = 'LGBTCK_AUX_TRS_DBO@L$ODBMS_ADM_POLLC1';
		$bigt_productos = 'BIGT_PRODUCTOS@L$ODBMS_ADM_POLLC1';

		$data = array();

		$sql = "SELECT X.BTATB_FCH_VTA     FCHVTA
		     		 , TRIM(X.BTATB_COC_DBO_K)   NROPKT
		     		 , TRIM(X.BTATB_COC_UNI_DBO) NROCUD
		     		 , X.BTATB_COC_PRD     CODSKU
		     		 , P.CODIGO_VTA        CODVTA
		     		 , X.BTATB_CAN_DBO     CANTID
				FROM  $lgbtck_aux_trs_dbo X
		   			, $bigt_productos     P
				WHERE P.ARTICULO = X.BTATB_COC_PRD
		  		AND TRUNC(X.BTATB_FCH_VTA) = '$fecha'
		  		ORDER BY 1,2";

		$result = $this->db->query($sql);
		$in = "";
		if($result || $result != null){

			foreach ($result->result() as $cuds) {
				$sql = "SELECT TRUNC(PKT_HDR.MOD_DATE_TIME) MOD_DATE_TIME
						  FROM PKT_HDR_INTRNL,
						       PKT_HDR
						 WHERE PKT_HDR.PKT_CTRL_NBR = PKT_HDR_INTRNL.PKT_CTRL_NBR AND
						       PKT_HDR.ORD_TYPE IN ('I', 'B') AND
						       PKT_HDR_INTRNL.STAT_CODE = '99' AND
						       TRIM(PKT_HDR.PKT_CTRL_NBR) = '$cuds->NROPKT'";

				$FechaCancelacion = $this->db->query($sql);
				$FCancelacion = "";
				foreach ($FechaCancelacion->result() as $key) {
					$FCancelacion = $key->MOD_DATE_TIME;
				}

				/*$sql = "SELECT 
							NROTICKET 
						FROM 
							[dbo].[AURIS_ABIERTOS] 
						WHERE 
							TRIM(CUD) = '$cuds->NROCUD' 
							OR TRIM(CUD1) = '$cuds->NROCUD' 
							OR TRIM(CUD2) = '$cuds->NROCUD'
							AND DESCMOTIVO2 = 'Sin Stock'";

				$resultAbiertos = $sqlsrv->query($sql);
				$ticketAbierto = $resultAbiertos->result();
				$sql = "SELECT 
							NROTICKET 
						FROM 
							[dbo].[AURIS_HIST] 
						WHERE 
							TRIM(CUD) = '$cuds->NROCUD' 
							OR TRIM(CUD1) = '$cuds->NROCUD' 
							OR TRIM(CUD2) = '$cuds->NROCUD'
							AND DESCMOTIVO2 = 'Sin Stock'";

				$resultHistoricos = $sqlsrv->query($sql);
				$ticketHistorico = $resultHistoricos->result();
				$ticketAuris = 'No';

				if(sizeof($ticketHistorico) > 0 || sizeof($ticketAbierto) > 0){
					$ticketAuris = 'Si';
				}*/
				array_push($data, array(
					"FCHVTA" => $cuds->FCHVTA,
					"NROPKT" => $cuds->NROPKT,
					"NROCUD" => $cuds->NROCUD,
					"CODSKU" => $cuds->CODSKU,
					"CODVTA" => $cuds->CODVTA,
					"CANTID" => $cuds->CANTID,
					"FECHA_MODIFICACION" => $FCancelacion,
					//"TICKETAURIS" => $ticketAuris,
				));
			//}
			}
			$this->db->close();
			return json_encode($data);
		}
		else{
			return $this->db->error();
		}
	}
}