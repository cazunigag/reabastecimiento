<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sublineas_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database("PMMWMS");
		
	}
	public function obtenerTabla(){
		$sql = "SELECT 
					SUBLINEA,
					DES_SUBLINEA,
					MINIMO,
					MAXIMO
				FROM 
					RDX_SUBLINEA_MAXMIN";

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
	function guardarCambios($changes){
		foreach ($changes as $key) {
			$sql = "UPDATE RDX_SUBLINEA_MAXMIN SET MINIMO = $key->MINIMO, MAXIMO = $key->MAXIMO WHERE SUBLINEA = '$key->SUBLINEA'";

			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
}