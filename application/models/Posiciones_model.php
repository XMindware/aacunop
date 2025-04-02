<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Posiciones_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function CompanyPositions($idempresa){

		$sql = "
			SELECT 
				'p' as type,
				uniqueid,
				idempresa,
				code,
				description,
				cando,
				startdate,
				enddate,
				starttime,
				endtime,
				workday
			FROM 
				cunop_positions 
			WHERE 
				idempresa = ?
			UNION
			SELECT 
				'e' as type,
				-1 AS uniqueid,
				idempresa,
				code,
				description,
				'' AS cando,
				'2021-01-01' AS startdate,
				'2025-12-31' AS enddate,
				'' AS starttime,
				'' AS endtime,
				'' AS workday
			FROM 
				cunop_extrapositions 
			WHERE 
				idempresa = ?
			ORDER BY 
				starttime, 
				code
		";

		$query = $this->db->query($sql, array($idempresa,$idempresa));
	
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function CompanyGatePositions($empresa)
	{
		$this->db->where('cando','G');
		$this->db->where('idempresa',$empresa);

		$query = $this->db->order_by('workday','ASC');
		$query = $this->db->order_by('starttime','ASC');
		$query = $this->db->get('cunop_positions');
		
	
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}		
	}

	
	public function CompanyExtraGatePositions($empresa)
	{
		$this->db->like('cando','G');
		$this->db->where('idempresa',$empresa);

		$query = $this->db->order_by('workday','ASC');
		$query = $this->db->order_by('starttime','ASC');
		$query = $this->db->get('cunop_positions');
		
	
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}		
	}
	
	public function PostPosition($uniqueid,$idempresa,$code,$description,$stime,$etime,$sdate,$edate,$workday,$cando){
		
		$result = $this->LoadPositionUniqueId($idempresa, $uniqueid);
		
		if($uniqueid > -1)
		{
			echo 'existe ';
			$data = array(
				'idempresa' 	=> $idempresa,
	            'code' 			=> $code,
	            'description' 	=> $description,
	            'starttime' 	=> $stime,
	            'endtime' 		=> $etime,
	            'startdate' 	=> $sdate,
	            'enddate' 		=> $edate,
	            'workday' 		=> $workday,
	            'cando' 		=> $cando,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $this->session->userdata('shortname')
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where(array('uniqueid'  => $uniqueid));
	        $this->db->update('cunop_positions', $data);

		}
		else
		{
			echo 'no existe';
			$data = array(
				'idempresa' 	=> $idempresa,
	            'code' 			=> $code,
	            'description' 	=> $description,
	            'starttime' 	=> $stime,
	            'endtime' 		=> $etime,
	            'startdate' 	=> $sdate,
	            'enddate' 		=> $edate,
	            'workday' 		=> $workday,
	            'cando' 		=> $cando,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $this->session->userdata('shortname')
	        );
	        $this->db->replace('cunop_positions', $data);
	    }
		
		return $this->LoadPositionUniqueId($idempresa, $uniqueid);
	}
	
	public function LoadPositionId($idempresa,$code,$sdate){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		$this->db->where('startdate',$sdate);
		
		$query = $this->db->get('cunop_positions');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadPositionUniqueId($idempresa,$uniqueid){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('uniqueid',$uniqueid);
		
		$query = $this->db->get('cunop_positions');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function DeletePositionId($idempresa,$uniqueid){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('uniqueid',$uniqueid);
		
		$query = $this->db->delete('cunop_positions');
		
	}


	// regresa el skills necesario para una posicion
	public function GetSkillForPosition($idempresa,$idoficina,$posicion,$workday){

		$sql = "SELECT cando FROM cunop_positions WHERE".
		" idempresa=? and code=?"; //" and workday=? ";

		$query = $this->db->query($sql,array($idempresa,$posicion));//,$workday));
		if($query){
			return $query->row();
		}
		else{
			return array();
		}

	}
}