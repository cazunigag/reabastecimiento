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
		$DBWMS = $this->load->database("prodWMS",TRUE);
		$DBPMM = $this->load->database("PMMPRODCONT",TRUE);
		$sqlwms = "SELECT 
						WMS.SHPMT_NBR,
						WMS.VENDOR_ID,
						WMS.REP_NAME,
						WMS.MANIF_NBR,
						WMS.PO_NBR,
						WMS.VERF_DATE_TIME,
						CASE
					        WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))
					        ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*24),24)))
					    END ||':'||
					    CASE
					        WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))
					        ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*60*24),60)))
					    END ||':'||
					    CASE
					        WHEN LENGTH(TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))) = 1 THEN '0'|| TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))
					        ELSE TO_CHAR(TRUNC(MOD(((TO_DATE(WMS.SYSTEMDATE, 'DD/MM/YYYY HH24:MI:SS') - TO_DATE(WMS.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS'))*60*60*24),60)))
					    END AS DIFERENCIA_HHMMSS,
					    WMS.UNITS_RCVD
					FROM
						(SELECT
							AH.SHPMT_NBR,
							VM.VENDOR_ID,
							AH.REP_NAME,
							AH.MANIF_NBR,
							AD.PO_NBR,
							TO_CHAR(AH.VERF_DATE_TIME, 'DD/MM/YYYY HH24:MI:SS') AS VERF_DATE_TIME,
							TO_CHAR(SYSDATE, 'DD/MM/YYYY HH24:MI:SS') AS SYSTEMDATE,
							SUM(AD.UNITS_RCVD) AS UNITS_RCVD
						FROM
							ASN_HDR AH,
							ASN_DTL AD,
							VENDOR_MASTER VM
						WHERE
							TRUNC(AH.VERF_DATE_TIME) = TRUNC(SYSDATE)
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
							VM.VENDOR_ID,
							AH.MANIF_NBR) WMS";

		$resultWMS = $DBWMS->query($sqlwms)->result();
		foreach ($resultWMS as $key) {

			$sqlPMM = "SELECT
							RCV.RCV_DOC_NUMBER,
							RCVS.RCV_SESSION_ID,
							RCVS.PMG_PO_NUMBER,
							SUM(RCVS.RCV_SELL_QTY)
						FROM
							RCVDOCEE RCV,
							RCVSSDEE RCVS
						WHERE
							TRUNC(RCV.RCV_DATE_DOC_OPN) = TRUNC(SYSDATE)
							AND RCV.ORG_LVL_CHILD = 300
							AND RCV.RCV_DOC_ID = RCVS.RCV_DOC_ID
							AND RCV.RCV_DOC_NUMBER = '$key->MANIF_NBR'
							AND RCVS.PMG_PO_NUMBER = '$key->PO_NBR'
							AND RCVS.PRD_UPC IS NOT NULL
						GROUP BY
							RCV.RCV_DOC_ID,
							RCV.RCV_DOC_NUMBER,
							RCVS.RCV_SESSION_ID,
							RCVS.PMG_PO_NUMBER
						ORDER BY
							RCV.RCV_DOC_NUMBER";

			$existe = $DBPMM->query($sqlPMM)->num_rows();
			if($existe == 0){
				if(substr($key->DIFERENCIA_HHMMSS,3,2)>=30){
					array_push($noenviados, $key);
				}	
			}
		}
		return json_encode($noenviados);
	}
}	