<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Enero 2017
 */
class Agentcomments_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	
	public function LoadComments($idempresa,$idoficina){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->order_by('updated', 'DESC');
	
		$query = $this->db->get('cunop_comments');
	
		//echo $this->db->last_query();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}


	public function PostNews($uniqueid,$idempresa,$idoficina,$title,$fullnews,$author,$validthru,$usuario){
		
        $result = $this->ViewFullNewsId($idempresa, $idoficina, $uniqueid);
		
		if($uniqueid > -1)
		{
			$data = array(
	            'idempresa' 	=> $idempresa,
	            'idoficina'		=> $idoficina,
	            'title'			=> $title,
	            'fullnews'		=> $fullnews,
	            'validthru'		=> $validthru,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			// actualiza la info
			$this->db->set($data);
			$this->db->where(array('uniqueid'  => $uniqueid));
	        $this->db->update('cunop_newsfeed', $data);

		}
		else
		{
			echo 'no existe';
			$data = array(
	            'idempresa' 	=> $idempresa,
	            'idoficina'		=> $idoficina,
	            'title'			=> $title,
	            'fullnews'		=> $fullnews,
	            'author'		=> $author,
	            'validthru'		=> $validthru,
	            'updated' 		=> $date = date('Y-m-d H:i:s'),
				'usuario' 		=> $usuario
	        );
			
			$this->db->replace('cunop_newsfeed', $data);

		}
		
		return $this->ViewFullNewsId($idempresa, $idoficina, $uniqueid);
		
	}
	
	public function ViewFullNewsId($idempresa,$idoficina, $uniqueid){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('uniqueid',$uniqueid);

		$query = $this->db->get('cunop_newsfeed');
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	

	}
	
	public function DeleteNewsRowId($idempresa,$idoficina, $uniqueid){
		$this->db->where('idempresa',$idempresa);
		$this->db->where('idoficina',$idoficina);
		$this->db->where('uniqueid',$uniqueid);
		
		$query = $this->db->delete('cunop_newsfeed');
		
	}
}