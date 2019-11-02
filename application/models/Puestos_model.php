<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Puestos_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function CompanyPositions($empresa){
		$this->db->where('idempresa',$empresa);

		$query = $this->db->get('cunop_puestos');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function PostPosition($idempresa,$code,$description){
		
		$data = array(
            'idempresa' 	=> $idempresa,
            'code' 			=> $code,
            'description' 	=> $description,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('idagente')
        );
		
		// actualiza la info
        $this->db->replace('cunop_puestos', $data);
		
		//echo $this->db->last_query();
		
		return $this->LoadPositionId($idempresa, $code);
	}
	
	public function LoadPositionId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->get('cunop_puestos');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function DeletePositionId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->delete('cunop_puestos');
		
	}
}