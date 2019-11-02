<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Mayo 2017
 */
class Loadleadsmensual_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function ActualizarScheduleLead($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion){

		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'idagente'		=> $idagente,
			'posicion'		=> $posicion,
			'fecha'			=> $fecha,
			'workday'		=> $workday,
			'shortname'		=> $shortname,
			'asignacion'	=> $asignacion,
			'usuario'		=> $this->session->userdata('shortname'),
			'updated' 		=> $date = date('Y-m-d H:i:s')
			);

		// actualiza la info
        $this->db->replace('cunop_distribleads', $data);
		
		//echo $this->db->last_query();
	}
	
	public function CleanSchedulerDates($idempresa,$idoficina,$fechaini,$fechafin)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha>=', $fechaini);
		$this->db->where('fecha<=', $fechafin);
		$this->db->delete('cunop_distribleads');

		//echo $this->db->last_query();
	}
	
}