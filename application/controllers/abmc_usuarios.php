<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abmc_usuarios extends MY_Controller{

	public $directorioFotos = './uploads/fotos_oradores';

	function Abmc_usuarios(){
		parent::__construct();
		$this->load->model('Usuario_model');
		$this->load->model('Orador_model');
		$this->load->model('Direccion_model');
		$this->load->model('Ubicacion_model');
		$this->load->database('mydb');
		$this->load->model('Foto_model');
	}

	function index(){
		$this->recargar();
	}
	
	function recargar($data = array()) {
		$data['titulo'] = 'Administración de Oradores';
		$data['contenido'] = 'abmc_usuarios_view.php';
		$busqueda = $this->input->post('busqueda');
		$ordena = false;
		//Me fijo si ordenaba
		if (isset($_GET['columna']) && isset($_GET['orden'])) {
			$columna = $_GET['columna'];
			$orden = $_GET['orden'];
			$ordena = true;
		} else {
			$columna = '';
			$orden = '';
		}
		//pagino y paso las opciones de ordenamiento para agregarlas a los links
		$opciones_pag = $this->paginar($columna,$orden);
		if ($ordena && $columna!= '' && $orden!='') {
			$data['oradores'] = $this->Orador_model->obtener_oradores_en_orden($opciones_pag['cantPP'],
				$opciones_pag['per_page'],$columna,$orden);
		}
		else {
			$data['oradores'] = $this->Orador_model->obtener_oradores($opciones_pag['cantPP'],$opciones_pag['per_page']);
		}
		$data['paginacion'] = $opciones_pag['paginacion'];
		$this->cargarContenido($data,'oradores'); 
	
	}
	
	function busqueda(){
	if ($this->input->post('boton_busqueda')=='busqueda') {
		$data['titulo'] = 'Administración de Oradores';
		$data['contenido'] = 'abmc_usuarios_view.php';
		$opciones_pag = $this->paginar('','');
		$busqueda = $this->input->post('busqueda');
		$data['oradores'] = $this->Orador_model->obtener_oradores_busqueda($opciones_pag['cantPP'],$opciones_pag['per_page'],$busqueda);
		$data['paginacion'] = $opciones_pag['paginacion'];
		$data['volver'] = true;
		$this->cargarContenido($data,'oradores'); 
	} else $this->index();
	}
	
	function paginar($columna,$orden) {
		$this->load->library('pagination');
		$cantPP = $this->input->get('cantPP',TRUE);
		if (!$cantPP) {
			$cantPP = 5;
		}
		$config['base_url'] = site_url('abmc_usuarios').'?cantPP='.$cantPP.'&columna='.$columna.'&orden='.$orden;
		$config['total_rows'] = $this->Orador_model->obtener_cantidad_oradores();
		$config['per_page'] = $cantPP;
		$config['page_query_string'] = TRUE;
		$config['first_link'] = 'Primero';
		$config['last_link'] = 'Último';
		$config['next_link'] = '<i class="icon-chevron-right"></i>';
		$config['prev_link'] = '<i class="icon-chevron-left"></i>';
		$this->pagination->initialize($config);
		$paginacion = $this->pagination->create_links();
		$per_page = $this->input->get('per_page',TRUE);
		if (!$per_page) {
			$per_page = 0;
		}
		return array ('per_page' => $per_page, 'cantPP' => $cantPP, 'paginacion' => $paginacion);
	}
	
	function cargar_usuario () {
		$usuario = new Usuario_model();
		$usuario->nombre = $this->input->post('nombre');
		$usuario->apellido = $this->input->post('apellido');
		$usuario->id_usuario = $this->input->post('id_usuario');
		if ($this->input->post('guardar') == 1)
			$usuario->contrasenia = hash("sha256",$this->input->post('contrasenia'));
		else
			$usuario->contrasenia = $this->input->post('contrasenia');
		$usuario->sexo = substr($this->input->post('sexo'),0,1);
		$usuario->fecha_nacimiento = date('Y-m-d', strtotime($this->input->post('fecha_nacimiento')));
		$usuario->estado_civil = $this->input->post('estado_civil');
		$usuario->telefono = $this->input->post('telefono');
		$usuario->email = $this->input->post('email');
		$usuario->profesion = $this->input->post('profesion');
		$usuario->direccion = $this->cargar_direccion();
		return $usuario;
	}
	
	function cargar_direccion() {
		$direccion = new Direccion_model();
		$pais = $this->input->post('pais');
		$provincia = $this->input->post('provincia');
		$ciudad = $this->input->post('ciudad');
		if ($provincia == "Otra") {
			$provincia = $this->input->post('otra_provincia');
		}
		if ($ciudad == "Otra") {
				$ciudad = $this->input->post('otra_ciudad');
				$this->Ubicacion_model->nueva($pais,$provincia,$ciudad);
			}
		$direccion->id_ciudad = $this->Ubicacion_model->getID($pais,$provincia,$ciudad);
		$direccion->calle = $this->input->post('direccion');
		if ($direccion->calle == '') $direccion->calle = '';
		$direccion->altura = $this->input->post('altura');
		if ($direccion->altura == '') $direccion->altura = 0;
		$direccion->codigo_postal = $this->input->post('codigopostal');
		if ($direccion->codigo_postal == '') $direccion->codigo_postal = '';
		return $direccion;
	}
	

	
	function cargar_orador(){
		$orador = new Orador_Model();
		$orador->id_usuario = $this->input->post('id_usuario');
		$orador->especialidad = $this->input->post('especialidad');
		$orador->usuario = $this->cargar_usuario();
		$orador->foto = $this->input->post('foto1');
		return $orador;
	}
	
	function alta_usuario(){
			$data['titulo'] = 'Agregar nuevo Orador';
			$data['contenido'] = 'altaUsuario_view.php';
			$data['paises'] = $this->Ubicacion_model->get_paises();
			$data['permisosDir'] = is_writable($this->directorioFotos);
			$this->cargarContenido($data,'paises','permisosDir');
	}
	
	
	function modificacion_usuario(){
		if (!isset($id)) $id = $_GET['id'];
		$orador = new Orador_model();
		$direccion = new Direccion_model();
		if (isset($id)) {	
			$orador->levantar_orador($id);
			if (isset($orador->usuario->id_direccion)) $direccion->cargarDireccion_ID($orador->usuario->id_direccion);
		}		
		$ubicacion = $this->Ubicacion_model->getPorID($direccion->id_ciudad);
		$data['titulo'] = 'Modificación del orador '.$orador->usuario->nombre.' '.$orador->usuario->apellido;
		$data['contenido'] = 'altaUsuario_view.php';
		$data['orador'] = $orador;
		$data['modifica'] = TRUE;
		$data['ubicacion'] = $ubicacion;
		$data['direccion'] = $direccion;
		$data['paises'] = $this->Ubicacion_model->get_paises();
		$data['permisosDir'] = is_writable($this->directorioFotos);
		$this->cargarContenido($data,'orador','modifica','ubicacion','direccion','paises','permisosDir');
	}
	
	function cargar_reglas($modifica) {
		$this->form_validation->set_rules('nombre', 'nombre', 'required');
		$this->form_validation->set_rules('apellido', 'apellido', 'required');
		
		if (!$modifica) $this->form_validation->set_rules('id_usuario', 'DNI', 'required|integer|greater_than[0]|is_unique[usuario.id_usuario]');
		$this->form_validation->set_rules('contrasenia', 'Contraseña', 'required');
		$this->form_validation->set_rules('fecha_nacimiento', 'Fecha de Nacimiento', 'callback_check_fecha_nacimiento');
		$this->form_validation->set_rules('telefono', 'Teléfono', 'required|callback_check_telefono');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('especialidad', 'Especialidad', 'required');
		$this->form_validation->set_rules('profesion', 'Profesion', 'required');
		$this->form_validation->set_rules('direccion', 'Calle', 'max_length[30]');
		$this->form_validation->set_rules('altura', 'Altura', 'numeric|max_length[12]');
		$this->form_validation->set_rules('codigopostal', 'Código Postal', 'max_length[12]');
		$this->form_validation->set_rules('ciudad', 'Ciudad','callback_checkCiudad');
		$this->form_validation->set_rules('provincia', 'Provincia', 'callback_checkProvincia');
		$this->form_validation->set_rules('pais', 'País','callback_checkPais');
		$this->form_validation->set_rules('otra_provincia', 'Otra provincia', 'callback_otra_provincia');
		$this->form_validation->set_rules('otra_ciudad', 'Otra ciudad', 'callback_otra_ciudad');
		$this->form_validation->set_message('check_telefono', 'El teléfono ingresado no es válido.');
		$this->form_validation->set_message('check_nombre', 'El campo nombre sólo puede contener letras y espacios.');
		$this->form_validation->set_message('check_apellido', 'El campo apellido sólo puede contener letras y espacios.');
		$this->form_validation->set_message('check_fecha_nacimiento', 'La fecha debe ser anterior a hoy.');
		$this->form_validation->set_message('otra_provincia', 'Debe ingresar el nombre de una provincia o seleccionar una.');
		$this->form_validation->set_message('otra_ciudad', 'Debe ingresar el nombre de una ciudad o seleccionar una.');
		$this->form_validation->set_message('checkCiudad', 'Debe seleccionar una opción.');
		$this->form_validation->set_message('checkProvincia', 'Debe seleccionar una opción.');
		$this->form_validation->set_message('checkPais', 'Debe seleccionar una opción.');
		$this->form_validation->set_message('is_unique', 'El campo %s debe ser único.');
	}
	
	public function check_telefono($str) {
		return !preg_match('/[^-_() 0-9]/', $this->input->post('telefono'));
	}
	
	public function check_string_alpha_num($str) {
		return !preg_match('/[^-_.\' A-Za-z]/', $str);
	}
	
	public function check_nombre(){
		return $this->check_string_alpha_num($this->input->post('nombre'));
	}
	
	public function check_apellido(){
		return $this->check_string_alpha_num($this->input->post('apellido'));
	}
	
	public function check_fecha_nacimiento(){
		$str =  strtotime($this->input->post('fecha_nacimiento'));
		return $str < strtotime(date('d-m-Y')) ;
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
		$pais = $this->input->post('pais');
		$provincia = $this->input->post('provincia');
		$ciudad = $this->input->post('ciudad');
		if ($pais!='Ninguno' || $provincia!='Ninguna') return ($ciudad != 'Ninguna');
	}

	public function checkProvincia($str) {
		$pais = $this->input->post('pais');
		$provincia = $this->input->post('provincia');
		if ($pais!='Ninguno') return ($provincia != 'Ninguna');
		else return true;
	}
	
	public function checkPais($str) {
		$pais = $this->input->post('pais');
		$otra_ciudad = $this->input->post('otra_ciudad');
		$otra_provincia = $this->input->post('otra_provincia');
		if ($otra_ciudad!='' || $otra_provincia!='')
		return ($pais != 'Ninguno');
		else return true;
	}
	
	function guardar_usuario() {
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		//$this->cargar_reglas();
		if ($_POST['guardar'] == 1) $this->cargar_reglas(false);
			else $this->cargar_reglas(true);
		$orador = $this->cargar_orador();
		$ubicacion = $this->Ubicacion_model->getPorID($orador->usuario->direccion->id_ciudad);
		if ($_POST['guardar'] == 1 || $_POST['guardar'] == 2) {	
			if ($this->form_validation->run() == FALSE)
			{
					if ($_POST['guardar'] == 1) $this->alta_usuario();
					if ($_POST['guardar'] == 2) {
						$_GET['id'] = $orador->id_usuario;
						$this->modificacion_usuario();
					}
			}
			else
			{	
				if ($_POST['guardar'] == 2){
					//ver si hay que cambiar la foto
					$orador->actualizar();
					/*
					$this->load->helper('file');
						if (!delete_files('./uploads/fotos_oradores/'.$orador->id_usuario.'/')){
							$data['tipoMensaje'] = 'alert-error';
							$data['hayMensaje'] = false;
							$data['mensaje'] = 'Error al cambiar la imagen.';
						}					
					*/
					$data['tipoMensaje'] = 'alert-success';
					$data['hayMensaje'] = true;
					$data['mensaje'] = 'Orador editado exitosamente.';
					
				} else {
					$orador->grabar();
					$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Alta de orador exitosa.');
				}
				if (isset($orador->foto) && is_writable($this->directorioFotos)) {
					foreach($_FILES as $key => $archivo)
						if ($archivo['name']){
							$data = $this->Foto_model->nuevaFotoOrador($key,$orador->id_usuario);
						}
				}
				$this->form_validation->unset_field_data();  
				$this->recargar($data);
			}			
		} else redirect('/abmc_usuarios');
	}
	
	function eliminar_usuario() {
		$id = $_GET['id'];	
		
		if($this->Orador_model->tiene_reservas_id($id)){
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se puede eliminar el orador, tiene reservas asociadas.');
			return $this->recargar($data);
		};
		
		if ($this->Orador_model->borrar_id($id) == FALSE){
				if($this->Orador_model->tiene_charlas_id($id)) $data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se puede eliminar el orador, tiene charlas asociadas.');
				else $data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'Error al intentar eliminar el orador. Intente nuevamente.');
				$this->recargar($data);

		} else {
				$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-success' , 'mensaje' => 'Orador eliminado con éxito.');
				$this->Foto_model->eliminarFotoOrador($id);
				$this->recargar($data);
			}
	}
	
	function vista_usuario() {
	
		$id = $_GET['id'];
		$orador = new Orador_model();
		$direccion = new Direccion_model();
		if (isset($id)) {
			$orador->levantar_orador($id);
			if (isset($orador->usuario->id_direccion)) $direccion->cargarDireccion_ID($orador->usuario->id_direccion);
			else $direccion = new Direccion_Model();
		}
		$ubicacion = $this->Ubicacion_model->getPorID($direccion->id_ciudad);
		$data['titulo'] = $orador->usuario->nombre.' '.$orador->usuario->apellido;
		$data['contenido'] = 'usuario_view.php';		
		$data['orador'] = $orador;
		$data['ubicacion'] = $ubicacion;
		$data['direccion'] = $direccion;
		$data['foto'] =  $orador->foto;
		$this->cargarContenido($data,'usuario','orador','ubicacion','direccion','foto','vista');
	}
	
	function ejecutarAccion(){
	}	
}