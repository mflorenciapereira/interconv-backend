<?php

require_once('application/models/reservas_hotel/idespegar_service.php');

define("url_disponibilidad", "http://api.despegar.com/availability/hotels/");
define("url_hotel", "http://api.despegar.com/hotels/");
define("url_vuelos", "http://api.despegar.com/availability/flights/");
define("url_aeropuertos", "http://api.despegar.com/airports/");

	


class NoHayConexion extends Exception { }
class LimiteExcedido extends Exception { }
class NoHayAeropuerto extends Exception { }

class Despegar_service_impl implements idespegar_service{



	function get_data($url) {
		//var_dump($url);
		$ch = curl_init();
		$timeout = 10;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_ENCODING,'gzip');		
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
		
		// return '';
		
		/* Para probar sin internet, comentar todo menos el ultimo return */
	}
	
	
	public function obtener_disponibilidad($longitud,$latitud,$fecha_desde, $fecha_hasta, $distribucion, $radio){
	
		
		
		$url=constant("url_disponibilidad").$latitud.
		"/".$longitud."?".
		"checkin=".$fecha_desde."&".
		"checkout=".$fecha_hasta."&".
		"distribution=".$distribucion."&".
		"radius=".$radio."&pagesize=10&sort=price&order=asc";
		
		
		$jsdatos = $this->get_data($url);
		$datos=json_decode($jsdatos);
		
		if($datos==NULL) throw new NoHayConexion("No es posible consultar la API de Despegar porque no hay conexi&oacute;n a Internet.");
		if(!isset($datos->availability))throw new LimiteExcedido("No es posible consultar la API de Despegar porque se ha excedido el l&iacute;mite de consultas diario.");
		return $datos;
		
		
	}
	
	public function obtener_informacion_hotel($idhotel){
		$url=constant("url_hotel").$idhotel."?includeamenities=true";
		$jsdatos = $this->get_data($url);
		$datos=json_decode($jsdatos);
		if($datos==NULL) throw new NoHayConexion("No es posible consultar Despegar porque no hay conexi&oacute:n a Internet.");
		if(!isset($datos->hotels))throw new LimiteExcedido("No es posible consultar Despegar porque se ha excedido el l&iacute;mite de consultas diario.");
		return $datos->hotels[0];
	
	}
	

	
	
	public function reservar($checkin,$checkout,$distribucion,$idhotel,$precio,$nombre,$email){
	
		return ($this->rand_string(8));
	
	}
	
	
	function rand_string( $length ) {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
	$str='';
	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;
}



	public function obtener_disponibilidad_vuelos($origen,$destino,$fecha_desde, $fecha_hasta, $distribucion){
	
		
	
		if($fecha_hasta)
			$tipo='roundTrip';
		else
			$tipo='oneWay';
		$url=constant("url_vuelos").$tipo.
		"/".$origen.
		"/".$destino.
		"/".$fecha_desde;
		if($fecha_hasta)
			$url.="/".$fecha_hasta;
		$url.="/".$distribucion.
		"/0/0?pagesize=10&sort=price&order=asc";
		
		
		$jsdatos = $this->get_data($url);
		$datos=json_decode($jsdatos);
		
		if($datos==NULL) throw new NoHayConexion("No es posible consultar la API de Despegar porque no hay conexi&oacute;n a Internet.");
		if(isset($datos->meta->suggestions)){
			$mensaje="Alguna de las ciudades ingresadas no posee aeropuerto.<br>";
			if(isset($datos->meta->suggestions->$origen)){
				$sug_origen=$datos->meta->suggestions->$origen;
				
				$mensaje.="Pruebe los siguientes c&oacute;digos para el origen: ";
				for($i=0;$i<count($sug_origen);$i++){
					$mensaje.=$sug_origen[$i]->code.' ';
				};
			};
			
			$mensaje.='<br>';
			if(isset($datos->meta->suggestions->$destino)){
				$sug_destino=$datos->meta->suggestions->$destino;
				$mensaje.="Pruebe los siguientes c&oacute;digos para el destino: ";
				for($j=0;$j<count($sug_destino);$j++){
					$mensaje.=$sug_destino[$j]->code.' ';
				};
			};
			
			throw new NoHayAeropuerto($mensaje);
			
		};	
		if(!isset($datos->flights))throw new LimiteExcedido("No es posible consultar Despegar.com porque se ha excedido el l&iacute;mite de consultas diario o existe alg&uacute;n error en los destinos ingresados.");
		return $datos;
		
		
	}
	
	public function reservar_vuelo($id,$precio,$email){
	
		return;
	
	}
	
	public function obtener_longitud_latitud($codigo,&$longitud,&$latitud){
	
		$url=constant("url_aeropuertos").$codigo;
		$jsdatos = $this->get_data($url);
		
		$datos=json_decode($jsdatos);
		
		if($datos==NULL) throw new NoHayConexion("No es posible consultar la API de Despegar porque no hay conexi&oacute;n a Internet.");
		if(!isset($datos->airports))throw new LimiteExcedido("No es posible consultar la API de Despegar porque se ha excedido el l&iacute;mite de consultas diario o el aeropuerto buscado no se encuentra cargado en el sistema.");
		
		$longitud=$datos->airports[0]->geoLocation->longitude;
		$latitud=$datos->airports[0]->geoLocation->latitude;
		
	
	
	}
	



}

?>