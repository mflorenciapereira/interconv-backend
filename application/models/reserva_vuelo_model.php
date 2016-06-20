<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('application/models/reservas_hotel/despegar_service_impl.php');
require_once('application/models/reservas_hotel/despegar_mock_service_impl.php');



class Reserva_vuelo_model extends CI_Model {
	
	public $despegar_service;
	
	//RESERVA
	//interno
	public $usuario;
	public $evento;
	public $cantidad_personas;
	public $hora_salida;	//esto deberían ser fechas
	public $hora_llegada;	//esto tb
	public $id_reserva_vuelo;
	public $id_usuario;
	public $id_evento;
	public $tipo;
	
	//externo
	public $aerolinea;
	public $precio;
	public $moneda;
	public $escalas;
	public $clase;
	public $aerop_salida;
	public $aerop_llegada;
	public $numero;
	public $id_despegar;
	public $codigo_salida;
	public $codigo_llegada;


	function Reserva_vuelo_model(){
		parent::__construct();
		$this->load->model('Direccion_model');
		$this->load->model('Usuario_model');
		$this->despegar_service=new Despegar_service_impl();
		
	}
	
	//Levanta todas las reservas
	function levantar_reservas($cant=-1,$offset=-1){
		if (($cant >= 0) && ($offset >= 0)){
				$this->db->limit($cant,$offset);
			}
		$reservas = array();
		$this->db->from('reserva_vuelo');
		$query_reserva_hotel = $this->db->get();
		foreach ($query_reserva_hotel->result('Reserva_vuelo_model') as $row)
		{	
			$row->usuario = new Usuario_model();
			$row->usuario->levantar_usuario($row->id_usuario);
			//$row->direccion = $this->Direccion_model->obtenerDireccion($row->id_direccion);
			array_push($reservas,$row);
		}
		return $reservas;
	}
	
	function levantar_reservas_evento($id,$cant=-1,$offset=-1){
		if (($cant >= 0) && ($offset >= 0)){
				$this->db->limit($cant,$offset);
			}
		$reservas = array();
		$this->db->from('reserva_vuelo');
		$this->db->where('id_evento',$id);
		$query_reserva_hotel = $this->db->get();
		foreach ($query_reserva_hotel->result('Reserva_vuelo_model') as $row)
		{	
			$row->usuario = new Usuario_model();
			$row->usuario->levantar_usuario($row->id_usuario);
			//$row->direccion = $this->Direccion_model->obtenerDireccion($row->id_direccion);
			array_push($reservas,$row);
		}
		return $reservas;
	
	}
	
