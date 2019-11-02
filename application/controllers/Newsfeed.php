<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 *  XALFEIRAN 2017
 */
class Newsfeed extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model('Newsfeed_model');
		$this->load->model('Admin_model');
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
		

		// consulta las oficinas de la cuales es admin el usuario actual
		$adminoficina = $this->Admin_model->GetOficinasAdmin();
		
		$idempresa = $this->session->userdata('idempresa');
		$idoficina = $this->session->userdata('idoficina');

		$data['titulo'] = 'Bienvenido!';
		$data['oficinas'] = $adminoficina;
		$data['perfil'] = $this->session->userdata('perfil');
		$data['idempresa'] = $idempresa;
		$data['idoficina'] = $idoficina;
		$data['usuario'] = $this->session->userdata('shortname');
 		if($this->session->userdata('perfil')=='usuario')
				$this->load->view('paginas/header_u',$data);
			else
				$this->load->view('paginas/header',$data);

		$data['rowslist'] = $this->Newsfeed_model->LoadAllNews($idempresa,$idoficina);
	

		$this->load->view('Newsfeed_view',$data);
		$this->load->view('paginas/footer',$data);
		
	}

	public function Postnews()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
		$title = $this->input->post("title");
		$fullnews = $this->input->post("fullnews");
		$validthru = $this->input->post("validthru");
		$author = $this->input->post("author");
		$usuario = $this->input->post("usuario");
			
		$insert = $this->Newsfeed_model->PostNews($uniqueid,$idempresa,$idoficina,$title,$fullnews,$author,$validthru,$usuario);
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($insert);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			$this->response($this->json($error), 400);
		}
	}

	public function ViewFullNewsId(){

		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
			
		$insert = $this->Newsfeed_model->ViewFullNewsId($idempresa,$idoficina, $uniqueid);
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($insert);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error");
			$this->response($this->json($error), 400);
		}
	}

	public function DeleteNewsRowId()
	{
		$idempresa = $this->input->post("idempresa");
		$idoficina = $this->input->post("idoficina");
		$uniqueid = $this->input->post("uniqueid");
			
		$insert = $this->Newsfeed_model->DeleteNewsRowId($idempresa,$idoficina,$uniqueid);
		if($insert)
		{
			header('Content-type: application/json; charset=utf-8');
    		echo json_encode($insert);
		}
		else
		{
			$error = array('status' => "Failed", "msg" => "Error al actualizar la posicion");
			$this->response($this->json($error), 400);
		}
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

