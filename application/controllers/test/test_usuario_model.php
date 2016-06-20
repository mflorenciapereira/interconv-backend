<?php
require_once('application/libraries/MY_TestController.php');
// requiring that was another CI irritation...but I'll save that for another day

class Test_usuario_model extends MY_TestController
{
  function Test_usuario_model()
  {
	parent::MY_TestController("Usuario_model");
   }
 
	function obtener_direccion(){
	$direccion = new Direccion_model();
	$this->load->model("Direccion_model");
	$direccion->calle = 'Rivadavia';
	$direccion->altura = '3456';
	$direccion->codigo_postal ='45622';
	$direccion->id_ciudad = 1;
	return $direccion;
	}
  
   function obtener_usuario(){
	$this->load->model("Direccion_model");
	$this->load->model("Usuario_model");
	$usuario = new Usuario_model();
	$usuario->nombre = "Roberto";
	$usuario->apellido = "Gomez";
	$usuario->id_usuario = "34567789";
	$usuario->sexo = "M";
	$usuario->fecha_nacimiento = "2012-04-14";
	$usuario->estado_civil = "Soltero";
	$usuario->telefono = "1567897777";
	$usuario->email = "rgomez@gmail.com";	
	$usuario->profesion = "Medico";
	$usuario->contrasenia="asd";
	return $usuario;
  }

  function test_grabar_usuario_sin_direccion()
  {
	$this->load->model("Direccion_model");
	$this->load->model("Usuario_model");
	$usuario = $this->obtener_usuario();
	$usuario->grabar();
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => "34567789"), 1);
	$usuario2 = $query_usuario->first_row('Usuario_model');
	$this->unit->run($usuario->array_base() == $usuario2->array_base(), true, 'Grabar usuario sin direccion');
	$this->db->delete('usuario', array('id_usuario' => 34567789));	
  }
  
    function test_grabar_usuario_con_direccion()
  {
	$this->load->model("Direccion_model");
	$this->load->model("Usuario_model");
	$usuario = $this->obtener_usuario();
	$usuario->direccion = $this->obtener_direccion();
	$usuario->grabar();
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => "34567789"), 1);
	$usuario2 = $query_usuario->first_row('Usuario_model');
	$query_direccion = $this->db->get_where('direccion', array('id_direccion' => $usuario->id_direccion), 1);
	$direccion = $query_direccion->first_row('Direccion_model');
	$this->unit->run($direccion->id_ciudad, "1", 'Grabar Ciudad');
	$this->unit->run($usuario->direccion->calle == $direccion->calle, true, 'Grabar Calle');
	$this->unit->run($usuario->direccion->altura == $direccion->altura, true, 'Grabar Altura');
	$this->unit->run($usuario->direccion->codigo_postal == $direccion->codigo_postal, true, 'Grabar Codigo');
	$this->db->delete('usuario', array('id_usuario' => 34567789));	
	$this->db->delete('direccion', array('id_direccion' => $usuario2->id_direccion));	
  }
  
 
  function testLevantarUsuario(){
	$this->load->model("Usuario_model");
	$this->load->model("Direccion_model");
	$usuario = $this->obtener_usuario();
	$usuario->direccion = $this->obtener_direccion();
	$this->db->insert('usuario',$usuario);
	$usuario2 = new Usuario_model();
	$usuario2->levantar_usuario(34567789);
	$this->unit->run($usuario->array_base() == $usuario2->array_base(), true, 'Levantar Usuario');
	$this->db->delete('usuario', array('id_usuario' => 34567789));
	$this->db->delete('direccion', array('id_direccion' => $usuario->id_direccion));	
  }
 
  function testBorrar(){
	$this->load->model("Usuario_model");
	$usuario = $this->obtener_usuario();
	$usuario->direccion = $this->obtener_direccion();
	$usuario->id_direccion = $this->Direccion_model->altaSinTransaccion(
									$usuario->direccion->calle,
									$usuario->direccion->altura,
									$usuario->direccion->codigo_postal,
									1,
									2,
									4);
	$this->db->insert('usuario',$usuario);
	$usuario->borrar();
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => 34567789), 1);
	$this->unit->run($query_usuario->num_rows() > 0, false, 'Borrar Usuario');
	$query_direccion = $this->db->get_where('direccion', array('id_direccion' => $usuario->direccion->id_direccion), 1);
	$this->unit->run($query_direccion->num_rows() > 0, false, 'Borrar Dirección');
  }
 
  function testActualizar(){
	$this->load->model("Usuario_model");
	$usuario = new Usuario_model();
	$usuario = $this->obtener_usuario();
	$usuario->direccion = $this->obtener_direccion();
	$this->db->insert('usuario',$usuario);
	$usuario->nombre='Lorenzo';
	$usuario->actualizar();
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => "34567789"), 1);
	$usuario2 = $query_usuario->first_row('Usuario_model');
	$this->unit->run($usuario->array_base() == $usuario2->array_base(), true, 'Actualizar Usuario');
	$this->db->delete('usuario', array('id_usuario' => 34567789));
	$this->db->delete('direccion', array('id_direccion' => $usuario->id_direccion));	
  }

}
?>