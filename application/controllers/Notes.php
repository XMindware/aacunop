<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *	Controller de Notas al calendario
 *  XALFEIRAN 2019-20
 */
class Notes extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model(array('Admin_model','Notes_model','Agentes_model'));
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
	}
	
	public function index()
	{

		if($this->session->userdata('perfil') == FALSE )
		{
			redirect(base_url().'login');
		}

		if($this->session->userdata('isadmin'))
		{
			$this->LoadNotas();	
		}
		else
		{
			//redirect(base_url().'admin');
		}
		
	}
	
	public function LoadNotas()
	{
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');
		
		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$tdata['titulo'] = 'Calendar Notes';
		$tdata['oficinas'] = $adminoficina;
		$tdata['perfil'] = $this->session->userdata('perfil');
		$notes = $this->Notes_model->StationNotes($idempresa,$idoficina,2);
		
		$data['notes'] = $notes;
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;

		if($data)
		{
			//$data = Array('userlist' => $userlist);
			//print_r($userlist);
			$this->load->view('paginas/header',$tdata);
			$this->load->view('Notes_view',$data);
			$this->load->view('paginas/footer');
		}
	
	}
	

	public function loadDateNote()
	{
		$fecha = $this->input->post("fecha");
		$idoficina = $this->input->post('idoficina');
		$idempresa = $this->input->post('idempresa');
		
		$data = $this->Notes_model->loadDateNote($idempresa,$idoficina,$fecha);
		
		header('Content-type: application/json; charset=utf-8');
		print_r(json_encode($data));
		return $data;
		
	}

	public function PostNote()
	{
		$idempresa = $this->input->post('idempresa');
		$idoficina = $this->input->post('idoficina');
		$fecha = $this->input->post("fecha");
		$textonota = $this->input->post("textonota");
		$usuario = $this->session->userdata('shortname');

		$insert = $this->Notes_model->PostNote($idempresa, $idoficina, $fecha, $textonota, $usuario);

		$this->response($this->json($insert), 200);

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

