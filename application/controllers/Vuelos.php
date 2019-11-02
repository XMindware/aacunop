<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

error_reporting(E_ERROR | E_PARSE);

/**
 * 
 */
class Vuelos extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Vuelos_model');
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE)
		{
			redirect(base_url().'login');
		}
		
		$this->LoadVuelos();
	}
	
	public function LoadVuelos()
	{
		$empresa = $this->session->userdata('idempresa');
		$estacion = $this->session->userdata('iatacode');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$rowlist = $this->Vuelos_model->LoadVuelos($estacion, null);


		
		$tdata['oficinas'] = $adminoficina;
		$tdata['titulo'] = 'Flight List';
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['idempresa'] = $this->session->userdata('idempresa');
		$data['flightcount'] = sizeof($rowlist);
		$data['estacion'] = $estacion;
		//$data['rowlist'] = $rowlist;
		$data['timezone'] = $this->session->userdata('timezone');

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Vuelos_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}

	public function VuelosEstacion()
	{
		$fecha = $this->input->post('fecha');
		$estacion = $this->input->post('iatacode');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$insert = $this->Vuelos_model->LoadVuelos($estacion, $fecha);
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
			print_r(json_encode($insert));
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al obtener informacion");
			$this->response($this->json($error), 400);
		}
	}

	public function Asyncloadvuelos()
	{
		$idempresa = $this->input->post('idempresa');
		$estacion = $this->input->post('iatacode');
		$timezone = $this->session->userdata('timezone');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$rowlist = $this->Vuelos_model->LoadVuelos($estacion, null);
		if($rowlist)
		{
			$final = [];
			foreach($rowlist as $row)
			{
				$horasalida = $row['horasalida'] - ($timezone * 3600);
				$hours = intval($horasalida / 3600);
				$minutes = (($horasalida / 3600) - $hours) * 60;
				$departure = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
				$hours = intval($row['duracionvuelo'] / 3600);
				$minutes = (($row['duracionvuelo'] / 3600) - $hours) * 60;
				$arrival = ($hours<=9?('0' . $hours) : $hours) . ':' . ($minutes<=9?('0' . $minutes) : $minutes);
				
				if($row['lun']==1 && $row['mar']==1 && $row['mie']==1 && $row['jue']==1 && $row['vie']==1 &&
					$row['sab']==1 && $row['dom']==1 ){
					$msj = "ALL WEEK";
				}
				else
				{
					$msj='';
					if($row['lun']==1)
						$msj = "MON";
					else if($row['mar']==1)
						if(strlen($msj) > 0)
							$msj=$msj . ',TUE';
						else
							$msj='TUE';
					if($row['mie']==1)
						if(strlen($msj) > 0)
							$msj=$msj . ',WED';
						else
							$msj='WED';
					if($row['jue']==1)
						if(strlen($msj) > 0)
							$msj=$msj . ',THU';
						else
							$msj='THU';
					if($row['vie']==1)
						if(strlen($msj) > 0)
							$msj=$msj . ',FRI';
						else
							$msj='FRI';
					if($row['sab']==1)
						if(strlen($msj) > 0)
							$msj=$msj . ',SAT';
						else
							$msj='SAT';
					if($row['dom']==1)
						if(strlen($msj) > 0)
							$msj=$msj . ',SUN';
						else
							$msj='SUN';
				}
				$alert = ( strtotime($row['enddate']) < time()) ? "<i style='color:red'>OUTDATED</i>"  : '';
					
				$final[] = ['idvuelo' 	=> $row['idvuelo'],
							'alert'		=> $alert,
							'enddate' 	=> $row['enddate'],
							'departure' => $departure,
							'arrival' 	=> $arrival,
							'origen'	=> $row['origen'],
							'destino'	=> $row['destino'],
							'msj'		=> $msj];
			

			  } 


			header('Content-type: application/json; charset=utf-8');
			print_r(json_encode($final));
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al obtener informacion");
			$this->response($this->json($error), 400);
		}
	}

	public function VuelosFechaEstacion()
	{
		$fecha = $this->input->get('fecha');
		$estacion = $this->input->get('iatacode');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$insert = $this->Vuelos_model->LoadVuelosFechaEstacion($estacion, $fecha);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			$this->response($this->json($error), 400);
		}
	}
	
	public function PostVuelo()
	{

		$code = $this->input->post("code");
		$origen = $this->input->post("origen");
		$horasalida = $this->input->post("horasalida");
		$destino = $this->input->post("destino");
		$duracionvuelo = $this->input->post("duracionvuelo");
		$lun = $this->input->post("lun");
		$mar = $this->input->post("mar");
		$mie = $this->input->post("mie");
		$jue = $this->input->post("jue");
		$vie = $this->input->post("vie");
		$sab = $this->input->post("sab");
		$dom = $this->input->post("dom");
		$begindate = $this->input->post("begindate");
		$enddate = $this->input->post("enddate");
	
		$insert = $this->Vuelos_model->PostVuelo($code,$origen,$horasalida,$destino,$duracionvuelo,$lun,$mar,$mie,$jue,$vie,$sab,$dom,$begindate,$enddate);
		if($insert)
		{
			$this->response($this->json($insert), 200);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			$this->response($this->json($error), 400);
		}
	}

	public function DeleteRowId()
	{
		$idvuelo = $this->input->post("idvuelo");
		$origen = $this->input->post("origen");
		
		$data = $this->Vuelos_model->DeleteRowId($idvuelo,$origen);
		print_r(json_encode($data));
		return $data;
	}
	
	
	public function LoadVueloCode()
	{
		$idvuelo = $this->input->post("idvuelo");
		
		$data = $this->Vuelos_model->LoadVueloCode($idvuelo);
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