	function levantar_reserva_id($id){
		$reservas = array();
		$this->db->from('reserva_vuelo');
		$this->db->where('reserva_vuelo.id_reserva_vuelo',$id);
		$query_reserva_hotel = $this->db->get();
		$reserva = $query_reserva_hotel->first_row('Reserva_vuelo_model');
		$reserva->usuario = new Usuario_model();
		$reserva->usuario->levantar_usuario($reserva->id_usuario);
		//$reserva->direccion = $this->Direccion_model->obtenerDireccion($reserva->id_direccion);
		//$reserva->direccion_str = $this->Direccion_model->obtenerDireccion_String($reserva->id_direccion);
		return $reserva;
	}
	
	
	public function cambiar_servicio(){
	
		$this->despegar_service=new Despegar_mock_service_impl();
	}
	
	
	function obtener_vuelta($datos,$moneda){
		$reserva=new reserva_vuelo_model();
		$reserva->precio=$datos->priceInfo->total->fare;
		$reserva->id_despegar=$datos->id;
		$reserva->hora_salida= $datos->inboundRoutes[0]->segments[0]->departure->date;
		$reserva->aerop_salida= $datos->inboundRoutes[0]->segments[0]->departure->locationDescription;
		$reserva->codigo_salida= $datos->inboundRoutes[0]->segments[0]->departure->location;
		
		$reserva->hora_llegada= $datos->inboundRoutes[0]->segments[count($datos->inboundRoutes[0]->segments)-1]->arrival->date;
		$reserva->aerop_llegada= $datos->inboundRoutes[0]->segments[count($datos->inboundRoutes[0]->segments)-1]->arrival->locationDescription;
		$reserva->codigo_llegada= $datos->inboundRoutes[0]->segments[count($datos->inboundRoutes[0]->segments)-1]->arrival->location;
		
		$reserva->clase= $datos->inboundRoutes[0]->segments[0]->marketingCabinTypeCode;
		$reserva->aerolinea= $datos->inboundRoutes[0]->segments[0]->marketingCarrierDescription;
		$reserva->numero_vuelo= $datos->inboundRoutes[0]->segments[0]->flightNumber;
		$reserva->escalas= count($datos->inboundRoutes[0]->segments)>1;
		$reserva->moneda=$moneda;
		$reserva->tipo='REGRESO';
		return $reserva;
	
	
	}
	
	
	function obtener_ida($datos,$moneda){
	
		$reserva=new reserva_vuelo_model();
		
		$reserva->precio=$datos->priceInfo->total->fare;
		$reserva->id_despegar=$datos->id;
		$reserva->hora_salida= $datos->outboundRoutes[0]->segments[0]->departure->date;
		$reserva->aerop_salida= $datos->outboundRoutes[0]->segments[0]->departure->locationDescription;
		$reserva->codigo_salida= $datos->outboundRoutes[0]->segments[0]->departure->location;
		
		$reserva->hora_llegada= $datos->outboundRoutes[0]->segments[count($datos->outboundRoutes[0]->segments)-1]->arrival->date;
		$reserva->aerop_llegada= $datos->outboundRoutes[0]->segments[count($datos->outboundRoutes[0]->segments)-1]->arrival->locationDescription;
		$reserva->codigo_llegada= $datos->outboundRoutes[0]->segments[count($datos->outboundRoutes[0]->segments)-1]->arrival->location;
		
		$reserva->clase= $datos->outboundRoutes[0]->segments[0]->marketingCabinTypeCode;
		$reserva->aerolinea= $datos->outboundRoutes[0]->segments[0]->marketingCarrierDescription;
		$reserva->numero_vuelo= $datos->outboundRoutes[0]->segments[0]->flightNumber;
		$reserva->escalas= count($datos->outboundRoutes[0]->segments)>1;
		
		$reserva->tipo='IDA';
		$reserva->moneda=$moneda;
		
		return $reserva;
	
	
	}

	
	public function obtener_disponibilidad_vuelos(&$mensaje,$origen,$destino,$fecha_desde, $fecha_hasta, $cantidad_personas){
	
		
		try{
		
				
			$resultado=$this->despegar_service->obtener_disponibilidad_vuelos($origen,$destino,$fecha_desde, $fecha_hasta, $cantidad_personas);			
			$i=0;		
			
			
			$reservas = array();	
			foreach ($resultado->flights as $vuelo){
			
				$reservaida=$this->obtener_ida($vuelo,$resultado->meta->currencyCode);
				$reservas[$i]=$reservaida;
				$i++;
				
				
				if(count($vuelo->inboundRoutes)>0){
					$reservavuelta=$this->obtener_vuelta($vuelo,$resultado->meta->currencyCode);
					$reservas[$i]=$reservavuelta;
					
				$i++;
				};
				
			};
			
			return $reservas;
		} catch (NoHayAeropuerto $e) {
				
                $mensaje.=$e->getMessage();
				
				
        
		} catch (Exception $e) {
				
                $mensaje.=$e->getMessage()." Se devuelven resultados default.";
				$this->cambiar_servicio();
				
				$reservas= $this->obtener_disponibilidad_vuelos($mensaje,$origen,$destino,$fecha_desde, $fecha_hasta, $cantidad_personas);
				
				return $reservas;
				
        };
		
		
		
		
	}
	
	function extraer_amenities($hotel){
		$amenities='';
		foreach($hotel->amenities as $amenity){
			$amenities.=$amenity->description.", ";
		}
		
		$pos = strrpos($amenities, ",");
		if($pos !== false) $amenities = substr_replace($amenities, '.', $pos, strlen($amenities));
   				
		return $amenities;
	
	
	}
	
	
	function completar_datos_hotel($reserva){
	
		
		try{
			$hotel=$this->despegar_service->obtener_informacion_hotel($reserva->id_hotel);			
			$reserva->nombre=$hotel->name;
			$reserva->descripcion=$hotel->description;
			$reserva->estrellas=$hotel->starRating;
			$reserva->check_in=$hotel->time->checkIn;
			$reserva->check_out=$hotel->time->checkOut;
			$reserva->direccion=$hotel->address->fullAddress;
			$reserva->longitud=$hotel->geoLocation->longitude;
			$reserva->latitud=$hotel->geoLocation->latitude;
			$reserva->foto=$hotel->pictures[0];
			$reserva->amenities=$this->extraer_amenities($hotel);
			
			return;
			
			
			
		}catch(Exception $e){
		
			$this->cambiar_servicio();
			return $this->completar_datos_hotel($reserva);	
		}

	}
	
	
	
	public function confirmar($reserva){
	
		$this->despegar_service->reservar_vuelo($reserva->id_despegar,$reserva->precio,"eventos@interconv.com");			
		return $this->agregar($reserva);
	
	}
	
