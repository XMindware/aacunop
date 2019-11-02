<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * 
 */
class Admin_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
		$this->load->library(array('session','form_validation'));
		$this->load->helper(array('url','form'));
		$this->load->database('default');
	}
	
	public function index()
	{
		if($this->session->userdata('perfil') == FALSE || $this->session->userdata('perfil') == 'suscriptor')
		{
			redirect(base_url().'login');
		}
		$data['titulo'] = 'Bienvenido de nuevo ' .$this->session->userdata('perfil');
		$this->load->view('editor_view',$data);
	}
	
	public function GetOficinasAdmin()
	{
		$query = $this->db->query('select cunop_oficinas.* from cunop_reladminoficina inner join cunop_oficinas on ' .
			'cunop_reladminoficina.idoficina=cunop_oficinas.idoficina where cunop_reladminoficina.idagente=' . $this->session->userdata('idagente'));
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}		
		
	}

	
	public function AcceptTerms($idempresa,$idoficina,$idagente)
	{

		$data = array(
			'termsaccepted'	=> 1,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
        );

        $this->db->where('idempresa', $idempresa);
        $this->db->where('idoficina', $idoficina);
        $this->db->where('idagente', $idagente);

        $this->db->update('cunop_agentes', $data);
	}

	public function PlaceComment($idempresa,$idoficina,$idagente,$shortname,$title,$comment,$usuario)
	{

		$data = array(
			'idempresa'		=> $idempresa,
			'idoficina'		=> $idoficina,
            'idagente'		=> $idagente,
            'shortname'		=> $shortname,
            'title'			=> $title,
            'comment'		=> $comment,
            'updated' 		=> $date = date('Y-m-d H:i:s'),
			'usuario' 		=> $usuario
        );
		
		// actualiza la info
        $this->db->replace('cunop_comments', $data);	
		
	}
}