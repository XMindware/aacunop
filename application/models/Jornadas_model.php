<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Jornadas_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function CompanyWorkdays($empresa){
		$this->db->where('idempresa',$empresa);

		$query = $this->db->get('cunop_workday');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function PostWorkday($idempresa,$code,$description,$hours){
		
		$data = array(
            'idempresa' 	=> $idempresa,
            'code' 			=> $code,
            'description' 	=> $description,
			'hours'			=> $hours,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('idagente')
        );
		
		// actualiza la info
        $this->db->replace('cunop_workday', $data);
		
		echo $this->db->last_query();
		
		return $this->LoadWorkdayId($idempresa, $code);
	}
	
	public function LoadWorkdayId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->get('cunop_workday');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function DeleteWorkdayId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->delete('cunop_workday');
		
	}
}