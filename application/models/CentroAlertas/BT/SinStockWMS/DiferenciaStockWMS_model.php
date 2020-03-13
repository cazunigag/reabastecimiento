<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiferenciaStockWMS_model extends CI_Model{

	public function __construct(){
		$this->load->database('PMMWMS');
	}

	public function read(){

		$db2 = $this->load->database('BTPROD', TRUE);

		$datos = array();
		$big_stock = 'BIGT_STOCK@L$ODBMS_ADM_POLLC1';
		$bigt_productos = 'BIGT_PRODUCTOS@L$ODBMS_ADM_POLLC1';


		$sql = "SELECT 
				    DIFERENCIA.ARTICULO SKU_ID,
				    DIFERENCIA.SKU_BRCD,
				    DIFERENCIA.DISP_BT,
				    DIFERENCIA.DESCRIPCION,
				    DIFERENCIA.DEPTO,
				    DIFERENCIA.DEPTO_DESC,
				    DIFERENCIA.ACTIVO,
				    DIFERENCIA.RESERVA,
				    DIFERENCIA.BLOQUEO
				FROM 
				(
				    SELECT 
				        P.ARTICULO ARTICULO,
				        IM.SKU_BRCD,
				        S.DISPONIBLE DISP_BT,
				        IM.SKU_DESC DESCRIPCION,
				        IM.MERCH_TYPE DEPTO,
				        CASE
				            WHEN IM.MERCH_TYPE IS NULL THEN 'ARTICULO NO EXISTE EN WMS'
				            WHEN IM.MERCH_TYPE IS NOT NULL  THEN (SELECT SC.CODE_DESC FROM SYS_CODE SC WHERE SC.REC_TYPE='B' AND SC.CODE_TYPE='752' AND SC.CODE_ID=IM.MERCH_TYPE) 
				        END DEPTO_DESC,    
				        CASE
				            WHEN  (SELECT  SUM(PLD.ACTL_INVN_QTY) TOTAL FROM  PICK_LOCN_DTL PLD WHERE PLD.SKU_ID = TRIM(P.ARTICULO)  GROUP BY  PLD.SKU_ID)  >0  THEN   (SELECT  SUM(PLD.ACTL_INVN_QTY) TOTAL FROM  PICK_LOCN_DTL PLD WHERE PLD.SKU_ID = TRIM(P.ARTICULO)  GROUP BY  PLD.SKU_ID) 
				        ELSE
				                0
				       END ACTIVO,
				       CASE
				         WHEN (SELECT SUM(CD.ACTL_QTY) TOTAL FROM CASE_DTL CD WHERE  CD.SKU_ID =TRIM(P.ARTICULO)   AND  CD.CASE_NBR NOT IN (SELECT CL.CASE_NBR FROM CASE_LOCK CL) GROUP BY CD.SKU_ID) >0  THEN (SELECT SUM(CD.ACTL_QTY) TOTAL FROM CASE_DTL CD WHERE  CD.SKU_ID =TRIM(P.ARTICULO)   AND  CD.CASE_NBR NOT IN (SELECT CL.CASE_NBR FROM CASE_LOCK CL) GROUP BY CD.SKU_ID) 
				         ELSE
				             0
				         END RESERVA,
				         CASE
				        WHEN (SELECT  COUNT(*) FROM  CASE_DTL CD, CASE_LOCK CL, CASE_HDR CH WHERE  CD.SKU_ID =TRIM(P.ARTICULO) AND CD.CASE_NBR=CL.CASE_NBR AND CL.CASE_NBR = CH.CASE_NBR AND CH.STAT_CODE IN(10,30) GROUP BY CD.SKU_ID) >0  THEN 'SI'
				         ELSE
				                 'NO'
				         END BLOQUEO
				     FROM 
				        $big_stock S , 
				        $bigt_productos P,
				       ITEM_MASTER IM
				    WHERE 
				               S.CODIGO_VTA = P.CODIGO_VTA
				        AND S.NRO_LOC_BOD=10095 
				        AND S.INVENTARIO=0 
				        AND S.DISPONIBLE > 0
				        AND TRIM(P.ARTICULO)=IM.SKU_ID (+) 
				    ORDER BY 
				             P.ARTICULO
				) DIFERENCIA

				WHERE 
				        DIFERENCIA.ACTIVO=0 AND  DIFERENCIA.RESERVA=0";

		$result = $this->db->query($sql);

		if($result || $result != null){
			foreach ($result->result() as $key) {
				if($key->SKU_BRCD == ''){
					$sku_brcd = 'NULL';
				}else{
					$sku_brcd = $key->SKU_BRCD;
				}
				$sql = " SELECT CASE  WHEN ( (SUM(A.STK) > 0) AND COUNT(A.LOC) > 2)   THEN 'PRODUCTO PUBLICADO'
						        ELSE 'PRODUCTO NO PUBLICADO'
						        END  RESPUESTA
						 FROM ( SELECT SUM(TO_NUMBER(X.QUANTITY)) STK
						               , X.FCN                      LOC
						        FROM LGBTCK_TMP_RPT_STK_MSV  X
						        WHERE X.PARTNUMBER = TO_CHAR(TRIM($sku_brcd))
						        AND TO_NUMBER(X.QUANTITY) > 0
						        GROUP BY X.PARTNUMBER, X.FCN ) A";
				$result2 = $db2->query($sql);
				foreach ($result2->result() as $key2) {
					array_push($datos, array(
						"SKU_ID" => $key->SKU_ID,
						"SKU_BRCD" => $key->SKU_BRCD,
						"DISP_BT" => $key->DISP_BT,
						"DESCRIPCION" => $key->DESCRIPCION,
						"DEPTO" => $key->DEPTO,
						"DEPTO_DESC" => $key->DEPTO_DESC,
						"ACTIVO" => $key->ACTIVO,
						"RESERVA" => $key->RESERVA,
						"BLOQUEO" => $key->BLOQUEO,
						"RESPUESTA" => $key2->RESPUESTA,
					));
				}
			}

			return json_encode($datos);
		}
		else{
			return $this->db->error();
		}		
	}

	public function detalleBloqueo($sku){
		$sql = "SELECT 
				    CL.CASE_NBR,
				    LH.DSP_LOCN,
				    CL.INVN_LOCK_CODE,
				    CH.STAT_CODE,
				    SC.CODE_DESC,
				    CL.MOD_DATE_TIME,
				    SUM(ACTL_QTY) STOCK
				FROM
				    CASE_HDR CH,
				    CASE_DTL CD,
				    CASE_LOCK CL,
				    SYS_CODE SC,
				    LOCN_HDR LH
				WHERE 
				    CH.CASE_NBR = CD.CASE_NBR
				    AND CH.STAT_CODE IN (10, 30)
				    AND CD.CASE_NBR = CL.CASE_NBR
				    AND CD.SKU_ID = TRIM('$sku')
				    AND (SC.REC_TYPE = 'S' AND SC.CODE_TYPE = '509' AND SC.CODE_ID = CH.STAT_CODE)
				    AND CH.LOCN_ID = LH.LOCN_ID(+)
				GROUP BY
				    CL.CASE_NBR,
				    LH.DSP_LOCN,
				    CL.INVN_LOCK_CODE,
				    CH.STAT_CODE,
				    SC.CODE_DESC,
				    CL.MOD_DATE_TIME
				ORDER BY CASE_NBR";

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