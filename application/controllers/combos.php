<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Combos extends MY_Controller{

	//----Para los combos, con ajax----//
	
	public function get_provincias_por_pais() {
        $provincias = array();
		if ($this->input->get("pais")) {
			$pais = $this->input->get("pais");
			$this->load->model('Ubicacion_model');
			$provincias = $this->Ubicacion_model->get_provincias_por_pais($pais);
		}
        $this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($provincias);
	}
	
	public function get_ciudades_por_provincia_y_pais() {
        $ciudades = array();
		if ($this->input->get("pais") && $this->input->get("provincia"))
		{
			$pais = $this->input->get("pais");
			$provincia = $this->input->get("provincia");
			$this->load->model('Ubicacion_model');
			$ciudades = $this->Ubicacion_model->get_ciudades_por_pais_y_provincia($pais, $provincia);
		}
        $this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($ciudades);
	}
	
	//----Para el arbol de ubicaciones, con ajax----//
	
	//Devuelve una lista de paises (si hay mas de 1 pais) o una lista de provincias (si hay un solo pais) como JSON
	public function get_primer_nivel() {
        $this->load->model('Ubicacion_model');
		$paises = $this->Ubicacion_model->getPaisesConProvincias();
		
		if (sizeof($paises) > 1) {
			$respuesta = array();
			foreach ($paises as $pais){
				$datos['data'] = $pais;
				$paisURL = str_replace(' ','+',$pais);
				$paisID = str_replace(' ','_',$pais);
				$datos['attr'] = array('id' => $paisID,
									   'href' => 'busqueda/busquedaPorUbicacion/?pais='.$paisURL);
				$datos['state'] = 'closed';
				array_push($respuesta,$datos);
			}
			$this->output->set_header('Content-Type: application/json; charset=UTF-8');
			echo json_encode($respuesta);
		} else
			$this->get_provincias_por_pais2($paises[0]);
	}
	
	//Devuelve una lista de provincias del pais que llega por parametro o por GET como JSON
	public function get_provincias_por_pais2($pais = null) {
		if (!$pais)
			$pais = $this->input->get("pais");
		
		$paisURL = str_replace(' ','+',$pais);
		$paisID = str_replace(' ','_',$pais);
		$this->load->model('Ubicacion_model');
		$provincias = $this->Ubicacion_model->get_provincias_por_pais($pais);
				
		$respuesta = array();
		foreach ($provincias as $provincia){
			$datos['data'] = $provincia->provincia;
			$provinciaURL = str_replace(' ','+',$datos['data']);
			$provinciaID = str_replace(' ','_',$datos['data']);
			$datos['attr'] = array('id' => $paisID.'-'.$provinciaID,
								   'href' => 'busqueda/busquedaPorUbicacion/?pais='.$paisURL.'&provincia='.$provinciaURL);
			$datos['state'] = 'closed';
			array_push($respuesta,$datos);
		}
		$this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($respuesta);
	}
	
	//Devuelve una lista de ciudades del pais y la provincia que llegan por GET como JSON
	public function get_ciudades_por_provincia_y_pais2() {
		$pais = $this->input->get("pais");
		$paisURL = str_replace(' ','+',$pais);
		$paisID = str_replace(' ','_',$pais);
		
		$provincia = $this->input->get("provincia");
		$provinciaURL = str_replace(' ','+',$provincia);
		$provinciaID = str_replace(' ','_',$provincia);
			
		$this->load->model('Ubicacion_model');
		$ciudades = $this->Ubicacion_model->get_ciudades_por_pais_y_provincia($pais, $provincia);
		
		$respuesta = array();
		foreach ($ciudades as $ciudad){
			$datos['data'] = $ciudad->ciudad;
			$ciudadURL = str_replace(' ','+',$datos['data']);
			$ciudadID = str_replace(' ','_',$datos['data']);
			$datos['attr'] = array('id' => $paisID.'-'.$provinciaID.'-'.$ciudadID,
								   'href' => 'busqueda/busquedaPorUbicacion/?pais='.$paisURL.'&provincia='.$provinciaURL.'&ciudad='.$ciudadURL);
			array_push($respuesta,$datos);
		}
		
		$this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($respuesta);
	}
	
	
	public function get_eventos() {
        $eventos = array();
		$this->load->model('Charla_model');
		$eventos = $this->Charla_model->get_eventos();
		
        $this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($eventos);
	}
	
	
	public function get_oradores() {
        $oradores = array();
		$this->load->model('Orador_model');
		$oradores = $this->Orador_model->obtener_oradores_en_orden(-1,-1,'nombre','asc');
		$this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($oradores);
	}
	
	public function get_oradores_por_evento() {
		$oradores = array();
		if ($this->input->get("evento")) {
			$evento = $this->input->get("evento");
			$this->load->model('Charla_model');
			$oradores = $this->Charla_model->obtenerOradoresDelEvento($evento);
		}
		$this->output->set_header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($oradores);
	}
}
?>
