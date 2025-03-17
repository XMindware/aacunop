<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 		Modelo para el control de las asistencias perfectas mensuales
 */
class Perfectattendance_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function ConsultarPerfectAttendanceMesEstacion($idempresa,$idoficina,$fecha){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('month', date('m', strtotime($fecha)));
		$this->db->where('year', date('Y', strtotime($fecha)));
		$query = $this->db->order_by('shortname','ASC');

		$query = $this->db->get('cunop_perfectattendance');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadRow($idempresa,$idoficina,$uniqueid){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);

		$query = $this->db->get('cunop_perfectattendance');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}

	public function DeleteRow($idempresa,$idoficina,$uniqueid){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('uniqueid', $uniqueid);

		$this->db->delete('cunop_perfectattendance');
			
	}
	
	public function IngresarAgente($idempresa, $idoficina, $idagente, $shortname, $month, $year, $usuario){
		
		//echo 'existe ';
		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
            'idagente' 		=> $idagente,
            'shortname' 	=> $shortname,
            'month' 		=> $month,
			'year'			=> $year,
			'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
		
		$this->db->replace('cunop_perfectattendance', $data);
		return $this->LoadRow($idempresa, $idoficina, $this->db->insert_id() );
		//echo $this->db->last_query();
	
	}

	public function CleanPADates($idempresa,$idoficina,$mes,$year)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('month', $mes);
		$this->db->where('year', $year);
		$this->db->delete('cunop_perfectattendance');

		//echo $this->db->last_query();
	}

	public function GetAgentsByLastname($idempresa,$idoficina,$lastname)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->like('apellidos', $lastname);

		$query = $this->db->get('cunop_agentesactivos');

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
}