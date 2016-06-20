
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
		<h6>Nombre</h6><?=$reserva->usuario->get_nombre()?> <?=$reserva->usuario->get_apellido()?>
	</div>
	<div class="span2">
		<h6>DNI </h6><?=$reserva->usuario->id_usuario?>
	</div>
	<div class="span3">
		<h6>Dirección </h6><?=$reserva->usuario->direccion->calle!=''?$reserva->usuario->direccion->calle.$reserva->usuario->direccion->altura:'-'?>
	</div>
	
</div>
<br>
<div class="row">
	
	<div class="span3">
		<h6>Email </h6><?=$reserva->usuario->email?>
	</div>
	<div class="span2">
		<h6>Telefono </h6><?=$reserva->usuario->telefono?>
	</div>
	<div class="span2">
		<h6>Evento en que participa </h6><?=$evento?>
	</div>
</div>
<br><br>
<h4>Hotel</h4>
<div class="row">
	<div class="span3">
		<h6>Nombre</h6><?=$reserva->nombre?>
	</div>

	<div class="span2">
		<h6>Dirección </h6><?=$reserva->direccion?>
	</div>
	
</div>
<br>
<br><br>
<h4>Reserva</h4>
<div class="row">

	<div class="span2">
		<h6>Fecha desde</h6><?=$reserva->fecha_desde?>
	</div>
	<div class="span2">
		<h6>Check-in</h6><?=$reserva->check_in.' hs.'?>
	</div>
	
	<div class="span2">
		<h6>Cantidad de personas</h6><?=$reserva->cantidad_personas?>
	</div>

	
</div>
<br>
<div class="row">

	<div class="span2">
		<h6>Fecha hasta</h6><?=$reserva->fecha_hasta?>
	</div>
	<div class="span2">
		<h6>Check-out</h6><?=$reserva->check_out.'hs.'?>
	</div>
	<div class="span2">
		<h6>Regimen</h6><?=$reserva->regimen?>
	</div>
	
</div>
<br>
<div class="row">

	
	

	
</div>

<div class="row">

<div class="span2">
		<h6>Precio</h6><p class="text-info"><strong><?=$reserva->moneda?> <?=$reserva->precio?></strong></p>
	</div>
	


	
</div>
<br><br>

<?php echo form_open_multipart('abmc_reservas_hoteles/confirmar',array('id' => 'confirmar_form')); echo "\n";?>
<button type="submit" name="buscarHoteles" onclick="confirmarReserva()" class="btn btn-primary" value="1">Confirmar reserva</button>
<button type="submit" name="cancelar"  class="btn" value="1">Cancelar</button>
	


<input type="hidden" id="id_evento" name="id_evento" value="<?=$reserva->id_evento?>" />
<input type="hidden" id="id_usuario" name="id_usuario" value="<?=$reserva->usuario->id_usuario?>" />
<input type="hidden" id="id_hotel"  name="id_hotel" value="<?=$reserva->id_hotel?>" />
<input type="hidden" id="fecha_desde" name="fecha_desde" value="<?=$reserva->fecha_desde?>" />
<input type="hidden" id="fecha_hasta" name="fecha_hasta" value="<?=$reserva->fecha_hasta?>" />
<input type="hidden" id="check_in" name="check_in" value="<?=$reserva->check_in?>" />
<input type="hidden" id="check_out" name="check_out" value="<?=$reserva->check_out?>" />
<input type="hidden" id="cantidad_personas" name="cantidad_personas" value="<?=$reserva->cantidad_personas?>" />
<input type="hidden" id="regimen" name="regimen" value="<?=$reserva->regimen?>" />
<input type="hidden" id="precio" name="precio" value="<?=$reserva->precio?>" />
<input type="hidden" id="moneda" name="moneda" value="<?=$reserva->moneda?>" />
<input type="hidden" id="longitud" name="longitud" value="<?=$reserva->longitud?>" />
<input type="hidden" id="latitud" name="latitud" value="<?=$reserva->latitud?>" />
<input type="hidden" id="nombre" name="nombre" value="<?=$reserva->nombre?>" />
<input type="hidden" id="direccion" name="direccion" value="<?=$reserva->direccion?>" />





<?=form_close();?>
</div>
</div>