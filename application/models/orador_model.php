<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orador_model extends CI_Model{

	public $usuario;
	public $especialidad;
	public $id_usuario;
	public $foto;
	
	function Orador_model(){
		parent::__construct();
		$this->load->model('Usuario_model');
		$this->usuario = new Usuario_model();
	}
	
	function set_usuario($valor) {
		if (!isset($this->id_usuario)) $this->id_usuario = $valor->id_usuario;
		else $usuario->id_usuario = $this->id_usuario;
		return $this->usuario = $valor;
	}
	
	function get_usuario() {
		return $this->usuario;
	}
	
	function set_dni($valor) {
		return $this->id_usuario = $valor;
	}
	
	function get_dni() {
		return $this->id_usuario;
	}
	
	function set_especialidad($valor) {
		return $this->especialidad = $valor;
	}
	
	function get_especialidad() {
		return $this->especialidad;
	}
	
	function obtener_cantidad_oradores(){
		return $this->db->count_all('orador');
	}
	
	function array_base(){
		return array(
					'id_usuario' => $this->id_usuario,
					'especialidad' => $this->especialidad,
					'usuario' => $this->usuario->array_base(),);
	}
	
	function levantar_orador($id){
		$query_orador = $this->db->get_where('orador', array('id_usuario' => $id), 1);
		$orador = $query_orador->first_row('Orador_model');
		if (isset($orador)){	
			if ($this->usuario->levantar_usuario($id)){
				$this->especialidad = $orador->especialidad;
				$this->id_usuario = $id;
			}
		}
		$this->load->helper('file');
		$fotos = get_filenames('uploads/fotos_oradores/'.$this->id_usuario);
		if (is_array($fotos) && (count($fotos)>0)) $this->foto = base_url('/uploads/fotos_oradores/'.$this->id_usuario.'/'.urlencode($fotos[0]));
		else	$this->foto = $path = base_url('/assets/img/'.urlencode('profile.png'));
		return $this;
	}
	
	function levantar_oradores($resultados){
		$oradores = array();
		foreach ($resultados as $resultado) {
				$orador = new Orador_model();
				array_push($oradores,$orador->levantar_orador($resultado->id_usuario));
		}
		return ($oradores);
	}
	
	function obtener_oradores($cant = -1, $offset = -1){
		if (($cant >= 0) && ($offset >= 0)){
			$this->db->limit($cant,$offset);
		}
		$query = $this->db->get('orador');
		if ($query->num_rows() > 0)	{
				return $this->levantar_oradores($query->result());
		}
		return false;
	}

	function obtener_oradores_en_orden($cant = -1, $offset = -1, $columna, $orden){
		$this->db->join('usuario','orador.id_usuario=usuario.id_usuario');
		$this->db->order_by($columna,$orden);
		return $this->obtener_oradores($cant,$offset);
	}
	
	function obtener_oradores_busqueda($cant = -1, $offset = -1, $busqueda) {
		if (($cant >= 0) && ($offset >= 0)){
				$this->db->limit($cant,$offset);
			}		
		$this->db->join('orador','orador.id_usuario=usuario.id_usuario');
		$like = $this->db->or_like(array('nombre'=>$busqueda, 
														'apellido'=>$busqueda, 
														'orador.id_usuario'=>$busqueda, 
														'especialidad'=>$busqueda));
	
		$query = $this->db->get('usuario');
		
		if ($query->num_rows() > 0)	{
			
				return $this->levantar_oradores($query->result());
			}
		return false;		
	}
	
	function existe_id($id){
		$query_orador = $this->db->get_where('orador', array('id_usuario' => $id), 1);
		/*$orador = $query_orador->result('Orador_model');*/
		return ($query_orador->num_rows() > 0);
	}
	
	function existe(){
		$id = $this->id_usuario;
		$query_orador = $this->db->get_where('orador', array('id_usuario' => $id), 1);
		return ($query_orador->num_rows() > 0);
	}
	
	function tiene_charlas_id($id){
		$query_orador = $this->db->get_where('charla_orador', array('id_usuario' => $id), 1);
		return ($query_orador->num_rows() > 0);
	}
	
	
	function tiene_charlas(){
		return $this->tiene_charlas_id($this->id_usuario);
	}
	
	function grabar(){
		$this->db->trans_start();
		$array_datos = array (
			'id_usuario' => $this->id_usuario,
			'especialidad' => $this->especialidad,
		);
		$this->usuario->grabar_sin_transaccion();
		$this->db->insert('orador',$array_datos);
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
	}
	
	function actualizar() {
		if ($this->existe()){
			$this->db->trans_start();
			$this->db->where('id_usuario', $this->id_usuario);
			$this->db->update('orador', array('especialidad'=>$this->especialidad));
			$this->usuario->actualizar_sin_transaccion();
			$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
		}
	}
	
	function borrar_id($id){
		if( $this->existe_id($id) && (!$this->tiene_charlas_id($id))){
			$this->db->trans_start(); 
			$this->db->delete('orador', array('id_usuario' => $id));
			$this->Usuario_model->borrar_id($id);
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE)
			return false;
			else
			return true;
		 }
		else return false;
	}
	
	function borrar(){
		if ($this->existe()) {
		$this->borrar_id($this->id_usuario);
		return true;
		}
		else return false;
	}

	function tiene_reservas_id($id){
		if($id!=null){
			$query_orador = $this->db->get_where('reserva_hotel', array('id_usuario' => $id), 1);
			if ($query_orador->num_rows() > 0)
				return true;
				
			$query_orador = $this->db->get_where('reserva_vuelo', array('id_usuario' => $id), 1);
			return ($query_orador->num_rows() > 0);

		}else return true;
	}
}