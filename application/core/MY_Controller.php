<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

abstract class MY_Controller extends CI_Controller{
	
	function cargarHelpers(){
		$this->load->helper('asset');
		$this->load->helper('form');
		$this->load->helper('url');
	}
	
	function armarData($masData = NULL,$seccion = "home"){
		$data = array();
		
		$data['seccion'] = $seccion;
		
		if ($masData){
			$data += $masData;
		}

		return $data;
	}
	
	function cargarContenido($data = NULL,$seccion = "home"){
		$this->cargarHelpers();
		$dataFinal = $this->armarData($data,$seccion);
		$this->load->view('principal_view',$dataFinal);
	}
}
