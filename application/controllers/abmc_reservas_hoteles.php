<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Abmc_reservas_hoteles extends MY_Controller{

	function Abmc_reservas_hoteles(){
		parent::__construct();
		$this->load->model('Usuario_model');
		$this->load->model('Orador_model');
		$this->load->model('Evento_model');
		$this->load->model('Centro_model');
		$this->load->model('Reserva_hotel_model');
		$this->load->model('Direccion_model');
		$this->load->model('Ubicacion_model');
		$this->load->model('Charla_model');
		$this->load->database('mydb');
	}

	function index(){
		$this->recargar();
	}
	
	function recargar($data = array()) {
		$data['titulo'] = 'Administración de Reservas';
		$data['contenido'] = 'abmc_reservas_hoteles_view.php';
		$data ['eventos'] = $this->Evento_model->obtenerEventos();
		
		$filtro = $this->input->get('filtro',TRUE);
		if (!$filtro){
			if (isset($data['filtro'])) $filtro = $data['filtro'];
		}
			
		
		if ($filtro!='') {
				$opciones_pag = $this->paginar($filtro);
				$data['reservas'] = $this->Reserva_hotel_model->levantar_reservas_evento($filtro,$opciones_pag['cantPP'],$opciones_pag['per_page']);
				
		}
		else {
				$opciones_pag = $this->paginar();
				$data['reservas'] = $this->Reserva_hotel_model->levantar_reservas($opciones_pag['cantPP'],$opciones_pag['per_page']);
		}
		$data['paginacion'] = $opciones_pag['paginacion'];
		
		$this->cargarContenido($data); 
	}
	
	function paginar($filtro=null) {
		$this->load->library('pagination');
		$cantPP = $this->input->get('cantPP',TRUE);
		if (!$cantPP) {
			$cantPP = 5;
		}
		if($filtro!=''){
			$config['base_url'] = site_url('abmc_reservas_hoteles').'?cantPP='.$cantPP.'&filtro='.$filtro;
		}else{
		
			$config['base_url'] = site_url('abmc_reservas_hoteles').'?cantPP='.$cantPP;
		
		};
		
		$config['total_rows'] = $this->Reserva_hotel_model->obtener_total_reservas($filtro);
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
	
	function filtrar() {	
		$id_evento = $this->input->post('evento');
		$data['filtro'] = $id_evento;
		$this->recargar($data);
	}
	
	function alta_reserva($reservas=null,$mensaje='',$data=null){
			if($data==null){
				$data['distribucion'] = 1;
				$data['radio'] = 0.25;
			
			}
			$this->rehacer_alta($reservas,$mensaje,$data);
	}
	
	
	function rehacer_alta($reservas=null,$mensaje='',$data=null){
		
			$data['titulo'] = 'Realizar Nueva Reserva';
			$data['contenido'] = 'altaReservaHotel_view.php';
			$data['eventos'] = $this->Evento_model->obtenerEventos();
			$data['accion'] = 'abmc_reservas_hoteles/buscar_hoteles';
			$data['mensaje'] = $mensaje;
			$data['reservas']=$reservas;
			$data['tipoAccion'] = 'alta';
			$this->cargarContenido($data);
	}
	
	


	function set_reglas(){
		$this->form_validation->set_rules('eventoOrador', 'Evento', 'required');
		$this->form_validation->set_rules('orador', 'Orador', 'required');
		$this->form_validation->set_rules('fecha_desde', 'Fecha inicial','required|callback_validar_fecha[fecha_desde]|callback_validar_periodo|callback_validar_hoy[fecha_desde]');
		$this->form_validation->set_rules('fecha_hasta', 'Fecha fin','required|callback_validar_fecha[fecha_hasta]|callback_validar_periodo|callback_validar_hoy[fecha_hasta]');
		$this->form_validation->set_rules('cantidad_personas', 'Cantidad de personas','required|is_natural_no_zero');
		$this->form_validation->set_rules('radio', 'Radio','required|numeric|greater_than[0]');
		$this->form_validation->set_message('validar_fecha', 'El formato de la fecha es incorrecto (dd-mm-aaa) o ingres&oacute; una fecha que no existe.');
		$this->form_validation->set_message('decimal', 'El número ingresado debe ser decimal.');
		$this->form_validation->set_message('validar_periodo', 'La fecha inicial es posterior a la final.');
		$this->form_validation->set_message('validar_hoy', 'La fecha debe ser posterior a hoy.');
		$this->form_validation->set_message('greater_than', 'El número debe ser positivo.');
		
		
	}

	
	function buscar_hoteles(){
		switch ( $_SERVER ['REQUEST_METHOD'] )	{
			case 'GET':
				$this->alta_reserva();
			break;
							
			case 'POST':
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				$this->set_reglas();
				$longitud='';
				$latitud='';
				
				
				$fecha_desde= date('Y-m-d', strtotime($this->input->post('fecha_desde')));
				$fecha_hasta=date('Y-m-d', strtotime($this->input->post('fecha_hasta')));
				$distribucion=$this->input->post('cantidad_personas');
				$radio=$this->input->post('radio');
				$orador=$this->input->post('orador');
				
				$data['fecha_desde'] = $fecha_desde;
				$data['fecha_hasta'] = $fecha_hasta;
				$data['orador'] = $this->input->post('orador');
				$data['distribucion'] = $distribucion;
				$data['radio'] = $radio;
				$data['eventoOrador'] = $this->input->post('eventoOrador');
				
				
				if ($this->form_validation->run() == FALSE){
					$this->rehacer_alta();
				} else {
					$mensaje='';
					
					$this->transformar_longitud_latitud($mensaje,$this->input->post('eventoOrador'),$longitud,$latitud);
					$reservas_obtenidas=$this->Reserva_hotel_model->obtener_disponibilidad($mensaje,$longitud,$latitud,$fecha_desde,$fecha_hasta,$distribucion,$radio);
					if($mensaje!=''){
						$data['hayMensaje']=true;
						$data['tipoMensaje'] = 'alert-error';
						$data['mensaje'] = $mensaje;
					};
					$this->alta_reserva($reservas_obtenidas, $mensaje,$data);
				};
	
		
		};
		 			
		
		
	}
	
	
	function vista_reserva() {
		$id = $_GET['id'];
		$reserva= $this->Reserva_hotel_model->levantar_reserva_id($id);
		$data['titulo'] = 'Reserva código: '.$reserva->id_despegar;
		$data['contenido'] = 'reserva_hotel_view.php';
		$data['evento'] = $this->Evento_model->obtenerEvento($reserva->id_evento)->nombre;
		$data['reserva'] = $reserva;
		$mensaje='';
		$direccion='';
		$this->transformar_direccion($mensaje,$direccion,$reserva->longitud,$reserva->latitud);
		$data['direccion'] =$direccion;
		if($mensaje!=''){
							
							
			$data['hayMensaje'] = true;
			$data['tipoMensaje'] = 'alert-error';
			$data['mensaje'] = 'No es posible obtener la direcci&oacute;n del hotel porque no hay conexi&oacute;n a Internet.';
		
		};
		$this->cargarContenido($data,'reserva');
	}
	
	
	
	function ver_reserva(){
	
		
		$data['titulo'] = 'Confirmar reserva';
		$data['contenido'] = 'confirmar_reserva_hotel_view.php';
		$this->cargarContenido($data);
	
	
	}
	
	function reservar(){
	
	switch ( $_SERVER ['REQUEST_METHOD'] )	{
			case 'GET':
				$this->alta_reserva();
			break;
							
			case 'POST':
	
				$reserva=new reserva_hotel_model();
				$reserva->usuario=new Usuario_model();
				$reserva->usuario->levantar_usuario($this->input->post('id_orador_reserva'));
				$reserva->id_evento=$this->input->post('id_evento_reserva');
				$reserva->cantidad_personas=$this->input->post('distribucion_reserva');
				$reserva->fecha_desde=$this->input->post('fecha_desde_reserva');
				$reserva->fecha_hasta=$this->input->post('fecha_hasta_reserva');
				$reserva->regimen=$this->input->post('regimen_reserva');
				$reserva->precio=$this->input->post('precio_reserva');
				$reserva->moneda=$this->input->post('moneda_reserva');
				$reserva->id_hotel=$this->input->post('id_hotel_reserva');
				$reserva->longitud=$this->input->post('longitud_reserva');
				$reserva->latitud=$this->input->post('latitud_reserva');
				$reserva->nombre=$this->input->post('nombre_hotel_reserva');
				$reserva->check_in=$this->input->post('checkin_reserva');
				$reserva->check_out=$this->input->post('checkout_reserva');
				$reserva->direccion=$this->input->post('direccion_reserva');
				$data['evento'] = $this->Evento_model->obtenerEvento($reserva->id_evento)->nombre;
				$data['titulo'] = "Confirmar reserva";
				$data['contenido'] = 'confirmar_reserva_hotel_view.php';
				$data['reserva']=$reserva;
				$this->cargarContenido($data);
		};
	}
	
	function confirmar(){
	
	switch ( $_SERVER ['REQUEST_METHOD'] )	{
			case 'GET':
				$this->alta_reserva();
			break;
							
			case 'POST':
			
			
				if ($this->input->post('cancelar')) {
					redirect('abmc_reservas_hoteles','recargar');
					
				}
				$reserva=new reserva_hotel_model();
				$reserva->usuario=new Usuario_model();
				$reserva->usuario->levantar_usuario($this->input->post('id_usuario'));
				$reserva->id_evento=$this->input->post('id_evento');
				$reserva->id_hotel=$this->input->post('id_hotel');
				$reserva->fecha_desde=$this->input->post('fecha_desde');
				$reserva->fecha_hasta=$this->input->post('fecha_hasta');
				$reserva->check_in=$this->input->post('check_in');
				$reserva->check_out=$this->input->post('check_out');
				$reserva->cantidad_personas=$this->input->post('cantidad_personas');
				$reserva->regimen=$this->input->post('regimen');
				$reserva->precio=$this->input->post('precio');
				$reserva->moneda=$this->input->post('moneda');
				$reserva->longitud=$this->input->post('longitud');
				$reserva->latitud=$this->input->post('latitud');
				$reserva->nombre=$this->input->post('nombre');
				$reserva->direccion=$this->input->post('direccion');
				
				
				$res=$this->Reserva_hotel_model->confirmar($reserva);
				if($res){
						$data['hayMensaje']=true;
						$data['tipoMensaje'] = 'alert-success';
						$data['mensaje'] = "Reserva realizada exitosamente";
						return $this->recargar($data);
				}else{
				
						$data['hayMensaje']=true;
						$data['tipoMensaje'] = 'alert-error';
						$data['mensaje'] = "Error: no fue posible realizar la reserva.";
						return $this->recargar($data);
				
				}
				
				
	
			};
	}
	
	
	function validar_fecha($valor,$campo){
		$fecha = $this->input->post($campo);
		if($fecha=='') return true;
	
		if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $fecha, $parts)){
    
        if(checkdate($parts[2],$parts[1],$parts[3]))
          return true;
        else
         return false;
		}
		else
			return false;
	}	
	
	function reemplazar($string){
		return (str_replace(' ', '+', $string));

	
	}
	
	
	function obtener_direccion($centro){
	
		$dir=$centro->direccion_string;
		$dir=$this->reemplazar($dir);
		return $dir;
		
		
	
	
	}
	
	function transformar_longitud_latitud(&$mensaje,$idevento,&$longitud,&$latitud){
		$evento=$this->Evento_model->obtenerEvento($idevento);
		$centro=$this->Centro_model->obtenerCentro($evento->id_centro);
		$direccion=$this->obtener_direccion($centro);
		
		if(!$this->Reserva_hotel_model->get_coordenadas($direccion,$longitud,$latitud)) $mensaje="No es posible obtener las coordenadas del centro donde se realizar&aacute; el evento porque no hay conexi&oacute;n a Internet. <br>";
		

	}
	
	function transformar_direccion(&$mensaje,&$direccion,$longitud,$latitud){
		
		if(!$this->Reserva_hotel_model->get_direccion($direccion,$longitud,$latitud)) $mensaje="No es posible obtener la direcci&oacute;n del centro donde se realizar&aacute; el evento porque no hay conexi&oacute;n a Internet. <br>";
		

	}
	
	function do_eliminar($id){
		if(!$this->Reserva_hotel_model->eliminar($id)){
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se pudo acceder a la base de datos para eliminar la reserva.');
		}else{
			$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Cancelación de reserva exitosa.');
		}
		$this->recargar($data);
	}
	
	
	function validar_periodo(){
		$desde = $this->input->post('fecha_desde');
		$hasta = $this->input->post('fecha_hasta');
		if(($desde!='')&&($hasta!=''))
			return (strtotime($hasta) >= strtotime($desde));
	}
	
	function validar_hoy($valor,$campo){
		$fecha = $this->input->post($campo);
		return (strtotime("now") < strtotime($fecha));
		
	}	
}


