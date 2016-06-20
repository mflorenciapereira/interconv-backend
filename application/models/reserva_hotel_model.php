<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once('application/models/reservas_hotel/despegar_service_impl.php');
require_once('application/models/reservas_hotel/despegar_mock_service_impl.php');

function exception_handler($exception) {
  echo "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');

class Reserva_hotel_model extends CI_Model {
	
	public $despegar_service;
	
	//RESERVA
	//interno
	public $usuario;
	public $evento;
	public $cantidad_personas;
	public $fecha_desde;
	public $fecha_hasta;
	public $id_reserva_hotel;
	public $id_usuario;
	public $id_evento;
	
	//externo
	public $regimen;
	public $precio;
	public $moneda;
	public $id_hotel;
	public $id_despegar;
	
	
	//HOTEL
	public $nombre;
	public $descripcion;
	public $estrellas;
	public $check_in;
	public $check_out;
	public $direccion;
	public $latitud;
	public $longitud;
	public $amenities;
	public $telefono;
	public $foto;
	public $id_direccion;
	

	
	function Reserva_hotel_model(){
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
		$this->db->from('reserva_hotel');
		$query_reserva_hotel = $this->db->get();
		foreach ($query_reserva_hotel->result('Reserva_hotel_model') as $row)
		{	
			
			$row->usuario = new Usuario_model();
			$row->usuario->levantar_usuario($row->id_usuario);
			$row->direccion = $this->Direccion_model->obtenerDireccion($row->id_direccion);
			array_push($reservas,$row);
		}
		return $reservas;
	}
	
	function levantar_reservas_evento($id,$cant=-1,$offset=-1){
		if (($cant >= 0) && ($offset >= 0)){
				$this->db->limit($cant,$offset);
			}
		$reservas = array();
		$this->db->from('reserva_hotel');
		$this->db->where('id_evento',$id);
		$query_reserva_hotel = $this->db->get();
		foreach ($query_reserva_hotel->result('Reserva_hotel_model') as $row)
		{	
			$row->usuario = new Usuario_model();
			$row->usuario->levantar_usuario($row->id_usuario);
			$row->direccion = $this->Direccion_model->obtenerDireccion($row->id_direccion);
			array_push($reservas,$row);
		}
		return $reservas;
	}
	
	function levantar_reserva_id($id){
		$reservas = array();
		$this->db->from('reserva_hotel');
		$this->db->where('reserva_hotel.id_reserva_hotel',$id);
		$query_reserva_hotel = $this->db->get();
		$reserva = $query_reserva_hotel->first_row('Reserva_hotel_model');
		$reserva->usuario = new Usuario_model();
		$reserva->usuario->levantar_usuario($reserva->id_usuario);
		$reserva->direccion = $this->Direccion_model->obtenerDireccion($reserva->id_direccion);
		$reserva->direccion_str = $this->Direccion_model->obtenerDireccion_String($reserva->id_direccion);
		return $reserva;
	}
	
	
	public function cambiar_servicio(){
		$this->despegar_service=new Despegar_mock_service_impl();
	}

	
	public function obtener_disponibilidad(&$mensaje,$longitud,$latitud,$fecha_desde, $fecha_hasta, $cantidad_personas, $radio){
	
		
		try{
		
				
			$resultado=$this->despegar_service->obtener_disponibilidad($longitud,$latitud,$fecha_desde, $fecha_hasta, $cantidad_personas, $radio);			
			$i=0;			
			
			$reservas = array();	
			foreach ($resultado->availability as $reserva_resultado){
				$reserva=new reserva_hotel_model();
				$reserva->precio=$reserva_resultado->avgPriceWithoutTax;
				$reserva->moneda=$resultado->meta->currencyCode;
				$reserva->regimen=$reserva_resultado->regimeDescription;
				$reserva->id_hotel=$reserva_resultado->id;
				$this->completar_datos_hotel($reserva);
				$reservas[$i]=$reserva;
				$i++;
			};
			
			return $reservas;
		} catch (Exception $e) {
				
                $mensaje.=$e->getMessage()." Se devuelven resultados default.";
				$this->cambiar_servicio();
				
				$reservas= $this->obtener_disponibilidad($mensaje,$longitud,$latitud,$fecha_desde, $fecha_hasta, $cantidad_personas, $radio);
				
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
	
		$reserva->id_despegar=$this->despegar_service->reservar($reserva->check_in,$reserva->check_out,$reserva->cantidad_personas,$reserva->id_hotel,$reserva->precio,$reserva->nombre,"eventos@interconv.com");			
		return $this->agregar($reserva);
	
	}
	
	function obtener_array_reserva_hotel($reserva){
		$direccion='';
		$ok=$this->get_direccion($direccion,$reserva->longitud,$reserva->latitud);
			
		$row=array('nombre' => $reserva->nombre,
					'regimen' => $reserva->regimen,
					'check_in' => $reserva->check_in,
					'check_out' => $reserva->check_out,
					'cantidad_personas' => $reserva->cantidad_personas,
					'fecha_desde' => $reserva->fecha_desde,
					'fecha_hasta' => $reserva->fecha_hasta,
					'check_in' => $reserva->check_in,
					'precio' => $reserva->precio,
					'id_hotel' => $reserva->id_hotel,
					'latitud' => $reserva->latitud,
					'longitud' => $reserva->longitud,
					'moneda' => $reserva->moneda,
					'id_despegar' => $reserva->id_despegar,
					'direccion' => $ok?$direccion:$reserva->direccion.utf8_encode(' Buenos Aires, Ciudad Autónoma de Buenos Aires, Argentina '),
					'id_usuario' => $reserva->usuario->id_usuario,
					'id_evento' => $reserva->id_evento,
					
					);
					
		return $row;
	
	
	}
	
	
	function agregar($reserva){
		$this->db->trans_start();
		$this->db->insert('reserva_hotel',$this->obtener_array_reserva_hotel($reserva));
		$id=$this->db->insert_id();	
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return $id;
	}
	
	
	function eliminar($id){
		$this->db->trans_begin();
		$this->db->where('id_reserva_hotel', $id);
		$this->db->delete("reserva_hotel");
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE)
			return false;
		else
			return true;
		
	}
	
	function obtener_total_reservas($id=null){
	if($id!=null){
		$this->db->from('reserva_hotel');
		$this->db->where('id_evento',$id);
		return $this->db->count_all_results();
	
	}
	return $this->db->count_all('reserva_hotel');
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
	
		
		$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitud.','.$longitud."&sensor=false&language=es";
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
			$this->db->from('reserva_hotel');
			$this->db->where('id_evento',$id);
			return $this->db->count_all_results()>0;
		
		}
		else return false;
	
	}
	
	function obtener_reservas_evento_orador($evento,$orador){
		$this->db->where('id_evento',$evento);
		$this->db->where('id_usuario',$orador);
		$this->db->from('reserva_hotel');
		return $query_reserva_hotel = $this->db->get()->result();
	
	}
	
	
	

}