<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 		Modelo para el control de solicitudes de cambio de horario
 */
class Timeswitch_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function ConsultarAgentRequests($idempresa,$idoficina,$idagente){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idagente', $idagente);
		
		$this->db->or_where('idagentecambio',$idagente);
		$query = $this->db->order_by('fechacambio','DESC');

		$query = $this->db->get('cunop_switchrequests');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function ConsultarAgentHistoricalRequests($idempresa,$idoficina)
	{
		/*$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('status<>"DEC"');
		
		$query = $this->db->order_by('fechacambio','DESC');

		$query = $this->db->get('cunop_switchrequests');
		*/
		/*
		$sql = "SELECT *,case when fechatarget>'0000-00-00' " .
			   "then (case when fechatarget<fechacambio then fechatarget else fechacambio end) else fechacambio end as fechaorden " .
			   "FROM `cunop_switchrequests` WHERE `idempresa` = ? AND `idoficina` = ? AND `status` <> 'DEC' " .
			   "ORDER BY `fechaorden` DESC";*/

		$sql = "SELECT *, case when fechatarget>'0000-00-00' " .
			   "then (case when fechatarget<fechacambio then fechatarget else fechacambio end) else fechacambio end as fechaorden " .
			   "FROM `cunop_switchrequests` WHERE `idempresa` = ? AND `idoficina` = ? AND `status` <> 'DEC' and tipocambio<>'Triangle' UNION " .
			   "select *,fechacambio as fechaorden from cunop_switchrequests where triangulo in( SELECT triangulo from cunop_switchrequests " .
			   "where idempresa=? and idoficina=? and tipocambio='Triangle' group by triangulo) and uniqueid not in(SELECT triangulo from " .
			   "cunop_switchrequests where idempresa=? and idoficina=? and tipocambio='Triangle' group by triangulo)" .
			   "ORDER BY `fechaorden` DESC";

		$query = $this->db->query($sql,array($idempresa,$idoficina,$idempresa,$idoficina,$idempresa,$idoficina));
		//echo $this->db->last_query() . PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}		
	}

