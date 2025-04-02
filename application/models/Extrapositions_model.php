<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Junio 2017
 */
class Extrapositions_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	
	}
	
	
	public function LoadRows($idempresa){

		$this->db->where('idempresa', $idempresa)
				 ->order_by('code','asc');
		
		$query = $this->db->get('cunop_extrapositions');

		return $query->result_array();
	}

	
	public function PostPosition($uniqueid,$idempresa, $code,$description){
		
		$result = $this->LoadPositionUniqueId($idempresa, $uniqueid);
		
		if($uniqueid > -1)
		{
			$data = array(
				'idempresa' 	=> $idempresa,
	            'code' 			=> $code,
	            'description' 	=> $description,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $this->session->userdata('shortname')
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where(array('uniqueid'  => $uniqueid));
	        $this->db->update('cunop_extrapositions', $data);

	        return $this->LoadPositionUniqueId($idempresa, $uniqueid);
		}
		else
		{
			$data = array(
				'idempresa' 	=> $idempresa,
	            'code' 			=> $code,
	            'description' 	=> $description,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $this->session->userdata('shortname')
	        );
	        $this->db->replace('cunop_extrapositions', $data);

	        $uniqueid = $this->db->insert_id();

	        return $this->LoadPositionUniqueId($idempresa, $uniqueid);
	    }
		
		
	}
	
	public function LoadPositionUniqueId($idempresa, $uniqueid){
		
		$this->db->where('idempresa',$idempresa);
		$this->db->where('uniqueid',$uniqueid);

		$query = $this->db->get('cunop_extrapositions');

		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}

	public function DeletePositionId($idempresa,$idoficina, $uniqueid){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('uniqueid',$uniqueid);
		
		$query = $this->db->delete('cunop_extrapositions');
		
	}
}