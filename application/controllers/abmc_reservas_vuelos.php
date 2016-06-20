<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Abmc_reservas_vuelos extends MY_Controller{

	function Abmc_reservas_vuelos(){
		parent::__construct();
		$this->load->model('Usuario_model');
		$this->load->model('Orador_model');
		$this->load->model('Evento_model');
		$this->load->model('Centro_model');
		$this->load->model('Reserva_vuelo_model');
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
		$data['contenido'] = 'abmc_reservas_vuelos_view.php';
		$data ['eventos'] = $this->Evento_model->obtenerEventos();
		$filtro = $this->input->get('filtro',TRUE);
		if (!$filtro){
			if (isset($data['filtro'])) $filtro = $data['filtro'];
		}
		if ($filtro!='') {
				$opciones_pag = $this->paginar($filtro);
				$data['reservas'] = $this->Reserva_vuelo_model->levantar_reservas_evento($filtro,$opciones_pag['cantPP'],$opciones_pag['per_page']);
				
		}
		else {
				$opciones_pag = $this->paginar();
				$data['reservas'] = $this->Reserva_vuelo_model->levantar_reservas($opciones_pag['cantPP'],$opciones_pag['per_page']);
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
			$config['base_url'] = site_url('abmc_reservas_vuelos').'?cantPP='.$cantPP.'&filtro='.$filtro;
		}else{
		
			$config['base_url'] = site_url('abmc_reservas_vuelos').'?cantPP='.$cantPP;
		
		};
		
		$config['total_rows'] = $this->Reserva_vuelo_model->obtener_total_reservas($filtro);
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
			
			$this->rehacer_alta($reservas,$mensaje,$data);
	}
	
	function obtener_mapa_direcciones_eventos(){
		$mapa=array();
		$eventos= $this->Evento_model->obtenerEventos();
		if (is_array($eventos)) {
		foreach($eventos as $evento){
			$this->Centro_model->obtenerCentro($evento->id_centro);
			$mapa[$evento->id_evento]=$this->Direccion_model->obtenerDireccion_String($this->Centro_model->obtenerCentro($evento->id_centro)->id_direccion);
		
		}
		}
		return json_encode($mapa);
	
	}
	
	function obtener_mapa_direcciones_oradores(){
		$mapa=array();
		$oradores= $this->Orador_model->obtener_oradores();
		if (is_array($oradores)) {
		foreach($oradores as $orador){
			$mapa[$orador->usuario->id_usuario]=$this->Direccion_model->obtenerDireccion_String($orador->usuario->id_direccion);
		
		}
		}
		return json_encode($mapa);
	
	}
	
	
	function rehacer_alta($reservas=null,$mensaje='',$data=null){
	
			$data['titulo'] = 'Realizar Nueva Reserva';
			$data['contenido'] = 'altaReservaVuelo_view.php';
			$data['eventos'] = $this->Evento_model->obtenerEventos();
			$data['mapaDireccionesEventos'] = $this->obtener_mapa_direcciones_eventos();
			$data['mapaDireccionesOradores'] = $this->obtener_mapa_direcciones_oradores();
			$data['objetos'] = $this->obtener_objetos_despegar();
			$data['accion'] = 'abmc_reservas_hoteles/buscar_hoteles';
			$data['mensaje'] = $mensaje;
			$data['reservas']=$reservas;
			$data['tipoAccion'] = 'alta';
			$this->cargarContenido($data);
	}
	
	


	function set_reglas(){
		$this->form_validation->set_rules('eventoOradorHotel', 'Evento', 'required');
		$this->form_validation->set_rules('oradorHotel', 'Orador', 'required');
		$this->form_validation->set_rules('fecha_desde', 'Fecha inicial','required|callback_validar_fecha[fecha_desde]|callback_validar_periodo|callback_validar_hoy[fecha_desde]');
		$this->form_validation->set_rules('fecha_hasta', 'Fecha inicial','callback_validar_fecha[fecha_hasta]|callback_validar_periodo|callback_validar_hoy[fecha_hasta]');
		$this->form_validation->set_rules('cantidad_personas', 'Cantidad de personas','required|is_natural_no_zero');
		$this->form_validation->set_rules('origen', 'Origen','required');
		$this->form_validation->set_rules('destino', 'Destino','required');
		$this->form_validation->set_rules('origenAuto', 'Origen','');
		$this->form_validation->set_rules('destinoAuto', 'Destino','');
		$this->form_validation->set_message('validar_fecha', 'El formato de la fecha es incorrecto (dd-mm-aaa) o ingres&oacute; una fecha que no existe.');
		$this->form_validation->set_message('decimal', 'El número ingresado debe ser decimal.');
		$this->form_validation->set_message('validar_periodo', 'La fecha inicial es posterior a la final.');
		$this->form_validation->set_message('validar_hoy', 'La fecha debe ser posterior a hoy.');
		
		
	}

	
	function buscar_vuelos(){
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
				
				if($this->input->post('fecha_hasta')!=''){
					$fecha_hasta=date('Y-m-d', strtotime($this->input->post('fecha_hasta')));
				}else{
					$fecha_hasta='';
				};
				$distribucion=$this->input->post('cantidad_personas');
				$origen=$this->input->post('origen');
				$destino=$this->input->post('destino');
				$oradorHotel=$this->input->post('oradorHotel');
				
				$data['fecha_desde'] = $this->input->post('fecha_desde');
				$data['fecha_hasta'] = $this->input->post('fecha_hasta');
				$data['oradorHotel'] = $this->input->post('oradorHotel');
				$data['distribucion'] = $distribucion;
				
				$data['eventoOradorHotel'] = $this->input->post('eventoOradorHotel');
				
				
				if ($this->form_validation->run() == FALSE){
					$this->rehacer_alta(null,'',$data);
				} else {
					$mensaje='';
					
					$reservas_obtenidas=$this->Reserva_vuelo_model->obtener_disponibilidad_vuelos($mensaje,$origen,$destino,$fecha_desde,$fecha_hasta,$distribucion);
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
		$reserva= $this->Reserva_vuelo_model->levantar_reserva_id($id);
		$data['titulo'] = 'Reserva código: '.$reserva->id_despegar;
		$data['contenido'] = 'reserva_vuelo_view.php';
		$data['evento'] = $this->Evento_model->obtenerEvento($reserva->id_evento)->nombre;
		$data['reserva'] = $reserva;
		/*
		$mensaje='';
		$direcciondireccion='';
		$this->transformar_direccion($mensaje,$direccion,$reserva->longitud,$reserva->latitud);
		$data['direccion'] =$direccion;
		if($mensaje!=''){			
			$data['hayMensaje'] = true;
			$data['tipoMensaje'] = 'alert-error';
			$data['mensaje'] = 'No es posible obtener la direcci&oacute;n del hotel porque no hay conexi&oacute;n a Internet.';
		
		};
		*/
		$this->cargarContenido($data,'reserva');
	}
	
	
	
	function ver_reserva(){
	
		
		$data['titulo'] = 'Confirmar reserva';
		$data['contenido'] = 'confirmar_reserva_vuelo_view.php';
		$this->cargarContenido($data);
	
	
	}
	
	
	function armar_reserva($tipo){
	
						
				$reserva=new reserva_vuelo_model();
				$reserva->usuario=new Usuario_model();
				$reserva->usuario->levantar_usuario($this->input->post('id_usuario'));
				$reserva->id_usuario=$this->input->post('id_usuario');
				$reserva->usuario->direccion=$this->Direccion_model->obtenerDireccion_String($reserva->usuario->id_direccion);
				$reserva->id_evento=$this->input->post('id_evento');
				$reserva->cantidad_personas=$this->input->post('cantidad_personas_res');
				$reserva->hora_salida=$this->input->post($tipo.'_hora_salida');
				$reserva->hora_llegada=$this->input->post($tipo.'_hora_llegada');
				$reserva->precio=$this->input->post($tipo.'_precio');
				$reserva->aerolinea=$this->input->post($tipo.'_aerolinea');
				$reserva->moneda=$this->input->post($tipo.'_moneda');
				$reserva->escalas=$this->input->post($tipo.'_escalas');
				$reserva->aerop_salida=$this->input->post($tipo.'_aerop_salida');
				$reserva->aerop_llegada=$this->input->post($tipo.'_aerop_llegada');
				$reserva->codigo_salida=$this->input->post($tipo.'_codigo_salida');
				$reserva->codigo_llegada=$this->input->post($tipo.'_codigo_llegada');
				$reserva->id_despegar=$this->input->post($tipo.'_id_despegar');
				$reserva->numero_vuelo=$this->input->post($tipo.'_numero_vuelo');
				$reserva->tipo=$this->input->post($tipo.'_tipo');
				$reserva->clase=$this->input->post($tipo.'_clase');
				
				return $reserva;
				
	
	
	}
	
	function reservar(){
	
	switch ( $_SERVER ['REQUEST_METHOD'] )	{
			case 'GET':
				$this->alta_reserva();
			break;
							
			case 'POST':
				
				
				$reservaida=$this->armar_reserva('ida');
				
				if($this->input->post('vuelta_id_despegar')!=''){
				
					$reservavuelta=$this->armar_reserva('vuelta');
					$data['reservavuelta']=$reservavuelta;
				
					
				};
				
				
				
				
				$data['evento'] = $this->Evento_model->obtenerEvento($reservaida->id_evento)->nombre;
				
				$data['titulo'] = "Confirmar reserva";
				$data['contenido'] = 'confirmar_reserva_vuelo_view.php';
				$data['reservaida']=$reservaida;
				
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
					redirect('abmc_reservas_vuelos','recargar');
					
				}
				$reservaida=$this->armar_reserva('ida');
				$res=$this->Reserva_vuelo_model->confirmar($reservaida);
				
				if(($res)&&($this->input->post('vuelta_id_despegar')!='')){
					
					$reservavuelta=$this->armar_reserva('vuelta');
					$res=$this->Reserva_vuelo_model->confirmar($reservavuelta);
				};
				
				
				
				
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
	
	
	function do_eliminar($id){
		if(!$this->Reserva_vuelo_model->eliminar($id)){
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se pudo acceder a la base de datos para eliminar la reserva.');
		}else{
			$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Cancelación de reserva exitosa.');
		}
		$this->recargar($data);
	}
	
	function obtener_reservas_asociadas(){
		
		$id_evento=$_POST['evento'];
		$id_usuario=$_POST['usuario'];
		$reservas=$this->Reserva_hotel_model->obtener_reservas_evento_orador($id_evento,$id_usuario);
		$output_string='<span style="font-size : smaller;">';
        if(count($reservas)>0){
			foreach($reservas as $reserva){
			
			$direccion='';
			$this->Reserva_hotel_model->get_direccion($direccion,$reserva->longitud,$reserva->latitud);
			$output_string .= $reserva->id_despegar.' - '.$reserva->nombre.': '.$direccion.'<br>';
			
			};
			
		
		
		}else{
			$output_string .= "No hay reservas de hotel asociadas.";
		}		
			$output_string .= "</span>";
        echo json_encode($output_string);
    }
	
	function obtener_objetos_despegar(){
	
		return $this->Reserva_vuelo_model->get_objetos_despegar();
	}
	
	function get_ciudades(){
		$term = $this->input->get('q', TRUE);
		
        return $this->Reserva_vuelo_model->get_ciudades($term);
	
	}
	
	
}


