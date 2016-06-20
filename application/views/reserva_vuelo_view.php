<?= form_open_multipart('abmc_usuarios/guardar_usuario'); echo "\n";?>

<script>
	 
	 	function actualizarMapa(){
		var mapa='<img id="mapa" name="mapa" border="0" src="https://maps.google.com/maps/api/staticmap?zoom=14' + 
			'&size=400x400&markers=color:red%7C' + 
			$("#latitud").text() + '+' + $("#longitud").text() + '&maptype=roadmap&sensor=false" alt="Mapa" />';
			$('#mapa').html(mapa);
		};
		
		$(document).ready(function() {	
			actualizarMapa();
		
	});
</script>

<?php if (isset($hayMensaje))
	if ($hayMensaje):?>
	<div class="alert <?php echo $tipoMensaje;?>">
	<a class="close" data-dismiss="alert">×</a>
	<?php echo $mensaje;?>
	</div>
<?php endif;?>
<ul class="nav nav-tabs">
	<?php if (!isset($tabActivo)) $tabActivo='reserva';?>
		<li class="<?php  if ($tabActivo == 'reserva') echo 'active'; ?>"><a href="#1" data-toggle="tab">Reserva</a></li>
		<li class="<?php  if ($tabActivo == 'orador') echo 'active'; ?>"><a href="#2" data-toggle="tab">Orador</a></li>
</ul>

<div class="tab-content" style="overflow:inherit;">
	<div class="tab-pane <?php  if ($tabActivo == 'reserva') echo 'active'; ?>" id="1">
		<div class="row show-grid">
			<div class="tab-content" style="overflow:inherit;">
			
				<div class="span8" >		
				<h3><?php echo  "Datos de la reserva"?></h3>
				
	
				<div class="row">
					<div class="span4">
						<h6>Aerolínea</h6><?php echo  $reserva->aerolinea?>
					</div>
									
					<div class="span4" >
						<h6>Número de vuelo</h6> <?php echo  $reserva->numero_vuelo?></h3> 
					</div>									
									
				</div>
				<br>
				<div class="row">
					<div class="span4">
						<h6>Aeropuerto de Salida</h6> <?php echo  $reserva->aerop_salida?></h3> 
					</div>
									
					<div class="span4" >
						<h6>Aeropuerto de Llegada</h6> <?php echo  $reserva->aerop_llegada?></h3> 
					</div>									
									
				</div>
				<br>
				
				<div class="row">
					<div class="span4">
						<h6>Fecha Salida</h6><?php echo  $reserva->hora_salida?>
					</div>
					<div class="span4">
						<h6>Fecha Llegada</h6><?php echo  $reserva->hora_llegada?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="span4">
						<h6>Clase</h6><?php echo  $reserva->clase?>
					</div>
					<div class="span4">
						<h6>Cantidad de personas</h6><?php echo  $reserva->cantidad_personas?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="span4">
					<h6>Escalas</h6><?php echo  $reserva->escalas?>
					</div>
					<div class="span4">
					<h6>Precio (total)</h6><?php echo  $reserva->moneda.' '.$reserva->precio?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="span4">
						<h6>Ida/Vuelta</h6><?php echo  $reserva->tipo?>
					</div>
					<div class="span4">
						<h6>Aerolinea</h6><?php echo  $reserva->aerolinea?>
					</div>
				</div>
				
				<br><br>	
			</div>
		</div>
	</div>
	</div>


<div class="tab-pane <? if ($tabActivo == 'orador') echo 'active'; ?>" id="2">
		<div class="row show-grid">
			<div class="tab-content" style="overflow:inherit;">
				<div class="span8">	
				<h3>Datos del Orador</h3>
				<div class="row">
					<div class="span3">
						<h6>Nombre</h6><?php echo $reserva->usuario->apellido.", ".$reserva->usuario->nombre?>
					</div>
					<div class="span2">
						<h6>DNI </h6><?php echo $reserva->usuario->id_usuario?>
					</div>
					<div class="span3">
						<h6>Dirección </h6><?= $reserva->usuario->direccion->calle!=null?$reserva->usuario->direccion->calle." ".$reserva->usuario->direccion->altura:'-';?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="span3">
						<h6>Telefono </h6><?= $reserva->usuario->telefono;?>
					</div>
					<div class="span2">
						<h6>Email </h6><?= $reserva->usuario->email;?>
					</div>
					<div class="span3">
						<h6>Evento en que participa </h6><?= $evento;?>
					</div>
				</div>
				</div>
				<br>
			</div>
		</div>
	</div>
</div>


<span class="hidden" id="latitud"><?=$reserva->latitud?></span>
<span class="hidden" id="longitud"><?=$reserva->longitud?></span>

<div class="form-actions">	

		<a href="<?=site_url('/abmc_reservas_vuelos/index');?>" class="btn">Volver</a>
</div>

<?= form_close();?>