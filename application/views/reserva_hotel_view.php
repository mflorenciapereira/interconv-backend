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
			
				<div class="span4" >		
				<h3><?php echo  "Datos de la reserva"?></h3>
				
	
				
				<div class="row">
					<div class="span2">
						<h6>Hotel</h6> <?php echo  $reserva->nombre?></h3> 
					</div>
									
				</div>
<br>
				
				<div class="row">
					<div class="span4" >
						<h6>Dirección</h6> <?php echo  $direccion?></h3> 
					</div>									
									
				</div>
				<br>
				
				
				<div class="row">
					<div class="span2">
						<h6>Check-in</h6><?php echo  $reserva->fecha_desde." ".$reserva->check_in?>
					</div>
					<div class="span2">
						<h6>Check-out</h6><?php echo  $reserva->fecha_hasta." ".$reserva->check_out?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="span2">
						<h6>Cantidad de personas</h6><?php echo  $reserva->cantidad_personas?>
					</div>
					<div class="span2">
						<h6>Regimen</h6><?php echo  $reserva->regimen?>
					</div>
					
				</div>
				
				
				<br><br>	
			</div>
			<div class="span4" style="padding:20px;">
			<div class="pull-right" > 
						<div id="mapa" name="mapa"></div>
					</div>
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

		<a href="<?=site_url('/abmc_reservas_hoteles/index');?>" class="btn">Volver</a>
</div>

<?= form_close();?>