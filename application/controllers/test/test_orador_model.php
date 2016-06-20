<?php
require_once('application/libraries/MY_TestController.php');
// requiring that was another CI irritation...but I'll save that for another day

class Test_orador_model extends MY_TestController
{
  function Test_orador_model()
  {
    parent::MY_TestController("Orador_model");
    // NB: you could also put 'TheModel' here...it's purpose is merely documentation
	//No lo puedo hacer funcionar cargando esto aca
	//$this->load->model("Usuario_model");
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
  
  
  function obtener_orador(){
	$this->load->model("Orador_model");
	$orador = new Orador_model();
	$orador->id_usuario = 34567789;
	$orador->especialidad = "Cardiologia";
	$orador->usuario = $this->obtener_usuario();
	return $orador;
  }
  
  function testCrearOrador()
  {
	$this->load->model("Orador_model");
	$orador = new Orador_model();
    $this->unit->run($orador, 'is_object', 'CrearOrador');
  }
  
  function testGrabar()
  {
	$orador = $this->obtener_orador();
	$orador->grabar();
	$query_usuario = $this->db->get_where('orador', array('id_usuario' => "34567789"), 1);
	$orador2 = $query_usuario->first_row('Orador_model');
	$this->unit->run($orador->id_usuario == $orador2->id_usuario, true, 'Grabar id');
	$this->unit->run($orador->especialidad == $orador2->especialidad, true, 'Grabar especialidad');
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => "34567789"), 1);
	$usuario2 = $query_usuario->first_row('Usuario_model');
	$this->unit->run($orador->usuario->array_base() == $usuario2->array_base(), true, 'Grabar usuario');
	$this->db->delete('orador', array('id_usuario' => 34567789));
	$this->db->delete('usuario', array('id_usuario' => 34567789));
	$this->db->delete('direccion', array('id_direccion' => $orador->usuario->id_direccion));
  }
 
  function testLevantarOrador(){
	$orador = $this->obtener_orador();
	$this->db->insert('usuario',$orador->usuario->array_base());
	$this->db->insert('orador',array ('id_usuario' => $orador->id_usuario, 'especialidad' => $orador->especialidad));
	$orador2 = new Orador_model();
	$orador2->levantar_orador(34567789);
	$this->unit->run($orador->array_base() == $orador2->array_base(), true, 'Levantar orador');
	$this->db->delete('orador', array('id_usuario' => 34567789));
	$this->db->delete('usuario', array('id_usuario' => 34567789));
	$this->db->delete('direccion', array('id_direccion' => $orador->usuario->id_direccion));
  }

  
  function testBorrar(){
	$orador = $this->obtener_orador();
	$this->db->insert('usuario',$orador->usuario->array_base());
	$this->db->insert('orador',array ('id_usuario' => $orador->id_usuario, 'especialidad' => $orador->especialidad));
	$orador->borrar();
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => 34567789), 1);
	$this->unit->run($query_usuario->num_rows() > 0, false, 'BorrarUsuario');
	$query_orador = $this->db->get_where('orador', array('id_usuario' => 34567789), 1);
	$this->unit->run($query_orador->num_rows() > 0, false, 'BorrarOrador');
  }
  
    function testActualizar(){
	$this->load->model("Usuario_model");
	$orador = $this->obtener_orador();
	$this->db->insert('usuario',$orador->usuario->array_base());
	$this->db->insert('orador',array ('id_usuario' => $orador->id_usuario, 'especialidad' => $orador->especialidad));
	$orador->especialidad='Neurologia';
	$orador->actualizar();
	$query_orador = $this->db->get_where('orador', array('id_usuario' => 34567789), 1);
	$orador2 = $query_orador->first_row('Orador_model');
	$query_usuario = $this->db->get_where('usuario', array('id_usuario' => 34567789), 1);
	$usuario2 = $query_usuario->first_row('Usuario_model');
	$orador2->usuario = $usuario2;
	$this->unit->run($orador->array_base() == $orador2->array_base(), true, 'Levantar orador');
	$this->db->delete('orador', array('id_usuario' => 34567789));
	$this->db->delete('usuario', array('id_usuario' => 34567789));
	$this->db->delete('direccion', array('id_direccion' => $orador->usuario->id_direccion));
  }
  
}

?> 