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
		/*
		$sql = "SELECT uniqueid,tipocambio,agentecambio,fechacambio,posicionsolicitada,posicioninicial,shortname,leadautoriza,fechaacepta,fechaautoriza,status,info,triangulo, case when fechatarget>'0000-00-00' " .
			   "then (case when fechatarget<fechacambio then fechatarget else fechacambio end) else fechacambio end as fechaorden " .
			   "FROM `cunop_switchrequests` WHERE `idempresa` = ? AND `idoficina` = ? and tipocambio not in ('Triangle','Partial') and fechacambio between ? and ? UNION " .
			   "select triangulo,tipocambio,agentecambio,fechacambio,posicionsolicitada,posicioninicial,shortname,leadautoriza,fechaacepta, fechaautoriza,status,info,triangulo, fechacambio as fechaorden from cunop_switchrequests where triangulo in( SELECT triangulo from cunop_switchrequests " .
			   "where idempresa=? and idoficina=? and tipocambio='Triangle' and fechacambio between ? and ? group by triangulo) and uniqueid not in(SELECT triangulo from " .
			   "cunop_switchrequests where idempresa=? and idoficina=? and tipocambio='Triangle' group by triangulo)" .
			   "ORDER BY `fechaorden` asc";
			   */
		$sql = "SELECT
					`cunop_switchrequests`.uniqueid,
					`cunop_switchrequests`.tipocambio,
					`cunop_switchrequests`.agentecambio,
					`cunop_switchrequests`.fechacambio,
					`cunop_switchrequests`.posicionsolicitada,
					`cunop_switchrequests`.posicioninicial,
					`cunop_switchrequests`.shortname,
					`cunop_switchrequests`.leadautoriza,
					`cunop_switchrequests`.fechaacepta,
					`cunop_switchrequests`.fechaautoriza,
					`cunop_switchrequests`.status,
					`cunop_switchrequests`.info,
					`cunop_switchrequests`.triangulo,
					CASE 
						WHEN `cunop_switchrequests`.fechatarget > '0000-00-00' THEN (CASE 
							WHEN `cunop_switchrequests`.fechatarget < `cunop_switchrequests`.fechacambio THEN `cunop_switchrequests`.fechatarget 
							ELSE `cunop_switchrequests`.fechacambio END) 
			ELSE `cunop_switchrequests`.fechacambio END AS fechaorden 
			FROM
			`cunop_switchrequests` 
			WHERE
			`cunop_switchrequests`.`idempresa` = ? 
			AND `cunop_switchrequests`.`idoficina` = ?
			AND `cunop_switchrequests`.tipocambio NOT IN (
				'Triangle', 'Partial'
			) 
			AND `cunop_switchrequests`.fechacambio BETWEEN ? AND ? 
			UNION
			SELECT
			cunop_switchrequests.triangulo,
			cunop_switchrequests.tipocambio,
			cunop_switchrequests.agentecambio,
			cunop_switchrequests.fechacambio,
			cunop_switchrequests.posicionsolicitada,
			cunop_switchrequests.posicioninicial,
			cunop_switchrequests.shortname,
			cunop_switchrequests.leadautoriza,
			cunop_switchrequests.fechaacepta,
			cunop_switchrequests.fechaautoriza,
			cunop_switchrequests.status,
			cunop_switchrequests.info,
			cunop_switchrequests.triangulo,
			cunop_switchrequests.fechacambio AS fechaorden 
			FROM
			cunop_switchrequests 
			WHERE
			cunop_switchrequests.triangulo IN (
				SELECT
					cunop_switchrequests.triangulo 
				FROM
					cunop_switchrequests 
				WHERE
					cunop_switchrequests.idempresa = ? 
					AND cunop_switchrequests.idoficina = '?
					AND cunop_switchrequests.tipocambio = 'Triangle' 
					AND cunop_switchrequests.fechacambio BETWEEN ? AND ? 
				GROUP BY
					cunop_switchrequests.triangulo 
				ORDER BY
					NULL
			) 
			AND cunop_switchrequests.uniqueid NOT IN (
				SELECT
					cunop_switchrequests.triangulo 
				FROM
					cunop_switchrequests 
				WHERE
					cunop_switchrequests.idempresa = ?
					AND cunop_switchrequests.idoficina = ?
					AND cunop_switchrequests.tipocambio = 'Triangle' 
				GROUP BY
					cunop_switchrequests.triangulo 
				ORDER BY
					NULL
			) 
			ORDER BY
			`fechaorden` ASC";
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

		// anteriormente solo checaba los que no descansaban o estuvieran de vacaciones
		/*$sql = "SELECT idagente,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina=? and fecha=? " . 
			   " and posicion not in ('XX','0') union " . 
			   "SELECT idagente,shortname as name,posicion as status FROM `cunop_distribleads` WHERE idempresa=? and idoficina=? and fecha=? " . 
			   " and posicion not in ('XX','0') order by idagente";
		
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idempresa,$idoficina,$fecha));*/

		// ahora se consultan los agentes que estan disponibles y revisa sus skills
		/*
		$sql = "select distinct sc.idagente,sc.shortname as name,sc.posicion as status from cunop_agentscheduler sc inner join cunop_agentes ag on " .
			   "ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where can.idcando in " .
			   "(select cando from cunop_positions where code in (SELECT posicion FROM `cunop_agentscheduler` sc " .
			   "WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? union " .
			   "SELECT posicion  FROM `cunop_distribleads` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=?)) and sc.fecha=? and sc.posicion not in ('XX','0') order by sc.idagente";
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha, $idagentebase,$idempresa,$idoficina,$fecha,$idagentebase,
				$fecha));
		
		$sql = "select distinct sc.idagente,ag.shortname as name,sc.posicion as status from cunop_agentscheduler sc inner join cunop_agentes ag on " .
			   "ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where can.idcando in " .
			   "(select cando from cunop_positions where code in (SELECT posicion FROM `cunop_agentscheduler` sc " .
			   "WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? union " .
			   "SELECT posicion  FROM `cunop_distribleads` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=?)) and sc.fecha=? and sc.posicion not in ('XX','0') UNION " .
			   "select distinct sc.idagente,sc.shortname as name,sc.posicion as status from cunop_distribleads sc inner join cunop_agentes ag on " .
			   "ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where can.idcando in " .
			   "(select cando from cunop_positions where code in (SELECT posicion FROM `cunop_agentscheduler` sc " .
			   "WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? union " .
			   "SELECT posicion  FROM `cunop_distribleads` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=?)) and sc.fecha=? and sc.posicion not in ('XX','0') " .
			   "order by idagente"; */


		// XAS 2020-03-08 en la consulta considera que no se ponga al mismo agente para cubrir y que no permita hacer switch a un agente mas de una veces en una fecha
		$sql = "SELECT distinct sc.idagente,ag.shortname as name,sc.posicion as status from cunop_agentscheduler sc inner join cunop_agentes ag on " .
			   "ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where can.idcando in " .
			   "(SELECT cando from cunop_positions where ag.idagente not in (select idagente from cunop_castigados cas where ? between cas.fechacastigo and cas.fechafin) and " .
			   "code in (SELECT posicion FROM `cunop_agentscheduler` sc " .
			   "WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? union " .
			   "SELECT posicion  FROM cunop_distribleads sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0'))) and " . 
			   "sc.idagente not in (select idagentecambio from cunop_switchrequests where fechacambio=? union select ?) and sc.fecha=? and " .
			   "sc.posicion not in ('XX','0') UNION select distinct sc.idagente,sc.shortname as name,sc.posicion as status from cunop_distribleads sc " . 
			   "inner join cunop_agentes ag on ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where " .
			   "can.idcando in (select cando from cunop_positions where code in (SELECT posicion FROM `cunop_agentscheduler` sc WHERE idempresa=? ".
			   "and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? union SELECT posicion  FROM cunop_distribleads sc " .
			   "WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0'))) and sc.idagente not in (select idagentecambio from " .
			   "cunop_switchrequests where fechacambio=? union select ?) and sc.fecha=? and sc.posicion not in ('XX','0') order by idagente";
		/*
			"SELECT distinct sc.idagente,ag.shortname as name,sc.posicion as status from cunop_agentscheduler sc inner join cunop_agentes ag on ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where can.idcando in (SELECT cando from cunop_positions where code in (SELECT posicion FROM `cunop_agentscheduler` sc WHERE idempresa='1' and idoficina='1' and fecha='2020-11-07' and posicion not in ('XX','0') and idagente='201611' union SELECT posicion  FROM cunop_distribleads sc WHERE idempresa='1' and idoficina='1' and fecha='2020-11-07' and posicion not in ('XX','0'))) and sc.idagente not in (select idagentecambio from cunop_switchrequests where fechacambio='2020-11-07' union select '201611') and sc.fecha='2020-11-07' and sc.posicion not in ('XX','0') UNION select distinct sc.idagente,sc.shortname as name,sc.posicion as status from cunop_distribleads sc inner join cunop_agentes ag on ag.idagente=sc.idagente inner join cunop_relcandoagents can on can.idagente=ag.uniqueid where can.idcando in (select cando from cunop_positions where code in (SELECT posicion FROM `cunop_agentscheduler` sc WHERE idempresa='1' and idoficina='1' and fecha='2020-11-07' and posicion not in ('XX','0') and idagente='201611' union SELECT posicion  FROM cunop_distribleads sc WHERE idempresa='1' and idoficina='1' and fecha='2020-11-07' and posicion not in ('XX','0'))) and sc.idagente not in (select idagentecambio from cunop_switchrequests where fechacambio='2020-11-07' union select '201611') and sc.fecha='2020-11-07' and sc.posicion not in ('XX','0') order by idagente
			*/

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
		//($idempresa,$idoficina,$fecha,$posiciones,$userpos,$userjornada)
	
		/* tomamos a todos los agentes *
		$sql = "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? and posicion='XX' union SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? and posicion='XX' union " . 
			   "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? and " . $posiciones . " and posicion in " .
			   " (select code from cunop_positions where endtime<(select starttime from cunop_positions where code=? and workday=?) union select code from cunop_positions where starttime>(select endtime from cunop_positions where code=? and workday=?) ) order by id desc";
		
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idempresa,$idoficina,$fecha,$idempresa,$idoficina,$fecha,$userpos,$userjornada,$userpos,$userjornada));*/
		if($puesto == 'LEAD')
		{
			/*
			$sql = "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? " . 
			"and posicion<>'0' union SELECT idagente as id,shortname as name,posicion as status FROM `cunop_distribleads` WHERE idempresa=? and idoficina = ? " .
			"and fecha=? order by id desc";
		
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idempresa,$idoficina,$fecha));*/
			$sql = "select distinct dl.idagente as id,ag.shortname as name, dl.posicion as status from cunop_distribleads dl inner join cunop_agentes ag on ag.idagente=dl.idagente " . 
				   "where dl.idagente in (select ag.idagente from cunop_relcandoagents can " .
				   "inner join cunop_agentes ag on ag.uniqueid=can.idagente where ag.status='OK' and idcando in(select distinct cando from cunop_positions where " .
				   "code in " .
				   "(SELECT posicion FROM `cunop_agentscheduler` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? " .
				   "union SELECT posicion  FROM `cunop_distribleads` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') " .
				   "and idagente=?)union select 'AL' union select 'L'  ) ) and dl.idagente!=? and dl.fecha=? and dl.posicion!='0' union select distinct dl.idagente as id,dl.shortname as name, dl.posicion as status from cunop_agentscheduler dl where dl.idagente in (select distinct  ag.idagente from cunop_relcandoagents can inner join cunop_agentes ag on ag.uniqueid=can.idagente where ag.status='OK' and idcando='AL' ) and dl.idagente!=? and dl.fecha=? and dl.posicion!='0' order by id";
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagentebase,$idempresa,$idoficina,$fecha,$idagentebase,$idagentebase,$fecha,$idagentebase,$fecha));
			//echo $this->db->last_query() . PHP_EOL;
		}
		else
		{
			/*
			$sql = "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? " .
					"and posicion<>'0' order by id desc";
		
			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha));*/
			$sql = "select distinct dl.idagente as id,ag.shortname as name,dl.posicion as status from cunop_agentscheduler dl inner join cunop_agentes ag on ag.idagente=dl.idagente " . 
				   "where dl.idagente in (select ag.idagente from cunop_relcandoagents can " .
				   "inner join cunop_agentes ag on ag.uniqueid=can.idagente and ag.status='OK' where idcando in(select cando from cunop_positions where " .
				   "code in " .
				   "(SELECT posicion FROM `cunop_agentscheduler` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') and idagente=? " .
				   "union SELECT posicion  FROM `cunop_distribleads` sc WHERE idempresa=? and idoficina=? and fecha=? and posicion not in ('XX','0') " .
				   "and idagente=?))) and dl.idagente!=? and dl.fecha=? order by dl.idagente";

			$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagentebase,$idempresa,$idoficina,$fecha,$idagentebase,$idagentebase,$fecha));
		}
		//echo $this->db->last_query() .PHP_EOL;
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// obtiene los agentes que tienen disponible hacer un cover en una fecha
	public function getAgentsLastCoverDate($idempresa,$idoficina,$fecha,$idagentebase,$puesto)
	{
		/*
		$this->db->where('fecha', $fecha);
		$this->db->where('posicion','XX');
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('idagente<>' . $idagentebase);
		$this->db->select('idagente as id,shortname as name,posicion as status');
		*/
		$sql = 'SELECT idagente as id, shortname as name,"XX" as status FROM cunop_agentes a WHERE a.status="OK" and idempresa=? and idoficina=? ' .
		'and idagente not in(select idagente from cunop_agentscheduler where posicion<>"XX" and fecha=?) and idagente<>?';


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
			$sql = "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? " . 
			"and posicion<>'0' union SELECT idagente as id,shortname as name,posicion as status FROM `cunop_distribleads` WHERE idempresa=? and idoficina = ? " .
			"and fecha=? order by id desc";
		
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

		$sql = "SELECT idagente as id,shortname as name,posicion as status FROM `cunop_agentscheduler` WHERE idempresa=? and idoficina = ? and fecha=? and posicion='XX' " .
		"and idagente not in (select idagente from cunop_castigados cas where ? between cas.fechacastigo and cas.fechafin)  " .
		"union SELECT idagente as id,shortname as name,posicion as status FROM `cunop_distribleads` WHERE idempresa=? and idoficina = ? and fecha=? and posicion='XX' " . 
		"and idagente not in (select idagente from cunop_castigados cas where ? between cas.fechacastigo and cas.fechafin)  order by id desc";
		
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$fecha, $idempresa,$idoficina,$fecha,$fecha));
		//echo $this->db->last_query();
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