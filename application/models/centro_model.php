<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Centro_model extends CI_Model{
	public $nombre;
	public $calle;
	public $altura;
	public $codigo_postal;
	public $ciudad;
	public $provincia;
	public $pais;
	
	function alta($datosCentro){
		$nombre = $datosCentro->nombre;
		$this->db->trans_start();
		$this->load->model('Direccion_model');
		$id_direccion = $this->Direccion_model->altaSinTransaccion($datosCentro->calle,
									$datosCentro->altura,
									$datosCentro->codigo_postal,
									$datosCentro->ciudad,
									$datosCentro->provincia,
									$datosCentro->pais);
		if ($id_direccion) $this->db->insert('centro',array('nombre' => $nombre,'id_direccion' => $id_direccion));
		$id = $this->db->insert_id();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return $id;
	}
	
	function modificacion($datosCentro,$centroOriginal){
		$id_centro = $centroOriginal->id_centro;
		$nombre = $datosCentro->nombre;
		$this->db->trans_start();
		$this->load->model('Direccion_model');
		$ok = $this->Direccion_model->modificacionSinTransaccion($centroOriginal->id_direccion,
									$datosCentro->calle,
									$datosCentro->altura,
									$datosCentro->codigo_postal,
									$datosCentro->ciudad,
									$datosCentro->provincia,
									$datosCentro->pais);
				
		if ($ok){
			$this->db->where('id_centro',$id_centro);
			$this->db->update('centro',array('nombre' => $nombre,
											'id_direccion' => $centroOriginal->id_direccion));
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
	}
	
	function obtenerCantidadCentros(){
		return $this->db->count_all('centro');
	}
	
	function armarCentro($centro){
		//agrego paths a las fotos
		$this->load->helper('file');
		$fotos = get_filenames('uploads/fotos_centros/'.$centro->id_centro);
		$centro->fotos = array();
		if (is_array($fotos)){
			foreach ($fotos as $foto){
				array_push($centro->fotos,base_url('/uploads/fotos_centros/'.$centro->id_centro.'/'.urlencode($foto)));
			}
		}
		//agrego objeto direccion
		$this->load->model('Direccion_model');
		$centro->direccion = $this->Direccion_model->obtenerDireccion($centro->id_direccion);
		
		//agrego direccion en formato string
		$direccion_string = $this->Direccion_model->obtenerDireccion_String($centro->id_direccion);
		if ($direccion_string === false)
			$centro->direccion_string = '<span style="color=red;">Error al leer dirección</span>';
		else
			$centro->direccion_string = $direccion_string;
			
		//agrego eventos asociados
		$this->load->model('Evento_model');
		$centro->eventos = $this->Evento_model->obtenerEventosDelCentro($centro->id_centro);
		
		return $centro;
	}
	
	function armarCentros($centros){
		foreach ($centros as $centro){
			$centro = $this->armarCentro($centro);
		}
		return $centros;
	}
	
	function obtenerCentros($cant = -1, $offset = -1,$orden = 'nombre',$sentido = 'asc'){
		if (($cant >= 0) && ($offset >= 0)){
			$this->db->limit($cant,$offset);
		}
		$this->db->order_by($orden,$sentido);
		$query = $this->db->get('centro');
		if ($query->num_rows() > 0)
			return $this->armarCentros($query->result());
		else
			return false;
	}
	
	function obtenerCentro($id){
		$this->db->where('id_centro',$id);
		$this->db->limit(1);
		$query = $this->db->get('centro');
		if ($query->num_rows() == 1)
			return $this->armarCentro($query->row());
		else
			return false;
	}
	
	function eliminar($id){
		$centro = $this->obtenerCentro($id);
		if ($centro){
			$this->load->model('Evento_model');
			if (!$this->Evento_model->obtenerEventosDelCentro($id)){
				$this->load->model('Direccion_model');
				$this->db->trans_begin();
					if ($this->db->delete('centro', array('id_centro' => $id))){
						if (!$this->Direccion_model->eliminar($centro->id_direccion)){
							$this->db->trans_rollback();
							return false;
						}
					} else {
						$this->db->trans_rollback();
						return false;
					}
					if ($this->db->trans_status() === FALSE){
						$this->db->trans_rollback();
						return false;
					} else {
						$this->load->model('Foto_model');
						if ($this->Foto_model->eliminarFotosCentro($id)){
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
		$this->db->join('direccion', 'direccion.id_direccion = centro.id_direccion');
		$this->db->join('ciudades', 'direccion.id_ciudad = ciudades.idciudades');
		$this->db->join('provincias', 'ciudades.idprovincia = provincias.idprovincias');
		$this->db->join('paises', 'provincias.idpais = paises.idpaises');
		$palabra = strtok($busqueda,' ');
		while($palabra){
			$this->db->or_like('nombre',$palabra);
			$this->db->or_like('calle',$palabra);
			$this->db->or_like('altura',$palabra);
			$this->db->or_like('codigo_postal',$palabra);
			$this->db->or_like('provincia',$palabra);
			$this->db->or_like('pais',$palabra);
			$this->db->or_like('ciudad',$palabra);
			$palabra = strtok(' ');
		}
	}
	
	function busqueda($busqueda,$cant = -1, $offset = -1,$orden = 'nombre',$sentido = 'asc'){
		$this->busquedaSQL($busqueda);
		return $this->obtenerCentros($cant,$offset,$orden,$sentido);
	}
	
	function obtenerCantidadCentrosConBusqueda($busqueda){
		$this->busquedaSQL($busqueda);
		$this->db->from('centro');
		return $this->db->count_all_results();
	}
}