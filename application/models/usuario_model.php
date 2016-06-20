<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends CI_Model{
	public $nombre;
	public $apellido;
	public $id_usuario;
	public $contrasenia;
	public $sexo;
	public $fecha_nacimiento;
	public $estado_civil;
	public $telefono;
	public $email;	
	public $id_direccion;
	public $profesion;
	public $direccion;
	
	function Abmc_usuarios(){
		parent::__construct();
		$this->load->model('Direccion_model');
		$this->direccion = new Direccion_model();
	}
	
	function array_base(){
		$this->load->model('Direccion_model');
		return array(
					'id_usuario' => $this->id_usuario,
					'id_direccion' => $this->id_direccion,
					'contrasenia' => $this->contrasenia,
					'nombre' => $this->nombre,
					'apellido' => $this->apellido,
					'sexo' => $this->sexo,
					'fecha_nacimiento' => $this->fecha_nacimiento,
					'estado_civil' => $this->estado_civil,
					'telefono' => $this->telefono,
					'email' => $this->email,
					'profesion' => $this->profesion,
					);
	}
	
	function set_nombre($valor){
		$this->nombre = $valor;
	}
	
	function get_nombre()	{
		return $this->nombre;
	}
	
	function set_apellido($valor){
		$this->apellido = $valor;
	}
	
	function get_apellido()	{
		return $this->apellido;
	}
	
	function set_dni($valor){
		$this->id_usuario = $valor;
	}
	
	function get_dni()	{
		return $this->id_usuario;
	}
	
	function get_contrasenia() {
		return $this->contrasenia;
	}
	
	function set_sexo($valor){
		$this->sexo = $valor;
	}
	
	function get_sexo()	{
		return $this->sexo;
	}
	
	function set_fecha_nacimiento($valor){
		$this->fecha_nacimiento = $valor;
	}
	
	function get_fecha_nacimiento()	{
		return $this->fecha_nacimiento;
	}
	
	function set_estado_civil($valor){
		$this->estado_civil = $valor;
	}
	
	function get_estado_civil()	{
		return $this->estado_civil;
	}
	
	function set_telefono($valor){
		$this->telefono = $valor;
	}
	
	function get_telefono()	{
		return $this->telefono;
	}
	
	function set_email($valor){
		$this->email = $valor;
	}
	
	function get_email()	{
		return $this->email;
	}
	
	function set_id_direccion($valor){
		$this->id_direccion = $valor;
		if(isset($this->direccion))	$this->direccion->id_direccion = $valor;
	}
	
	function get_id_direccion()	{
		return $this->id_direccion;
	}
	
	function set_profesion($valor){
		$this->profesion = $valor;
	}
	
	function get_profesion()	{
		return $this->profesion;
	}
	
	function set_direccion($valor){
		$this->direccion = $valor;
	}
	
	function get_direccion()	{
		return $this->direccion;
	}
	
	function existe(){
		$id = $this->id_usuario;
		$query_usuario = $this->db->get_where('usuario', array('id_usuario' => $id), 1);
		return ($query_usuario->num_rows() > 0);
	}
	
	function existe_id($id){
		$query_usuario = $this->db->get_where('usuario', array('id_usuario' => $id), 1);
		return (($query_usuario->num_rows() > 0));
	}
	
	function actualizar(){
	if ($this->existe()){	
		$ok = true;
		$this->load->model('Direccion_model');
		$this->load->model('Ubicacion_model');
		$ubicacion = new Ubicacion_model();
		$this->db->trans_start();
		$this->actualizar_sin_transaccion();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
		} else return false;
	}
	
	function actualizar_sin_transaccion(){
	if ($this->existe()){	
		$ok = true;
		$this->load->model('Direccion_model');
		$this->load->model('Ubicacion_model');
		$ubicacion = new Ubicacion_model();
		if (isset($this->id_direccion)){
			$this->direccion->cargarDireccion_ID($this->id_direccion);
			$ubicacion = $this->Ubicacion_model->getPorID($this->direccion->id_ciudad);
			$ok = $this->Direccion_model->modificacionSinTransaccion(
									$this->direccion->id_direccion,
									$this->direccion->calle,
									$this->direccion->altura,
									$this->direccion->codigo_postal,
									$ubicacion['ciudad'],
									$ubicacion['provincia'],
									$ubicacion['pais']);				
									
		} elseif (isset($this->direccion->id_ciudad)) { 
			$ubicacion = $this->Ubicacion_model->getPorID($this->direccion->id_ciudad);
			if ($ubicacion['ciudad']!='' && $ubicacion['provincia']!='' && $ubicacion['pais']!=''){
				$this->id_direccion = $this->Direccion_model->altaSinTransaccion($this->direccion->calle,
									$this->direccion->altura,
									$this->direccion->codigo_postal,
									$ubicacion['ciudad'],
									$ubicacion['provincia'],
									$ubicacion['pais']);	
			}
		}
		
		$this->db->where('id_usuario', $this->id_usuario);
		$this->db->update('usuario', $this->array_base());			
		
		} else return false;
	}
	
	function levantar_usuario($id){
		$this->load->model('Direccion_model');
		$query_usuario = $this->db->get_where('usuario', array('id_usuario' => $id), 1);
		$usuario = $query_usuario->first_row('Usuario_model');
		if (isset ($usuario)){
			$this->nombre = $usuario->nombre;
			$this->apellido = $usuario->apellido;
			$this->id_usuario = $usuario->id_usuario;
			$this->contrasenia = $usuario->contrasenia;
			$this->sexo = $usuario->sexo;
			$this->fecha_nacimiento = $usuario->fecha_nacimiento;
			$this->estado_civil = $usuario->estado_civil;
			$this->telefono = $usuario->telefono;
			$this->email = $usuario->email;	
			$this->id_direccion = $usuario->id_direccion;
			$this->profesion = $usuario->profesion;
			if (isset($this->id_direccion)) {
				$this->direccion = new Direccion_model();
				$this->direccion->cargarDireccion_ID($this->id_direccion);             
			}
			return true;
		}
		else return false;
	}

	function borrar_id($id){
		$this->levantar_usuario($id);
		$this->db->trans_start();
		if ($this->existe_id($id)) {
				$this->db->delete('usuario_charla', array('id_usuario' => $id)); 
				$this->db->delete('usuario_evento', array('id_usuario' => $id)); 
				$this->db->delete('usuario', array('id_usuario' => $id));
				if (isset ($this->id_direccion)) $this->Direccion_model->eliminar($this->id_direccion);
		}
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
	}
	
	function borrar() {
		$this->load->model('Direccion_model');
		$this->borrar_id($this->id_usuario);
	}
	
	function grabar(){
		$this->db->trans_start();
		$this->grabar_sin_transaccion();
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
	}
	
	function grabar_sin_transaccion(){	
		if (isset($this->direccion)){
			$this->load->model('Direccion_model');
			$this->load->model('Ubicacion_model');
			$ubicacion = $this->Ubicacion_model->getPorID($this->direccion->id_ciudad);
		if (($ubicacion['ciudad']!='') && ($ubicacion['provincia']!='') && ($ubicacion['pais']!='')) {	
			$this->id_direccion = $this->Direccion_model->altaSinTransaccion(
									$this->direccion->calle,
									$this->direccion->altura,
									$this->direccion->codigo_postal,
									$ubicacion['ciudad'],
									$ubicacion['provincia'],
									$ubicacion['pais']);
			}
		}
		return $this->db->insert('usuario',$this->array_base());
	}
	
	function getEventos($id){
		$eventosUsuario = array();
		
		$this->load->model('Evento_model');
		$eventos = $this->Evento_model->obtenerEventos(-1,-1,'fecha_desde');
		
		$idsOrador = $this->esOrador($id);
		$idsAsistente = $this->esAsistente($id);
		
		foreach($eventos as $evento){
			if (in_array($evento->id_evento,$idsOrador))
				array_push($eventosUsuario,array('evento' => $evento,'esOrador' => true));
			else if (in_array($evento->id_evento,$idsAsistente))
				array_push($eventosUsuario,array('evento' => $evento,'esOrador' => false));
		}
		return $eventosUsuario;
	}
	
	function esOrador($id){
		$ids = array();
		$query = $this->db->query("	SELECT * FROM evento e 
										WHERE EXISTS (										
											SELECT * FROM charla ch 
											WHERE (e.id_evento = ch.id_evento) 
											AND EXISTS (
												SELECT * FROM charla_orador cho 
												WHERE (cho.id_charla = ch.id_charla) 
												AND (cho.id_usuario = $id)
											)
										)									 
										ORDER BY e.fecha_desde ASC");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row)
				array_push($ids, $row->id_evento);
		}
		return $ids;
	}
	
	function esAsistente($id){
		$ids = array();
		// Levanto los eventos en los cuales el usuario esta inscripto para participar(excluye en los que es orador)
		$query = $this->db->query("	SELECT * FROM evento e 
									WHERE EXISTS (
										SELECT * FROM usuario_evento ue 
										WHERE (ue.id_usuario = $id) 
										AND (ue.id_evento = e.id_evento)
									) 
									AND NOT EXISTS (
										SELECT * FROM charla ch1 
										WHERE (e.id_evento = ch1.id_evento) 
										AND EXISTS (
											SELECT * FROM charla_orador cho1 
											WHERE (cho1.id_charla = ch1.id_charla) 
											AND (cho1.id_usuario = $id)
										)
									)									
									ORDER BY e.fecha_desde ASC");
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row)
				array_push($ids, $row->id_evento);
		}
		return $ids;
	}
	
	function validarUsuario($dni,$password){
		if (!$this->existe_id($dni))
			return false;
		
		
		$this->levantar_usuario($dni);
		if (hash("sha256",$password) != $this->contrasenia)
			return false;
		
		return true;
	}
}