	function obtener_array_reserva_vuelo($reserva){
		$longitud_llegada=0;
		$latitud_llegada=0;
		$longitud_salida=0;
		$latitud_salida=0;
		try{
			$this->despegar_service->obtener_longitud_latitud($reserva->codigo_llegada,$longitud_llegada,$latitud_llegada);
			$this->despegar_service->obtener_longitud_latitud($reserva->codigo_salida,$longitud_salida,$latitud_salida);
		}catch (Exception $e) {
				$this->cambiar_servicio();
				$this->despegar_service->obtener_longitud_latitud($reserva->codigo_llegada,$longitud_llegada,$latitud_llegada);	
				$this->despegar_service->obtener_longitud_latitud($reserva->codigo_salida,$longitud_salida,$latitud_salida);
				
				
        };
			
		$row=array(
					'hora_salida' => $reserva->hora_salida,
					'hora_llegada' => $reserva->hora_llegada,
					'aerop_salida' => $reserva->aerop_salida,
					'aerop_llegada' => $reserva->aerop_llegada,
					'escalas' => $reserva->escalas,
					'clase' => $reserva->clase,
					'id_despegar' => $reserva->id_despegar,
					'precio' => $reserva->precio,
					'id_despegar' => $reserva->id_despegar,
					'id_usuario' => $reserva->usuario->id_usuario,
					'id_evento' => $reserva->id_evento,
					'aerolinea' => $reserva->aerolinea,
					'moneda' => $reserva->moneda,
					'numero_vuelo' => $reserva->numero_vuelo,
					'tipo' => $reserva->tipo,
					'cantidad_personas' => $reserva->cantidad_personas,
					'longitud_llegada' => $longitud_llegada,
					'latitud_llegada' => $latitud_llegada,
					'longitud_salida' => $longitud_salida,
					'latitud_salida' => $latitud_salida,
					
					
					);
		
		return $row;
	
	
	}
	
	
	function agregar($reserva){
	
	
		$this->db->trans_start();
		$this->db->insert('reserva_vuelo',$this->obtener_array_reserva_vuelo($reserva));
		$id=$this->db->insert_id();	
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return $id;
	}
	
	
	
	
	function obtener_total_reservas($id=null){
	if($id!=null){
		$this->db->from('reserva_vuelo');
		$this->db->where('id_evento',$id);
		return $this->db->count_all_results();
	
	}
	return $this->db->count_all('reserva_vuelo');
	}
	
	function get_coordenadas($direccion,&$longitud,&$latitud){
		$url = "http://maps.google.com/maps/api/geocode/json?address=".$direccion."&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		if($response_a!=null){
			$latitud = $response_a->results[0]->geometry->location->lat;
			$longitud = $response_a->results[0]->geometry->location->lng;
		};
		return($response_a!=null);
	
	}
	
	function get_direccion(&$direccion,$longitud,$latitud){
	
		
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitud.','.$longitud."&sensor=false";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		curl_close($ch);
		$response_a = json_decode($response);
		if($response_a!=null){
			$direccion = $response_a->results[0]->formatted_address;			
		};
		return($response_a!=null);
	
	}
	
	
	function hay_reservas_asociadas_evento($id){
		if($id!=null){
			$this->db->from('reserva_vuelo');
			$this->db->where('id_evento',$id);
			return $this->db->count_all_results()>0;
		
		}
		else return false;
	
	}
	
	
	
	function eliminar($id){
		$this->db->trans_begin();
		$this->db->where('id_reserva_vuelo', $id);
		$this->db->delete("reserva_vuelo");
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
		
	}
	
	function get_objetos_despegar(){
		$this->db->from('objetos_despegar');
		return $query_reserva_hotel = $this->db->get()->result();
	
	}
	
	function get_ciudades($termino){
		$despegar_service=new Despegar_service_impl();
		$url='http://api.despegar.com/autocomplete/'.$termino;
		
		$jsonstring=$despegar_service->get_data($url);
		$json=json_decode($jsonstring);
		
		if(isset($json->autocomplete)){
			$resultados=$json->autocomplete;
			$data=array();
			foreach($resultados as $objeto){
				$item = array('label' => $objeto->name, 'value'=> $objeto->id);
				array_push($data,$item);
			}
			
			$valor=json_encode($data);
			header("Content-Type: application/json");
			echo $valor;
			
		
		}else{
		
			$data=array();
			
			$item = array('label' => 'Buenos Aires, Argentina', 'value'=> 'BUE');
			array_push($data,$item);
			
			$item = array('label' => 'Rosario, Argentina', 'value'=> 'ROS');
			array_push($data,$item);
			
			$item = array('label' => 'Londres, Reino Unido', 'value'=> 'LON');
			array_push($data,$item);
			
			$item = array('label' => 'Madrid, España', 'value'=> 'MAD');
			array_push($data,$item);
			
			$valor=json_encode($data);
			header("Content-Type: application/json");
			echo $valor;
		
			
		
		};
		
	}
	
	
	

}