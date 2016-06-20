<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ServiciosMock extends MY_Controller{
	
	function index(){
		echo "Error, no se pidió ningún servicio.";
	}
	
	function getValidacion() {		
		$usuario_arr = array (
				'nombre' => 'Diego',
				'apellido' => 'Juodziukynas',
				'id_usuario' => 32496444);
		
		echo json_encode($usuario_arr);
	}
	
	function getEventos(){
		$eventos = array(
			array('evento' => array('id_evento' => 1,
					'nombre' => 'Networks Forum',
					'descripcion' => 'Un evento re entretenido de noseque',
					'fecha_desde' => '10-10-2012',
					'fecha_hasta' => '12-10-2012',
					'id_centro' => 4,
					'fotos' => array()),
				'esOrador' => false),
			array('evento' => array('id_evento' => 2,
					'nombre' => 'Agile Convetion',
					'descripcion' => 'Este evento termina muy rapido!',
					'fecha_desde' => '9-11-2013',
					'fecha_hasta' => '15-12-2013',
					'id_centro' => 10,
					'fotos' => array()),
				'esOrador' => true));
		
		echo json_encode($eventos);
	}
	
	function getCentro(){
		
		echo '{"id_centro":"63","nombre":"Primer centro","id_direccion":"71","fotos":[],"direccion":{"id_direccion":"71","calle":"Paseo colon","altura":"850","codigo_postal":"","latitud":null,"longitud":null,"id_ciudad":"2367","ciudad":"San Telmo","provincia":"Capital Federal","pais":"Argentina"},"direccion_string":"Paseo colon 850 (), San Telmo, Capital Federal, Argentina","eventos":[{"id_evento":"67","nombre":"Primer evento con precio","descripcion":"este tiene precio","fecha_desde":"2012-10-07","fecha_hasta":"2012-10-09","precio":"1.50","estado":"No publicado","id_centro":"63"},{"id_evento":"68","nombre":"Segundo evento","descripcion":"asdasd","fecha_desde":"2012-10-05","fecha_hasta":"2012-10-07","precio":"0.00","estado":"No comenzado","id_centro":"63"}]}';
	}
	
	function getCharlas(){
		$charlas = array(
			array('id_charla' => 1,
				'nombre' => 'CodeIgniter',
				'descripcion' => 'Charla sobre CodeIgniter con ejemplos',
				'sala' => 3,
				'fecha' => '9-10-2012',
				'hora_desde' => '10:30:00',
				'hora_hasta' => '11:00:00'),
			array('id_charla' => 2,
				'nombre' => 'Android',
				'descripcion' => 'Charla sobre Android con ejemplos',
				'sala' => 2,
				'fecha' => '12-10-2012',
				'hora_desde' => '15:30:00',
				'hora_hasta' => '18:00:00'),
			array('id_charla' => 3,
				'nombre' => 'Otra cosa',
				'descripcion' => 'Charla sobre muchos temas',
				'sala' => 2,
				'fecha' => '12-10-2012',
				'hora_desde' => '15:30:00',
				'hora_hasta' => '18:00:00'));
		
		echo json_encode($charlas);
	}
	
	function getOradoresCharla(){
		$oradores = array(
			array( 'orador' => array( 'id_usuario' => 32496444,
				   'nombre' => 'Diego',
				   'apellido' => 'Juodziukynas',
				   'profesion' => 'Estudiante',
				   'especialidad' => 'Dingoo',
				   'foto' => 'http://localhost/backend/assets/img/profile.png')),
			array( 'orador' => array( 'id_usuario' => 35496879,
				   'nombre' => 'Pepe',
				   'apellido' => 'Lalala',
				   'profesion' => 'Sarasa',
				   'especialidad' => 'Ninguna',
				   'foto' => 'http://localhost/backend/assets/img/profile.png')));
		
		echo json_encode($oradores);
	}
	
	function getOradoresEvento(){
		$oradores = array(
			array( 'orador' => array( 'id_usuario' => 32496444,
				   'nombre' => 'Diego',
				   'apellido' => 'Juodziukynas',
				   'profesion' => 'Estudiante',
				   'especialidad' => 'Dingoo',
				   'foto' => 'http://localhost/backend/assets/img/profile.png')),
			array( 'orador' => array( 'id_usuario' => 35496879,
				   'nombre' => 'Pepe',
				   'apellido' => 'Lalala',
				   'profesion' => 'Sarasa',
				   'especialidad' => 'Ninguna',
				   'foto' => 'http://localhost/backend/assets/img/profile.png')));
		
		echo json_encode($oradores);
	}
}
