<?php
interface Idespegar_service
{
    public function obtener_disponibilidad($longitud,$latitud,$fecha_desde, $fecha_hasta, $cantidad_personas, $radio);
	public function obtener_informacion_hotel($idhotel);
	public function reservar($checkin,$checkout,$distribucion,$idhotel,$precio,$nombre,$email);
	public function reservar_vuelo($id,$precio,$email);
	public function obtener_disponibilidad_vuelos($origen,$destino,$fecha_desde, $fecha_hasta, $distribucion);
	public function obtener_longitud_latitud($codigo,&$longitud,&$latitud);
	
}
?>