<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 	Controlador para importar las posiciones de los leads del mes
 *   XALFEIRAN Mayo 2017
 */
class Loadleadsmensual extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Loadleadsmensual_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') != 'admin')
		{
			redirect(base_url().'login');
		}
		
		$this->LoadIndex();
	}
	
	public function LoadIndex()
	{
		$empresa = $this->session->userdata('idempresa');
		$oficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Import Leads Monthly positions';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;
	
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Loadleadsmensual_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function loadcsv()
	{
		$idempresa = $this->input->post("inputIdEmpresa");
		$idoficina = $this->input->post("inputIdOficina");
		$fechaini = $this->input->post("inputFechaIni");
		$fechafin = $this->input->post("inputFechaFin");
		
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

        		// la primera linea es la cabecera del mes
        		//  la segunda linea es de los dias de la semana

        		// las lineas de leads inician con su shortname

        		// el final de los agentes es cuando en la primera celda de la linea se encuentra la palabra TOTAL

        		$linecount = 0;

        		// limpia los registros de ese rango de fechas
        		$this->Loadleadsmensual_model->CleanSchedulerDates($idempresa,$idoficina,$fechaini,$fechafin);

				while (($line = fgetcsv($file)) !== FALSE) {

					if($line[0] == 'TOTAL')
						break;
					
					// las lineas 0 y 1 no cuentan			
					if($linecount > 1 && $line[0] != '')
					{
						//echo $line[0] . ' ' . strpos($line[0], 'AGENT') . '-';

						// tenemos ya la linea de un lead, ahora la analizamos y vamos creando cada campo para insertar
						$notfound = $this->ProcesarLinea($line, $fechaini, $fechafin);
					  	//print_r($line);
					  	
					  	if(sizeof($notfound) > 0)
					  		array_push($finalnotfound, $notfound);
					  	else
					  		$agentesok++;
					  	
					}
					$linecount++;
				}
				fclose($file);
				//echo 'agentes ok ' . $agentesok;
				$data['notfound'] = $finalnotfound;
				$data['agentesok'] = $agentesok;

				// consulta las oficinas de la cuales es admin el usuario actual
				$adminoficina = $this->Admin_model->GetOficinasAdmin();
				
				$tdata['titulo'] = 'Import results';
				$tdata['oficinas'] = $adminoficina;
				$tdata['perfil'] = $this->session->userdata('perfil');
				
                $this->load->view('paginas/header',$tdata);
                $this->load->view('Loadimpresionmensual_view', $data);
                $this->load->view('paginas/footer');
        }
	}

	// recibe una linea del impressions con el nombre del agente
	public function ProcesarLinea($linea, $fechaini, $fechafin)
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
				// tiene que existir el agente y tener un puesto de LEAD
				if(isset($agente['idagente']) && $agente['puesto']=='LEAD'){
					
					$fecha = date('Y-m-d', strtotime("+".$dias." days", strtotime($fechaini)));
					$idagente = $agente['idagente'];
					//$fecha = date('Y-m-d', mktime(0, 0, 0, $mes, $tokencount, $year)); 
					$workday = $agente['jornada'];
					$posicion = $token;
					$asignacion = '';
					//echo $fecha . ' ' . $agente['shortname'] . '<br>';
					$this->Loadleadsmensual_model->ActualizarScheduleLead($idempresa, $idoficina, $idagente, $fecha, $workday, $shortname, $asignacion, $posicion);
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

