<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AlmacenamientoLocn_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	public function info(){
		$sql = "SELECT 
					A.AISLE, 
					A.LOCN_CLASS, 
					A.PUTWY_TYPE, 
					B.CODE_DESC 
				FROM 
					PMMWMS.RDX_PUTWY_LOCN A,
					SYS_CODE B
				WHERE
					B.REC_TYPE = 'B'
					AND B.CODE_TYPE = '667'
					AND B.CODE_ID = A.PUTWY_TYPE
				ORDER BY
					A.AISLE";

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
	public function update($updated, $old){
		foreach ($old as $key) {
			$sql = "UPDATE PMMWMS.RDX_PUTWY_LOCN SET LOCN_CLASS = '$key->LOCN_CLASS', PUTWY_TYPE = '$key->PUTWY_TYPE'
			 		WHERE AISLE = '$key->AISLE'";

			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
	public function create($created){
		foreach ($created as $key) {
			$sql = "SELECT 
					A.AISLE, 
					A.LOCN_CLASS, 
					A.PUTWY_TYPE
				FROM 
					PMMWMS.RDX_PUTWY_LOCN A
				WHERE
					A.AISLE = '$key->AISLE'
					AND A.LOCN_CLASS = '$key->LOCN_CLASS'
					AND A.PUTWY_TYPE = '$key->PUTWY_TYPE'";

			$result = $this->db->query($sql);

			if(sizeof($result->result())>0){
				return 1;
			}
			else{
				$sql = "SELECT LOCN_BRCD FROM LOCN_HDR WHERE SUBSTR(LOCN_BRCD,1,4) = '$key->AISLE'";

				$result = $this->db->query($sql);

				if(sizeof($result->result())==0){
					return 3;
				}
				else{
					$sql = "SELECT CODE_ID FROM SYS_CODE WHERE REC_TYPE = 'B' AND CODE_TYPE = '667' AND CODE_ID = '$key->PUTWY_TYPE'";

					$result = $this->db->query($sql);

					if(sizeof($result->result())==0){
						return 4;
					}else{
						$sql = "INSERT INTO PMMWMS.RDX_PUTWY_LOCN (AISLE, LOCN_CLASS, PUTWY_TYPE) VALUES ('$key->AISLE', '$key->LOCN_CLASS',
						 '$key->PUTWY_TYPE')";
						 $result = $this->db->query($sql);
						 if(!$result){
							return 2;
						}
					}
				}
			}
		}
		return 0;
	}
	public function delete($destroyed){
		foreach ($destroyed as $key) {
			$sql = "DELETE FROM PMMWMS.RDX_PUTWY_LOCN WHERE AISLE = '$key->AISLE' AND LOCN_CLASS = '$key->LOCN_CLASS'
					AND PUTWY_TYPE = '$key->PUTWY_TYPE'";
			$result = $this->db->query($sql);

			if(!$result){
				break;
				return 1;
			}
		}
		return 0;
	}
}