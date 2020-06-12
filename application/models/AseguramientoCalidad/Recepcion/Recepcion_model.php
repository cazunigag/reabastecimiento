<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recepcion_model extends CI_Model{

	public function __construct(){
		$this->load->database('PMMWMS');
	}

	public function read(){

		$sql = "SELECT 
				    SHPMT_NBR,
				    CREATE_DATE_TIME,
				    VERF_DATE_TIME,
				    UNITS_RCVD,
				    CASES_RCVD,
				    INSERT_DATE_TIME,
				    INTERNAL_STATE,
				    VIPME_COD_EST_K_ORIGEN, 
				    VIPME_COD_EST_K_DESTINO,
				    CASE
				        WHEN INTERNAL_STATE = 0 THEN 'Sin procesar'
				        WHEN INTERNAL_STATE = 10 THEN 'Con informacion PIX_TRAN'
				        WHEN INTERNAL_STATE = 11 THEN 'Sin informacion PIX_TRAN'
				        WHEN INTERNAL_STATE = 20 THEN 'Actulaicion Estado 90'
				        WHEN INTERNAL_STATE = 21 THEN 'Sin Actualzación 90'
				        WHEN INTERNAL_STATE = 30 THEN 'Con Informacion PIX_TRAN_W06'
				        WHEN INTERNAL_STATE = 31 THEN 'Sin Informacion PIX_TRAN_W06'
				        WHEN INTERNAL_STATE = 40 THEN 'Proceso OK PIX_TRAN_W06'
				        WHEN INTERNAL_STATE = 41 THEN 'SIN Proceso OK PIX_TRAN_W06'
				    END DESC_ESTADO,
				    INTERNAL_STATE_LPN
				FROM 
				    ASN_HDR_CHG 
				ORDER BY 1 DESC";

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

	public function detalle($asns){

		$select = "";

		foreach ($asns as $key) {
			if(next($asns) == false){
				$select = $select.$key->SHPMT_NBR;
			}else{
				$select = $select.$key->SHPMT_NBR."','";
			}
		}

		$sql = "SELECT * FROM CASE_HDR_CHG WHERE SHPMT_NBR IN ('$select')";

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

	public function reprocesar($asns){

		foreach ($asns as $key) {

			if($key->INTERNAL_STATE == 11){
				$sql = "UPDATE ASN_HDR_CHG SET INTERNAL_STATE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'";
				$result = $this->db->query($sql);
				if($result || $result != null){
					$response = 0;
				}
				else{
					$response = 1;
				}
			}elseif ($key->INTERNAL_STATE == 21) {
				$sql = "UPDATE ASN_HDR_CHG SET INTERNAL_STATE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'";
				$result = $this->db->query($sql);
				if($result || $result != null){
					$response = 0;
				}
				else{
					$response = 1;
				}
			}elseif ($key->INTERNAL_STATE == 31) {
				$sql = "UPDATE ASN_HDR_CHG SET INTERNAL_STATE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'";
				$result = $this->db->query($sql);
				if($result || $result != null){
					$response = 0;
				}
				else{
					$response = 1;
				}
			}elseif ($key->INTERNAL_STATE == 41) {
				$sql = "UPDATE ASN_HDR_CHG SET INTERNAL_STATE = 0 WHERE SHPMT_NBR = '$key->SHPMT_NBR'";
				$result = $this->db->query($sql);
				if($result || $result != null){
					$response = 0;
				}
				else{
					$response = 1;
				}
			}
			if($response == 1){
				break;
			}
		}
		return $response;
	}

	public function detalleErrInterfaz($asns){

		$db2 = $this->load->database('PMMQA', true);

		$select = "";

		foreach ($asns as $key) {
			if(next($asns) == false){
				$select = $select.$key->SHPMT_NBR;
			}else{
				$select = $select.$key->SHPMT_NBR."','";
			}
		}

		$sql = "SELECT
				/*+ PUSH_SUBQ
				INDEX (C LGVIWP_MVM_TRN_IX_AK01) 
				INDEX (D )
				INDEX (M )
				INDEX (ED )
				INDEX (EO )
				*/
				 C.VIPTR_NRO_COR_MVM_K COR_MVM,
				 C.VIPMT_COC_TRN_K     COC_TRN,
				 VIPTR_NRO_TRN         TRAN_NBR,
				 VIPTR_COC_ASN         ASN,
				 VIPTR_NRO_DCT         NRO_DCT,
				 VIPTR_COC_TIP         TRAN_TYPE,
				 VIPTR_COC_COD         TRAN_CODE,
				 VIPTR_COC_ACN         ACT_TRN,
				 VIPTR_COC_RAZ         RSN_TRN,
				 EO.VIPME_DES_EST      GLS_ORE,
				 ED.VIPME_DES_EST      GLS_DTN,
				 C.VIPTR_FCH_CRC       FCH_ORE,
				 C.VIPTR_FCH_CRG       FCH_DTN,
				 D.VIPDV_NRO_COR_DET_K COR_DET,
				 C.VIPTR_NRO_COR_MVM_K COR_MV2,
				 D.VIPMV_COD_VAL_K     COC_VAL,
				 M.VIPMV_DES_VAL       DES_VAL,
				 D.VIPDV_DML_VAL       DML,
				 D.VIPDV_NRO           NRO,
				 D.VIPDV_GLS           GLS,
				 D.VIPDV_FCH           FCH
				  FROM LGVIWP_MAE_EST EO,
				       LGVIWP_MAE_EST ED,
				       LGVIWP_MAE_VAL M,
				       LGVIWP_DET_VAL D,
				       LGVIWP_MVM_TRN C
				 WHERE M.VIPMV_COD_VAL_K = D.VIPMV_COD_VAL_K AND
				       D.VIPTR_NRO_COR_MVM_K = C.VIPTR_NRO_COR_MVM_K AND
				       EO.VIPME_COD_EST_K = C.VIPTR_COD_EST_ORE AND
				       ED.VIPME_COD_EST_K = C.VIPTR_COD_EST_DTN AND
				       C.VIPTR_NRO_TRN IN (SELECT A.TRAN_NBR FROM PIX_TRAN_W06 A WHERE TRIM(A.REF_FIELD_1) IN ('$select'))
				 ORDER BY C.VIPTR_NRO_COR_MVM_K DESC,
				          D.VIPDV_NRO_COR_DET_K DESC";

		$result = $db2->query($sql);
		if($result || $result != null){
			$data = json_encode($result->result());
			$db2->close();
			return $data;
		}
		else{
			return $db2->error();
		}
	}
}
