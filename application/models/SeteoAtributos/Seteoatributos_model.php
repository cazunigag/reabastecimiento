<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Seteoatributos_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database('prodWMS');
		
	}
	public function infoSku($sku){
		$sql = "SELECT
					IM.SKU_ID,
					IM.EXP_LICN_SYMBOL,
					IM.SKU_DESC,
					IM.MERCH_TYPE,
					SC.CODE_DESC,
					IM.SALE_GRP,
					IM.COMMODITY_CODE,
					IM.SPL_INSTR_1,
					IM.COMMODITY_LEVEL_DESC
				FROM
					ITEM_MASTER IM,
					SYS_CODE SC
				WHERE 
					IM.SKU_ID = '$sku'
					AND SC.REC_TYPE = 'B'
					AND SC.CODE_TYPE = '752'
					AND IM.MERCH_TYPE = TO_CHAR(SC.CODE_ID)";

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
	public function cboCartonType(){
		$DB2 = $this->load->database('PMMPRODCONT', TRUE);
		$sql = "SELECT 
					C.ATR_CODE AS CARTON_TYPE,
					C.ATR_CODE_DESC
				FROM
					BASACDEE C,
					BASAHREE D,
					BASATYEE E
				WHERE
					D.ATR_TYP_TECH_KEY = E.ATR_TYP_TECH_KEY
					AND C.ATR_HDR_TECH_KEY = D.ATR_HDR_TECH_KEY
					AND E.ATR_TYP_TECH_KEY = 10
					AND C.ATR_HDR_TECH_KEY = 232
				ORDER BY
					C.ATR_CODE";
		$result = $DB2->query($sql);
		if($result || $result != null){
			$resultado = json_encode($result->result());
			$this->db->close();
			return $resultado;
		}
		else{
			return $this->db->error();
		}			
	}
}