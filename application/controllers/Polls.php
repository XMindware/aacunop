<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Lista de encuestas
 *  XALFEIRAN 2017
 */
class Polls extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Admin_model');
		$this->load->model('Agentes_model');
		$this->load->model('Polls_model');
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

		$this->LoadInfo();
	}
	
	public function LoadInfo()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'List of Polls';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$data['usuario'] = $this->session->userdata('shortname');

		$registros = $this->LoadPolls();

		$agentes = $this->Agentes_model->StationAgents($idempresa,$idoficina);
		
		$data['fullagents'] =  $agentes;	
		$data['registros'] = $registros;
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;

		if($data)
		{
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Polls_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}


	public function LoadPolls(){
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');

		$registros = $this->Polls_model->ConsultarRegistros($idempresa,$idoficina);
	
		if($registros)
			for ($i=0;$i<count($registros);$i++) {
				$info = $this->Polls_model->GetPollResult($registros[$i]['uniqueid']);
				$registros[$i]['result'] = $info;
			}
		return $registros;
	}

	public function DeleteRow()
	{
		$uniqueid = $this->input->post("uniqueid");
		$idoficina = $this->input->post('idoficina');
		$idempresa = $this->input->post('idempresa');
		
		$this->Polls_model->DeleteRow($idempresa,$idoficina,$uniqueid);

		$data = array( 'status' => 'OK' );
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}
	

	public function LoadRow()
	{
		$uniqueid = $this->input->post("uniqueid");
		$idoficina = $this->input->post('idoficina');
		$idempresa = $this->input->post('idempresa');
		
		$data = $this->Polls_model->LoadRow($idempresa,$idoficina,$uniqueid);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
		
	}

	public function PostPollRecord()
	{
		$pollid = $this->input->post("pollid");
		$idagente = $this->session->userdata('idagente');
		$opcion = $this->input->post("opcion");
		$texto = $this->input->post("texto");
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');

		$insert = $this->Polls_model->PostPollRecord($idempresa,$idoficina,$pollid, $idagente, $opcion, $texto);
		
		$data = isset($insert->status)?'OK':'ERROR';

		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function PostRow()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		$uniqueid = $this->input->post("uniqueid");
		$nombre = $this->input->post("nombre");
		$descripcion = $this->input->post("descripcion");
		$fechainicio = $this->input->post("fechaini");
		$fechalimite = $this->input->post("fechafin");
		$opcion1 = $this->input->post("opcion1");
		$opcion2 = $this->input->post("opcion2");
		$opcion3 = $this->input->post("opcion3");
		$opcion4 = $this->input->post("opcion4");
		$opcion5 = $this->input->post("opcion5");
		$opcion6 = $this->input->post("opcion6");
		$opcion7 = $this->input->post("opcion7");
		$opcion8 = $this->input->post("opcion8");
		$opcion9 = $this->input->post("opcion9");
		$opcion10 = $this->input->post("opcion10");
		$opcion11 = $this->input->post("opcion11");
		$opcion12 = $this->input->post("opcion12");
		$opcion13 = $this->input->post("opcion13");
		$opcion14 = $this->input->post("opcion14");
		$opcion15 = $this->input->post("opcion15");
		$opcion16 = $this->input->post("opcion16");
		$opcion17 = $this->input->post("opcion17");
		$opcion18 = $this->input->post("opcion18");
		$opcion19 = $this->input->post("opcion19");
		$opcion20 = $this->input->post("opcion20");
		$usuario = $this->session->userdata("shortname");

		$insert = $this->Polls_model->PostRow($idempresa, $idoficina, $uniqueid, $nombre, $descripcion, $fechainicio, $fechalimite, $opcion1, $opcion2, $opcion3, $opcion4, $opcion5, $opcion6, $opcion7, $opcion8, $opcion9, $opcion10, $opcion11, $opcion12, $opcion13, $opcion14, $opcion15, $opcion16, $opcion17, $opcion18, $opcion19, $opcion20,  $usuario);
		
		$data = isset($insert->status)?'OK':'ERROR';

		header('Content-type: application/json; charset=utf-8');
		echo json_encode($data);
	}

	public function Asynclist()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$data = $this->Polls_model->ConsultarRegistros($idempresa,$idoficina);
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

