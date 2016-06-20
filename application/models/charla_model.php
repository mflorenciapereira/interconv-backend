<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Charla_model extends CI_Model{
	public $nombre;
	public $descripcion;
	public $fecha;
	public $hora_desde;
	public $hora_hasta;
	public $registrados;
	public $confirmados;
	public $contiene_multimedia;
	public $sala;
	public $id_charla;
	public $id_evento;
	public $nombre_evento;
	public $capacidad;
	
    function __construct(){
        
        parent::__construct();
    }
	
	private $tabla= 'charla';

	// cantidad de charlas en la bd
	function contar_todos(){
		$this->db->from($this->tabla);
		return $this->db->count_all_results();
	}
	
	function contar_busqueda($busqueda){
		$this->consulta_busqueda($busqueda);
		$this->db->from($this->tabla);
		return $this->db->count_all_results();
	}

	
	// get por key
	function get($id){
		$this->db->where('id_charla', $id);
		return $this->db->get($this->tabla);
		
	}
	// agregar
	function agregar($charla){
		$this->db->trans_start();
		$this->db->insert($this->tabla, $charla);
		$id=$this->db->insert_id();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return $id;
	}
	// actualizar
	function actualizar($id,$charla){
		$this->db->trans_start();
		$this->db->where('id_charla', $id);
		$this->db->update($this->tabla, $charla);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
	}
	// eliminar
	function eliminar($id){
		$this->db->trans_begin();
			if (!$this->eliminar_asociaciones_orador($id)){
				$this->db->trans_rollback();
				return false;
			}
			
			if (!$this->eliminar_asociaciones_usuario($id)){
				$this->db->trans_rollback();
				return false;
			}
		
		$this->db->where('id_charla', $id);
		$this->db->delete($this->tabla);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
		
	}
	
	// TODO: esto debería ir en el model de eventos.
	function get_eventos(){
		$this->db->select('id_evento,nombre');
		$this->db->from('evento');
		$query = $this->db->get();
		return $query->result();
	}
	
	function obtenerCharlasDelEvento($id_evento){
		$this->db->where('id_evento',$id_evento);
		$query = $this->db->get('charla');
		if ($query->num_rows() > 0) return $query->result();
		else return false;
	}
	
	//TODO ya que estamos sigo metiendo cosas aca
	//devuelve la lista de los oradores con todos todos los datos
	function obtenerOradoresDelEvento($id_evento){
		$this->load->model('Orador_model');
		$oradores = array();
		$this->db->where('id_evento',$id_evento);
		$query = $this->db->get('charla');
		foreach ($query->result('Charla_model') as $charla){
			$query_charla = $this->get_oradores_por_charla($charla->id_charla);
			foreach($query_charla->result('Orador_model') as $dni){
						$orador = new Orador_model();
						$orador->levantar_orador($dni->id_usuario);
						if (!in_array($orador,$oradores)) array_push($oradores, $orador);
				}
		}
		return $oradores;
	}

	
	function consulta_busqueda($busqueda){
	if($busqueda!='')
		while($busqueda){
			$this->db->or_like('nombre',$busqueda);
			$this->db->or_like('descripcion',$busqueda);
			$busqueda = strtok(' ');
		}
	
	
	}
	function ordenar($columna='id_charla',$orden='asc'){
	if(($columna!='')&&($orden!='')){
		$this->db->order_by($columna,$orden);	
	};
	
	}
	
	function limitar($limite=2,$offset=0){
		$this->db->limit($limite,$offset);
	}
	
	function obtener_busqueda_orden($busqueda,$columna,$orden,$limite,$offset) {
		$this->consulta_busqueda($busqueda);
		$this->ordenar($columna,$orden);
		$this->limitar($limite,$offset);
		$query = $this->db->get($this->tabla);
		return $query->result();
	}
	
		
	function obtener_orden($columna,$orden,$limite,$offset) {
		$this->ordenar($columna,$orden);
		$this->limitar($limite,$offset);
		$query = $this->db->get($this->tabla);
		return $query->result();
	}
	
		
	function get_oradores_por_charla($idcharla){
		$this->db->where('id_charla', $idcharla);
		$this->db->order_by('id_usuario','asc');	
		return $this->db->get('charla_orador');
		
	}
	
	function eliminar_asociacion($idcharla,$idusuario){
	
		$this->db->where('id_charla', $idcharla);
		$this->db->where('id_usuario', $idusuario);
		$this->db->delete('charla_orador');
	
	
	}
	
	function eliminar_asociaciones_orador($idcharla){
	
		$this->db->where('id_charla', $idcharla);
		return $this->db->delete('charla_orador');
	
	
	}
	
	function eliminar_asociaciones_usuario($idcharla){
	
		$this->db->where('id_charla', $idcharla);
		return $this->db->delete('usuario_charla');
	
	
	}
	
	function asociar($idcharla,$idusuario){
		$data = array('id_charla' => $idcharla ,'id_usuario' => $idusuario);
		$this->db->insert('charla_orador', $data); 
		
	}
	
	function contar_registrados($idcharla){
		$this->db->where('id_charla', $idcharla);
		$this->db->from("usuario_charla");
		return $this->db->count_all_results();
			
	}
	
	function contar_confirmados($idcharla){
		$this->db->where('id_charla', $idcharla);
		$this->db->where('asistio', 1);
		$this->db->from("usuario_charla");
		return $this->db->count_all_results();
			
	}
	
	function yaTermino($idCharla){
		$charla = $this->get($idCharla)->row();
		$fechaCharla = strtotime($charla->fecha.' '.$charla->hora_hasta);
		$fechaActual = strtotime(date("Y-m-d H:i:s"));
		return $fechaActual > $fechaCharla;
	}
	
	function obtener_asistencia_charla_sala($idevento){
		$query='
			SELECT 
				charla.nombre, 
				charla.sala, 
				COUNT(CASE WHEN asistencia.asistio IS NOT NULL THEN 1 END) as interesados, 
				COUNT(CASE WHEN asistencia.asistio=1 THEN 1 END) as asistentes,
				charla.capacidad as capacidad
			FROM 
				charla as charla
			
			LEFT OUTER JOIN 
				usuario_charla asistencia ON charla.id_charla=asistencia.id_charla

			WHERE 
				 
				charla.id_evento='.$idevento.'
				
			GROUP BY 
				charla.nombre,
				charla.sala,
				charla.capacidad;
				';
		$query = $this->db->query($query);
		return $query->result();
	
	
	}
}