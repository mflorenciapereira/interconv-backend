<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abmc_centros_de_convenciones extends MY_Controller{
	public $directorioFotos = './uploads/fotos_centros';

	function index(){
		$this->recargar();
	}
	
	function recargar($data = array()){
		$data['titulo'] = 'Administración de Centros de Convenciones';
		$data['contenido'] = 'abmc_centros_de_convenciones_view.php';
		$this->load->model('Centro_model');
		
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
			$config['base_url'] = site_url('abmc_centros_de_convenciones').'?busqueda='.$busqueda.'&orden='.$orden.'&sentido='.$sentido.'&cantPP='.$cantPP;
			$config['total_rows'] = $this->Centro_model->obtenerCantidadCentrosConBusqueda($busqueda);
		} else {
			$config['base_url'] = site_url('abmc_centros_de_convenciones').'?orden='.$orden.'&sentido='.$sentido.'&cantPP='.$cantPP;
			$config['total_rows'] = $this->Centro_model->obtenerCantidadCentros();
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
			$this->load->model('Centro_model');
			$data['centros'] = $this->Centro_model->busqueda($busqueda,$cantPP,$per_page,$orden,$sentido);
		} else {
			$data['centros'] = $this->Centro_model->obtenerCentros($cantPP,$per_page,$orden,$sentido);
		}
		
		$this->cargarContenido($data,'centros');
	}
		
	function altaCentro($masData = array()){
		$data = array();
		array_push($data,$masData);
		$data['titulo'] = 'Agregar nuevo Centro de Convenciones';
		$data['contenido'] = 'altaCentro_view.php';
		
		$this->load->model('Ubicacion_model');
		$data['paises'] = $this->Ubicacion_model->get_paises();
		$data['paisSel'] = $this->input->post('pais');
		$data['provinciaSel'] = $this->input->post('provincia');
		$data['ciudadSel'] = $this->input->post('ciudad');

		$data['accion'] = 'abmc_centros_de_convenciones/ejecutarAltaCentro';
		$data['tipoAccion'] = 'alta';
		
		$data['permisosDir'] = is_writable($this->directorioFotos);
		$this->cargarContenido($data,'centros');
	}
		
	public function otra_provincia() {
		if ($this->input->post('provincia') != 'Otra')
			return true;
		
		return $this->input->post('otra_provincia') == '' ? false : true;
	}
	
	public function otra_ciudad() {
		if ($this->input->post('ciudad') != 'Otra')
			return true;
		
		return $this->input->post('otra_ciudad') == '' ? false : true;
	}
	
	public function checkCiudad($str) {
		$ciudad = $this->input->post('ciudad');
		return ($ciudad != 'Ninguna');
	}

	public function checkProvincia($str) {
		$provincia = $this->input->post('provincia');
		return ($provincia != 'Ninguna');
	}
	
	public function checkPais($str) {
		$pais = $this->input->post('pais');
		return ($pais != 'Ninguno');
	}
	
	function prepararCentro(){
		$this->load->model('Centro_model');
		
		$centro = new Centro_model();
		
		$centro->nombre = $this->input->post('nombre');
		$centro->calle = $this->input->post('calle');
		$centro->altura = $this->input->post('altura');
		$centro->codigo_postal = $this->input->post('codigo_postal');

		
		$centro->pais = $this->input->post('pais');
		$centro->provincia = $this->input->post('provincia');
		if ($centro->provincia == 'Otra') $centro->provincia = $this->input->post('otra_provincia');
		$centro->ciudad = $this->input->post('ciudad');
		if ($centro->ciudad == 'Otra') $centro->ciudad = $this->input->post('otra_ciudad');
		
		return $centro;
	}
	
	public function check_imagen($str,$campo){
		if ($_FILES[$campo]['name']){
			$this->load->model('Foto_model');
			return $this->Foto_model->imagen_valida($campo,'check_imagen');
		}
		return true;
	}
		
	function ejecutarAltaCentro() {
		$this->ejecutarAccionCentro('alta');
	}
	
	function ejecutarAccionCentro($accion) {
		$data = array();
		$data = array('hayMensaje' => false,'tipoMensaje' =>'alert-success');
		if ($this->input->post('cancelar')) {
			redirect('abmc_centros_de_convenciones','refresh');
		}
		
		if ($accion == 'editar'){
			$id_centro = $this->input->post('id_centro');
			$this->load->model('Centro_model');
			$centro = $this->Centro_model->obtenerCentro($id_centro);
			$data['centro'] = $centro;
		}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('nombre', 'Nombre', 'required|max_length[45]');
		$this->form_validation->set_rules('calle', 'Calle', 'required|max_length[30]');
		$this->form_validation->set_rules('altura', 'Altura', 'required|numeric|max_length[12]');
		$this->form_validation->set_rules('codigo_postal', 'Código Postal', 'max_length[12]');
		$this->form_validation->set_rules('ciudad', 'Ciudad', 'callback_checkCiudad');
		$this->form_validation->set_rules('provincia', 'Provincia', 'callback_checkProvincia');
		$this->form_validation->set_rules('pais', 'País', 'callback_checkPais');
		$this->form_validation->set_rules('otra_provincia', 'Otra provincia', 'callback_otra_provincia');
		$this->form_validation->set_rules('otra_ciudad', 'Otra ciudad', 'callback_otra_ciudad');
		$this->form_validation->set_rules('foto1', 'Foto', 'callback_check_imagen[foto1]');
		$this->form_validation->set_rules('foto2', 'Foto', 'callback_check_imagen[foto2]');
		$this->form_validation->set_rules('foto3', 'Foto', 'callback_check_imagen[foto3]');
		$this->form_validation->set_rules('foto4', 'Foto', 'callback_check_imagen[foto4]');
		$this->form_validation->set_rules('foto5', 'Foto', 'callback_check_imagen[foto5]');
		$this->form_validation->set_message('required', 'El campo %s es obligatorio.');
		$this->form_validation->set_message('numeric', 'El campo %s debe ser un número.');
		$this->form_validation->set_message('max_length', 'El campo %s no puede tener mas de %s caracteres.');
		$this->form_validation->set_message('min_length', 'El campo %s no puede tener menos de %s caracteres.');
		$this->form_validation->set_message('otra_provincia', 'Debe ingresar el nombre de una provincia o seleccionar una.');
		$this->form_validation->set_message('otra_ciudad', 'Debe ingresar el nombre de una ciudad o seleccionar una.');
		$this->form_validation->set_message('checkCiudad', 'Debe seleccionar una opción.');
		$this->form_validation->set_message('checkProvincia', 'Debe seleccionar una opción.');
		$this->form_validation->set_message('checkPais', 'Debe seleccionar una opción.');
		$this->form_validation->set_message('check_imagen', 'La foto seleccionada no cumple los requisitos.');
		
		if ($this->form_validation->run() == true){
			$this->load->model('Centro_model');
			if ($accion == 'editar'){
				$this->Centro_model->modificacion($this->prepararCentro(),$centro);
			} else {
				$id_centro = $this->Centro_model->alta($this->prepararCentro());
			}
			if ($id_centro && is_writable($this->directorioFotos)) {
				$this->load->model('Foto_model');
				//si estoy editando, elimino fotos seleccionadas
				if ($accion == 'editar'){
					$fotos = $this->input->post('fotoActual');
					if (is_array($fotos)){
						foreach ($fotos as $foto){
							if (!$this->Foto_model->eliminarFotoCentro($foto,$id_centro)){
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
						$data = $this->Foto_model->nuevaFotoCentro($key,$id_centro);
					}
			}
			if ($data['tipoMensaje'] == 'alert-error'){
				if ($accion == 'editar'){
					$this->editarCentro($data);
				} else {
					$this->altaCentro($data);
				}
			} else {
				if ($accion == 'editar'){
					$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Centro editado exitosamente.');
				} else {
					$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Alta de centro exitosa.');
				}
				$this->form_validation->unset_field_data();  
				$this->recargar($data);
			}
		} else {
			if ($accion == 'editar')  $this->editarCentro($id_centro);
			else $this->altaCentro();
		}
	}
	
	function ejecutarEditarCentro(){
		$this->ejecutarAccionCentro('editar');
	}
		
	function editarCentro($id_centro = null,$masData = array()){
		$data = array();
		array_push($data,$masData);
		if (!$id_centro) $id_centro = $this->uri->segment(3);
		$this->load->model('Centro_model');
		$centro = $this->Centro_model->obtenerCentro($id_centro);
		if ($centro){
			$data['centro'] = $centro;
			
			$data['titulo'] = 'Edición del centro "'.$centro->nombre.'"';
			$data['contenido'] = 'altaCentro_view.php';
			
			$this->load->model('Ubicacion_model');
			$data['paises'] = $this->Ubicacion_model->get_paises();
			$data['paisSel'] = $this->input->post('pais');
			$data['provinciaSel'] = $this->input->post('provincia');
			$data['ciudadSel'] = $this->input->post('ciudad');
			
			$data['accion'] = 'abmc_centros_de_convenciones/ejecutarEditarCentro';
			$data['tipoAccion'] = 'editar';
			
			$data['permisosDir'] = is_writable($this->directorioFotos);
			$this->cargarContenido($data,'centros');
		} else {
			$this->recargar();
		}
	}
	
	function eliminar(){
		$id_centro = $this->uri->segment(3);
		$this->load->model('Evento_model');
		if ($this->Evento_model->obtenerEventosDelCentro($id_centro) === false){
			//el centro no tiene eventos asociados se puede eliminar
			$this->load->model('Centro_model');
			if ($this->Centro_model->eliminar($id_centro) === FALSE){
				$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Error al intentar eliminar el centro. Intente nuevamente.');
				$this->recargar($data);
			} else {
				$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-success' , 'mensaje' => 'Centro eliminado con éxito.');
				$this->recargar($data);
			}
		} else {
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Existen eventos asociados, desasocie los mismos para poder eliminar el centro.');
			$this->recargar($data);
		}
	}
	
	function busqueda(){
		$data['busqueda'] = $this->input->post('busqueda');
		$this->recargar($data);
	}
}