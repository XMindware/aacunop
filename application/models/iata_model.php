<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 	XALFEIRAN Marzo 2016
 *   Gracias http://iatacodes.org/
 */
class Iata_model extends CI_Model {
        private $api_key = "da1e8a72-7df6-4d85-861e-1686410a0b70";
        private $version = "1";
        private $api_url = "http://iatacodes.org/api/";
		
       public function __construct() {
            if(!empty($api_key)) $this->api_key = $api_key;
            if(!empty($version)) $this->version = $version;
			parent::__construct();
        }
		
		public LoadIataCodes($string){
			return $this->api("airport",$string);
		}
		
        public function api($method, $params = array()) {
            $url = $this->api_url . "v" . $this->version . "/" . $method . "?" . 
                http_build_query(array_merge(array("api_key" => $this->api_key), $params));
            return json_decode(file_get_contents($url), true);
        }
    }
?>