<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 */
class Storms_model extends CI_Model {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function UpdateUser($userid,$email, $deviceid, $platform, $nombre, $push){
		
		$data = array(
			'userid'		=> $userid,
            'nombre' 		=> $nombre,
            'email' 		=> $email,
            'platform'		=> $platform,
            'deviceid' 		=> $deviceid,
            'receivepush'	=> $push,
            'lastupdate' 	=> $date = date('Y-m-d H:i:s')
        );
		
		// actualiza la info
        $this->db->replace('storm_users', $data);
		
		return $this->LoadUserId($userid);
	}
	
	public function LoadUserId($userid){
		
		$this->db->where('userid', $userid);
		$query = $this->db->get('storm_users');
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}	
	}
	
	
	public function DeleteRowId($idvuelo,$origen){
		$this->db->where('idvuelo',$idvuelo);
		$this->db->where('origen',$origen);
		
		$query = $this->db->delete('cunop_vuelos');
		
	}
}