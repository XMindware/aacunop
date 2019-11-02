<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Posicionesvuelos_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Vuelos_model');
		$this->load->model('Config_model');
	}
	
	
	public function LoadRows($empresa, $oficina){

		$sql = "SELECT a.idempresa, a.idoficina, a.idvuelo,a.linea, b.horasalida, a.posmon,a.postue,a.poswed,a.posthu,a.posfri,a.possat,a.possun, b.destino, b.duracionvuelo, a.usuario, a.updated FROM cunop_relvuelosposiciones as a inner join cunop_vuelos as b " .
				"on a.idvuelo=b.idvuelo where a.idempresa = ? and a.idoficina= ? and a.linea=0 ORDER BY horasalida ASC";

		$query = $this->db->query($sql, array($empresa, $oficina));
		//echo $empresa . ' ' . $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	// obtenemos la lista de vuelos con posiciones asignadas contra el total de vuelos para la estacion
	public function GetVuelosAsignadosVsTotal($idempresa, $idoficina, $iatacode)
	{

		// trae los vuelos de esa estacion
		$vuelos = $this->Vuelos_model->LoadVuelos($iatacode);

		$asignados = $this->LoadRows($idempresa, $idoficina);

		$response = array(
			'vuelos' 	=> sizeof($vuelos),
			'asignados' => sizeof($asignados)
		);

		return $response; 
	}

	// obtenemos la lista de vuelos con posiciones asignadas contra el total de vuelos para la estacion
	public function GetVuelosSinAsignar($idempresa, $idoficina, $iatacode)
	{

		// trae los vuelos de esa estacion
		$vuelos = $this->Vuelos_model->LoadVuelos($iatacode);

		$asignados = $this->LoadRows($idempresa, $idoficina);

		$sinasignar = array();

		foreach($vuelos as $vuelo)
		{
			foreach($asignados as $asignado)
			{
				// busca si el vuelo ha sido asignado
				$found = false;
				if($asignado['idvuelo'] == $vuelo['idvuelo'])
				{
					// sale del ciclo
					$found = true;
					break;
				}
			}
			if(!$found)
			{
				// si no lo encontro, entonces lo agrega a la lista
				array_push($sinasignar, $vuelo);	
			}
			
		}
		return $sinasignar; 
	}
	
	public function PostRow($idempresa, $idoficina, $idvuelo, $linea, $posmon, $postue, $poswed, $posthu, $posfri, $possat, $possun, $usuario){
		
		if($this->LoadRowCode($idempresa, $idoficina, $idvuelo, $linea))
		{
			// update flight
			$data = array(
				'linea'			=> $linea,
				'posmon'		=> $posmon,
				'postue'		=> $postue,
				'poswed'		=> $poswed,
				'posthu'		=> $posthu,
				'posfri'		=> $posfri,
				'possat'		=> $possat,
				'possun'		=> $possun,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			$this->db->where('idempresa', $idempresa);
			$this->db->where('idoficina', $idoficina);
			$this->db->where('idvuelo', $idvuelo);
			$this->db->where('linea', $linea);
			$this->db->update('cunop_relvuelosposiciones',$data);
		}
		else
		{
			$data = array(
				'idempresa'		=> $idempresa,
				'idoficina'		=> $idoficina,
	            'idvuelo' 		=> $idvuelo,
	            'linea'			=> $linea,
				'posmon'		=> $posmon,
				'postue'		=> $postue,
				'poswed'		=> $poswed,
				'posthu'		=> $posthu,
				'posfri'		=> $posfri,
				'possat'		=> $possat,
				'possun'		=> $possun,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			// actualiza la info
	        $this->db->replace('cunop_relvuelosposiciones', $data);	
		}
		
		//echo $this->db->last_query();
		return $this->LoadRowCode($idempresa, $idoficina, $idvuelo);
	}
	
	public function LoadRowCode($idempresa, $idoficina, $idvuelo, $linea){
		
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idvuelo',$idvuelo);
		if($linea!='')
		$this->db->where('linea',$linea);
		$this->db->order_by('linea', 'ASC');

		$query = $this->db->get('cunop_relvuelosposiciones');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadPosicionesVuelo($idempresa, $idoficina, $idvuelo){
		
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idvuelo',$idvuelo);
		$this->db->order_by('idvuelo', 'ASC');

		$query = $this->db->get('cunop_relvuelosposiciones');
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function PosicionesHorario($horario){

		$this->db->where('starttime <=', $horario);
		$this->db->where('endtime >=', $horario);
		$this->db->where('cando', 'G');
		$this->db->order_by('starttime','ASC');
		$query = $this->db->get('cunop_positions');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function PosicionesParaAgente($idempresa,$idoficina,$idagente)
	{
		$sql = "SELECT p.* FROM cunop_relcandoagents r inner join cunop_positions p on r.idcando=p.cando where r.idagente=? and r.idempresa=? and r.idoficina=? order by p.code";
		$result = $this->db->query($sql,array($idagente,$idempresa,$idoficina));
		echo $this->db->last_query();
		return $result->result_array();
	}

	public function PosicionesParaAgenteUnique($idempresa,$idoficina,$idagente)
	{
		$sql = "SELECT p.* FROM cunop_relcandoagents r inner join cunop_positions p on r.idcando=p.cando inner join cunop_agentes a on a.uniqueid=r.idagente where a.idagente=? and r.idempresa=? and r.idoficina=? order by p.code";
		$result = $this->db->query($sql,array($idagente,$idempresa,$idoficina));
		//echo $this->db->last_query();
		return $result->result_array();
	}
	
	
	public function DeletePositionId($idempresa,$idoficina,$idvuelo,$posicion){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idvuelo',$idvuelo);
		$this->db->where('posmon',$posicion);

		
		$query = $this->db->delete('cunop_relvuelosposiciones');
		
	}
}