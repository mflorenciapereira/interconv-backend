
<script>	
	function confirmarReserva(){
			confirm('La acción seleccionada hará efectiva la reserva a través de Despegar.com \r\n ¿Confirma la reserva?')
				
		
	};
</script>


<div class="row">
<div class="span8">




<h4>Orador</h4>
<div class="row">
	<div class="span3">
		<h6>Nombre</h6><?=$reservaida->usuario->get_nombre()?> <?=$reservaida->usuario->get_apellido()?>
	</div>
	<div class="span2">
		<h6>DNI </h6><?=$reservaida->usuario->id_usuario?>
	</div>
	<div class="span3">
		<h6>Dirección </h6><?=$reservaida->usuario->direccion?>
	</div>
	
</div>
<br>
<div class="row">
	
	<div class="span3">
		<h6>Email </h6><?=$reservaida->usuario->email?>
	</div>
	<div class="span2">
		<h6>Telefono </h6><?=$reservaida->usuario->telefono?>
	</div>
	<div class="span2">
		<h6>Evento en que participa </h6><?=$evento?>
	</div>
</div>
<br><br>
<h4>Reserva de vuelos</h4>
<div class="row">
	<div class="span3">
		<h6>Cantidad de personas</h6><?=$reservaida->cantidad_personas?>
	</div>
	
	<div class="span3">
		<h6>ID Despegar</h6><?=$reservaida->id_despegar?>
	</div>

	<div class="span2">
		<h6>Precio</h6><p class="text-info"><strong><?=$reservaida->moneda?> <?=$reservaida->precio?></strong></p>
	</div>
	
</div>
<br>

<h5>Ida</h5>
<div class="row">

	<div class="span2">
		<h6>Hora salida</h6><?=$reservaida->hora_salida?>
	</div>
	<div class="span6">
		<h6>Aeropuerto origen</h6><?=$reservaida->aerop_salida?>
		
	</div>
	
	
</div>
<br>
<div class="row">

	<div class="span2">
		<h6>Hora llegada</h6><?=$reservaida->hora_llegada?>
	</div>
	<div class="span4">
		<h6>Aeropuerto destino</h6><?=$reservaida->aerop_llegada?>
	</div>
	
</div>
<br>
<div class="row">

	<div class="span2">
		<h6>Escalas</h6><?=$reservaida->escalas?>
	</div>
	<div class="span2">
		<h6>Aerol&iacute;nea</h6><?=$reservaida->aerolinea?>
	</div>
		<div class="span2">
		<h6>Clase</h6><?=$reservaida->clase?>
	</div>
	<div class="span2">
		<h6>Número de vuelo</h6><?=$reservaida->numero_vuelo?>
	</div>
	
	

	
</div>
<br>
<?php if (isset($reservavuelta)) :?>
<h5>Regreso</h5>
<div class="row">

	<div class="span2">
		<h6>Hora salida</h6><?=$reservavuelta->hora_salida?>
	</div>
	<div class="span6">
		<h6>Aeropuerto origen</h6><?=$reservavuelta->aerop_salida?>
		
	</div>
	
	
</div>
<br>
<div class="row">

	<div class="span2">
		<h6>Hora llegada</h6><?=$reservavuelta->hora_llegada?>
	</div>
	<div class="span4">
		<h6>Aeropuerto destino</h6><?=$reservavuelta->aerop_llegada?>
	</div>
	
</div>
<br>
<div class="row">

	<div class="span2">
		<h6>Escalas</h6><?=$reservavuelta->escalas?>
	</div>
	<div class="span2">
		<h6>Aerol&iacute;nea</h6><?=$reservavuelta->aerolinea?>
	</div>
	<div class="span2">
		<h6>Clase</h6><?=$reservavuelta->clase?>
	</div>
	
	<div class="span2">
		<h6>Número de vuelo</h6><?=$reservavuelta->numero_vuelo?>
	</div>
	
	

	
</div>
<br>



<?php endif;?>

<?php echo form_open_multipart('abmc_reservas_vuelos/confirmar',array('id' => 'confirmar_form')); echo "\n";?>
<button type="submit" name="buscarHoteles" onclick="confirmarReserva()" class="btn btn-primary" value="1">Confirmar reserva</button>
<button type="submit" name="cancelar"  class="btn" value="1">Cancelar</button>
	
