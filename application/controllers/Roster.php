<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Descansos
 *  XALFEIRAN 2016
 */
class Roster extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Posiciones_model');
		$this->load->model('Posicionesvuelos_model');
		$this->load->model('Roster_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE)
		{
			redirect(base_url().'login');
		}

		$fecha = $this->input->post("inputFecha");

		if(!isset($fecha) || $fecha=='')	
            $fecha = date("Y-m-d", strtotime('monday this week'));
        else
        	$fecha = date("Y-m-d", $this->last_monday($fecha));

		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');

		$this->LoadCunopFecha($empresa,$oficina,$fecha);
	}

	public function LoadCunopFecha($empresa,$oficina,$qdate)
	{

		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();

		$fullagents = $this->LoadAgents($empresa,$oficina);
			
		$posiciones = $this->Posiciones_model->CompanyPositions($empresa);
		// el listado de agentes
		$agents = $this->Roster_model->LoadAgentsScheduleFecha($empresa,$oficina,$qdate);

		$fechafin = date('Y-m-d',strtotime($qdate . ' + 6 days'));

		$filtroagente = $this->input->post("inputAgentID");
		$filtroposicion = $this->input->post("inputPosicion");


		//$weekinfo = $this->getWeekInfo($empresa,$oficina,$qdate, $fechafin,$filtroagente,$filtroweekday,$filtroposicion,$filtrohora1,$filtrohora2);

		// el footer con datos de gerentes, fechas e indicaciones extras
		$footer = $this->Roster_model->LoadFlightDateHeaderFooter($empresa,$oficina,$qdate,'F');
		$data['timezone'] = $this->session->userdata('timezone');
		$data['idusuario'] = $this->session->userdata('shortname');
		$tdata['perfil'] =$this->session->userdata('perfil');
		$tdata['titulo'] = 'Web CunOP Off';
		$tdata['oficinas'] = $adminoficina;
		
		$data['inputAgentID'] = $filtroagente;
		$data['inputPosicion'] = $filtroposicion;
		//$data['weekinfo'] = $weekinfo;
		$data['week'] = date('W',strtotime($qdate));
		$data['fecha'] = $qdate;
		$data['agentslist'] =  $agents;	
		$data['posiciones'] = $posiciones;
		$data['fullagents'] =  $fullagents;
		$data['footerlist'] =  $footer;	
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;
		$data['step'] = 1;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			if($this->session->userdata('perfil')=='usuario')
				$this->load->view('paginas/header_u',$tdata);
			else
				$this->load->view('paginas/header',$tdata);
			$this->load->view('Roster_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}

	public function AsyncRoster()
	{

		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$qdate = $this->input->post('fechaini');
		$filtroagente = $this->input->post('filtroagente');
		$filtroweekday = $this->input->post('filtroweekday');
		$filtroposicion = $this->input->post('filtroposicion');
		$filtrohora1 = $this->input->post('filtrohora1');
		$filtrohora2 = $this->input->post('filtrohora2');

		$fechafin = date('Y-m-d',strtotime($qdate . ' + 6 days'));


		$weekinfo = $this->getWeekInfo($empresa,$oficina,$qdate, $fechafin,$filtroagente,$filtroweekday,$filtroposicion,$filtrohora1,$filtrohora2);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($weekinfo);
	}

	public function AsyncRosterMonth()
	{

		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$qdate = $this->input->post('fechaini');
		$filtroagente = $this->input->post('filtroagente');
		$filtroweekday = $this->input->post('filtroweekday');
		$filtroposicion = $this->input->post('filtroposicion');
		$filtrohora1 = $this->input->post('filtrohora1');
		$filtrohora2 = $this->input->post('filtrohora2');

		$test = new DateTime($qdate);
		$qdate = $test->modify('first day of this month')->modify('first monday')->format('Y-m-d');
		$last = $test->modify('last day of this month')->format('Y-m-d');
		$fechafin = date('Y-m-d',strtotime($qdate . ' + 6 days'));

		$final = [];
		while($fechafin < $last)
		{
			$weekinfo = $this->getWeekInfo($empresa,$oficina,$qdate, $fechafin,$filtroagente,$filtroweekday,$filtroposicion,$filtrohora1,$filtrohora2);
			$final[] = array($qdate,$weekinfo);
			$qdate = date('Y-m-d',strtotime($qdate . '+1 week'));
			$fechafin = date('Y-m-d',strtotime($qdate . ' + 6 days'));
		}

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($final);
	}


	public function AsyncRosterCSV()
	{

		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$qdate = $this->input->post('fechaini');
		$filtroagente = $this->input->post('filtroagente');
		$filtroweekday = $this->input->post('filtroweekday');
		$filtroposicion = $this->input->post('filtroposicion');
		$filtrohora1 = $this->input->post('filtrohora1');
		$filtrohora2 = $this->input->post('filtrohora2');

		$fechafin = date('Y-m-d',strtotime($qdate . ' + 6 days'));


		$weekinfo = $this->getWeekInfo($empresa,$oficina,$qdate, $fechafin,$filtroagente,$filtroweekday,$filtroposicion,$filtrohora1,$filtrohora2);

		$arr = [];
		for($i=0;$i<sizeof($weekinfo);$i++)
		{
			$row = $weekinfo[$i];
			$sub = [];
			if($i==0)
				$index = 2;
			else
				$index = 0;
			$sub[0] = $row[0];
			$sub[1] = $row[1];
			$sub[2] = $row[2];
			$sub[3] = $row[3];
			$sub[4] = $row[4];

			if(sizeof($row)>0)
			for($j=5;$j<sizeof($row);$j++)
			{
				$sub[$j] = $row[$j][$index];
			
			}
			//print_r($sub);
			$arr[] = $sub;
		}
		
		$file = $this->generateRandomString(10);


		$fp = fopen('uploads/'. $file . '.csv', 'w');

		foreach ($arr as $fields) {
		    fputcsv($fp, $fields);
		}

		fclose($fp);

		echo $file;
		//die();
	}

	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	public function array2csv($array) {
		$string = '';
	    foreach($array as $value) {    
	    	$linea = '';  
	    	foreach($value as $key) {      
	        	$linea .= $key . ',';
	    	}   
	    	$string .= $linea . "\n";
	    }
	    return $string;
	}


	public function getWeekInfo($idempresa,$idoficina,$fechaini,$fechafin,$filtroagente,$filtroweekday,$filtroposicion,$filtrohora1,$filtrohora2)
	{

		// trae los agentes disponibles para esa semana
		$weekagents = $this->Roster_model->getWeeklyAgents($idempresa,$idoficina,$fechaini,$fechafin);
		//echo 'weekagents ' . sizeof($weekagents);
		$positions = $this->Posiciones_model->CompanyPositions($idempresa);

		if(!$weekagents)
			return false;

		$found = true;

		$final = [];

		// agregamos un row de referencia
		$currow[0] = 'ID';
		$currow[1] = 'Shortname';
		$currow[2] = 'Date';
		$currow[3] = 'Workday';
		$currow[4] = 'Day';
		$currow[5] = 'Position';

		// recorre cada agente de la estacion
		foreach ($weekagents as $eachagent) {

			$gointo = true;
			if($filtroagente && $found)
				if($filtroagente == $eachagent['idagente'])
					$gointo = true;
				else
					$gointo = false;
			if($gointo)
			{
			
				$idagente 	= $eachagent['idagente'];
				$shortname 	= $eachagent['shortname'];
				$workday 	= $eachagent['workday'];

				// recorre cada fecha de la semana para ese agente
				$fechatmp = strtotime($fechaini);
				$dfechafin = strtotime($fechafin);
				
				while($fechatmp <= $dfechafin)
				{

					$fecha = date('Y-m-d',$fechatmp);

					// filtro de fecha
					$gointo = true;

					

					if($filtroweekday != '' && $found)
					{
						//echo ' filtrando ';
						if((int)date('w',$fechatmp) == $filtroweekday)
						{
							$gointo = true;
						}
						else
						{
							$gointo = false;
						}
					}
					//echo 'f ' . (int)date('w',$fechatmp) . ' ' . $filtroweekday . ' ' . (int)date('w',$dfechafin) . PHP_EOL;

					if($gointo)
					{

						$daypos = $this->Roster_model->getDateRoster($idempresa,$idoficina,$fecha,$idagente, $filtroposicion);

						//echo $idagente . ' ' . $fecha . ' ' . sizeof($daypos)  . '  <br>';
						$foundpos = false;
						$currow = [];
						$currow[0] = $idagente;
						$currow[1] = $fecha;
						$currow[2] = $shortname;
						$currow[3] = $workday;
						$currow[4] = $this->getWeekday(date('N',strtotime($fecha))-1);

						if(sizeof($daypos)>0)
						{
							$currow[5] = $daypos[0]['posicion'];
							$final[] = $currow;
						}
					}
					$fechatmp = strtotime(date('Y-m-d',$fechatmp) . '+1 days');
					
				} // fecha  < fechafinal
			}
		}

		// ya tenemos el arreglo de horarios

		return $final;
	}
	
	public function LoadAgents($empresa,$oficina)
	{
		
		$data = $this->Agentes_model->StationAgents($empresa,$oficina);
		return $data;
		
	}
	
	public function AsyncLoadStationDate()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$main = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$fecha);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}

	public function AsyncLoadStationLeadsDate()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$main = $this->Webcunop_model->LoadLeadsFecha($empresa,$oficina,$fecha);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}

	public function AppLoadStationDate()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$main = $this->Webcunop_model->LoadFlightsDate($empresa,$oficina,$fecha);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}

	public function AsyncLoadStationSchedule()
	{
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		$fecha 	 = $this->input->post('fecha');

		$pt4 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$fecha,'PT');
		$pt6 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$fecha,'PT6');
		$ft8 = $this->Webcunop_model->LoadAgentsScheduleFechaByPos($empresa,$oficina,$fecha,'FT');
		$leads = $this->Webcunop_model->LoadLeadsFecha($empresa,$oficina,$fecha);
		$bmas = $this->Webcunop_model->LoadBmasFecha($empresa,$oficina,$fecha);
		$main = array($pt4,$pt6,$ft8,$bmas,$leads);

		header('Content-type: application/json; charset=utf-8');
    	echo json_encode($main);
	}
	
	public function LoadAgentId()
	{
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$data = $this->Agentes_model->LoadAgentId($empresa,$oficina,$agenteid);
		echo  json_encode($data);
		return $data;
		
	}

	public function validateAgentId()
	{
		$agenteid = $this->input->post("agenteid");
		$oficina = $this->input->post('idoficina');
		$empresa = $this->input->post('idempresa');
		
		$data = $this->Agentes_model->LoadAgentId($empresa,$oficina,$agenteid);
		$res['status'] = sizeof($data)>0 ? 'OK':'NO';
		$res['shortname'] = $data[0]['shortname'];
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($res);
	}

	

	public function PosicionesHorario($idvuelo){

		$vuelo = $this->Vuelos_model->LoadVueloCode($idvuelo);

		if(sizeof($vuelo))
		{
			$horario = $vuelo[0]['horasalida'];
			
			$rows = $this->Posicionesvuelos_model->PosicionesHorario($horario);

			if($rows)
			{
				return $rows;
			}
		}
	}

	public function PosicionesParaAgente()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$idagente = $this->input->post("idagente");

		$data = $this->Posicionesvuelos_model->PosicionesParaAgenteUnique($idempresa,$idoficina,$idagente);
		if($data)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($data);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error while obtaining position data");
			$this->response($this->json($error), 400);
		}
	}

	function last_monday($date) {
	    if (!is_numeric($date))
	        $date = strtotime($date);
	    if (date('w', $date) == 1)
	        return $date;
	    else
	        return strtotime(
	            'last monday',
	             $date
	        );
	}

	public function getWeekday($weekday)
	{
		$add = 0;
		switch($weekday)
		{
			case 0 : $add = 'Mon'; break;
			case 1 : $add = 'Tue'; break;
			case 2 : $add = 'Wed'; break;
			case 3 : $add = 'Thu'; break;
			case 4 : $add = 'Fri'; break;
			case 5 : $add = 'Sat'; break;
			case 6 : $add = 'Sun'; break;
		}

		return $add;
	}

	function download_send_headers($filename) {
	    // disable caching
	    $now = gmdate("D, d M Y H:i:s");
	    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
	    header("Last-Modified: {$now} GMT");

	    // force download  
	    header("Content-Type: application/force-download");
	    header("Content-Type: application/octet-stream");
	    header("Content-Type: application/download");

	    // disposition / encoding on response body
	    header("Content-Disposition: attachment;filename={$filename}");
	    header("Content-Transfer-Encoding: binary");
	}

	
	/*
	 *	Encode array into JSON
	*/
	private function json($data){
		if(is_array($data)){
			return json_encode($data);
		}
	}

	
	public function response($data,$status){
		$this->_code = ($status)?$status:200;
		echo $data;
		exit;
	}
}

