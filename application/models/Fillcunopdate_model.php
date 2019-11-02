<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Fillcunopdate_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function LoadAgentesScheduleDatePosicion($idempresa, $idoficina, $fecha, $posicion)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('posicion', $posicion);
		$this->db->order_by('LENGTH(posicion)','ASC');
		$this->db->order_by('posicion','ASC');
		$query = $this->db->get('cunop_agentscheduler');
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function cleanDate($idempresa,$idoficina,$fecha)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);

		$query = $this->db->delete('cunop_distribucionagentesvuelos');

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);

		$query = $this->db->delete('cunop_distribucionvuelos');
	}

	public function LoadAgentesScheduleDate($idempresa, $idoficina, $fecha)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);

		$query = $this->db->get('cunop_distribucionagentesvuelos');
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function SetAgentScheduleDay($idempresa, $idoficina, $fecha, $idvuelo,$linea,$shortname,$posicion,$usuario)
	{
		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'idvuelo'		=> $idvuelo,
			'fecha'			=> $fecha,
			'linea'			=> $linea,
			'idagente'		=> $shortname,
			'posicion'		=> $posicion,
			'usuario'		=> $usuario,
			'updated' 		=> $date = date('Y-m-d H:i:s')
			);

		// actualiza la info
        $this->db->replace('cunop_distribucionagentesvuelos', $data);
		
		//echo $this->db->last_query();
	}

	public function RemoveAgentScheduleDay($idempresa, $idoficina, $fecha, $idvuelo,$linea)
	{

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('linea', $linea);
		$this->db->where('idvuelo', $idvuelo);


		// actualiza la info
        $this->db->delete('cunop_distribucionagentesvuelos');
		
		//echo $this->db->last_query();
	}
	
	public function SetFlightHeader($idempresa,$idoficina,$idvuelo,$fecha,$stime,$lead,$usuario)
	{
		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'idvuelo'		=> $idvuelo,
			'fecha'			=> $fecha,
			'horasalida'	=> $stime,
			'lead'			=> $lead,
			'usuario'		=> $usuario,
			'updated' 		=> $date = date('Y-m-d H:i:s')
			);

		// actualiza la info
        $this->db->replace('cunop_distribucionvuelos', $data);
	}

	public function UpdateFlightHeader($idempresa,$idoficina,$idvuelo,$fecha,$usuario)
	{
		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'idvuelo'		=> $idvuelo,
			'fecha'			=> $fecha,
			'usuario'		=> $usuario,
			'updated' 		=> $date = date('Y-m-d H:i:s')
			);

		// actualiza la info
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);

        $this->db->update('cunop_distribucionvuelos', $data);
	}


	public function ActualizarScheduleAgente($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion){

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
        $this->db->replace('cunop_agentscheduler', $data);
		
		//echo $this->db->last_query();
	}
	
	public function SetAgentBmasDate($idempresa, $idoficina, $fecha, $idagente,$shortname,$posicion,$usuario)
	{
		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'fecha'			=> $fecha,
			'idagente'		=> $idagente,
			'shortname'		=> $shortname,
			'position'		=> $posicion,
			'usuario'		=> $usuario,
			'updated' 		=> $date = date('Y-m-d H:i:s')
			);

		// actualiza la info
        $this->db->replace('cunop_distribbmasfecha', $data);
		
		//echo $this->db->last_query();
	}
}