<?php if (isset($reservavuelta)) :?>
<input type="hidden" id="vuelta_aerop_salida" name="vuelta_aerop_salida"  value="<?=$reservavuelta->aerop_salida?>" />
<input type="hidden" id="vuelta_aerop_llegada" name="vuelta_aerop_llegada" value="<?=$reservavuelta->aerop_llegada?>"  />
<input type="hidden" id="vuelta_codigo_salida" name="vuelta_codigo_salida"  value="<?=$reservavuelta->codigo_salida?>" />
<input type="hidden" id="vuelta_codigo_llegada" name="vuelta_codigo_llegada" value="<?=$reservavuelta->codigo_llegada?>"  />
<input type="hidden" id="vuelta_aerolinea" name="vuelta_aerolinea"  value="<?=$reservavuelta->aerolinea?>" />
<input type="hidden" id="vuelta_clase" name="vuelta_clase" value="<?=$reservavuelta->clase?>"  />
<input type="hidden" id="vuelta_escalas" name="vuelta_escalas" value="<?=$reservavuelta->escalas?>"  />
<input type="hidden" id="vuelta_precio" name="vuelta_precio" value="<?=$reservavuelta->precio?>"  />
<input type="hidden" id="vuelta_moneda" name="vuelta_moneda" value="<?=$reservavuelta->moneda?>"  />
<input type="hidden" id="vuelta_hora_salida" name="vuelta_hora_salida" value="<?=$reservavuelta->hora_salida?>"  />
<input type="hidden" id="vuelta_hora_llegada" name="vuelta_hora_llegada" value="<?=$reservavuelta->hora_llegada?>"  />
<input type="hidden" id="vuelta_id_despegar" name="vuelta_id_despegar" value="<?=$reservavuelta->id_despegar?>"  />
<input type="hidden" id="vuelta_id_numero_vuelo" name="vuelta_id_numero_vuelo" value="<?=$reservavuelta->numero_vuelo?>"  />
<input type="hidden" id="vuelta_tipo" name="vuelta_tipo" value="<?=$reservavuelta->tipo?>"  />
<input type="hidden" id="vuelta_cantidad_personas" name="vuelta_cantidad_personas" value="<?=$reservavuelta->cantidad_personas?>"  />
<input type="hidden" id="vuelta_clase" name="vuelta_clase" value="<?=$reservavuelta->clase?>"  />

<?php endif;?>

<input type="hidden" id="ida_aerop_salida" name="ida_aerop_salida"  value="<?=$reservaida->aerop_salida?>" />
<input type="hidden" id="ida_aerop_llegada" name="ida_aerop_llegada" value="<?=$reservaida->aerop_llegada?>"  />
<input type="hidden" id="ida_codigo_salida" name="ida_codigo_salida"  value="<?=$reservaida->codigo_salida?>" />
<input type="hidden" id="ida_codigo_llegada" name="ida_codigo_llegada" value="<?=$reservaida->codigo_llegada?>"  />
<input type="hidden" id="ida_aerolinea" name="ida_aerolinea"  value="<?=$reservaida->aerolinea?>" />
<input type="hidden" id="ida_clase" name="ida_clase" value="<?=$reservaida->clase?>"  />
<input type="hidden" id="ida_escalas" name="ida_escalas" value="<?=$reservaida->escalas?>"  />
<input type="hidden" id="ida_precio" name="ida_precio" value="<?=$reservaida->precio?>"  />
<input type="hidden" id="ida_moneda" name="ida_moneda" value="<?=$reservaida->moneda?>"  />
<input type="hidden" id="ida_hora_salida" name="ida_hora_salida" value="<?=$reservaida->hora_salida?>"  />
<input type="hidden" id="ida_hora_llegada" name="ida_hora_llegada" value="<?=$reservaida->hora_llegada?>"  />
<input type="hidden" id="ida_id_despegar" name="ida_id_despegar" value="<?=$reservaida->id_despegar?>"  />
<input type="hidden" id="ida_numero_vuelo" name="ida_numero_vuelo" value="<?=$reservaida->numero_vuelo?>"  />
<input type="hidden" id="ida_tipo" name="ida_tipo" value="<?=$reservaida->tipo?>"  />
<input type="hidden" id="ida_cantidad_personas" name="ida_cantidad_personas" value="<?=$reservaida->cantidad_personas?>"  />
<input type="hidden" id="ida_clase" name="ida_clase" value="<?=$reservaida->clase?>"  />



<input type="hidden" id="id_evento" name="id_evento" value="<?=$reservaida->id_evento?>"/>
<input type="hidden" id="id_usuario" name="id_usuario" value="<?=$reservaida->usuario->id_usuario?>" />
<input type="hidden" id="cantidad_personas_res" name="cantidad_personas_res" value="<?=$reservaida->cantidad_personas?>" />




<?=form_close();?>
</div>
</div>