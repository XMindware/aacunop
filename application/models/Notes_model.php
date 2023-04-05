<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Notes_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function StationNotes($idempresa,$idoficina,$months){

		$this->db->order_by('fecha','DESC');
		$query = $this->db->get('cunop_dailynotes');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
	}

	public function LoadDateNote($empresa,$oficina,$fecha){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $empresa);
		$this->db->where('fecha', $fecha);

		$query = $this->db->get('cunop_dailynotes');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
		else
			return array('textonota' => '');
	}
	
	public function PostNote($idempresa, $idoficina, $fecha, $textonota, $usuario){
		
		$data = array(
            'idempresa' => $idempresa,
            'idoficina' => $idoficina,
            'fecha' 	=> $fecha,
            'textonota'	=> $textonota,
			'updated' 	=> date('Y-m-d H:i:s'),
			'usuario' 	=> $usuario
        );
		
		// actualiza la info
        $this->db->replace('cunop_dailynotes', $data);
		//echo $this->db->last_query();
		return $this->LoadDateNote($idempresa, $idoficina, $fecha);
	}
}