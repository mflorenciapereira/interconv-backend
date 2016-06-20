<?php

require_once('application/models/reservas_hotel/idespegar_service.php');


class Despegar_mock_service_impl implements idespegar_service{

	

	
	
	public function obtener_disponibilidad($longitud,$latitud,$fecha_desde, $fecha_hasta, $distribucion, $radio){
				
		$meta=array("currencyCode" =>'ARS');
		
				
		$availability=array();
	
		$reserva1= array(
						'avgPriceWithoutTax' => 727,
						'regimeDescription' => "Bed and Breakfast",
						'id' => 1
						
			);
			
			
		$reserva2= array(
						'avgPriceWithoutTax' => 332,
						'regimeDescription' => "Bed and Breakfast",
						'id' => 2 
			);
			
		array_push($availability,$reserva1);
		array_push($availability,$reserva2);
		$json=array("meta"=>$meta,"availability"=>$availability);
		$json= json_encode($json);
		return json_decode($json);
		
		
	}
	
	public function obtener_informacion_hotel($idhotel){
	
	
		$hotel1= array(
						'name' => "NH Hotel and Tower",
						'description' =>  'Este hotel urbano est&aacute; construido en un edificio moderno y ofrece una estupenda ubicaci&oacute;n, un estilo europeo tradicional y un servicio personalizado. El Hotel cuenta con todas sus habitaciones renovadas, ofreciendo todo el confort necesario para pasar una estancia agradable. Dispone de un total de 74 habitaciones y de instalaciones como vest&iacute;bulo con recepci&oacute;n 24 horas, bar, restaurante y conexi&oacute;n a Internet (con coste extra). Tambi&eacute:n podr&aacute; hacer uso del servicio de habitaciones y de la lavander&iacute;a. ',
						'starRating' => 5,
						'time' => array("checkIn" =>"10:00","checkOut"=>"21:00"),
						'address' => array("fullAddress"=>"Bolivar 160"),
						'geoLocation' => array("longitude"=>-58.382125,"latitude" => -34.606616),
						'pictures' => array("new/008/343008/3040466_38_b_5ad72a94-bb7c-498f-8cd9-d7a13783c1f5.jpg"),
						'amenities' =>array(array("description"=>"Piscina, Restaurante, Gimnasio, Ascensor, Internet, Caja Fuerte, Tel&eacute;fono, Tintorer&iacute;a, Business Center, Aire Acondicionado, Calefacci&oacute;n, Room Service, Sala de Reuniones, Servicio de Lavander&iacute;a, Televisi&oacute;n por Cable, Rent a Car"))
											
			);
			
		$hotel2= array(
						'name' => "Rochester Concept Hotel",
						'description' =>  'Este hotel urbano est&aacute; construido en un edificio moderno y ofrece una estupenda ubicaci&oacute;n, un estilo europeo tradicional y un servicio personalizado. El Hotel cuenta con todas sus habitaciones renovadas, ofreciendo todo el confort necesario para pasar una estancia agradable. Dispone de un total de 74 habitaciones y de instalaciones como vest&iacute;bulo con recepci&oacute;n 24 horas, bar, restaurante y conexi&oacute;n a Internet (con coste extra). Tambi&eacute:n podr&aacute; hacer uso del servicio de habitaciones y de la lavander&iacute;a. ',
						'starRating' => 3,
						'time' => array("checkIn" =>"10:00","checkOut"=>"21:00"),
						'address' => array("fullAddress"=>"Maip&uacute; 572"),
						'geoLocation' => array("longitude"=>-58.37665,"latitude" => -34.60164),
						'pictures' => array("new/698/240698/972416-50.jpg"),
						'amenities' =>array(array("description"=>"Gimnasio, Servicio Maletero, Ascensor, Tel&eacute;fono, Caja Fuerte, Business Center, Aire Acondicionado, Calefacci&oacute;n, WI-FI, Minibar en la habitaci&oacute;n, Servicio de Lavander&iacute;a, Servicio de Transfer"))
											
			);
			
	
			
		$hoteles=array(2=>$hotel1,1=>$hotel2);
		
		$json=json_encode($hoteles[$idhotel]);
		return json_decode($json);
		
		
	
	}
	

	
	
		public function reservar($checkin,$checkout,$distribucion,$idhotel,$precio,$nombre,$email){
	
		return ($this->rand_string(8));
	
	}
	
	
	function rand_string( $length ) {
	$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	

	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}

	return $str;

	}
	
	public function obtener_disponibilidad_vuelos($origen,$destino,$fecha_desde, $fecha_hasta, $distribucion){
		$meta=array("currencyCode" =>'ARS');
		
				
		$flights=array();
	
		$reserva1= array(
						'priceInfo' => array('total' =>array('fare'=>500)) ,
						'id' => "ABSD984893",
						'inboundRoutes' => array('0'=>
							array('segments'=>
								array('0' => 
									array('departure' => array('date'=>"2012-12-07T12:00:00",'locationDescription'=>'Aeropuerto de Fisherton','location'=>'ROS'),
										'arrival' => array('date'=>"2012-12-07T10:00:00",'locationDescription'=>'Aeropuerto Buenos Aires Ministro Pistarini Ezeiza','location'=>'EZE'),
										'flightNumber' => "2110",
										'marketingCabinTypeCode' => "Econ&oacute;mica",
										'marketingCarrierDescription' => "LAN"
									)
								)
							)	
						)	,
						'outboundRoutes' => array('0'=>
							array('segments'=>
								array('0' => 
									array('departure' => array('date'=>"2012-12-02T08:00:00",'locationDescription'=>'Aeropuerto Buenos Aires Ministro Pistarini Ezeiza','location'=>'EZE'),
										'arrival' => array('date'=>"2012-12-02T10:00:00",'locationDescription'=>'Aeropuerto Fisherton','location'=>'ROS'),
										'marketingCabinTypeCode' => "Econ&oacute;mica",
										'flightNumber' => "2107",
										'marketingCarrierDescription' => "LAN"
									)
								)
							)	
						)				
						
						
			);
			
			
			$reserva2= array(
						'priceInfo' => array('total' =>array('fare'=>500)) ,
						'id' => "ABSD9848dfdf93",
						'inboundRoutes' => array()	,
						'outboundRoutes' => array('0'=>
							array('segments'=>
								array('0' => 
									array('departure' => array('date'=>"2012-12-02T08:00:00",'locationDescription'=>'Aeropuerto Buenos Aires Ministro Pistarini Ezeiza ','location'=>'EZE'),
										'arrival' => array('date'=>"2012-12-02T10:00:00",'locationDescription'=>'Aeropuerto Fisherton','location'=>'ROS'),
										'marketingCabinTypeCode' => "Econ&oacute;mica",
										'flightNumber' => "2110",
										'marketingCarrierDescription' => "LAN"
									)
								)
							)	
						)				
						
						
			);
			
			
			
		array_push($flights,$reserva1);
		array_push($flights,$reserva2);
		$json=array("meta"=>$meta,"flights"=>$flights);
		$json= json_encode($json);
		return json_decode($json);
	
	
	
	}
	
	public function reservar_vuelo($id,$precio,$email){
	
		return;
	
	}
	
	public function obtener_longitud_latitud($codigo,&$longitud,&$latitud){
	
		if($codigo=='ROS'){
			$longitud=-60.779953;
			$latitud=-32.916584;
		
		};
		
		if($codigo=='EZE'){
		
		$longitud=-58.539761;
			$latitud=-34.81266;
		
		};
	
		return;
	
	
	}




}

?>