<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Junio 2017
 */
class Incidencias_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Agentes_model');
		$this->load->model('Config_model');
	}
	
	
	public function LoadIncidenciasRequired($idempresa,$idoficina, $fechaini, $fechafin){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		//$this->db->where('fechaini>=', $fechaini);
		//$this->db->where('fechafin<=', $fechafin);

		$query = $this->db->get('cunop_incidencias');

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	
	public function PostRow($uniqueid, $idempresa, $idoficina, $idagente, $shortname, $fechaini, $fechafin, $incidencia, $vigente, $usuario){
		
		if($this->LoadRowCode($idempresa, $idoficina, $uniqueid))
		{
			// update flight
			$data = array(
				'fechaini'		=> $fechaini,
				'fechafin'		=> $fechafin,
				'incidencia'	=> $incidencia,
				'vigente'		=> $vigente,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			$this->db->where('idempresa', $idempresa);
			$this->db->where('idoficina', $idoficina);
			$this->db->where('uniqueid', $uniqueid);
			$this->db->update('cunop_incidencias',$data);
		}
		else
		{
			$data = array(
				'idempresa'		=> $idempresa,
				'idoficina'		=> $idoficina,
				'idagente'		=> $idagente,
				'shortname'		=> $shortname,
				'fechaini'		=> $fechaini,
				'fechafin'		=> $fechafin,
				'incidencia'	=> $incidencia,
				'vigente'		=> $vigente,
				'responsable'	=> $usuario,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			// actualiza la info
	        $this->db->replace('cunop_incidencias', $data);	
		}
		
		//echo $this->db->last_query();
		return $this->LoadRowCode($idempresa, $idoficina, $uniqueid);
	}
	
	public function LoadRowCode($idempresa, $idoficina, $uniqueid){
		
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('uniqueid',$uniqueid);

		$query = $this->db->get('cunop_incidencias');
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}
}