	public function ConsultarAgentHistoricalRequestsDates($idempresa,$idoficina,$fechaini,$fechafin)
	{
		$sql = "SELECT uniqueid,tipocambio,agentecambio,fechacambio,posicionsolicitada,posicioninicial,shortname,leadautoriza,fechaacepta,fechaautoriza,status,info,triangulo, case when fechatarget>'0000-00-00' " .
			   "then (case when fechatarget<fechacambio then fechatarget else fechacambio end) else fechacambio end as fechaorden " .
			   "FROM `cunop_switchrequests` WHERE `idempresa` = ? AND `idoficina` = ? and tipocambio not in ('Triangle','Partial') and fechacambio between ? and ? UNION " .
			   "select triangulo,tipocambio,agentecambio,fechacambio,posicionsolicitada,posicioninicial,shortname,leadautoriza,fechaacepta, fechaautoriza,status,info,triangulo, fechacambio as fechaorden from cunop_switchrequests where triangulo in( SELECT triangulo from cunop_switchrequests " .
			   "where idempresa=? and idoficina=? and tipocambio='Triangle' and fechacambio between ? and ? group by triangulo) and uniqueid not in(SELECT triangulo from " .
			   "cunop_switchrequests where idempresa=? and idoficina=? and tipocambio='Triangle' group by triangulo)" .
			   "ORDER BY `fechaorden` asc";
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fechaini,$fechafin,$idempresa,$idoficina,$fechaini,$fechafin,$idempresa,$idoficina));	
		//echo $this->db->last_query() . PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}		
	}

	public function HayRequestsHoy($idempresa,$idoficina,$idagente)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idagente', $idagente);
		$this->db->where('DATE(fechasolicitud) = CURDATE()');

		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function PostSwitchRequest($idempresa,$idoficina,$idagente,$shortname,$fechacambio,$fechatarget,$posicioninicial,$tipocambio,$posicionsolicitada,$idagentecambio,$agentecambio)
	{
		//echo 'existe ';
		$data = array(
			'idempresa'			=> $idempresa,
			'idoficina'			=> $idoficina,
            'idagente' 			=> $idagente,
            'shortname' 		=> $shortname,
            'fechacambio' 		=> $fechacambio,
            'fechatarget' 		=> $fechatarget,
			'posicioninicial'	=> $posicioninicial,
			'tipocambio'		=> $tipocambio,
			'posicionsolicitada'=> $posicionsolicitada,
			'idagentecambio' 	=> $idagentecambio,
 			'agentecambio'		=> $agentecambio,
 			'status'			=> 'REQ',
			'updated' 			=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->replace('cunop_switchrequests', $data);
		return $this->LoadRow($idempresa, $idoficina, $this->db->insert_id() );
	}

	public function LoadRow($idempresa,$idoficina,$uniqueid){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);

		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}

	public function ConsultarSolicitudesCambioAgente($idempresa,$idoficina,$idagente){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('fechaacepta', '0000-00-00');
		$this->db->where('fechaautoriza', '0000-00-00');
		$this->db->where('status', 'REQ');
		$this->db->where('idagentecambio', $idagente);
		$this->db->where('fechacambio>curdate()');


		$query = $this->db->get('cunop_switchrequests');

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
			
	}

	public function ConsultarAgentCambioRequestById($idempresa, $idoficina, $idagentecambio, $requestid)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idagentecambio', $idagentecambio);
		$this->db->where('uniqueid', $requestid);

		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}
	
	public function AcceptSwitchRequest($idempresa,$idoficina,$requestid){
		
		//echo 'existe ';
		$data = array(
			'status'		=> 'ACC',
			'fechaacepta'	=> $date = date('Y-m-d H:i:s'),
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $requestid);
		
		$this->db->update('cunop_switchrequests', $data);
		return $this->LoadRow($idempresa, $idoficina, $requestid );
	}

	// consulta los requests de un agente que han sido aceptados y estan pendientes de autorizar
	public function ConsultarSolicitudesAceptadasAgente($idempresa,$idoficina,$idagente){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('fechaacepta>', '0000-00-00');
		$this->db->where('fechaautoriza', '0000-00-00');
		$this->db->where('fechacambio>=',date("Y-m-d", strtotime('-1 day')));
		$this->db->where('status', 'ACC');
		$this->db->where('idagente', $idagente);


		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
			
	}

	// consulta los requests de un agente que han sido aceptados y estan pendientes de autorizar
	public function ConsultarSolicitudesPorAutorizar($idempresa,$idoficina){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('fechaacepta>', '0000-00-00');
		$this->db->where('fechaautoriza', '0000-00-00');
		$this->db->where('fechacambio>=',date("Y-m-d", strtotime('-1 day')));
		$this->db->where('status', 'ACC');
		$this->db->order_by('fechacambio','ASC');

		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
			
	}

	public function ConsultarSolicitudesLeadsPorAutorizar($idempresa,$idoficina){

		$sql = "SELECT s.* FROM `cunop_switchrequests` s inner join cunop_agentes a on a.idagente=s.idagente  WHERE s.idempresa=? and s.idoficina=? and " .
			   "s.fechaacepta>'0000-00-00' and s.fechaautoriza='0000-00-00' and s.status='ACC' and s.fechacambio>=? and a.status='OK' and a.puesto='LEAD' " .
			   "order by s.fechacambio asc;";

		$query = $this->db->query($sql,array($idempresa,$idoficina,date("Y-m-d", strtotime('-1 day'))));

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
			
	}

	public function CalendarioCambios($idempresa,$idoficina){

		$sql = "SELECT count(*) as cambios,fechacambio FROM `cunop_switchrequests` WHERE fechaacepta>='0000-00-00' and fechaautoriza='0000-00-00' and fechacambio>? and status='ACC' group by fechacambio order by fechacambio";
		$query = $this->db->query($sql,array(date("Y-m-d", strtotime('-1 day'))));
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
			
	}

	public function ConsultarLeadCambioRequestById($idempresa,$idoficina,$requestid){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('uniqueid', $requestid);
		$this->db->where('fechaacepta>', '0000-00-00');
		$this->db->where('fechaautoriza', '0000-00-00');
		$this->db->where('fechacambio>=',date("Y-m-d", strtotime('-1 day')));
		$this->db->where('status', 'ACC');


		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
			
	}

	public function ConsultarLeadCambioThreeRequestById($idempresa,$idoficina,$requestid){

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('triangulo', $requestid);
		$this->db->where('fechaacepta>', '0000-00-00');
		$this->db->where('fechaautoriza', '0000-00-00');
		$this->db->where('fechacambio>=',date("Y-m-d", strtotime('-1 day')));
		$this->db->where('status', 'ACC');


		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
			
	}

	public function AuthorizeSwitchRequest($idempresa,$idoficina,$requestid,$idagente,$shortname)
	{
		//echo 'existe ';
		$data = array(
			'status'		=> 'AUT',
			'idleadautoriza'=> $idagente,
			'leadautoriza'	=> $shortname,
			'fechaautoriza'	=> $date = date('Y-m-d H:i:s'),
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $requestid);
		
		$this->db->update('cunop_switchrequests', $data);
		return $this->LoadRow($idempresa, $idoficina, $requestid );
	}

	public function PreAuthorizeSwitchThreeRequest($idempresa,$idoficina,$requestid,$idagente,$shortname)
	{
		//echo 'existe ';
		$data = array(
			'status'		=> 'PAUT',
			'idleadautoriza'=> $idagente,
			'leadautoriza'	=> $shortname,
			'fechaautoriza'	=> $date = date('Y-m-d H:i:s'),
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('triangulo', $requestid);
		
		$this->db->update('cunop_switchrequests', $data);

		// devuelve el primer registro del triangulo para validar el cambio
		return $this->LoadRow($idempresa, $idoficina, $requestid );
	}


	public function AuthorizeSwitchThreeRequest($idempresa,$idoficina,$requestid,$idagente,$shortname)
	{
		//echo 'existe ';
		$data = array(
			'status'		=> 'AUT',
			'idleadautoriza'=> $idagente,
			'leadautoriza'	=> $shortname,
			'fechaautoriza'	=> $date = date('Y-m-d H:i:s'),
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('triangulo', $requestid);
		
		$this->db->update('cunop_switchrequests', $data);
		
	}

	public function executeDeleteRequest($idempresa,$idoficina,$idagente,$idrequest)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		//$this->db->where('idagente', $idagente);
		$this->db->where('uniqueid', $idrequest);
		$this->db->delete('cunop_switchrequests');

		/*
		$data = array(
			'status'		=> 'CAN',
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		$this->db->update('cunop_switchrequests', $data);*/

		return $this->LoadRow($idempresa, $idoficina, $idrequest );
	}

	

	public function executeDeclineRequest($idempresa,$idoficina,$idagente,$shortname,$requestid, $reason)
	{

		$data = array(
			'status'		=> 'DEC',
			'info'			=> $reason,
			'idleadautoriza'=> $idagente,
			'leadautoriza'	=> $shortname,
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $requestid);
		
		$this->db->update('cunop_switchrequests', $data);
		return $this->LoadRow($idempresa, $idoficina, $requestid );
	}

	public function GetAgentsByLastName($idempresa,$idoficina,$lastname)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->like('apellidos', $lastname);

		$query = $this->db->get('cunop_agentes');

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// obtiene los agentes que pueden hacer switch en una fecha segun sus habilidades
	public function GetAgentsSwitchDate($idempresa,$idoficina,$fecha,$idagentebase)
	{
		// XAS 2020-03-08 en la consulta considera que no se ponga al mismo agente para cubrir y que no permita hacer switch a un agente mas de una veces en una fecha
		$sql = "
			SELECT DISTINCT 
				sc.idagente,
				ag.shortname AS name,
				sc.posicion AS status
			FROM 
				cunop_agentscheduler sc
			INNER JOIN 
				cunop_agentesactivos ag 
				ON ag.idagente = sc.idagente
			INNER JOIN 
				cunop_relcandoagents can 
				ON can.idagente = ag.uniqueid
			WHERE 
				can.idcando IN (
					SELECT 
						cando 
					FROM 
						cunop_positions 
					WHERE 
						ag.idagente NOT IN (
							SELECT 
								idagente 
							FROM 
								cunop_castigados cas 
							WHERE 
								? BETWEEN cas.fechacastigo AND cas.fechafin
						) 
						AND code IN (
							SELECT 
								posicion 
							FROM 
								`cunop_agentscheduler` sc 
							WHERE 
								idempresa = ? 
								AND idoficina = ? 
								AND fecha = ? 
								AND posicion NOT IN ('XX', '0') 
								AND idagente = ?
							UNION 
							SELECT 
								posicion  
							FROM 
								cunop_distribleads sc 
							WHERE 
								idempresa = ? 
								AND idoficina = ? 
								AND fecha = ? 
								AND posicion NOT IN ('XX', '0')
						)
				) 
				AND sc.idagente NOT IN (
					SELECT 
						idagentecambio 
					FROM 
						cunop_switchrequests 
					WHERE 
						fechacambio = ? 
					UNION 
					SELECT 
						?
				) 
				AND sc.fecha = ? 
				AND sc.posicion NOT IN ('XX', '0')
			UNION 
			SELECT DISTINCT 
				sc.idagente,
				sc.shortname AS name,
				sc.posicion AS status
			FROM 
				cunop_distribleads sc
			INNER JOIN 
				cunop_agentesactivos ag 
				ON ag.idagente = sc.idagente
			INNER JOIN 
				cunop_relcandoagents can 
				ON can.idagente = ag.uniqueid
			WHERE 
				can.idcando IN (
					SELECT 
						cando 
					FROM 
						cunop_positions 
					WHERE 
						code IN (
							SELECT 
								posicion 
							FROM 
								`cunop_agentscheduler` sc 
							WHERE 
								idempresa = ? 
								AND idoficina = ? 
								AND fecha = ? 
								AND posicion NOT IN ('XX', '0') 
								AND idagente = ?
							UNION 
							SELECT 
								posicion  
							FROM 
								cunop_distribleads sc 
							WHERE 
								idempresa = ? 
								AND idoficina = ? 
								AND fecha = ? 
								AND posicion NOT IN ('XX', '0')
						)
				) 
				AND sc.idagente NOT IN (
					SELECT 
						idagentecambio 
					FROM 
						cunop_switchrequests 
					WHERE 
						fechacambio = ? 
					UNION 
					SELECT 
						?
				) 
				AND sc.fecha = ? 
				AND sc.posicion NOT IN ('XX', '0')
			ORDER BY 
				idagente;
		";
		
		$query = $this->db->query($sql,array($fecha,$idempresa,$idoficina,$fecha, $idagentebase,$idempresa,$idoficina,$fecha,$fecha,$idagentebase,
				$fecha,$idempresa,$idoficina,$fecha, $idagentebase,$idempresa,$idoficina,$fecha,$fecha,$idagentebase,
				$fecha));
		//echo $this->db->last_query() .PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// obtiene los agentes que tienen disponible hacer un cover en una fecha
	public function getAgentsCoverDate($idempresa,$idoficina,$fecha,$idagentebase,$puesto)
	{
		if($puesto == 'LEAD')
		{
			$sql = "
				SELECT DISTINCT 
					dl.idagente AS id,
					ag.shortname AS name,
					dl.posicion AS status
				FROM 
					cunop_distribleads dl
				INNER JOIN 
					cunop_agentes ag 
					ON ag.idagente = dl.idagente
				WHERE 
					dl.idagente IN (
						SELECT 
							ag.idagente 
						FROM 
							cunop_relcandoagents can
						INNER JOIN 
							cunop_agentes ag 
							ON ag.uniqueid = can.idagente
						WHERE 
							ag.status = 'OK' 
							AND idcando IN (
								SELECT DISTINCT 
									cando 
								FROM 
									cunop_positions 
								WHERE 
									code IN (
										SELECT 
											posicion 
										FROM 
											`cunop_agentscheduler` sc 
										WHERE 
											idempresa = ? 
											AND idoficina = ? 
											AND fecha = ? 
											AND posicion NOT IN ('XX', '0') 
											AND idagente = ?
										UNION 
										SELECT 
											posicion  
										FROM 
											`cunop_distribleads` sc 
										WHERE 
											idempresa = ? 
											AND idoficina = ? 
											AND fecha = ? 
											AND posicion NOT IN ('XX', '0') 
											AND idagente = ?
									)
									UNION 
									SELECT 'AL' 
									UNION 
									SELECT 'L'
							)
					) 
					AND dl.idagente != ? 
					AND dl.fecha = ? 
					AND dl.posicion != '0'
				UNION 
				SELECT DISTINCT 
					dl.idagente AS id,
					dl.shortname AS name,
					dl.posicion AS status
				FROM 
					cunop_agentscheduler dl
				WHERE 
					dl.idagente IN (
						SELECT DISTINCT 
							ag.idagente 
						FROM 
							cunop_relcandoagents can
						INNER JOIN 
							cunop_agentes ag 
							ON ag.uniqueid = can.idagente
						WHERE 
							ag.status = 'OK'
					) 
					AND dl.idagente != ? 
					AND dl.fecha = ? 
					AND dl.posicion != '0'
				ORDER BY 
					id;
			";
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagentebase,$idempresa,$idoficina,$fecha,$idagentebase,$idagentebase,$fecha,$idagentebase,$fecha));
		}
		else
		{			
			$sql = "
				SELECT DISTINCT 
					dl.idagente AS id,
					ag.shortname AS name,
					dl.posicion AS status
				FROM 
					cunop_agentscheduler dl
				INNER JOIN 
					cunop_agentesactivos ag 
					ON ag.idagente = dl.idagente
				WHERE 
					dl.idagente IN (
						SELECT 
							ag.idagente 
						FROM 
							cunop_relcandoagents can
						INNER JOIN 
							cunop_agentes ag 
							ON ag.uniqueid = can.idagente
						WHERE 
							idcando IN (
								SELECT 
									cando 
								FROM 
									cunop_positions 
								WHERE 
									code IN (
										SELECT 
											posicion 
										FROM 
											`cunop_agentscheduler` sc 
										WHERE 
											idempresa = ? 
											AND idoficina = ? 
											AND fecha = ? 
											AND posicion NOT IN ('XX', '0') 
											AND idagente = ?
										UNION 
										SELECT 
											posicion  
										FROM 
											`cunop_distribleads` sc 
										WHERE 
											idempresa = ? 
											AND idoficina = ? 
											AND fecha = ? 
											AND posicion NOT IN ('XX', '0') 
											AND idagente = ?
									)
							)
					) 
					AND dl.idagente != ? 
					AND dl.fecha = ? 
				ORDER BY 
					dl.idagente;
			";

			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagentebase,$idempresa,$idoficina,$fecha,$idagentebase,$idagentebase,$fecha));			
		}
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// obtiene los agentes que tienen disponible hacer un cover en una fecha
	public function getAgentsLastCoverDate($idempresa,$idoficina,$fecha,$idagentebase,$puesto)
	{
		$sql = "
			SELECT 
				idagente AS id, 
				shortname AS name, 
				'XX' AS status 
			FROM 
				cunop_agentesactivos a 
			WHERE 
				a.status = 'OK' 
				AND idempresa = ? 
				AND idoficina = ? 
				AND idagente NOT IN (
					SELECT 
						idagente 
					FROM 
						cunop_agentscheduler 
					WHERE 
						posicion <> 'XX' 
						AND fecha = ?
				) 
				AND idagente <> ?;
		";


		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagentebase));

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}



	// obtiene los agentes que tienen disponible hacer un cover en una fecha
	public function getAgentsDatePerfil($idempresa,$idoficina,$fecha,$puesto)
	{

		if($puesto == 'LEAD')
		{
			$sql = "
				SELECT 
					idagente AS id, 
					shortname AS name, 
					posicion AS status 
				FROM 
					`cunop_agentscheduler` 
				WHERE 
					idempresa = ? 
					AND idoficina = ? 
					AND fecha = ? 
					AND posicion <> '0' 
				UNION 
				SELECT 
					idagente AS id, 
					shortname AS name, 
					posicion AS status 
				FROM 
					`cunop_distribleads` 
				WHERE 
					idempresa = ? 
					AND idoficina = ? 
					AND fecha = ? 
				ORDER BY 
					id DESC;
			";
		
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idempresa,$idoficina,$fecha));
		}
		else
		{
			$sql = "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? " .
					"and posicion<>'0' order by id desc";
		
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha));
		}
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// obtiene los agentes que tienen disponible hacer un cover en una fecha
	public function getAgentsDayOffDate($idempresa,$idoficina,$fecha)
	{

		$sql = "
			SELECT 
				idagente AS id,
				shortname AS name,
				posicion AS status
			FROM 
				cunop_agentscheduler
			WHERE 
				idempresa = ? 
				AND idoficina = ? 
				AND fecha = ? 
				AND posicion = 'XX' 
				AND idagente NOT IN (
					SELECT 
						idagente 
					FROM 
						cunop_castigados cas 
					WHERE 
						? BETWEEN cas.fechacastigo AND cas.fechafin
				)
			UNION 
			SELECT 
				idagente AS id,
				shortname AS name,
				posicion AS status
			FROM 
				cunop_distribleads
			WHERE 
				idempresa = ? 
				AND idoficina = ? 
				AND fecha = ? 
				AND posicion = 'XX' 
				AND idagente NOT IN (
					SELECT 
						idagente 
					FROM 
						cunop_castigados cas 
					WHERE 
						? BETWEEN cas.fechacastigo AND cas.fechafin
				)
			ORDER BY 
				id DESC;
		";
		
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$fecha, $idempresa,$idoficina,$fecha,$fecha));
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function BuscarAgenteSwitch($idempresa,$idoficina,$idagentecambio,$fechacambio)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idempresa);
		$this->db->where('idagentecambio', $idagentecambio);
		$this->db->where('fechacambio',$fechacambio);
		$this->db->where('status!=','DEC');

		$query = $this->db->get('cunop_switchrequests');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// toma 2 requests de triangulacion y los marca como unidos
	public function JoinRequestsTriangle($idempresa, $idoficina, $request1uniqueid,$request2uniqueid)
	{
		// req 1
		$data = array(
			'triangulo'		=> $request1uniqueid,
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $request1uniqueid);
		
		$this->db->update('cunop_switchrequests', $data);

		// req 2
		$data = array(
			'triangulo'		=> $request1uniqueid,
			'updated' 		=> $date = date('Y-m-d H:i:s')
        );
		
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $request2uniqueid);
		
		$this->db->update('cunop_switchrequests', $data);
	}

	// trae los registros de un cambio triangular
	public function GetTriangleRecords($idempresa,$idoficina,$triangleid)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('triangulo', $triangleid);

		$this->db->order_by('uniqueid','ASC');

		$query = $this->db->get('cunop_switchrequests');

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}
}