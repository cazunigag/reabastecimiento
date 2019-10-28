<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LPNDemora_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database('prodWMS');
		
	}
	public function totalDemoraFecha(){
		$sql = "SELECT
					TO_CHAR(CH.INCUB_DATE, 'YYYY/MM/DD HH24:MI:SS') AS INCUB_DATE,
					COUNT(*) AS TOTAL
				FROM
					CASE_HDR CH,
					ASN_HDR AH
				WHERE
					CH.INCUB_DATE IS NOT NULL
					AND CH.ORIG_SHPMT_NBR = AH.SHPMT_NBR
					AND AH.SHPMT_NBR LIKE '%C%'
				GROUP BY
					CH.INCUB_DATE
				ORDER BY
					CH.INCUB_DATE";
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
	public function resumenDemoraFecha($fecha){
		$sql = "SELECT
					CH.ORIG_SHPMT_NBR ASN,
					AH.STAT_CODE ESTADO_ASN,
					SC_ASN.CODE_DESC DESC_ESTADO_ASN,
					CH.CASE_NBR LPN,
					CH.INCUB_DATE FEC_LIBERACION,
					CH.STAT_CODE ESTADO_LPN,
					SC_CASE.CODE_DESC DESC_ESTADO_LPN,
					(SELECT LH.DSP_LOCN FROM LOCN_HDR LH WHERE CH.LOCN_ID = LH.LOCN_ID) UBICACION_LPN,
					CARTON.CARTON_NBR CARTON,
					CARTON.STAT_CODE ESTADO_CARTON,
					(SELECT SYS_CODE.CODE_DESC FROM SYS_CODE WHERE SYS_CODE.REC_TYPE = 'S' AND SYS_CODE.CODE_TYPE = '502'
					AND SYS_CODE.CODE_ID = CARTON.STAT_CODE) DESC_ESTADO_CARTON,
					(SELECT LOCN_HDR.DSP_LOCN FROM LOCN_HDR WHERE CARTON.CURR_LOCN_ID = LOCN_HDR.LOCN_ID) UBICACION_CARTON
				FROM
					CASE_HDR CH,
					CARTON_HDR CARTON,
					SYS_CODE SC_CASE,
					ASN_HDR AH,
					SYS_CODE SC_ASN
				WHERE
					CH.INCUB_DATE IS NOT NULL
					AND AH.SHPMT_NBR LIKE '%C%'
					AND CH.CASE_NBR = CARTON.CARTON_NBR(+)
					AND (SC_CASE.REC_TYPE = 'S' AND SC_CASE.CODE_TYPE = '509' AND SC_CASE.CODE_ID = CH.STAT_CODE)
					AND CH.ORIG_SHPMT_NBR = AH.SHPMT_NBR
					AND (SC_ASN.REC_TYPE = 'S' AND SC_ASN.CODE_TYPE = '564' AND SC_ASN.CODE_ID = AH.STAT_CODE)
					AND TRUNC(CH.INCUB_DATE) = TRUNC(TO_DATE('$fecha', 'YYYY/MM/DD'))
				ORDER BY
					CH.INCUB_DATE";

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