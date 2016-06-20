interface Idespegar_service
{
    public function obtener_disponibilidad($longitud,$latitud,$fecha_desde, $fecha_hasta, $cantidad_personas, $radio);
	public function obtener_informacion_hotel($idhotel);
	public function obtener_amenities($idhotel);
	public function reservar($checkin,$checkout,$distribucion,$idhotel,$precio,$dni,$nombre,$email);
}