<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller del listado y carga de archivo de asistencia perfecta
 *  XALFEIRAN 2019
 */
class PerfectAttendance extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Perfectattendance_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE )
		{
			//echo 'no paso';
			redirect(base_url().'login');
		}
		$mes = $this->input->get("inputFechaMonth");
		$year = $this->input->get('inputFechaYear');
		if($year && $mes)
			$this->LoadPerfectAttendance(date('Y-m-d',strtotime($year . '-' . $mes . '-01')));	
		else
			$this->LoadPerfectAttendance(date('Y-m-d'));
	}
	
	public function LoadPerfectAttendance($fecha)
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'List of Agents penalties';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['usuario'] = $this->session->userdata('shortname');
		$data['numbermes'] = date('m',strtotime($fecha));
		$data['numberyear'] = date('Y',strtotime($fecha));

		$perfect = $this->Perfectattendance_model->ConsultarPerfectAttendanceMesEstacion($empresa,$oficina,$fecha);
		$agentes = $this->Agentes_model->StationAgents($empresa,$oficina);
		
		$data['fullagents'] =  $agentes;	
		$data['perfect'] = $perfect;
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;

		if($data)
		{
			$this->load->view('paginas/header',$tdata);
			$this->load->view('PerfectAttendance_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}


	public function ClearMonth()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$fechames = $this->input->post("month");
		$fechayear = $this->input->post("year");

		$this->Perfectattendance_model->CleanPADates($idempresa,$idoficina,$fechames,$fechayear);

		$data = Array( 'status' => 'ok');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function uploadcsv()
	{

		$idempresa = $this->input->post("inputIdEmpresa");
		$idoficina = $this->input->post("inputIdOficina");
		$fechames = $this->input->post("inputFechaMonth");
		$fechayear = $this->input->post("inputFechaYear");

		
		$config['upload_path']          = './uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 30720;

        $this->load->library('upload', $config);

        $finalnotfound = array();
        $agentesok = 0;

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
        		
        		$this->Perfectattendance_model->CleanPADates($idempresa,$idoficina,$fechames,$fechayear);

				while (($line = fgetcsv($file)) !== FALSE) {

					// esta linea no cuenta				
					if($line[0] != '')
					{
						$shortname = $line[0];
						// el nombre no esta abreviado
						if(strpos($shortname,'.') === false)
						{
							$arr = explode(' ',$shortname);
							$firstname = explode(' ',$shortname)[0];
							$lastname = '';
							for($i = 1; $i<count($arr);$i++)
							{
								$lastname .= $arr[$i] . ' ';	
							}	
							$firstlen = strlen($firstname);						
						}
						else
						{
							$firstlen = strlen(explode('.',$shortname)[0]);
							$firstname = explode('.',$shortname)[0];
							$lastname = explode('.',$shortname)[1];
						}
						$lastname = strtoupper(trim($lastname));
						$firstname = strtoupper(trim($firstname));

						// obtenemos todos los agentes por apellido
						//echo '-- ' . $firstname . ' ' . $lastname . '<br>' . PHP_EOL;

						$agentes = $this->Perfectattendance_model->GetAgentsByLastname($idempresa,$idoficina,$lastname);
						
						$agfound = false;
						if($agentes)
						foreach ($agentes as $valagent) {
							//echo '*' . substr($valagent['nombre'],0,$firstlen) . '*' . $firstname . '*<br/>' . PHP_EOL;

							if(substr($valagent['nombre'],0,strlen($firstname)) ==  $firstname && $valagent['idagente'] != '')
							{
								$inserted = $this->Perfectattendance_model->IngresarAgente($idempresa, $idoficina, $valagent['idagente'], $valagent['shortname'], $fechames, $fechayear,$this->session->userdata('shortname'));
								// tenemos ya la linea de un agente, ahora la analizamos y vamos creando cada campo para insertar
								//print_r($inserted);
								$agfound = true;
							  	if($inserted)
							  		$agentesok++;
							}
								
								//
						}
						if(!$agfound)
						{
							array_push($finalnotfound, $line[0]);
						}
						//print_r($agentes);
						//echo '<br>';
					}
					$linecount++;

				}
				fclose($file);
				//echo 'agentes ok ' . $agentesok;
				$data['notfound'] = $finalnotfound;
				$data['agentesok'] = $agentesok;

				// consulta las oficinas de la cuales es admin el usuario actual
				$adminoficina = $this->Admin_model->GetOficinasAdmin();
				
				$data['perfect'] = $this->Perfectattendance_model->ConsultarPerfectAttendanceMesEstacion($idempresa,$idoficina,date('Y-m-d'));
				$tdata['titulo'] = 'Import results';
				$tdata['oficinas'] = $adminoficina;
				$tdata['perfil'] = $this->session->userdata('perfil');
				
               	$this->load->view('paginas/header',$tdata);
                $this->load->view('PerfectAttendance_view', $data);
                $this->load->view('paginas/footer');
                
        }
	}

	// recibe una linea del impressions con el nombre del agente
	public function ProcesarLineaAgente($agenterow, $month, $year)
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');

		// primero el agente
		//print_r($linea);
		if(!isset($linea[0]) || trim($linea[0]) == '') return;
		$shortname = $linea[0];

		// depurar al agente en ese rango de fechas
		
		$positions = array();
		$agentsnotfound = array();
		$tokencount= 0;
		$dias=0;
		foreach($linea as $token)
		{
			// nos brincamos el nombre del agente
			if($tokencount > 0)
			{
				//echo 'agente-' . $shortname . '-';
				$agente = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $shortname)[0];
				//print_r($agente);
				if(isset($agente['idagente'])){
					
					$fecha = date('Y-m-d', strtotime("+".$dias." days", strtotime($fechaini)));
					$idagente = $agente['idagente'];
					//$fecha = date('Y-m-d', mktime(0, 0, 0, $mes, $tokencount, $year)); 
					$workday = $agente['jornada'];
					$posicion = $token;
					$asignacion = '';
					//echo $fecha . ' ' . $agente['shortname'] . '<br>';
					$this->Loadimpresionmensual_model->ActualizarScheduleAgente($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion);
					$dias++;
				}
				else
				{
					// no encontro el agente en la lista
					array_push($agentsnotfound, $shortname);
					break;
				}
			}

			$tokencount++;
		}
		return $agentsnotfound;
	}

	public function Asynclist()
	{
		$empresa = $this->input->post('idempresa');
		$oficina = $this->input->post('idoficina');
		$data = $this->Castigados_model->ConsultarCastigadosEstacion($empresa,$oficina);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
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

