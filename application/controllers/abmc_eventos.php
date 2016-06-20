<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abmc_eventos extends MY_Controller{
	public $directorioFotos = './uploads/fotos_eventos';

	function index(){
		$this->recargar();
	}
	
	function recargar($data = array()){
		$data['titulo'] = 'Administración de Eventos';
		$data['contenido'] = 'abmc_eventos_view.php';
		$this->load->model('Evento_model');
		
		$this->load->library('pagination');
		$cantPP = $this->input->get('cantPP',TRUE);
		if (!$cantPP) {
			$cantPP = 5;
		}
		$busqueda = $this->input->get('busqueda',TRUE);
		if (!$busqueda){
			if (isset($data['busqueda'])) $busqueda = $data['busqueda'];
		}
		$data['busqueda'] = $busqueda;
		$orden = $this->input->get('orden',TRUE);
		if (!$orden){
			if (isset($data['orden'])) $orden = $data['orden'];
			else $orden = 'nombre';
		}
		$sentido = $this->input->get('sentido',TRUE);
		if (!$sentido){
			if (isset($data['sentido'])) $sentido = $data['sentido'];
			else $sentido = 'asc';
		}
		if ($busqueda){
			$config['base_url'] = site_url('abmc_eventos').'?busqueda='.$busqueda.'&orden='.$orden.'&sentido='.$sentido.'&cantPP='.$cantPP;
			$config['total_rows'] = $this->Evento_model->obtenerCantidadEventosConBusqueda($busqueda);
		} else {
			$config['base_url'] = site_url('abmc_eventos').'?orden='.$orden.'&sentido='.$sentido.'&cantPP='.$cantPP;
			$config['total_rows'] = $this->Evento_model->obtenerCantidadEventos();
		}
		$config['per_page'] = $cantPP;
		$config['page_query_string'] = TRUE;
		$config['first_link'] = 'Primero';
		$config['last_link'] = 'Último';
		$config['next_link'] = '<i class="icon-chevron-right"></i>';
		$config['prev_link'] = '<i class="icon-chevron-left"></i>';
		
		$this->pagination->initialize($config);
		$data['paginacion'] = $this->pagination->create_links();
		$per_page = $this->input->get('per_page',TRUE);
		if (!$per_page) {
			$per_page = 0;
		}
		if ($busqueda){
			$this->load->model('Evento_model');
			$data['eventos'] = $this->Evento_model->busqueda($busqueda,$cantPP,$per_page,$orden,$sentido);
		} else {
			$data['eventos'] = $this->Evento_model->obtenerEventos($cantPP,$per_page,$orden,$sentido);
		}
		$this->cargarContenido($data,'eventos');
	}
		
	function altaEvento($masData = array()){
		$data = array();
		array_push($data,$masData);
		$data['titulo'] = 'Agregar nuevo Evento';
		$data['contenido'] = 'altaEvento_view.php';
		
		$this->load->model('Centro_model');
		$data['centros'] = $this->Centro_model->obtenerCentros();
		if (!$data['centros']){
			$data['hayMensaje'] = true;
			$data['tipoMensaje'] = 'alert-error';
			$data['mensaje'] = 'No existen centros para asignar a un evento, ingrese primero un centro.';
			$this->recargar($data);
			return;
		}
		$data['accion'] = 'abmc_eventos/ejecutarAltaEvento';
		$data['tipoAccion'] = 'alta';
		
		$data['permisosDir'] = is_writable($this->directorioFotos);
		$this->cargarContenido($data,'eventos');
	}
	
	function prepararEvento(){
		$this->load->model('Evento_model');
		
		$evento = new Evento_model();		
		$evento->nombre = $this->input->post('nombre');
		$evento->descripcion = $this->input->post('descripcion');
		$evento->setFechaDesde($this->input->post('fecha_desde'));
		$evento->setFechaHasta($this->input->post('fecha_hasta'));
		$evento->estado = $this->input->post('estado');
		$evento->precio = $this->input->post('precio');
		$evento->id_centro = $this->input->post('centros');
		
		return $evento;
	}
	
	public function check_imagen($str,$campo){
		if ($_FILES[$campo]['name']){
			$this->load->model('Foto_model');
			return $this->Foto_model->imagen_valida($campo,'check_imagen');
		}
		return true;
	}
	
	function check_fecha($str){
		$fecha_desde = $this->input->post('fecha_desde');
		$fecha_hasta = $this->input->post('fecha_hasta');
		return (strtotime($fecha_desde) <= strtotime($fecha_hasta));
	}
	
	function check_fechaActual($str){
		return (strtotime($str) >= strtotime(date('d-m-Y')));
	}
		
	function ejecutarAltaEvento() {
		$this->ejecutarAccionEvento('alta');
	}
	
	function ejecutarAccionEvento($accion) {
		$data = array();
		$data = array('hayMensaje' => false,'tipoMensaje' =>'alert-success');
		if ($this->input->post('cancelar')) {
			redirect('abmc_eventos','refresh');
		}

		if ($accion == 'editar'){
			$id_evento = $this->input->post('id_evento');
			$this->load->model('Evento_model');
			$evento = $this->Evento_model->obtenerEvento($id_evento);
			$data['evento'] = $evento;
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[45]');
		$this->form_validation->set_rules('descripcion', 'Descripcion', '');
		if ($accion == 'editar'){
			$checkFecha = 'required|callback_check_fecha';
		} else {
			$checkFecha = 'required|callback_check_fecha|callback_check_fechaActual';
		}
		$this->form_validation->set_rules('fecha_desde', 'Fecha desde', $checkFecha);
		$this->form_validation->set_rules('fecha_hasta', 'Fecha hasta', $checkFecha);
		$this->form_validation->set_rules('centros', 'Centro', 'required');
		$this->form_validation->set_rules('estado', 'Estado', 'required');
		$this->form_validation->set_rules('precio', 'Precio', 'decimal');
		$this->form_validation->set_rules('foto1', 'Foto', 'callback_check_imagen[foto1]');
		$this->form_validation->set_rules('foto2', 'Foto', 'callback_check_imagen[foto2]');
		$this->form_validation->set_rules('foto3', 'Foto', 'callback_check_imagen[foto3]');
		$this->form_validation->set_rules('foto4', 'Foto', 'callback_check_imagen[foto4]');
		$this->form_validation->set_rules('foto5', 'Foto', 'callback_check_imagen[foto5]');
		$this->form_validation->set_message('required', 'El campo %s es obligatorio.');
		$this->form_validation->set_message('numeric', 'El campo %s debe ser un número.');
		$this->form_validation->set_message('decimal', 'El campo %s debe ser un número decimal.');
		$this->form_validation->set_message('max_length', 'El campo %s no puede tener mas de %s caracteres.');
		$this->form_validation->set_message('check_imagen', 'La foto seleccionada no cumple los requisitos.');
		$this->form_validation->set_message('check_fecha', 'La "fecha desde" debe ser anterior a la "fecha hasta".');
		$this->form_validation->set_message('check_fechaActual', 'La "%s" debe ser posterior o igual a la fecha actual ('.date('d-m-Y').').');
		
		if ($this->form_validation->run() == true){
			$this->load->model('Evento_model');
			if ($accion == 'editar'){
				$this->Evento_model->modificacion($this->prepararEvento(),$id_evento);
			} else {
				$id_evento = $this->Evento_model->alta($this->prepararEvento());
			}
			if ($id_evento && is_writable($this->directorioFotos)) {
				$this->load->model('Foto_model');
				//si estoy editando, elimino fotos seleccionadas
				if ($accion == 'editar'){
					$fotos = $this->input->post('fotoActual');
					if (is_array($fotos)){
						foreach ($fotos as $foto){
							if (!$this->Foto_model->eliminarFotoEvento($foto,$id_evento)){
								$data['tipoMensaje'] = 'alert-error';
								$data['hayMensaje'] = false;
								$data['mensaje'] = 'Error al eliminar la(s) imagen(es).';
							}
						}
					}
				}
				//cargo nuevas fotos
				foreach($_FILES as $key => $archivo)
					if ($archivo['name']){
						$data = $this->Foto_model->nuevaFotoEvento($key,$id_evento);
					}
			}
			if ($data['tipoMensaje'] == 'alert-error'){
				if ($accion == 'editar'){
					$this->editarEvento($data);
				} else {
					$this->altaEvento($data);
				}
			} else {
				if ($accion == 'editar'){
					$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Evento editado exitosamente.');
				} else {
					$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Alta de evento exitosa.');
				}
				$this->form_validation->unset_field_data();  
				$this->recargar($data);
			}
		} else {
			if ($accion == 'editar')  $this->editarEvento($id_evento);
			else $this->altaEvento();
		}
	}
	
	function ejecutarEditarEvento(){
		$this->ejecutarAccionEvento('editar');
	}
		
	function editarEvento($id_evento = null, $masData = array()){
		$data = array();
		array_push($data,$masData);
		if (!$id_evento) $id_evento = $this->uri->segment(3);
		$this->load->model('Evento_model');
		$evento = $this->Evento_model->obtenerEvento($id_evento);
		if ($evento){
			$data['evento'] = $evento;
			
			$data['titulo'] = 'Edición del evento "'.$evento->nombre.'"';
			$data['contenido'] = 'altaEvento_view.php';
			
			$this->load->model('Centro_model');
			$data['centros'] = $this->Centro_model->obtenerCentros();
			
			$data['accion'] = 'abmc_eventos/ejecutarEditarEvento';
			$data['tipoAccion'] = 'editar';
			
			$data['permisosDir'] = is_writable($this->directorioFotos);
			$this->cargarContenido($data,'eventos');
		} else {
			$this->recargar();
		}
	}
	
	function eliminar(){
		$id_evento = $this->uri->segment(3);
		$this->load->model('Charla_model');
		$this->load->model('Reserva_hotel_model');
		$this->load->model('Reserva_vuelo_model');
		
		if($this->Reserva_hotel_model->hay_reservas_asociadas_evento($id_evento)){
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Existen reservas de hoteles asociadas, desasocie las mismas para poder eliminar el evento.');
			return $this->recargar($data);		
		}
		
		if($this->Reserva_vuelo_model->hay_reservas_asociadas_evento($id_evento)){
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Existen reservas de vuelos asociadas, desasocie las mismas para poder eliminar el evento.');
			return $this->recargar($data);		
		}

		
		if ($this->Charla_model->obtenerCharlasDelEvento($id_evento) === false){
			//el evento tiene charlas asociadas, no se puede eliminar
			$this->load->model('Evento_model');
			if ($this->Evento_model->eliminar($id_evento) === FALSE){
				$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Error al intentar eliminar el evento. Intente nuevamente.');
				$this->recargar($data);
			} else {
				$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-success' , 'mensaje' => 'Evento eliminado con éxito.');
				$this->recargar($data);
			}
		} else {
		
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Existen charlas asociadas, desasocie las mismas para poder eliminar el evento.');
			$this->recargar($data);
		}
	}
	
	function busqueda(){
		$data['busqueda'] = $this->input->post('busqueda');
		$this->recargar($data);
	}
	
	function verEvento(){
		$id_evento = $this->uri->segment(3);
		$this->load->model('Evento_model');
		$evento = $this->Evento_model->obtenerEvento($id_evento);
		$data['evento'] = $evento;
		$data['titulo'] = 'Evento "'.$evento->nombre.'"';
		$data['contenido'] = 'evento_view.php';
		$this->cargarContenido($data,'eventos');
	}
}