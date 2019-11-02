<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Loadimpresionmensual_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
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
		
		//echo 'ActualizarScheduleAgente' . PHP_EOL;
		//echo $this->db->last_query() . PHP_EOL;
	}
	
	public function CompanySkills($empresa){
		$this->db->where('idempresa',$empresa);

		$query = $this->db->get('cunop_cando');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function PostRow($idempresa,$code,$description){
		
		$data = array(
            'idempresa' 	=> $idempresa,
            'code' 			=> $code,
            'description' 	=> $description,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('idagente')
        );
		
		// actualiza la info
        $this->db->replace('cunop_cando', $data);
		
		echo $this->db->last_query();
		
		return $this->LoadRowId($idempresa, $code);
	}
	
	public function AddSkillAgente($idempresa,$idoficina,$idagente,$idcando){
		
		$data = array(
            'idempresa' 	=> $idempresa,
            'idoficina' 	=> $idoficina,
            'idagente' 		=> $idagente,
			'idcando'		=> $idcando,
			'okusuario'		=> $this->session->userdata('shortname'),
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $this->session->userdata('idagente')
        );
		
		// actualiza la info
        $this->db->replace('cunop_relcandoagents', $data);
		
		echo $this->db->last_query();
		
		return $this->LoadRelCandoAgentsRowId($idempresa,$idoficina,$idagente);
	}
	
	public function RemoveSkillAgente($idempresa,$idoficina,$idagente,$idcando){
		
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);
		$this->db->where('idcando',$idcando);
		
		// actualiza la info
        $this->db->delete('cunop_relcandoagents');
		
		echo $this->db->last_query();	
	}
	
	public function LoadRelCandoAgentsRowId($idempresa,$idoficina,$idagente){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente',$idagente);
		
		$query = $this->db->get('cunop_relcandoagents');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadRowId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->get('cunop_cando');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function CleanSchedulerDates($idempresa,$idoficina,$fechaini,$fechafin)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha>=', $fechaini);
		$this->db->where('fecha<=', $fechafin);
		$this->db->delete('cunop_agentscheduler');

		//echo $this->db->last_query();
	}
	
	// obtiene todos los skills de un agente
	public function LoadCandoAgent($idempresa,$idoficina,$idagente)
	{
		$query = $this->db->query("select cunop_cando.code,cunop_cando.description from cunop_relcandoagents inner join cunop_cando on " .
				" cunop_relcandoagents.idcando" .
				"=cunop_cando.code where cunop_relcandoagents.idempresa=" . $idempresa . " and cunop_relcandoagents.idoficina=" . $idoficina . " and " .
				"cunop_relcandoagents.idagente=" . $idagente . " order by cunop_cando.code ");			
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function DeleteRowId($idempresa,$code){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('code',$code);
		
		$query = $this->db->delete('cunop_cando');
		
	}
}