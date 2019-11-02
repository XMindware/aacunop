<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Naranjas extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
     
        // load Session Library
        $this->load->library('session');
         
        // load url helper
        $this->load->helper('url');
    }
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
    {
        /**** SET SESSION DATA ****/
        // set single item in session
        
        $this->session->set_userdata('favourite_website', 'https://tutsplus.com');
         
        // set array of items in session
        $arraydata = array(
                'author_name'  => 'Sajal Soni',
                'website'     => 'http://code.tutsplus.com',
                'twitter_id' => '@sajalsoni',
                'interests' => array('tennis', 'travelling')
        );
        $this->session->set_userdata($arraydata);
         
        /**** GET SESSION DATA ****/
        // get data from session
        echo "Favourite Website: ". $this->session->userdata('favourite_website');
        echo "<br>";
        echo "Author Name: ". $this->session->userdata('author_name');
        echo "<br>";
        echo "Interest (Array Example): " . $this->session->userdata('interests')[0];
        echo "<br>";
     
        // get e'thing stored in session at once
        echo '<pre>';
        print_r($this->session->userdata());
         
        /**** REMOVE SESSION DATA ****/
        // unset specific key from session
        $this->session->unset_userdata('favourite_website');
         
        // unset multiple items at once
        $keys = array('twitter_id', 'interests');
        $this->session->unset_userdata($keys);
     
        echo '<pre>';
        print_r($this->session->userdata());
    }
     
    public function setflash()
    {
        // set flash data
        $this->session->set_flashdata('flash_welcome', 'Hey, welcome to the site!');
         
        // mark existing data as flash data
        $this->session->set_userdata('flash_message', 'I am flash message!');
        $this->session->mark_as_flash('flash_message');
         
        redirect(base_url() . 'naranjas/getflash');
    }
     
    public function getflash() 
    {
        // get flash data
        echo "Flash welcome message: ". $this->session->flashdata('flash_welcome');
        echo '<pre>';
        print_r($this->session->flashdata());
    }
 
    public function tempdata() 
    {
        // set temp data
        $this->session->set_tempdata('coupon_code', 'XYEceQ!', 300);
 
        // mark existing data as temp data
        $this->session->set_userdata('coupon_code', 'XYEceQ!');
        $this->session->mark_as_temp('coupon_code', 300);
         
        // get temp data
        echo $this->session->tempdata('coupon_code');
    }
 
    public function destroy()
    {
        $this->session->set_userdata('favourite_website', 'http://tutsplus.com');
         
        // destory session
        $this->session->sess_destroy();
    }
}
