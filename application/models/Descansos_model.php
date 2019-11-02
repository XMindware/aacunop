<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Descansos_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function StationDaysOff($empresa,$oficina){

		$query = $this->db->query('select a.idempresa,a.idoficina,a.idagente,b.shortname,b.puesto,a.dia1,a.dia2,b.ingreso,a.updated from ' .
					'cunop_descansos a inner join cunop_agentes b on a.idempresa=b.idempresa and a.idoficina=b.idoficina and ' .
					' a.idagente=b.idagente where a.idempresa='. $empresa . ' and a.idoficina=' . $oficina);
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadAgentId($empresa,$oficina,$agenteid){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $empresa);
		$this->db->where('idagente', $agenteid);

		$query = $this->db->get('cunop_descansos');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	public function PostAgent($empresa, $oficina, $agentid, $dia1, $dia2){
		
		$data = array(
            'idagente' 	=> $agentid,
            'idempresa' => $empresa,
            'idoficina' => $oficina,
            'dia1' 		=> $dia1,
            'dia2' 		=> $dia2,
			'updated' 	=> $date = date('Y-m-d H:i:s'),
			'usuario' 	=> $this->session->userdata('shortname')
        );
		
		// actualiza la info
        $this->db->replace('cunop_descansos', $data);
		//echo $this->db->last_query();
		return $this->LoadAgentId($empresa, $oficina, $agentid);
	}
}