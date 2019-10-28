<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class alertasPMM_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$DBWMS = $this->load->database("prodWMS",TRUE);
		$DBPMM = $this->load->database("PMMPRODCONT",TRUE);
	}

	public function diferenciasPMMWMS(){
		$noenviados = array();
		date_default_timezone_set("America/Santiago");
		$DBWMS = $this->load->database("prodWMS",TRUE);
		$DBPMM = $this->load->database("PMMPRODCONT",TRUE);
		$sqlwms = "SELECT 
						WMS.SHPMT_NBR,
						WMS.VENDOR_ID,
						WMS.REP_NAME,
						WMS.MANIF_NBR,
						WMS.PO_NBR,
						WMS.VERF_DATE_TIME,
					    WMS.UNITS_RCVD
					FROM
						(SELECT
							AH.SHPMT_NBR,
							VM.VENDOR_ID,
							AH.REP_NAME,
							AH.MANIF_NBR,
							AD.PO_NBR,
							TO_CHAR(AH.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS VERF_DATE_TIME,
							SUM(AD.UNITS_RCVD) AS UNITS_RCVD
						FROM
							ASN_HDR AH,
							ASN_DTL AD,
							VENDOR_MASTER VM
						WHERE
							TRUNC(AH.VERF_DATE_TIME) >= TRUNC(SYSDATE)-3
							AND AH.MANIF_NBR IS NOT NULL
							AND SUBSTR(AH.SHPMT_NBR,1,3) IN ('POR','BTR')
							AND AH.SHPMT_NBR = AD.SHPMT_NBR
							AND AD.VENDOR_MASTER_ID = VM.VENDOR_MASTER_ID
							AND AH.STAT_CODE >= 90
						GROUP BY
							AH.SHPMT_NBR,
							VM.VENDOR_ID,
							AH.REP_NAME,
							AH.MANIF_NBR,
							AD.PO_NBR,
							TO_CHAR(AH.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS')
						HAVING
							SUM(AD.UNITS_RCVD) > 0
						ORDER BY
							AH.MANIF_NBR) WMS";

		$sqlPMM = "SELECT
						RCV.RCV_DOC_NUMBER,
						RCVS.RCV_SESSION_ID,
						RCVS.PMG_PO_NUMBER
					FROM
						RCVDOCEE RCV,
						RCVSSDEE RCVS
					WHERE
						TRUNC(RCV.RCV_DATE_DOC_CLO) >= TRUNC(SYSDATE)-3
						AND RCV.ORG_LVL_CHILD = 300
						AND RCV.RCV_DOC_ID = RCVS.RCV_DOC_ID
						--AND RCVS.PRD_UPC IS NOT NULL
					GROUP BY
						RCV.RCV_DOC_NUMBER,
						RCVS.RCV_SESSION_ID,
						RCVS.PMG_PO_NUMBER
					ORDER BY
						RCV.RCV_DOC_NUMBER";
												
		$resultWMS = $DBWMS->query($sqlwms)->result();
		$resultPMM = $DBPMM->query($sqlPMM)->result();
		$systemdate=date('d/m/Y H:i:s');
		$existe = 0;
		foreach ($resultWMS as $wms) {
			$fechaCierre=$wms->VERF_DATE_TIME;
			foreach ($resultPMM as $pmm) {
				if(trim($wms->MANIF_NBR) == trim($pmm->RCV_DOC_NUMBER) && trim($wms->PO_NBR) == trim($pmm->PMG_PO_NUMBER)){
					$existe = 1;
					break;
				}
			}
			if($existe == 0){
				if(substr($systemdate,0,10) == substr($fechaCierre,0,10)){
					if(substr($systemdate,11,2) == substr($fechaCierre,11,2)){
						if((substr($systemdate,14,2) - substr($fechaCierre,14,2)) >= 30){
							array_push($noenviados, $wms);
						}
					}elseif ((substr($systemdate,11,2) - substr($fechaCierre,11,2)) >= 1){
						if(((60 - substr($fechaCierre,14,2)) + substr($systemdate,14,2)) >= 30){
							array_push($noenviados, $wms);
						}
					}
				}elseif ((substr($systemdate,6,4) - substr($fechaCierre,6,4)) >= 1) {
					array_push($noenviados, $wms);
				}elseif ((substr($systemdate,3,2) - substr($fechaCierre,3,2)) >= 1) {
					array_push($noenviados, $wms);
				}elseif ((substr($systemdate,0,2) - substr($fechaCierre,0,2)) >= 1) {
					array_push($noenviados, $wms);
				}
			}
			$existe = 0;

		}
		return json_encode($noenviados);
	}
	public function difCargaPMMWMS(){
		$noenviados = array();
		date_default_timezone_set("America/Santiago");
		$DBWMS = $this->load->database("prodWMS",TRUE);
		$sqlWMS = "SELECT
						OOL.INVC_BATCH_NBR BATCH,
						OOL.LOAD_NBR CARGA,
						OPH.STORE_NBR SUC_DESTINO,
						SM.NAME DESC_SUC_DESTINO,
						OOL.TRLR_NBR PATENTE,
						TO_CHAR(OOL.MOD_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') FECHA_CIERRE
					FROM
						OUTPT_OUTBD_LOAD OOL,
						OUTPT_PKT_HDR OPH,
						OUTPT_PKT_DTL OPD,
						STORE_MASTER SM,
						TRFMFHEE TRF
					WHERE
						TRUNC(OOL.MOD_DATE_TIME) = TRUNC(SYSDATE)
						AND OOL.INVC_BATCH_NBR = OPH.INVC_BATCH_NBR
						AND OPH.STORE_NBR IS NOT NULL
						AND OOL.TRLR_NBR NOT IN ('EX9999')
						AND OPH.STORE_NBR = SM.STORE_NBR
						AND OPH.PKT_CTRL_NBR = OPD.PKT_CTRL_NBR
						AND OOL.INVC_BATCH_NBR = OPD.INVC_BATCH_NBR
						AND SUBSTR(OPD.PKT_CTRL_NBR,1,3) <> 'BTC'
						AND SUBSTR(OPD.DISTRO_NBR,1,3) <> 'BIG'
						AND OOL.LOAD_NBR = TRF.TRF_MANIFEST_ID(+)
						AND TRF.TRF_MANIFEST_ID IS NULL
					GROUP BY
						OOL.INVC_BATCH_NBR,
						OOL.LOAD_NBR,
						OPH.STORE_NBR,
						SM.NAME,
						OOL.TRLR_NBR,
						TO_CHAR(OOL.MOD_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS')
					ORDER BY
						OOL.LOAD_NBR";
		
		$result = $DBWMS->query($sqlWMS)->result();
		$systemdate=date('d/m/Y H:i:s');
		foreach ($result as $key) {
			$fechaCierre=$key->FECHA_CIERRE;
			if(substr($systemdate,0,10) == substr($fechaCierre,0,10)){
				if(substr($systemdate,11,2) == substr($fechaCierre,11,2)){
					if((substr($systemdate,14,2) - substr($fechaCierre,14,2)) >= 30){
						array_push($noenviados, $wms);
					}
				}elseif ((substr($systemdate,11,2) - substr($fechaCierre,11,2)) >= 1){
					if(((60 - substr($fechaCierre,14,2)) + substr($systemdate,14,2)) >= 30){
						array_push($noenviados, $wms);
					}
				}
			}elseif ((substr($systemdate,6,4) - substr($fechaCierre,6,4)) >= 1) {
				array_push($noenviados, $wms);
			}elseif ((substr($systemdate,3,2) - substr($fechaCierre,3,2)) >= 1) {
				array_push($noenviados, $wms);
			}elseif ((substr($systemdate,0,2) - substr($fechaCierre,0,2)) >= 1) {
				array_push($noenviados, $wms);
			}
		}
		return json_encode($noenviados);
	}
	public function detalleErrCargaPMM($carga){
		$DBPMM = $this->load->database("PMMPRODCONT",TRUE);
		$sql = "SELECT
					SDI.ERROR_CODE,
					REJ.REJ_DESC,
					SDI.MNFST_NUMBER,
					SDI.CARTON_NUMBER,
					SDI.FROM_LOC,
					SDI.TO_LOC,
					SDI.TRF_NUMBER,
					SDI.DATE_CREATED
				FROM 
					SDITRFDTI SDI,
					SDIREJCD REJ
				WHERE
					SDI.MNFST_NUMBER = '$carga'
					AND SDI.ERROR_CODE <> 0
					AND SDI.ERROR_CODE = REJ.REJ_CODE
				GROUP BY
					SDI.ERROR_CODE,
					REJ.REJ_DESC,
					SDI.MNFST_NUMBER,
				    SDI.CARTON_NUMBER,
				    SDI.FROM_LOC ,
				    SDI.TO_LOC ,
				    SDI.TRF_NUMBER ,
				    SDI.DATE_CREATED";

		$result = $DBPMM->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$DBPMM->close();
			return $data;
		}
		else{
			return $DBPMM->error();
		}
	}
}	