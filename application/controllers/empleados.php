<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Empleados extends CI_Controller{

    public function mostrar_pagina_principal(){

        $this->load->view('pagina_principal');

    }

}

?>