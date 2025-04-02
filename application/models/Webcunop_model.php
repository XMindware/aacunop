<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 *   Ultimo cambio
 *   Borrar agente extra en vuelo 2017-06-29
 */
class Webcunop_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Posicionesvuelos_model');
		$this->load->model('Fillcunopdate_model');
		$this->load->library(array('session'));
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

	public function GetVueloDate($empresa,$oficina,$idvuelo,$qdate)
	{
		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $oficina);
		$this->db->where('fecha', $qdate);
		$this->db->where('idvuelo', $idvuelo);

		$query = $this->db->get('cunop_distribucionvuelos');

		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}
	
	public function LoadFlightsDate($empresa,$oficina,$qdate){

		//$this->db->where('idempresa', $empresa);
		//$this->db->where('idoficina', $oficina);
		//$this->db->where('fecha', $qdate);
		//$this->db->order_by('horasalida','ASC');

		//$query = $this->db->get('cunop_distribucionvuelos');

		/*
			SELECT d.idempresa,d.idoficina,d.idvuelo,d.fecha,v.horasalida,d.newdeparture,d.mensaje,d.updated,v.destino FROM cunop_distribucionvuelos d inner join cunop_vuelos v on d.idvuelo=v.idvuelo where d.idempresa=1 and d.idoficina=1 and d.fecha='2020-11-04' and '2020-11-04' between v.begindate and v.enddate order by d.horasalida asc 
			*/

		$day_of_week = date('N', strtotime($qdate)) ;
		switch ($day_of_week) {
			case 1:	$diasemana = 'lun=1'; break;   //lunes
			case 2:	$diasemana = 'mar=1'; break;   //martes
			case 3:	$diasemana = 'mie=1'; break;   //miercoles
			case 4:	$diasemana = 'jue=1'; break;   //jueves
			case 5:	$diasemana = 'vie=1'; break;   //viernes
			case 6:	$diasemana = 'sab=1'; break;   //sabado
			case 7:	$diasemana = 'dom=1'; break;   //domingo
		}
		//$diasemana = 'mie=1';

		$sql = 'SELECT d.*,v.destino FROM cunop_distribucionvuelos d inner join cunop_vuelos v on d.idvuelo=v.idvuelo where d.idempresa=? and d.idoficina=? and d.fecha=? and ? between v.begindate and v.enddate and ' . $diasemana . ' order by d.horasalida asc ';

		$query = $this->db->query($sql,array($empresa,$oficina,$qdate,$qdate));
		

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
				
				if($det)
				if(count($det) >= 2){
					$jefe = $det[0];
					//echo $header['idvuelo'] . ' ' . $det[0]['idagente'] . '<br>';	
					$row = array('idvuelo' 		=> $header['idvuelo'],
								 'fecha'		=> $header['fecha'],
								 'status'		=> $status,
								 'destino'		=> $header['destino'],
								 'mensaje'		=> $header['mensaje'],
								 'newdeparture'	=> $header['newdeparture'],
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

				if($det)
				if(count($det) == 0 )
				{
					// si el vuelo no tiene agentes asignados, se muestra un error
					$row = array('idvuelo' 	=> $header['idvuelo'],
								 'fecha'	=> $header['fecha'],
								 'errormsj' 	=> 'No agents have been assigned, check availability for flight position',
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

	// consulta el agente actual registrado para una posicion
	public function UpdateFlightMessage($idempresa,$idoficina,$idvuelo,$fecha,$mensaje,$usuario)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);


		$data = array(
			'mensaje'		=> $mensaje,
			'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
			);
		$this->db->update('cunop_distribucionvuelos', $data);

		$query = $this->LoadFlightDateDetail($idempresa, $idoficina, $fecha, $idvuelo);
		if(sizeof($query) > 0)
		{
			return $query;
		}	
	}

	// consulta el agente actual registrado para una posicion
	public function UpdateFlightDeparture($idempresa,$idoficina,$idvuelo,$fecha,$departure,$usuario)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);


		$data = array(
			'horasalida'	=> $departure,
			'newdeparture'	=> 1,
			'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
			);
		$this->db->update('cunop_distribucionvuelos', $data);

		$query = $this->LoadFlightDateDetail($idempresa, $idoficina, $fecha, $idvuelo);
		if(sizeof($query) > 0)
		{
			return $query;
		}	
	}

	// consulta el agente actual registrado para una posicion
	public function EmptyFlightAgents($idempresa,$idoficina,$idvuelo,$fecha,$departure,$usuario)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);

		$this->db->delete('cunop_distribucionvuelos');

		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('idvuelo', $idvuelo);
		$this->db->where('fecha', $fecha);

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

		$sql = "SELECT distinct a.*, w.hours, ifnull(pa.shortname, '') as perfect 
			FROM cunop_agentscheduler a 
			INNER JOIN cunop_workday w ON a.workday = w.code 
			INNER JOIN cunop_positions p ON p.code = a.posicion 
			INNER JOIN cunop_cando c ON c.code = p.cando 
			INNER JOIN cunop_agentes ag ON a.idagente = ag.idagente 
			INNER JOIN cunop_relcandoagents r ON ag.uniqueid = r.idagente AND r.idcando = c.code 
			LEFT OUTER JOIN cunop_perfectattendance pa ON a.idagente = pa.idagente AND pa.month = ? AND pa.year = ? 
			WHERE a.fecha = ? AND a.idempresa = ? AND a.idoficina = ? AND a.posicion LIKE '%B%' 
			ORDER BY p.starttime, c.orden, w.hours, a.posicion";

		$query = $this->db->query($sql,array(date('m',strtotime($qdate)),date('Y',strtotime($qdate)),$qdate,$idempresa,$idoficina));
		//echo $this->db->last_query() .PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadLeadsFecha($idempresa, $idoficina, $qdate){


		$sql = "SELECT distinct a.*, w.hours, ifnull(pa.shortname, '') as perfect 
				FROM cunop_distribleads a 
				INNER JOIN cunop_workday w ON a.workday = w.code 
				INNER JOIN cunop_positions p ON p.code = TRIM(a.posicion) 
				INNER JOIN cunop_cando c ON c.code = p.cando 
				INNER JOIN cunop_agentes ag ON a.idagente = ag.idagente 
				LEFT OUTER JOIN cunop_relcandoagents r ON ag.uniqueid = r.idagente AND r.idcando = c.code 
				LEFT OUTER JOIN cunop_perfectattendance pa ON a.idagente = pa.idagente AND pa.month = ? AND pa.year = ? 
				WHERE a.fecha = ? AND a.idempresa = ? AND a.idoficina = ? AND a.posicion != '' AND a.posicion != 'XX' AND a.posicion != 'VAC' 
				ORDER BY p.starttime, c.orden, w.hours, a.posicion";


		$query = $this->db->query($sql,array(date('m',strtotime($qdate)),date('Y',strtotime($qdate)),$qdate,$idempresa,$idoficina));
		//echo $this->db->last_query() .PHP_EOL;

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadAgentsDate($idempresa,$idoficina,$fecha)
	{
		$padate = strtotime($fecha . ' -1 month');

		$sql = "SELECT a.idagente,a.shortname,c.fechacastigo,c.fechafin,(case when c.razon<>'' then c.razon else (case when p.idagente<>'' then '' else '' end ) end) as comment FROM cunop_agentes a left outer join cunop_castigados c " .
			   "on a.idagente = c.idagente and '" . $fecha . "' between c.fechacastigo and c.fechafin " . 
			   "left outer join cunop_perfectattendance p on a.idagente = p.idagente and p.month='" . date('m',$padate) . "' and p.year='" . 
			   date('Y',$padate) . "' where a.idempresa=? and a.idoficina=? and a.status='OK' order by a.nombre,a.apellidos";

		$query = $this->db->query($sql,Array($idempresa,$idoficina));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadAgentsScheduleFecha($idempresa, $idoficina, $qdate){

		// el perfect attendance va desfazado un mes atras
		$padate = strtotime($qdate . ' -1 month');
		
		$sql = "SELECT distinct a.*,w.hours,c.orden,ifnull(p.shortname,'') as perfect FROM cunop_agentscheduler a inner join cunop_workday w on a.workday=w.code inner join cunop_cando c on a.posicion like concat(c.code,'%') left outer join cunop_perfectattendance p on a.idagente = p.idagente and p.month=? and p.year=? where a.fecha=? and a.idempresa=? and a.idoficina=? and a.posicion<>'XX' and a.posicion<>'VAC' order by c.orden,w.hours,a.posicion";

		$query = $this->db->query($sql,array(date('m',strtotime($padate)),date('Y',strtotime($padate)),$qdate,$idempresa,$idoficina));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadAgentsScheduleFechaByPos($idempresa, $idoficina, $qdate, $hours){
		
		// SQL para consultar la lista de agentes por posicion en una fecha, no considera la relacion entre agentes y sus skills
		/*
		//$sql = "SELECT distinct a.*,w.hours,c.orden FROM cunop_agentscheduler a inner join cunop_workday w on a.workday=w.code INNER JOIN cunop_positions p ON p.code = a.posicion INNER JOIN cunop_cando c " .
			   " ON c.code = p.cando where a.fecha=? and a.idempresa=? and a.idoficina=? and a.posicion<>'XX' and a.posicion<>'VAC' and a.workday=? order by c.orden,w.hours,a.posicion";*/
		// XAS 15-05-2019 se agrega una validacion extra contra los skills del agente para evitar duplicados
		//$sql = "SELECT distinct a.*,w.hours,ifnull(pa.shortname,'') as perfect FROM cunop_agentscheduler a inner join cunop_workday w on a.workday=w.code INNER JOIN cunop_positions p ON p.code = a.posicion INNER JOIN cunop_cando c ON c.code = p.cando INNER JOIN cunop_agentes ag ON a.idagente=ag.idagente and ag.status!='RL' INNER JOIN cunop_relcandoagents r ON ag.uniqueid=r.idagente AND r.idcando=c.code left outer join cunop_perfectattendance pa on a.idagente = pa.idagente and pa.month=? and pa.year=? where a.fecha=? and a.idempresa=? and a.idoficina=? and a.posicion<>'XX' and a.posicion<>'VAC' and a.workday=? order by c.orden,w.hours,a.posicion";
		$sql = "SELECT DISTINCT
					a.*,
					w.hours,
					p.starttime,
					IFNULL(pa.shortname, '') AS perfect
				FROM
					cunop_agentscheduler a
				INNER JOIN cunop_positions p ON
					p.code = a.posicion AND p.workday = a.workday
				INNER JOIN cunop_workday w ON
					p.workday = w.code
				INNER JOIN cunop_cando c ON
					c.code = p.cando
				INNER JOIN cunop_agentes ag ON
					a.idagente = ag.idagente AND ag.status != 'RL'
				INNER JOIN cunop_relcandoagents r ON
					ag.uniqueid = r.idagente AND r.idcando = c.code
				LEFT OUTER JOIN cunop_perfectattendance pa ON
					a.idagente = pa.idagente AND pa.month = ? AND pa.year = ?
				WHERE
					a.posicion NOT IN (select code FROM cunop_extrapositions) AND
					a.fecha = ? AND a.idempresa = ? AND a.idoficina = ? AND a.posicion <> 'XX' AND a.posicion <> 'VAC' AND a.workday = ?
			ORDER BY
				p.starttime, a.posicion;";

		$query = $this->db->query($sql,array(date('m',strtotime($qdate . ' -1 month')),date('Y',strtotime($qdate  . ' -1 month')),$qdate,$idempresa,$idoficina,$hours));
		
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
		
		//echo $this->db->last_query() . PHP_EOL;
		
		return $this->LoadAgentSchedule($uniqueid,$empresa,$oficina,$agenteid,$fecha,$posicion);
	}

	// XAS marzo 2020. Se borra la asignacion a un agente en un dia
	public function DeletePostSchedule($idempresa, $idoficina, $uniqueid, $fecha, $usuario)
	{	
		// actualiza la info
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);
		$this->db->where('fecha', $fecha);
        $this->db->delete('cunop_agentscheduler');
		
		//echo $this->db->last_query() . PHP_EOL;
			
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
        $this->db->insert('cunop_distribucionagentesvuelos', $data);
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

	public function ConsultarQuickSchedule($idempresa,$idoficina,$idagente,$fecha)
	{
		$sql = "SELECT a.uniqueid,a.posicion,a.fecha,p.starttime,p.endtime FROM cunop_agentscheduler a inner join cunop_positions p on " .
			   "p.code=a.posicion and p.workday = a.workday where a.idempresa=? and a.idoficina=? and a.fecha=? and a.idagente=? and ? between p.startdate and p.enddate UNION " .
			   "SELECT d.uniqueid,d.posicion,d.fecha,p.starttime,p.endtime FROM cunop_distribleads d inner join cunop_positions p on " .
			   "p.code=d.posicion and p.workday = d.workday where d.idempresa=? and d.idoficina=? and d.fecha=? and d.idagente=? and ? between p.startdate and p.enddate";
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagente,$fecha,$idempresa,$idoficina,$fecha,$idagente,$fecha));

		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function ConsultarMonthlySchedule($idempresa,$idoficina,$idagente,$fechaini, $fechafin)
	{
		$sql = "SELECT a.idagente,a.posicion,a.workday,a.fecha,ifnull(p.starttime,'0') as starttime,ifnull(p.endtime,'0') as endtime,ifnull(sw.status,'') as status,ifnull(sw.tipocambio,'') as tipocambio,ifnull(sw.agentecambio,'') as agentecambio,ifnull(sw.shortname,'') as shortname, ifnull(sw.idagente,'') as idagentec FROM cunop_agentscheduler a left outer join cunop_positions p on p.code=a.posicion and p.workday = a.workday left outer join  cunop_switchrequests sw on sw.fechacambio=a.fecha and (sw.idagente=a.idagente or sw.idagentecambio=a.idagente) where a.idempresa=? and a.idoficina=? and a.posicion not in('0') and a.fecha between ? and ? and a.idagente=? UNION " .
			   "SELECT a.idagente,a.posicion,a.workday,a.fecha,ifnull(p.starttime,'0') as starttime,ifnull(p.endtime,'0') as endtime,ifnull(sw.status,'') as status,ifnull(sw.tipocambio,'') as tipocambio,ifnull(sw.agentecambio,'') as agentecambio,ifnull(sw.shortname,'') as shortname, ifnull(sw.idagente,'') as idagentec FROM cunop_distribleads a left outer join cunop_positions p on p.code=a.posicion and p.workday = a.workday left outer join  cunop_switchrequests sw on sw.fechacambio=a.fecha and (sw.idagente=a.idagente or sw.idagentecambio=a.idagente) where a.idempresa=? and a.idoficina=? and a.posicion not in('0') and a.fecha between ? and ? and a.idagente=?";
		$query = $this->db->query($sql,array($idempresa,$idoficina,date('Y-m-01', strtotime($fechaini)),date('Y-m-t', strtotime($fechafin)),$idagente,$idempresa,$idoficina,date('Y-m-01', strtotime($fechaini)),date('Y-m-t', strtotime($fechafin)),$idagente));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function ConsultarDayOffSchedule($idempresa,$idoficina,$idagente,$fecha)
	{
		$sql = "SELECT a.idagente,a.posicion,a.workday,a.fecha,ifnull(sw.status,'') as status,ifnull(sw.tipocambio,'') as tipocambio,ifnull(sw.agentecambio,'') as agentecambio,ifnull(sw.shortname,'') as shortname, ifnull(sw.idagente,'') as idagentec FROM cunop_agentscheduler a left outer join  cunop_switchrequests sw on sw.fechacambio=a.fecha and (sw.idagente=a.idagente or sw.idagentecambio=a.idagente) where a.idempresa=? and a.idoficina=? and a.posicion='XX' and a.posicion<>'0' and a.fecha between ? and ? and a.idagente=? UNION " .
			   "SELECT a.idagente,a.posicion,a.workday,a.fecha,ifnull(sw.status,'') as status,ifnull(sw.tipocambio,'') as tipocambio,ifnull(sw.agentecambio,'') as agentecambio,ifnull(sw.shortname,'') as shortname, ifnull(sw.idagente,'') as idagentec FROM cunop_distribleads a left outer join  cunop_switchrequests sw on sw.fechacambio=a.fecha and (sw.idagente=a.idagente or sw.idagentecambio=a.idagente) where a.idempresa=? and a.idoficina=? and a.posicion='XX' and a.posicion<>'0' and a.fecha between ? and ? and a.idagente=?";
		$query = $this->db->query($sql,array($idempresa,$idoficina,date('Y-m-01', strtotime($fecha)),date('Y-m-t', strtotime($fecha . ' +1 month')),$idagente,$idempresa,$idoficina,date('Y-m-01', strtotime($fecha)),date('Y-m-t', strtotime($fecha . ' +1 month')),$idagente));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function ConsultarQuickGates($idempresa,$idoficina,$shortname,$fecha)
	{
		$sql = "SELECT d.fecha,d.posicion,d.idvuelo,v.horasalida,v.destino FROM cunop_distribucionagentesvuelos d inner join cunop_vuelos v on d.idvuelo=v.idvuelo " .
			   "where fecha=? and d.idempresa=? and d.idoficina=? and d.idagente=? " .
			   "order by d.fecha,idagente ";
	
		$query = $this->db->query($sql,array($fecha,$idempresa,$idoficina,$shortname));
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}


	// consultar un registro de asignacion por agente,fecha y posicion
	public function ConsultarRegistroAgenteFechaPos($idempresa,$idoficina,$idagente,$fecha,$posicion)
	{
		$sql = "SELECT a.uniqueid,a.idagente,a.workday,a.posicion,a.fecha,p.starttime,p.endtime FROM cunop_agentscheduler a left outer join cunop_positions p on " .
			   "p.code=a.posicion and p.workday = a.workday where a.idempresa=? and a.idoficina=? and a.fecha=? and a.idagente=? and a.posicion=? UNION " .
			   "SELECT d.uniqueid,d.idagente,d.workday,d.posicion,d.fecha,p.starttime,p.endtime FROM cunop_distribleads d left outer join cunop_positions p on " .
			   "p.code=d.posicion and p.workday = d.workday where d.idempresa=? and d.idoficina=? and d.fecha=? and d.idagente=? and d.posicion=?";
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagente,$posicion,$idempresa,$idoficina,$fecha,$idagente,$posicion));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}

	public function ConsultarRegistroAgenteFecha($idempresa,$idoficina,$idagente,$fecha)
	{
		$sql = "SELECT a.uniqueid,a.idagente,a.workday,a.posicion,a.fecha,p.starttime,p.endtime FROM cunop_agentscheduler a left outer join cunop_positions p on " .
			   "p.code=a.posicion and p.workday = a.workday where a.idempresa=? and a.idoficina=? and a.fecha=? and a.idagente=? and a.posicion<>'0' UNION " .
			   "SELECT d.uniqueid,d.idagente,d.workday,d.posicion,d.fecha,p.starttime,p.endtime FROM cunop_distribleads d left outer join cunop_positions p on " .
			   "p.code=d.posicion and p.workday = d.workday where d.idempresa=? and d.idoficina=? and d.fecha=? and d.idagente=?";
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagente,$idempresa,$idoficina,$fecha,$idagente));
			//echo $this->db->last_query() . PHP_EOL;
			//echo $query->num_rows() . PHP_EOL;
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}

	// consultar un registro de asignacion por agente,fecha y posicion
	public function ConsultarRegistroAgenteFechaDescanso($idempresa,$idoficina,$idagente,$fecha,$posicion)
	{
		$sql = "SELECT a.uniqueid,a.idagente,a.workday,a.posicion,a.fecha FROM cunop_agentscheduler a " .
			   " where a.idempresa=? and a.idoficina=? and a.fecha=? and a.idagente=? and a.posicion=? UNION " .
			   "SELECT d.uniqueid,d.idagente,d.workday,d.posicion,d.fecha FROM cunop_distribleads d " .
			   " where d.idempresa=? and d.idoficina=? and d.fecha=? and d.idagente=? and d.posicion=?";
		$query = $this->db->query($sql,array($idempresa,$idoficina,$fecha,$idagente,$posicion,$idempresa,$idoficina,$fecha,$idagente,$posicion));
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}

	public function DeleteAgenteSchedulerUniqueid($idempresa,$idoficina,$uniqueid)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);
        $this->db->delete('cunop_agentscheduler');
	}

	public function DeleteLeadSchedulerUniqueid($idempresa,$idoficina,$uniqueid)
	{
		$this->db->where('idempresa', $idempresa);
		$this->db->where('idoficina', $idoficina);
		$this->db->where('uniqueid', $uniqueid);
        $this->db->delete('cunop_distribleads');
	}

	public function ConsultarHorasAsignadasAgenteFecha($idempresa,$idoficina,$idagente,$fecha)
	{
		$sql = 'SELECT ifnull(sum(w.hours),0) as horas FROM `cunop_agentscheduler` a inner join cunop_workday w on a.workday=w.code ' .
				'where a.idempresa=? and a.idoficina=? and a.idagente=? and a.fecha=?';

		$query = $this->db->query($sql,array($idempresa,$idoficina,$idagente,$fecha));
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}

		//$this->db->where('idempresa', $idempresa);
		//$this->db->where('idoficina', $idoficina);
		//$this->db->where('idagente', $idagente);
		//$this->db->where('fecha', $fecha);
        //$this->db->delete('cunop_agentscheduler');		
	}

}