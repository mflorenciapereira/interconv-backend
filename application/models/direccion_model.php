<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Direccion_model extends CI_Model{
	public $id_direccion;
	public $calle;
	public $altura;
	public $latitud;
	public $longitud;
	public $codigo_postal;
	public $id_ciudad;
	
	private function alta($calle,$altura,$codigo_postal,$ciudad,$provincia,$pais){
		$this->load->model('Ubicacion_model');
		$id_ciudad = $this->Ubicacion_model->getID($pais,$provincia,$ciudad);
		if (!$id_ciudad)
			$id_ciudad = $this->Ubicacion_model->nueva($pais,$provincia,$ciudad);
		return $this->db->insert('direccion',array('calle' => $calle,'altura' => $altura,
											'codigo_postal' => $codigo_postal,
											'id_ciudad' => $id_ciudad));
	}

	function altaSinTransaccion($calle,$altura,$codigo_postal,$ciudad,$provincia,$pais){
		if ($this->alta($calle,$altura,$codigo_postal,$ciudad,$provincia,$pais))
			return $this->db->insert_id();
		else
			return false;
	}
	
	function altaConTransaccion($calle,$altura,$codigo_postal,$ciudad,$provincia,$pais){
		$this->db->trans_start();
			$this->alta($calle,$altura,$codigo_postal,$ciudad,$provincia,$pais);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return $this->db->insert_id();
	}
	
	private function modificacion($id_direccion,$calle,$altura,$codigo_postal,$ciudad,$provincia,$pais){
		$this->load->model('Ubicacion_model');
		$id_ciudad = $this->Ubicacion_model->getID($pais,$provincia,$ciudad);
		if (!$id_ciudad)
			$id_ciudad = $this->Ubicacion_model->nueva($pais,$provincia,$ciudad);
		$this->db->where('id_direccion',$id_direccion);
		return $this->db->update('direccion',array('calle' => $calle,'altura' => $altura,
											'codigo_postal' => $codigo_postal,
											'id_ciudad' => $id_ciudad));
	}

	function modificacionSinTransaccion($id_centro,$calle,$altura,$codigo_postal,$ciudad,$provincia,$pais){
		return $this->modificacion($id_centro,$calle,$altura,$codigo_postal,$ciudad,$provincia,$pais);
	}
	
	function armarDireccion($direccion){
		$this->load->model('Ubicacion_model');
		$ubicacion = $this->Ubicacion_model->getPorID($direccion->id_ciudad);
		$direccion->ciudad = $ubicacion['ciudad'];
		$direccion->provincia = $ubicacion['provincia'];
		$direccion->pais = $ubicacion['pais'];
		return $direccion;
	}
	
	function obtenerDireccion($id){
		$this->db->limit(1);
		$this->db->where('id_direccion',$id);
		$query = $this->db->get('direccion');
		if ($query->num_rows() == 1)
			return $this->armarDireccion($query->row());
		else
			return false;
	}
	
	function grabar(){
		$this->db->insert('direccion',$this);
	}
	
	function asignarDatos($datos){
			$this->id_direccion = $datos->id_direccion;
			$this->calle = $datos->calle;
			$this->altura = $datos->altura;
			$this->latitud = $datos->latitud;
			$this->longitud = $datos->longitud;
			$this->codigo_postal = $datos->codigo_postal;
			$this->id_ciudad = $datos->id_ciudad;
	}
	
	function cargarDireccion_Propiedades(array $propiedades){
		$query = $this->db->get_where('direccion', $propiedades, 1);
		$direcciones = $query->result();
		$this->asignarDatos($direcciones[count($direcciones)-1]);
		return $direcciones;
	}
	
	function cargarDireccion_ID($id){
		$query = $this->db->get_where('direccion', array('id_direccion'=>$id), 1);
		$direcciones = $query->result();
		$this->asignarDatos($direcciones[count($direcciones)-1]);
		return $direcciones;
	}
	
	
	function obtenerDireccion_String($id){
		$direccion = $this->obtenerDireccion($id);
		if ($direccion === false)
			return false;
		else {
			$direccion_String = $direccion->calle.' '.
								$direccion->altura.' ('.$direccion->codigo_postal.'), '.
								$direccion->ciudad.', '.
								$direccion->provincia.', '.
								$direccion->pais;
			return $direccion_String;
		}
	}
	
	function eliminar($id){
		$this->db->delete('direccion',array('id_direccion' => $id));
		return $this->db->_error_number() == 0;
	}
	
}