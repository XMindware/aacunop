<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 		Modelo para el control de los agentes castigados
 */
class Castigados_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function ConsultarCastigadosEstacion($empresa,$oficina){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $empresa);
		$this->db->order_by('fechacastigo', 'DESC');

		$query = $this->db->get('cunop_castigados');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}

	public function LoadRow($empresa,$oficina,$uniqueid){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $empresa);
		$this->db->where('uniqueid', $uniqueid);

		$query = $this->db->get('cunop_castigados');
		if($query->num_rows() > 0)
		{
			return $query->row();
		}	
	}

	public function DeleteRow($empresa,$oficina,$uniqueid){

		$this->db->where('idempresa', $empresa);
		$this->db->where('idoficina', $empresa);
		$this->db->where('uniqueid', $uniqueid);

		$this->db->delete('cunop_castigados');
			
	}
	
	public function PostRow($empresa, $oficina, $agenteid, $shortname, $fechaini, $fechafin, $razon, $usuario, $uniqueid){
			
		if($uniqueid > -1)
		{
			//echo 'existe ';
			$data = array(
				'idempresa'		=> $empresa,
				'idoficina'		=> $oficina,
	            'idagente' 		=> $agenteid,
	            'shortname' 	=> $shortname,
	            'fechacastigo' 	=> $fechaini,
				'razon'			=> $razon,
				'status'		=> 1,
				'fechafin' 		=> $fechafin,
				'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where(array('uniqueid'  => $uniqueid));
	        $this->db->update('cunop_castigados', $data);

	        return $this->LoadRow($empresa, $oficina, $uniqueid);
		}
		else
		{
			//echo 'no existe';
			$data = array(
				'idempresa'		=> $empresa,
				'idoficina'		=> $oficina,
	            'idagente' 		=> $agenteid,
	            'shortname' 	=> $shortname,
	            'fechacastigo' 	=> $fechaini,
				'aplicocastigo' => $usuario,
				'razon'			=> $razon,
				'status'		=> 1,
				'fechafin' 		=> $fechafin,
				'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			$this->db->replace('cunop_castigados', $data);
			return $this->LoadRow($empresa, $oficina, $this->db->insert_id() );
			//echo $this->db->last_query();
		}
		//echo $this->db->last_query();

		
	}
}