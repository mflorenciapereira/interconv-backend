<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Costos_model extends CI_Model{
	
	function modificacion($costos,$id_evento){
		$this->db->where('id_evento',$id_evento);
		return $this->db->update('evento',$costos);
	}

	function busquedaSQL($busqueda){
		$this->db->select('*');
		$palabra = strtok($busqueda,' ');
		while($palabra){
			$this->db->or_like('nombre',$palabra);
			$palabra = strtok(' ');
		}
	}
	
	function busqueda($busqueda,$cant = -1, $offset = -1,$orden = 'nombre', $sentido = 'asc'){
		$this->busquedaSQL($busqueda);
		$this->load->model('Evento_model');
		return $this->Evento_model->obtenerEventos($cant,$offset,$orden,$sentido);
	}
	
	function obtenerCantidadEventosConBusqueda($busqueda){
		$this->busquedaSQL($busqueda);
		$this->db->from('evento');
		return $this->db->count_all_results();
	}
}