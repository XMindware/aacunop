<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *   Modelo de datos para Monthly Roster
 */
class Monthlyroster_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Webcunop_model');
		$this->load->model('Agentes_model');
		$this->load->library(array('session'));
	}

	public function LoadRosterArrayFechas($idempresa, $idoficina, $fechaini, $fechafin){

		$result = array();

		$agentesroster = $this->LoadAgentsFechas($idempresa, $idoficina, $fechaini, $fechafin);
		if($agentesroster){
			foreach($agentesroster as $row){				
				$rowagente = $this->NewLoadRegistrosAgentesFechas($row['idagente'], $row['shortname'], $fechaini, $fechafin, $idempresa, $idoficina);
				
				if($asign = $this->ConsultarAsignacionesAgentePeriodo($idempresa, $idoficina, $row['idagente'], $fechaini, $fechafin)){
					array_push($result, $rowagente);
				}
			}
		}

		return $result;
	}

	public function LoadRosterAgenteArrayFechas($idempresa, $idoficina, $idagente, $shortname, $fechaini, $fechafin){

		$result = array();

		$rowagente = $this->NewLoadRegistrosAgentesFechas($idagente, $shortname, $fechaini, $fechafin, $idempresa, $idoficina);
		//if($this->ConsultarAsignacionesAgentePeriodo($idempresa, $idoficina, $idagente, $fechaini, $fechafin))
		//	array_push($result, $rowagente);
		

		return $rowagente;
	}


	public function ConsultarAsignacionesAgentePeriodo($idempresa, $idoficina, $idagente, $fechaini, $fechafin){

		$query = 'SELECT uniqueid,idempresa,idoficina,fecha,idagente,workday,shortname,asignacion,posicion from cunop_agentscheduler ' .
				   'where idempresa = ' . $idempresa . ' and idoficina = ' . $idoficina . ' and idagente = ' . $idagente . 
				   ' and fecha between "' . $fechaini . '" and "' . $fechafin . '"' . ' and posicion != ""' .
				   ' UNION ' .
				  'SELECT uniqueid,idempresa,idoficina,fecha,idagente,workday,shortname,asignacion,posicion from cunop_distribleads ' .
				   'where idempresa = ' . $idempresa . ' and idoficina = ' . $idoficina . ' and idagente = ' . $idagente . 
				   ' and fecha between "' . $fechaini . '" and "' . $fechafin . '"' . ' and posicion != "" ' .
				   'order by fecha, posicion';
		
		$query = $this->db->query($query);
		//echo $this->db->last_query();
		return $query->result_array();
	}

	public function LoadRegistrosAgentesFechas($idagente, $shortname, $fechaini, $fechafin){

		$mes = intval(date('m',strtotime($fechaini))) - 1;
		$year = intval(date('Y',strtotime($fechaini)));

		$sql = 'SELECT distinct date_field,result.idagente,result.shortname,result.posicion,result.uniqueid,status,tipocambio,cunop_switchrequests.idagente as idagenteinicial,cunop_switchrequests.shortname as agenteinicial,idagentecambio,agentecambio,posicioninicial,posicionsolicitada from (
				SELECT
			    AAA.date_field,
			    IFNULL(?,"") idagente,
			    IFNULL(BBB.posicion,"") posicion,
			    IFNULL(BBB.uniqueid,"") uniqueid,
			    IFNULL(?,"") shortname
			FROM
			(
			    SELECT date_field
			    FROM
			    (
			        SELECT MAKEDATE(?,1) +
			        INTERVAL (?) MONTH +
			        INTERVAL daynum DAY date_field
			        FROM
			        (
			            SELECT t*10+u daynum FROM
			            (SELECT 0 t UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 
			            UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) A,
			            (SELECT 0 u UNION SELECT 1 UNION SELECT 2 UNION SELECT 3
			            UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
			            UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13) B ORDER BY daynum
			        ) AA
			    ) AA WHERE date_field between ? and ?
			) AAA LEFT JOIN (SELECT distinct max(uniqueid) as uniqueid, fecha as date_field, idagente, posicion, shortname FROM cunop_agentscheduler where idagente=? group by idagente,fecha) BBB
			USING (date_field) where date_field between ? and ? ORDER BY date_field ) as result left outer join cunop_switchrequests on result.idagente=cunop_switchrequests.idagente and result.date_field=cunop_switchrequests.fechacambio and cunop_switchrequests.status="AUT" where result.date_field!="" order by date_field asc';
		$query = $this->db->query($sql,array($idagente,$shortname,$year,$mes,$fechaini,$fechafin,$idagente,$fechaini,$fechafin));
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function NewLoadRegistrosAgentesFechas($idagente, $shortname, $fechaini, $fechafin, $idempresa, $idoficina){

		// recibimos los datos de un agente y rango de fechas
		//   hacemos una busqueda en ese rango de fechas y agregamos lo encontrado a un array

		$curfecha = strtotime($fechaini);
		$finalfecha = strtotime($fechafin);

		$resultado = [];
		

		while($curfecha <= $finalfecha){

			// obtener asignaciones del mes
			$sql = 'SELECT cunop_agentscheduler.*,ifnull(cunop_extrapositions.code, "1") as regular ' .
					' from cunop_agentscheduler ' .
					'left outer join cunop_extrapositions on cunop_agentscheduler.posicion = cunop_extrapositions.code ' .
					'where cunop_agentscheduler.idempresa = ' . $idempresa . ' and cunop_agentscheduler.idoficina = ' . $idoficina . 
					' and cunop_agentscheduler.idagente = ' . $idagente . 
					' and cunop_agentscheduler.fecha= "' . date('Y-m-d', $curfecha) . '"' . 
					' UNION ' .
					'SELECT cunop_distribleads.*,ifnull(cunop_extrapositions.code, "1") as regular ' .
					' from cunop_distribleads ' .
					'left outer join cunop_extrapositions on cunop_distribleads.posicion = cunop_extrapositions.code ' .
					'where cunop_distribleads.idempresa = ' . $idempresa . ' and cunop_distribleads.idoficina = ' . $idoficina . 
					' and cunop_distribleads.idagente = ' . $idagente . 
					' and cunop_distribleads.fecha= "' . date('Y-m-d', $curfecha) . '"' . 
					'order by fecha, posicion';
			$query = $this->db->query($sql);
			$agent_data = ($query->num_rows() > 0) ? $query->result_array() : [];

			// consultamos los trades que pueda tener ese agente
			$query = $this->db->where('idempresa', $idempresa)
					 ->where('idoficina', $idoficina)
					 ->where('idagente', $idagente)
					 ->where('fechacambio', date('Y-m-d', $curfecha) )
					 ->where('status','AUT')
					 ->get('cunop_switchrequests');
			$trade1_data = ($query->num_rows() > 0) ? $query->result_array() : [];	

			$query = $this->db->where('idempresa', $idempresa)
					 ->where('idoficina', $idoficina)
					 ->where('idagentecambio', $idagente)
					 ->where('fechacambio', date('Y-m-d', $curfecha) )
					 ->where('status','AUT')
					 ->get('cunop_switchrequests');
			$trade2_data = ($query->num_rows() > 0) ? $query->result_array() : [];			

			$fechadata = [
			'fecha' => date('Y-m-d',$curfecha),
			'asignaciones' => [
				'agent_data'=> $agent_data,
				'trade1' 	=> $trade1_data,
				'trade2'	=> $trade2_data
			] ];

			$resultado[] = $fechadata;
			$curfecha = strtotime(date('Y-m-d',$curfecha) . " +1 days");

		}

		$final = [
			'idagente' 	=> $idagente,
			'shortname'	=> $shortname,
			'resultado'	=> $resultado
		];
		return $final;
	}

	// consulta los agentes y las posiciones cargadas en un rango de fechas
	public function LoadAgentsFechas($estacion, $fechaini, $fechafin){

		$this->db->select()
			 ->from('cunop_agentscheduler')
			 ->join('cunop_agentes','cunop_agentes.idagente = cunop_agentscheduler.idagente and cunop_agentes.status="OK"')
			 ->where("fecha BETWEEN '{$fechaini}' AND '{$fechafin}'")
	 		 ->group_by('cunop_agentscheduler.idagente')
	 		 ->order_by('cunop_agentscheduler.idagente asc');
	 	$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function getWeekRoster($idempresa,$idoficina,$fechaini,$fechafin, $idagente)
	{

		$sql = "SELECT r.uniqueid,a.idagente,a.shortname,r.posicion,r.workday,r.weekday,r.hora " . 
			   "FROM cunop_rosterinfo r INNER JOIN cunop_agentes a ON r.idagente = a.idagente " .
			   "WHERE r.idempresa = ? AND r.idoficina = ? AND r.fecha between ? AND ? AND r.idagente=? " .
			   "ORDER BY r.workday, r.idagente, r.weekday, r.hora";
		$data = $this->db->query($sql,array($idempresa,$idoficina,$fechaini,$fechafin,$idagente));
		if($data->num_rows() > 0)
		{
			return $data->result_array();
		}
	}

	public function getDateRoster($idempresa,$idoficina,$fecha, $idagente, $filtroposicion)
	{	
		$sql = "SELECT r.uniqueid,a.idagente,a.shortname,r.posicion,r.workday,r.fecha FROM cunop_agentscheduler r " .
		       "INNER JOIN cunop_agentes a ON r.idagente = a.idagente LEFT JOIN cunop_positions p ON p.code = r.posicion " .
		       "WHERE r.idempresa = ? AND r.idoficina = ? AND r.fecha = ? AND r.idagente=? and r.posicion<>'' " .
		       "ORDER BY r.workday, r.idagente, p.code";
		$data = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagente));
	
		//echo $this->db->last_query();
		if($data->num_rows() > 0)
		{
			if($filtroposicion != '')
				foreach($data->result_array() as $row)
				{
					if($filtroposicion == 'G')
					{
						if(substr($row['posicion'],0,1) == 'G')
							return $data->result_array();
					}
					if($filtroposicion == substr($row['posicion'],0,2))
						return $data->result_array();
				}
			else
				return $data->result_array();
		}
	}


	public function getWeeklyAgents($idempresa, $idoficina, $fechaini, $fechafin)
	{
		$sql = "SELECT r.idagente, a.shortname, r.workday, r.fecha FROM cunop_agentscheduler r INNER JOIN cunop_agentes a ON r.idagente = a.idagente " .
			   "WHERE r.idempresa=? and r.idoficina=? and r.fecha between ? and ?  group by idagente order by field(r.workday,'FT','PTE','PT'),r.idagente";
		$data = $this->db->query($sql,array($idempresa,$idoficina,$fechaini,$fechafin));
		//echo $this->db->last_query();
		if($data->num_rows() > 0)
		{
			return $data->result_array();
		}
	}

	public function getFlightStatus($idvuelo)
	{
		/*
		$vuelo = substr($idvuelo,2);
		$dia = substr($fecha,8,2);   // 2017-05-05
		$mes = substr($fecha,5,2);
		$ano = substr($fecha,0,4);
		*/
		if(file_exists('assets/flightstatus/' . $idvuelo . '.json'))
		{
			$json = json_decode(file_get_contents('assets/flightstatus/' . $idvuelo . '.json' ));
			//$url = 'https://api.flightstats.com/flex/flightstatus/rest/v2/json/flight/status/AA/' . $vuelo . '/dep/' . $ano . '/' . $mes . '/' . $dia . '?appId=d8358f80&appKey=f0437a765f84a757a807d12b23d4c879&utc=false&airport=CUN&codeType=IATA';
			//echo $url;
			//$json = json_decode(file_get_contents($url ));
			
			$flightstatus = $json->flightStatuses[0];
			
			return $flightstatus;
		}
		else
			return false;
		
	}
	
	public function LoadFlightsDate($empresa,$oficina,$qdate){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('fecha', $qdate);
		$this->db->order_by('horasalida','ASC');

		$query = $this->db->get('cunop_distribucionvuelos');

		if($query->num_rows() > 0)
		{
			$fullarray = array();
			foreach($query->result_array() as $header)
			{
			
				$det = $this->LoadFlightDateDetail($empresa,$oficina,$qdate,$header['idvuelo']);

				$estimado = '';
				$depAirport = '';
				$arrAirport = '';
				$status = '';
				$scheduleDep = '';
				$estimatedDep = '';
				$scheduleArr = '';
				$estimatedArr = '';
				$termDep = '';
				$termArr = '';
				$gateDep = '';
				$gateArr = '';
				$aircraft = '';
				$duration = '';
				if(date('Y-m-d') == $qdate )
				{
					$flightdata = $this->getFlightStatus($header['idvuelo']);

					if($flightdata)
					{
						if(property_exists($flightdata, "departureAirportFsCode"))
							$depAirport = $flightdata->departureAirportFsCode;
						// hora de salida programada
						if(property_exists($flightdata, "arrivalAirportFsCode"))
							$arrAirport = $flightdata->arrivalAirportFsCode;

						// horario de salida estimada
						if(property_exists($flightdata->operationalTimes, "estimatedGateDeparture"))
							$estimatedDep = date("H:i",strtotime($flightdata->operationalTimes->estimatedGateDeparture->dateLocal));
						// hora de salida programada
						if(property_exists($flightdata->operationalTimes, "scheduledGateDeparture"))
							$scheduleDep = date("H:i",strtotime($flightdata->operationalTimes->scheduledGateDeparture->dateLocal));
						// horario de llegada estimada
						if(property_exists($flightdata->operationalTimes, "estimatedGateArrival"))
							$estimatedArr = date("H:i",strtotime($flightdata->operationalTimes->estimatedGateArrival->dateLocal));
						// hora de llegada programada
						if(property_exists($flightdata->operationalTimes, "publishedArrival"))
							$scheduleArr = date("H:i",strtotime($flightdata->operationalTimes->publishedArrival->dateLocal));

						// terminal de salida
						if(property_exists($flightdata, "airportResources"))
							if(property_exists($flightdata->airportResources, "departureTerminal"))
								$termDep = $flightdata->airportResources->departureTerminal;
						// terminal de llegada
						if(property_exists($flightdata, "airportResources"))
							if(property_exists($flightdata->airportResources, "arrivalTerminal"))
								$termArr = $flightdata->airportResources->arrivalTerminal;

						// puerta de salida
						if(property_exists($flightdata, "airportResources"))
							if(property_exists($flightdata->airportResources, "departureGate"))
								$gateDep = $flightdata->airportResources->departureGate;
						// puerta de llegada
						if(property_exists($flightdata, "airportResources"))
							if(property_exists($flightdata->airportResources, "arrivalGate"))
								$gateArr = $flightdata->airportResources->arrivalGate;

						// tipo de aeronave
						if(property_exists($flightdata, "actualEquipmentIataCode"))
							$aircraft = $flightdata->flightEquipment->actualEquipmentIataCode;
						// duracion en minutos
						if(property_exists($flightdata, "flightDurations"))
							$duration = $flightdata->flightDurations->scheduledBlockMinutes;

						
						$status = $flightdata->status;
					}
					
				}
				else
				{}
				
				if(count($det) >= 2){
					$jefe = $det[0];
					//echo $header['idvuelo'] . ' ' . $det[0]['idagente'] . '<br>';	
					$row = array('idvuelo' 		=> $header['idvuelo'],
								 'fecha'		=> $header['fecha'],
								 'status'		=> $status,
								 'depAirport'	=> $depAirport,
								 'arrAirport'	=> $arrAirport,
								 'estimatedDep' => $estimatedDep,
								 'scheduleDep'	=> $scheduleDep,
								 'estimatedArr'	=> $estimatedArr,
								 'scheduleArr'	=> $scheduleArr,
								 'termDep'		=> $termDep,
								 'termArr'		=> $termArr,
								 'gateDep'		=> $gateDep,
								 'gateArr'		=> $gateArr,
								 'aircraft'		=> $aircraft,
								 'duration'		=> $duration,
								 'linea' 		=> $det[0]['linea'],
								 'salida' 		=> $header['horasalida'],

								 'lead'			=> $header['lead']
								 );
					
					for($i=0;$i<count($det);$i++)
					{
						$row['idagent'.($i+1)] = $det[$i]['idagente'];
						$row['pos'.($i+1)] = $det[$i]['posicion'];
					}
					
					array_push($fullarray, $row);
				}
				if(count($det) == 0 )
				{
					// si el vuelo no tiene agentes asignados, se muestra un error
					$row = array('idvuelo' 	=> $header['idvuelo'],
								 'fecha'	=> $header['fecha'],
								 'mensaje' 	=> 'No agents have been assigned, check availability for flight position',
								 );
					array_push($fullarray, $row);
				}
			}
			//print_r($fullarray);
			return $fullarray;
		}	
	}

	// consulta el agente asignado a un vuelo en una posicion, antes de cambiarlo
	public function LoadAgentPrevious($idempresa, $idoficina, $fecha, $idvuelo/*, $linea*/)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);
		/*
		if($linea == 1)
			$this->db->limit(0,1);
		else
			$this->db->limit(1,1);*/
		$query = $this->db->get('cunop_distribucionagentesvuelos');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	// consulta el agente actual registrado para una posicion
	public function ConsultarAgenteActual($idempresa,$idoficina,$uniqueid)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);

		$query = $this->db->get('cunop_agentscheduler');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	// consulta el agente actual registrado para una posicion
	public function DeleteExtraAgent($idempresa,$idoficina,$uniqueid,$idvuelo,$fecha)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);
		$this->db->where('uniqueid', $uniqueid);

		$this->db->delete('cunop_distribucionagentesvuelos');

		$query = $this->LoadFlightDateDetail($idempresa, $idoficina, $fecha, $idvuelo);
		if(sizeof($query) > 0)
		{
			return $query;
		}	
	}

	public function ConsultarLeadActual($idempresa,$idoficina,$uniqueid)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);

		$query = $this->db->get('cunop_distribleads');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadFlightDateDetail($idempresa, $idoficina, $qdate, $idvuelo){
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $qdate);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->order_by('uniqueid', 'ASC');
		$query = $this->db->get('cunop_distribucionagentesvuelos');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadFlightDateHeaderFooter($idempresa, $idoficina, $qdate, $tabla){
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $qdate);
		$this->db->where('tabla', $tabla);

		$this->db->order_by('linea', 'ASC');
		$query = $this->db->get('cunop_distribheadfoot');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadBmasFecha($idempresa, $idoficina, $qdate){
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $qdate);
		$this->db->like('posicion','B');

		$this->db->order_by('posicion', 'ASC');
		$query = $this->db->get('cunop_agentscheduler');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadLeadsFecha($idempresa, $idoficina, $qdate){
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('posicion!=','');
		$this->db->where('posicion!=','XX');
		$this->db->where('posicion!=','VAC');
		$this->db->where('fecha', $qdate);

		$this->db->order_by('idagente', 'ASC');
		$query = $this->db->get('cunop_distribleads');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadAgentsScheduleFecha($idempresa, $idoficina, $qdate){
		
		$sql = "SELECT a.*,w.hours,c.orden FROM cunop_agentscheduler a inner join cunop_workday w on a.workday=w.code inner join cunop_cando c on a.posicion " .
			   " like concat(c.code,'%') where a.fecha=? and a.idempresa=? and a.idoficina=? and a.posicion<>'XX' and a.posicion<>'VAC' order by c.orden,w.hours,a.posicion";

		$query = $this->db->query($sql,array($qdate,$idempresa,$idoficina));

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadAgentsScheduleFechaByPos($idempresa, $idoficina, $qdate, $hours){
		
		$sql = "SELECT a.*,w.hours,c.orden FROM cunop_agentscheduler a inner join cunop_workday w on a.workday=w.code INNER JOIN cunop_positions p ON p.code = a.posicion INNER JOIN cunop_cando c " .
			   " ON c.code = p.cando where a.fecha=? and a.idempresa=? and a.idoficina=? and a.posicion<>'XX' and a.posicion<>'VAC' and a.workday=? order by c.orden,w.hours,a.posicion";

		$query = $this->db->query($sql,array($qdate,$idempresa,$idoficina,$hours));

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	// hace un switch de agente sencillo, busca fecha, posicion, agente anterior y reemplaza con el nuevo
	public function SwitchAgentes($idempresa,$idoficina,$fecha,$shortname_old,$agente_nuevo,$posicion,$usuario)
	{

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('posicion', $posicion);
		$this->db->where('idagente', $shortname_old);

		$data = array(
			'idagente'		=> $agente_nuevo,
			'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
			);
		$this->db->update('cunop_distribucionagentesvuelos', $data);

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('posicion', $posicion);
		$this->db->where('idagente', $agente_nuevo);
		$query = $this->db->get('cunop_distribucionagentesvuelos');

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	

	}


	public function UpdateAgentsVuelosFecha($empresa,$oficina,$fecha,$oldagent,$newagent,$posicion,$usuario)
	{
		$data = array(
            'idagente' 		=> $newagent,
            'posicion'		=> $posicion,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );

        //  TODO: Guardar bitacora

        // obtener el id
		
		// actualiza la info
		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('idagente', $oldagent);
        $this->db->update('cunop_distribucionagentesvuelos', $data);

        $this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('idagente', $newagent);
        $query = $this->db->get('cunop_distribucionagentesvuelos');

        if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function LoadAgentSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion)
	{

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('uniqueid', $uniqueid);
		$this->db->where('idagente', $agenteid);

		$query = $this->db->get('cunop_agentscheduler');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadLeadSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion)
	{

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('uniqueid', $uniqueid);
		$this->db->where('idagente', $agenteid);

		$query = $this->db->get('cunop_distribleads');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function PostCambio($idempresa, $idoficina, $idvuelo, $fecha, $linea, $agente1, $agente2, $posicion1, $posicion2, $usuario)
	{

		// consulta los agentes actuales
		$oldvuelo = $this->LoadFlightDateDetail($idempresa,$idoficina,$fecha,$idvuelo);

		// actualizamos el hdr y el detlle del vuelo
		$this->Fillcunopdate_model->UpdateFlightHeader($idempresa,$idoficina,$idvuelo,$fecha,$usuario);

		$this->Fillcunopdate_model->RemoveAgentScheduleDay($idempresa, $idoficina, $fecha, $idvuelo,$linea);

		// agente1
		$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $idvuelo,$linea,$agente1,$posicion1,$usuario);

		// agente2
		$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $idvuelo,$linea,$agente2,$posicion2,$usuario);

		if(count($oldvuelo)>0)
		{
			$oldagente = $oldvuelo[0]['idagente'];
			$oldposicion = $oldvuelo[0]['posicion'];
			$this->RegistrarBitacora($idempresa,$idoficina,$fecha,$idvuelo,$oldagente,$oldposicion,$agente1,$posicion1,$usuario);
			if(count($oldvuelo)>1)
			{
				$oldagente = $oldvuelo[1]['idagente'];
				$oldposicion = $oldvuelo[1]['posicion'];
				$this->RegistrarBitacora($idempresa,$idoficina,$fecha,$idvuelo,$oldagente,$oldposicion,$agente2,$posicion2,$usuario);
			}
		}

		return $this->LoadFlightDateDetail($idempresa, $idoficina, $fecha, $idvuelo);

	}

	public function ConsultarNuevosAgentes($idempresa,$idoficina,$fecha,$agente)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('idagente', $agente);
        $query = $this->db->get('cunop_distribucionagentesvuelos');

        if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function RegistrarBitacora($idempresa,$idoficina,$fecha,$idvuelo,$oldagente,$oldposicion,$newagente,$newposicion,$usuario)
	{
		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'fecha'			=> $fecha,
			'idvuelo'		=> $idvuelo,
            'oldagente' 	=> $oldagente,
            'oldposicion'	=> $oldposicion,
            'newagente' 	=> $newagente,
            'newposicion'	=> $newposicion,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
        $this->db->replace('cunop_registrocambios',$data);
	}

	public function PostCambioSchedule($empresa, $oficina, $uniqueid, $fecha, $agenteid, $shortname, $posicion, $usuario)
	{
		$data = array(
            'idagente' 		=> $agenteid,
            'shortname'		=> $shortname,
            'posicion'		=> $posicion,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
		
		// actualiza la info
		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('uniqueid', $uniqueid);
		$this->db->where('fecha', $fecha);
        $this->db->update('cunop_agentscheduler', $data);
		
		//echo $this->db->last_query();
		
		return $this->LoadAgentSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion);
	}

	public function PostCambioScheduleLead($empresa, $oficina, $uniqueid, $fecha, $agenteid, $shortname, $posicion, $usuario)
	{
		$data = array(
            'idagente' 		=> $agenteid,
            'shortname'		=> $shortname,
            'posicion'		=> $posicion,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
		
		// actualiza la info
		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('uniqueid', $uniqueid);
		$this->db->where('fecha', $fecha);
        $this->db->update('cunop_distribleads', $data);
		
		//echo $this->db->last_query();
		
		return $this->LoadLeadSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion);
	}

	// agrega un agente asignado a un vuelo
	public function PostExtraAgent($idempresa, $idoficina, $idvuelo, $fecha, $linea, $idagente, $posicion, $usuario)
	{
		$data = array(
            'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
			'idvuelo'		=> $idvuelo,
			'fecha'			=> $fecha,
			'linea'			=> 100 + $linea,
			'idagente'		=> $idagente,
            'posicion'		=> $posicion,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
		
		// actualiza la info
        $this->db->replace('cunop_distribucionagentesvuelos', $data);
		//echo $this->db->last_query();
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('fecha', $fecha);
		$this->db->where('idagente', $idagente);
        $query = $this->db->get('cunop_distribucionagentesvuelos');

        if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

}