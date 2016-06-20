<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Servicios extends MY_Controller{
	
	function index(){
		echo "Error, no se pidió ningún servicio.";
	}
	
	function getOradoresEvento() {
		$this->load->helper('file');
		$id_evento = $this->input->post('id_evento');
		$oradores = array();
		$query = $this->db->query("	SELECT * FROM usuario u, orador o
									WHERE (u.id_usuario = o.id_usuario)
									AND EXISTS (
										SELECT * FROM charla ch
										WHERE (ch.id_evento = $id_evento)
										AND EXISTS (
											SELECT * FROM charla_orador cho
											WHERE (cho.id_usuario = u.id_usuario)
											AND (cho.id_charla = ch.id_charla)
										)
									)
									ORDER BY u.apellido ASC
									");
		if ($query->num_rows() > 0) {		
			foreach ($query->result() as $row) {
				$fotos = get_filenames('uploads/fotos_oradores/'.$row->id_usuario);
				if (is_array($fotos)) $foto = base_url('/uploads/fotos_oradores/'.$row->id_usuario.'/'.urlencode($fotos[0]));
				else	$foto = base_url('/assets/img/'.urlencode('profile.png'));
				$oradorAux = array( 'id_usuario' => $row->id_usuario,
														'nombre' => $row->nombre,
														'apellido' => $row->apellido,
														'profesion' => $row->profesion,
														'especialidad' => $row->especialidad,
														'foto' => $foto
														);
				array_push($oradores,array('orador' => $oradorAux));
			}
		}
		echo json_encode($oradores);
	}
	
	function getOradoresCharla() {
		$this->load->model('Charla_model');
		
		$id_charla = $this->input->post('id_charla');
		$queryOradores = $this->Charla_model->get_oradores_por_charla($id_charla);
		
		$oradores = array();
		if ($queryOradores->num_rows() > 0) {		
			$this->load->helper('file');
			$this->load->model('Orador_model');
			foreach ($queryOradores->result() as $rowOrador) {
				$orador = $this->Orador_model->levantar_orador($rowOrador->id_usuario);
				$usuario = $orador->usuario;
				$oradorAux = array( 'id_usuario' => $usuario->id_usuario,
									'nombre' => $usuario->nombre,
									'apellido' => $usuario->apellido,
									'profesion' => $usuario->profesion,
									'especialidad' => $orador->especialidad,
									'foto' => $orador->foto
							);
				array_push($oradores,array('orador' => $oradorAux));
			}
						
		}
		echo json_encode($oradores);
	}
	
	//Funcion de prueba para ver formato de fechas en android
	function getValidacion() {		
		$dni = $this->input->post('dniUsuario');
		$password = $this->input->post('passUsuario');
		
		$this->load->model('Usuario_model');
		echo $this->Usuario_model->validarUsuario($dni,$password) ? 'Login con usuario '.$dni : 'Error=1,Nombre de usuario o contraseña incorrectos';
	}
	
	// Funcion para obtener las reservas de avion por id de usuario y id de evento
	function getReservasVuelo() {
		$this->load->model('reserva_vuelo_model');
		$id_usuario = $this->input->get('usuario');
		$id_evento = $this->input->post('id_evento');
		
		$query = $this->db->query("	SELECT * FROM reserva_vuelo rv
									WHERE (rv.id_usuario = $id_usuario)
									AND (rv.id_evento = $id_evento)
									ORDER BY rv.hora_salida ASC
									");
		
		$reservasVuelo = array();		
				
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {				
				$reservaAux = array('id_reserva_vuelo' => $row->id_reserva_vuelo,
									'numero_vuelo' => $row->numero_vuelo,
									'hora_salida' => $row->hora_salida,
									'hora_llegada' => $row->hora_llegada,
									'aerop_salida' => $row->aerop_salida,
									'aerop_llegada' => $row->aerop_llegada,
									'escalas' => $row->escalas,
									'clase' => $row->clase,
									'id_usuario' => $row->id_usuario,
									'id_evento' => $row->id_evento,
									'id_despegar' => $row->id_despegar,
									'cantidad_personas' => $row->cantidad_personas,
									'aerolinea' => $row->aerolinea,
									'tipo' => $row->tipo,
									'moneda' => $row->moneda,
									'precio' => $row->precio,
									'longitud_llegada' => $row->longitud_llegada,
									'latitud_llegada' => $row->latitud_llegada,
									'longitud_salida' => $row->longitud_salida,
									'latitud_salida' => $row->latitud_salida
									);
				array_push($reservasVuelo,array('reservaVuelo' => $reservaAux));
			}
						
		}
		echo json_encode($reservasVuelo);
	}
	
	// Funcion para obtener las reservas de hotel por id de usuario y id de evento
	function getReservasHotel() {
		$this->load->model('reserva_hotel_model');
		$id_usuario = $this->input->get('usuario');
		$id_evento = $this->input->post('id_evento');
			
		$query = $this->db->query("	SELECT * FROM reserva_hotel rh
									WHERE (rh.id_usuario = $id_usuario)
									AND (rh.id_evento = $id_evento)
									ORDER BY rh.fecha_desde ASC
									");
									
		$reservasHotel = array();		
				
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {				
				$reservaAux = array('id_reserva_hotel' => $row->id_reserva_hotel,
									'nombre' => $row->nombre,
									'regimen' => $row->regimen,
									'direccion' => $row->direccion,
									'check_in' => $row->check_in,
									'check_out' => $row->check_out,
									'cantidad_personas' => $row->cantidad_personas,
									'fecha_desde' => $row->fecha_desde,
									'fecha_hasta' => $row->fecha_hasta,
									'precio' => $row->precio,
									'moneda' => $row->moneda,
									'latitud' => $row->latitud,
									'longitud' => $row->longitud,
									'id_despegar' => $row->id_despegar,
									);
				array_push($reservasHotel,array('reservaHotel' => $reservaAux));
			}
						
		}
		echo json_encode($reservasHotel);
	}
	
	function getEventos(){
		$usuario = $this->input->get('usuario');
		if ($usuario != null) {
			$this->load->model('Usuario_model');
			$eventos = $this->Usuario_model->getEventos($usuario);
			if (!$eventos) $eventos = array();
		} else {
			$this->load->model('Evento_model');
			$eventosAux = $this->Evento_model->obtenerEventos(-1,-1,'fecha_desde');
			$eventos = array();
			
			foreach($eventosAux as $evento)
				array_push($eventos,array('evento' => $evento,'esOrador' =>false));
		}
		echo json_encode($eventos);
	}
		
	function getCentro(){
		$this->load->model('Centro_model');
		$id_centro = $this->input->post('id_centro');

		$centro = $this->Centro_model->obtenerCentro($id_centro);
		
		echo json_encode($centro);
	}
	
	function getCharlas(){
		$id_evento = $this->input->post('id_evento');

		$this->load->model('Charla_model');
		$charlas = $this->Charla_model->obtenerCharlasDelEvento($id_evento);
		if ($charlas == false) $charlas = array();
		
		foreach ($charlas as $charla){
			//agrego path a la foto
			$charla->foto = '';
			$this->load->helper('file');
			$fotos = get_filenames('uploads/fotos_charlas/'.$charla->id_charla);
			if (is_array($fotos)){
				$charla->foto = base_url('/uploads/fotos_charlas/'.$charla->id_charla.'/'.urlencode(array_pop($fotos)));
			}
		}
		
		echo json_encode($charlas);
	}
	
	function setIntencionAsistir(){
		$id_usuario = $this->input->get('usuario');
		$id_charla = $this->input->post('id_charla');
		$this->load->model('Asistencia_model');
		echo $this->Asistencia_model->setIntencionAsistir($id_usuario,$id_charla);
	}
	
	function setIntencionNOAsistir(){
		$id_usuario = $this->input->get('usuario');
		$id_charla = $this->input->post('id_charla');
		$this->load->model('Asistencia_model');
		echo $this->Asistencia_model->setIntencionNOAsistir($id_usuario,$id_charla);
	}
	
	function getIntencionAsistir(){
		$id_usuario = $this->input->get('usuario');
		$id_charla = $this->input->post('id_charla');
		$this->load->model('Asistencia_model');
		echo $this->Asistencia_model->getIntencionAsistir($id_usuario,$id_charla);
	}
	
	function getInteresadosCharla() {
		$id_charla = $this->input->post('id_charla');
		$this->load->model('Asistencia_model');
		$interesados = $this->Asistencia_model->getInteresadosCharla($id_charla);
		$interesadosArray = array();
		if ($interesados) {		
			foreach ($interesados as $interesado){
				array_push($interesadosArray,array('asistente' => $interesado));
			}
		}
		echo json_encode($interesadosArray);
	}
	
	function getParticipantesCharla() {
		$id_charla = $this->input->post('id_charla');
		$this->load->model('Asistencia_model');
		$participantes = $this->Asistencia_model->getParticipantesCharla($id_charla);
		$participantesArray = array();
		if ($participantes) {		
			foreach ($participantes as $participante){
				array_push($participantesArray,array('asistente' => $participante));
			}			
		}
		echo json_encode($participantesArray);
	}
	
	function registrarParticipacion(){
		$id_usuario = $this->input->get('usuario');
		$id_charla = $this->input->post('id_charla');
		$this->load->model('Charla_model');
		$queryCharla = $this->Charla_model->get($id_charla);
		
		if ($queryCharla->num_rows() == 0)
			echo 'Error=1,El código QR escaneado no es válido';
		else {
			$charla = $queryCharla->row();
			
			$fechaCharlaString = $charla->fecha;
			$fechaHoyString = date("Y-m-d");
			$fechaHoy = strtotime($fechaHoyString);
			$fechaCharla = strtotime($fechaCharlaString);

			if ($fechaHoy == $fechaCharla){
				$this->load->model('Charla_model');
				if ($this->Charla_model->yaTermino($id_charla))
					echo 'Error=1,La charla ya terminó';
				else {
					$this->load->model('Asistencia_model');
					if ($this->Asistencia_model->estaAsistiendo($id_usuario,$id_charla))
						echo 'Error=1,Ya está participando de '.($charla->nombre);
					else {
						$this->Asistencia_model->setAsistio($id_usuario,$id_charla);
						echo $charla->nombre;
					}
				}
			} else
				if ($fechaHoy < $fechaCharla)
					echo 'Error=1,La charla no se da hoy';
				else
					echo 'Error=1,La charla ya se dió';
		}
	}
}
