<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Evento_model extends CI_Model{
	public $nombre;
	public $descripcion;
	public $fecha_desde;
	public $fecha_hasta;
	public $estado;
	public $precio;
	public $id_centro;
	
	public function setFechaDesde($fecha){
		$this->fecha_desde = date('Y-m-d',strtotime($fecha));
	}
	
	public function setFechaHasta($fecha){
		$this->fecha_hasta = date('Y-m-d',strtotime($fecha));
	}
	
	public function getFechaDesde(){
		return date('d-m-Y',strtotime($this->fecha_desde));
	}
	
	public function getFechaHasta(){
		return date('d-m-Y',strtotime($this->fecha_hasta));
	}
	
	function obtenerEventosDelCentro($id_centro){
		$this->db->where('id_centro',$id_centro);
		$query = $this->db->get('evento');
		if ($query->num_rows() > 0)
			return $query->result();
		else
			return false;
	}
	
	function armarEvento($evento){
		//cambio el formato de las fechas a dd-mm-yyyy
		$evento->fecha_desde = date('d-m-Y',strtotime($evento->fecha_desde));
		$evento->fecha_hasta = date('d-m-Y',strtotime($evento->fecha_hasta));
		
		//agrego paths a las fotos
		$this->load->helper('file');
		$fotos = get_filenames('uploads/fotos_eventos/'.$evento->id_evento);
		$evento->fotos = array();
		if (is_array($fotos)){
			foreach ($fotos as $foto){
				array_push($evento->fotos,base_url('/uploads/fotos_eventos/'.$evento->id_evento.'/'.urlencode($foto)));
			}
		}
		
		//agrego las charlas
		$this->load->model('Charla_model');
		$evento->charlas = $this->Charla_model->obtenerCharlasDelEvento($evento->id_evento);
		
		//agrego el nombre del centro
		$this->load->model('Centro_model');
		$evento->centro = $this->Centro_model->obtenerCentro($evento->id_centro)->nombre;
		
		return $evento;
	}
	
	function armarEventos($eventos){
		foreach ($eventos as $evento){
			$evento = $this->armarEvento($evento);
		}
		return $eventos;
	}
	
	function obtenerEventos($cant = -1, $offset = -1,$orden = 'nombre',$sentido = 'asc'){
		if (($cant >= 0) && ($offset >= 0)){
			$this->db->limit($cant,$offset);
		}
		$this->db->order_by($orden,$sentido);
		$query = $this->db->get('evento');
		if ($query->num_rows() > 0)
			return $this->armarEventos($query->result());
		else
			return false;
	}
	
	function obtenerNombresEventos($cant = -1, $offset = -1,$orden = 'nombre',$sentido = 'asc'){
		$eventos = $this->obtenerEventos();
		$nombres = array();
		foreach ($eventos as $evento) {
			array_push($nombres,$evento->nombre);
		}
		return $nombres;
	}
	
	function obtenerCantidadEventos(){
		return $this->db->count_all('evento');
	}
	
	function obtenerEvento($id){
		$this->db->where('id_evento',$id);
		$this->db->limit(1);
		$query = $this->db->get('evento');
		if ($query->num_rows() == 1)
			return $this->armarEvento($query->row());
		else
			return false;
	}
	
	function alta($evento){
		return $this->accion('alta',$evento);
	}
	
	function modificacion($evento,$id_evento){
		return $this->accion('modificacion',$evento,$id_evento);
	}
	
	private function accion($accion,$evento,$id_evento = null){
		$this->db->set('fecha_desde',date('Y-m-d',strtotime($evento->fecha_desde)));
		$this->db->set('fecha_hasta',date('Y-m-d',strtotime($evento->fecha_hasta)));
		if ($accion == 'alta'){
			$this->db->insert('evento',$evento);
			$id = $this->db->insert_id();
			if ($id != 0)
				return $id;
			else
				return false;
		} else {
			$this->db->where('id_evento',$id_evento);
			return $this->db->update('evento',$evento);
		}
	}
	
	function eliminar($id){
		$evento = $this->obtenerEvento($id);
		if ($evento){
			$this->load->model('Charla_model');
			if (!$this->Charla_model->obtenerCharlasDelEvento($id)){
				$this->load->model('Direccion_model');
				$this->db->trans_begin();
					if (!$this->db->delete('usuario_evento',array('id_evento' => $id))){
						$this->db->trans_rollback();
						return false;
					}
					if (!$this->db->delete('evento', array('id_evento' => $id))){
						$this->db->trans_rollback();
						return false;
					}
					if ($this->db->trans_status() === FALSE){
						$this->db->trans_rollback();
						return false;
					} else {
						$this->load->model('Foto_model');
						if ($this->Foto_model->eliminarFotosEvento($id)){
							$this->db->trans_commit();
							return true;
						} else {
							$this->db->trans_rollback();
							return false;
						}
					}
			} else return false;
		} else
			return FALSE;
	}
	
	function busquedaSQL($busqueda){
		$this->db->select('id_evento, evento.nombre AS nombre, descripcion,
		fecha_desde, fecha_hasta, estado, precio, evento.id_centro, 
		alquiler_centro, alojamiento_pasajes,publicidad,alquiler_equip,
		centro.nombre AS centroNombre');
		$this->db->join('centro', 'centro.id_centro = evento.id_centro');
		$palabra = strtok($busqueda,' ');
		while($palabra){
			$this->db->or_like('evento.nombre',$palabra);
			$this->db->or_like('descripcion',$palabra);
			$this->db->or_like('fecha_desde',@date('Y-m-d',@strtotime($palabra)));
			$this->db->or_like('fecha_hasta',@date('Y-m-d',@strtotime($palabra)));
			$this->db->or_like('estado',$palabra);
			$this->db->or_like('alquiler_centro',$palabra);
			$this->db->or_like('alojamiento_pasajes',$palabra);
			$this->db->or_like('publicidad',$palabra);
			$this->db->or_like('alquiler_equip',$palabra);
			$this->db->or_like('centro.nombre',$palabra);
			$palabra = strtok(' ');
		}
	}
	
	function busqueda($busqueda,$cant = -1, $offset = -1,$orden = 'nombre', $sentido = 'asc'){
		$this->busquedaSQL($busqueda);
		return $this->obtenerEventos($cant,$offset,$orden,$sentido);
	}
	
	function obtenerCantidadEventosConBusqueda($busqueda){
		$this->busquedaSQL($busqueda);
		$this->db->from('evento');
		return $this->db->count_all_results();
	}
}