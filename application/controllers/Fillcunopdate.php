<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 	Controlador para generar el scheduler del dia
 *   XALFEIRAN Marzo 2016
 */
class Fillcunopdate extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Vuelos_model');
		$this->load->model('Posicionesvuelos_model');
		$this->load->model('Fillcunopdate_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}
		
		$this->LoadIndex();
	}
	
	public function LoadIndex()
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		$iatacode = $this->session->userdata('iatacode');
		$tdata['perfil'] = $this->session->userdata('perfil');

		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Import Monthly positions';
		$tdata['oficinas'] = $adminoficina;
	
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;
		$data['iatacode'] = $iatacode;
		$data['usuario'] = $this->session->userdata('shortname');
	
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Fillcunopdate_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}

	public function Processdates()
	{
		error_reporting(E_ERROR | E_PARSE);
		header('Access-Control-Allow-Origin: *');
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$fecha  = $this->input->post('fecha');
		$estacion  = $this->input->post('estacion');
		$usuario  = $this->input->post('usuario');
		$timezone = $this->session->userdata('timezone');


		//  limpiamos el distribvuelos y distribvuelosagentes para fecha
		$this->Fillcunopdate_model->cleanDate($idempresa,$idoficina,$fecha);

		// cargamos los vuelos existentes
		$vueloslist = $this->Vuelos_model->LoadVuelos($estacion, $fecha);
		$linea = 0;

		echo 'TimeZone ' . $timezone . PHP_EOL;

		// creamos un pool de agentes
		$agentpool = array();
		$maxagentspergate = 2;
		$extraagentspergate = 2;
		
		foreach ($vueloslist as $vuelo) {
			
			// vamos a evaluar si el vuelo existe ese dia de la semana
			$weekday = date('w',strtotime($fecha));
			$weekdayisok  = false;
			$posday = '';
			
			switch ($weekday) {
				case 0:
					if($vuelo['dom'] == 1) $weekdayisok = true;
					$posday = 'possun';
					break;
				case 1:
					if($vuelo['lun'] == 1) $weekdayisok = true;
					$posday = 'posmon';
					break;
				case 2:
					if($vuelo['mar'] == 1) $weekdayisok = true;
					$posday = 'postue';
					break;
				case 3:
					if($vuelo['mie'] == 1) $weekdayisok = true;
					$posday = 'poswed';
					break;
				case 4:
					if($vuelo['jue'] == 1) $weekdayisok = true;
					$posday = 'posthu';
					break;
				case 5:
					if($vuelo['vie'] == 1) $weekdayisok = true;
					$posday = 'posfri';
					break;
				case 6:
					if($vuelo['sab'] == 1) $weekdayisok = true;
					$posday = 'possat';
					break;
			}
			
			if($weekdayisok == true)
			{
				// obtenemos la posicion que le corresponde al vuelo
				$posiciones = $this->Posicionesvuelos_model->LoadPosicionesVuelo($idempresa, $idoficina, $vuelo['idvuelo']);
				echo '==>vuelo ' . $vuelo['idvuelo'] . ' ' . $vuelo['horasalida'] . ' =' . sizeof($posiciones) . PHP_EOL;

				$cagents = 1;
				
				foreach($posiciones as $thispos)
				{
					$posicion = $thispos[$posday];

					echo '-->' . $posicion . PHP_EOL;
					
					// traemos los agentes para ese vuelo
					$agentesdisp = $this->Fillcunopdate_model->LoadAgentesScheduleDatePosicion($idempresa, $idoficina, $fecha, $posicion);

					$lead= 'RH';

					//test
					$hora = intval($vuelo['horasalida']);
					$horasalida = intval($hora) + (intval($timezone) * 60);
					$hora = intval($horasalida / 3600) ;
					$minutes = (($horasalida / 3600) - $hora) * 60;
					// end test

					$hora = intval($vuelo['horasalida']);
					$horasalida = $hora - (intval($timezone) * 3600);
					$hora = intval($horasalida / 3600);
					$minutes = (($horasalida / 3600) - $hora) * 60;

					//$horasalida = intval($hora) + (intval($timezone) * 60);
					//$hora = intval($horasalida / 3600) ;
					echo '-' . $horasalida . PHP_EOL;
					//$minutes = (($horasalida / 3600) - $hora) * 60;
					$stime = ($hora<=9?('0' . $hora) : $hora) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
					echo '-- vuelo ' . $vuelo['idvuelo'] . ' fecha ' . $fecha . ' salida ' . $stime . PHP_EOL;
					$this->Fillcunopdate_model->SetFlightHeader($idempresa,$idoficina,$vuelo['idvuelo'],$fecha,$stime,$lead,$usuario);

					$asignadosvuelo = array();

					foreach ($agentesdisp as $agente) {
						echo 'vuelo ' . $vuelo['idvuelo'] . ' posicion ' . $posicion . ' agente ' . $agente['shortname'] . PHP_EOL;
						echo 'cagents ' . $cagents . PHP_EOL;
						echo 'agentpool ' . sizeof($agentpool) . PHP_EOL;
					

						if($cagents<=$maxagentspergate )
						{
							// cada sala permite x agentes 
							$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $vuelo['idvuelo'],$linea,$agente['shortname'],$posicion,$usuario);
							// agregamos el agente al vuelo
							array_push($asignadosvuelo,$agente['shortname']);	
						}
						else
							{
								echo '-- asignando extras --' . PHP_EOL;
								// ya asigno los agentes principales, ahora asigna los extras
								if($cagents <= $maxagentspergate + $extraagentspergate)
								{
									// si el agente actual esta en el pool, entonces es de los que sobran, y se asigna
									$tmp = 0;
									$found = false;
									print_r($agentpool);
									foreach($agentpool as $tmpagent)
									{
										echo '   evaluando el agente del pool ' . $tmpagent['shortname'] . ' vs ' . $agente['shortname'] . ' ' . $agente['posicion'] . PHP_EOL;
										
										// revisando cada agente del pool
										if($tmpagent['shortname'] != $agente['shortname'] && $tmpagent['posicion'] == $agente['posicion'] && !in_array($tmpagent['shortname'],$asignadosvuelo))
										{
											echo '  <-- agente del pool ingresado ' . $tmpagent['shortname'] . PHP_EOL;
											// es el mismo agente, entonces lo agrega
											$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $vuelo['idvuelo'],$linea,$tmpagent['shortname'],$posicion,$usuario);

											// elimina el agente del pool
											unset($agentpool[$tmp]);
											$found = true;
											break 2;
										}
										$tmp++;
									}	
									if(!$found)
									{
										echo '  <-- agente disponible ' . $agente['shortname'] . PHP_EOL;
										// es el mismo agente, entonces lo agrega
										$this->Fillcunopdate_model->SetAgentScheduleDay($idempresa, $idoficina, $fecha, $vuelo['idvuelo'],$linea,$agente['shortname'],$posicion,$usuario);
										array_push($asignadosvuelo,$agente['shortname']);
									}
								}
								else
								{
									
									// los agentes sobrantes se agregan al pool
									array_push($agentpool,$agente);	

									echo 'add to pool ' . sizeof($agentpool) . ' ' . $agente['shortname'] . PHP_EOL;
								}
								
							}
						$cagents ++;
					}
				}
				$linea++;
			}
		}

		// traemos los de bmas
		$agentesbmas = $this->Fillcunopdate_model->LoadAgentesScheduleDatePosicion($idempresa, $idoficina, $fecha, 'B');

		foreach ($agentesbmas as $bmas) {
			
			$this->Fillcunopdate_model->SetAgentBmasDate($idempresa, $idoficina, $fecha, $bmas['idagente'],$bmas['shortname'],$bmas['posicion'],$usuario);
		}


		$insert=$this->Fillcunopdate_model->LoadAgentesScheduleDate($idempresa,$idoficina,$fecha);
		
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
			header('Access-Control-Allow-Origin: *');
    		echo json_encode($insert);
			//$this->output->set_header('application/json', 'utf-8');
			//$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la fecha " . $fecha);
			$this->response($this->json($error), 400);
		}
		//return $res;
	}
	
	public function do_upload()
	{
		$mes = $this->input->post("inputMonth");
		$year = $this->input->post("inputYear");
		
		$config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 30720;

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('userfile'))
        {
                $error = array('error' => $this->upload->display_errors());
               	print_r($error);
                $this->load->view('paginas/footer');
        }
        else
        {
        		// el csv ya esta en el servidor, ahora a cargarlo
				$filedata = $this->upload->data();
				ini_set("auto_detect_line_endings", true);
        		$file = fopen($filedata['full_path'], 'r');

        		// cada linea del csv es un registro de un agente, a excepcion de una cabecera que 
        		//   empieza con el texto LEADS o AGENTS

        		$linecount = 0;

				while (($line = fgetcsv($file)) !== FALSE) {

					// esta linea no cuenta				
					if($linecount > 5)
					{
						if($line[0] != 'LEADS' && $line[0] != 'AGENTS')
						{
							// tenemos ya la linea de un agente, ahora la analizamos y vamos creando cada campo para insertar
							$this->ProcesarLineaAgente($line, $mes, $year);
						  	//print_r($line);
						  	echo '<hr>';
						}
					}
					$linecount++;
				}
				fclose($file);

                
                //$this->load->view('paginas/upload_success', $data);
                $this->load->view('paginas/footer');
        }
	}

	// recibe una linea del impressions con el nombre del agente
	public function ProcesarLineaAgente($linea, $mes, $year)
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');

		// primero el agente
		//print_r($linea);
		if(!isset($linea[0])) return;
		$shortname = $linea[0];
		$positions = array();
		$tokencount= 0;
		foreach($linea as $token)
		{
			// nos brincamos el nombre del agente
			if($tokencount > 0)
			{
				$agente = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $shortname)[0];
				//print_r($agente);
				if(isset($agente['idagente'])){
					
					$idagente = $agente['idagente'];
					$fecha = date('Y-m-d', mktime(0, 0, 0, $mes, $tokencount, $year)); 
					$workday = $agente['jornada'];
					$posicion = $token;
					$asignacion = '';
					//echo $fecha . ' ' . $idagente . '<br>';
					$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion);
				}
			}

			$tokencount++;
		}
	}
		
	public function LoadRowId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Cando_model->LoadRowId($idempresa,$code);
		print_r(json_encode($data));
		return $data;
		
	}
	
	public function DeleteRowId()
	{
		$idempresa = $this->input->post("idempresa");
		$code = $this->input->post("code");
		
		$data = $this->Cando_model->DeleteRowId($idempresa,$code);
		print_r(json_encode($data));
		return $data;
		
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

