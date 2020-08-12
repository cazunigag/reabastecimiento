<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DescuadraturaInv_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database('PMMWMS');		
    }

    public function mainGrid()
    {
        $sql = "SELECT 
                    X.CUD,
                    X.ARTICULO_CORTO,
                    X.ARTICULO_LARGO,
                    X.DESCRIPCION_ARTICULO,
                    X.CANTIDAD,
                    X.SUCURSAL_STOCK,
                    X.SUCURSAL_DESPACHO,
                    X.NOMBRE_CLIENTE,
                    X.DIRECCION_DESPACHO,
                    X.FECHA_VENTA,
                    X.FECHA_PLANIFICACION,
                    X.ESTADO,
                    X.MOTIVO,
                    X.RESERVA,
                    X.FECHA_RESERVA,
                    X.STOCK_VENDIBLE,
                    (CASE X.ESTADO_INTERNO
                        WHEN 'PE' THEN
                        'PENDIENTE'
                        WHEN 'PR' THEN
                        'PREPARADO'
                        ELSE
                        'OTRO'
                        END) ESTADO_INTERNO,
                    X.FECHA_DESPACHO,
                    DECODE(X.ESTADO_INTERNO, 'PE', Y.DISP_CASE_PICK, NULL) DISP_CASE_PICK,
                    DECODE(X.ESTADO_INTERNO, 'PE', Y.DISP_ACTIVO, NULL) DISP_ACTIVO,
                    DECODE(X.ESTADO_INTERNO, 'PE', Y.RESERVA, NULL) RESERVA,
                    DECODE(X.ESTADO_INTERNO, 'PE', Y.PP, NULL) PP,
                    DECODE(X.ESTADO_INTERNO, 'PE', Y.TOTAL, NULL) TOTAL
                FROM 
                    CTL_DESCUADRATURA_INV_BT  X,
                    CTL_DESCUADRATURA_INV_WMS Y
                WHERE 
                    X.ARTICULO_CORTO = Y.ARTICULO_CORTO(+)";

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
    
    public function update($data){

        $cud = "";
        $numerr = 0;
        $mesgerr = "";

        $sql = "DELETE FROM CTL_DESCUADRATURA_INV_BT";

        $response = $this->db->query($sql);

        if($response){
            foreach ($data as $key) {

                $cud = $key['CUD'];
               
                $params = array(array('name' => ":p_Cud", 'value' => $cud, 'type' => SQLT_CHR, 'length' => 99 ),
                                array('name' => ":p_Err_Num", 'value' => $numerr, 'type' => SQLT_INT, 'length' => 99 ),
                                array('name' => ":p_Err_Msg", 'value' => $mesgerr, 'type' => SQLT_CHR, 'length' => 99 ));
    
                $this->db->stored_procedure("PMMWMS", "CRG_CTL_DESCUADRATURA_INV_BCUD", $params);
                
            }
            if($numerr == 0){
                $params = array(array('name' => ":p_Err_Num", 'value' => $numerr, 'type' => SQLT_INT, 'length' => 99 ),
                            array('name' => ":p_Err_Msg", 'value' => $mesgerr, 'type' => SQLT_CHR, 'length' => 99 ));
    
                $this->db->stored_procedure("PMMWMS", "Crg_Ctl_Descuadratura_Inv_Wms", $params);
            }else{
                return $numerr;
            } 
        }
        return $numerr; 

    }

    public function dataGrafico(){

        $sql = "SELECT 
                    B.FECHA,
                    ROUND((SELECT A.PORCENTAJE_SIN_STOCK FROM CTL_DESCUADRATURA_INV_RESUMEN A WHERE A.FECHA = B.FECHA AND CRITERIO = 'TOTAL'),2) POR_TOTAL,
                    ROUND((SELECT A.PORCENTAJE_SIN_STOCK FROM CTL_DESCUADRATURA_INV_RESUMEN A WHERE A.FECHA = B.FECHA AND CRITERIO = 'STS'),2) POR_STS
                FROM 
                    CTL_DESCUADRATURA_INV_RESUMEN B
                GROUP BY
                    B.FECHA";
        
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