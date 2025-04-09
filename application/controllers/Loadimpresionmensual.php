<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 	Controlador para importar las posiciones del mes a partir del CSV
 *   XALFEIRAN Marzo 2016
 */
class Loadimpresionmensual extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Loadimpresionmensual_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('isadmin')!='1')
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
		
		$tdata['titulo'] = 'Import Monthly positions';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['idempresa'] = $empresa;
		$data['idoficina'] = $oficina;
	
		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Loadimpresionmensual_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	
	public function do_upload()
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

        		// cada linea del csv es un registro de un agente, a excepcion de una cabecera que 
        		//   empieza con el texto LEADS o AGENTS

        		$linecount = 0;

        		$this->Loadimpresionmensual_model->CleanSchedulerDates($idempresa,$idoficina,$fechaini,$fechafin);

				while (($line = fgetcsv($file)) !== FALSE) {

					// esta linea no cuenta				
					if($linecount > 2 && $line[0] != '')
					{
						//echo $line[0] . ' ' . strpos($line[0], 'AGENT') . '-';
						if(strpos($line[0], 'LEAD') !== false || strpos($line[0], 'AGENT') !== false)
						{
							//echo $line[0] . ' nop <br>';
						}
						else
						{

							// tenemos ya la linea de un agente, ahora la analizamos y vamos creando cada campo para insertar
							$notfound = $this->ProcesarLineaAgente($line, $fechaini, $fechafin);
						  	//print_r($line);
						  	
						  	if($notfound && sizeof($notfound) > 0)
						  		array_push($finalnotfound, $notfound);
						  	else
						  		$agentesok++;
						  	
						}
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
	public function ProcesarLineaAgente($linea, $fechaini, $fechafin)
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$agentsnotfound = array();

		// primero el agente
		//print_r($linea);
		if(!isset($linea[0]) || trim($linea[0]) == '') return;
		$shortname = $linea[0];

		$agente = $this->Agentes_model->FindAgentByShortname($idempresa, $idoficina, $shortname)[0];

		if(!$agente)
		{
			array_push($agentsnotfound, $shortname);
			return $agentsnotfound;
		}
		$idagente = $agente['idagente'];
		//$fecha = date('Y-m-d', mktime(0, 0, 0, $mes, $tokencount, $year)); 
		$workday = $agente['jornada'];

		// depurar al agente en ese rango de fechas
		$this->Loadimpresionmensual_model->CleanSchedulerDatesAgente($idempresa,$idoficina,$fechaini,$fechafin,$idagente);
		
		$positions = array();
		$tokencount= 0;
		$dias=0;
		foreach($linea as $token)
		{
			// nos brincamos el nombre del agente
			if($tokencount > 0)
			{
				
				//print_r($agente);
				if(isset($agente['idagente'])){
					
					$fecha = date('Y-m-d', strtotime("+".$dias." days", strtotime($fechaini)));
					
					$posicion = $token;
					$asignacion = '';
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
	
	public function AddSkillAgente()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$idagente = $this->input->post("idagente");
		$idcando = $this->input->post("idcando");
			
		$insert = $this->Cando_model->AddSkillAgente($idempresa,$idoficina,$idagente,$idcando);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al agregar Skill");
			$this->response($this->json($error), 400);
		}
	}
		
	public function LoadSkillsAgente()
	{
		$idagente = $this->input->post("idagente");
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		$data = $this->Cando_model->LoadRelCandoAgentsRowId($idempresa,$idoficina,$idagente);
		print_r(json_encode($data));
		return $data;
		
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

