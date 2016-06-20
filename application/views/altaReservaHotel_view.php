<?php if ($eventos==false):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error: No se podrá realizar ninguna reserva porque no existen eventos en la base de datos.
	</div>
<?php endif;?>

<script>

	function mostrarDatosHotel(id){
	
		var estrellas='';
		var imagen='<div class="pull-right"><img style="padding:5px;" width=200 height=200 src="http://ar.staticontent.com/search/hoteles/fotos/'+$("#foto"+id).val()+'" /></div><br>';
	
		for(i=0;i<$("#estrellas"+id).val();i++){
			estrellas+='<i class="icon-star"/>&nbsp;';
		}
	
		var datos='<h4>'+$("#nombre"+id).text()+'</h4>'+ estrellas+
		'<br><small>'+$("#direccion"+id).text()+'</small><br>'+imagen+
		$("#descripcion"+id).val()+
		'<h6>Amenities:</h6>'+$("#amenities"+id).val();
		
			$('#datosHotel').html(datos);
		};


	function actualizarMapa(id){
		var mapa='<img id="mapa" name="mapa" border="0" src="https://maps.google.com/maps/api/staticmap?zoom=14' + 
			'&size=512x400&markers=color:red%7C' + 
			$("#latitud"+id).val() + '+' + $("#longitud"+id).val() + '&maptype=roadmap&sensor=false" alt="Mapa" />';
			$('#mapa').html(mapa);
		};
		
		
	function reservar(id){
		
		$("#id_evento_reserva").val($("#eventoOrador").val());
		$("#id_orador_reserva").val($("#orador").val());
		$("#fecha_desde_reserva").val($("#desde_submit").val());
		$("#fecha_hasta_reserva").val($("#hasta_submit").val());
		$("#distribucion_reserva").val($("#distribucion_submit").val());
		
		$("#regimen_reserva").val($("#regimen"+id).val());
		$("#id_hotel_reserva").val($("#idhotel"+id).val());
		$("#precio_reserva").val($("#precio"+id).text());
		$("#moneda_reserva").val($("#moneda"+id).text());
		
		$("#nombre_hotel_reserva").val($("#nombre"+id).text());
		$("#direccion_reserva").val($("#direccion"+id).text());
		
		$("#checkin_reserva").val($("#checkin"+id).val());
		$("#checkout_reserva").val($("#checkout"+id).val());
		
		$("#longitud_reserva").val($("#longitud"+id).val());
		$("#latitud_reserva").val($("#latitud"+id).val());
		
		
		$("#reservar_form").submit();
		
		
	
	}
	
</script>



<?php if ($eventos!=false) {?>
  
<?php if (isset($hayMensaje))
	if ($hayMensaje):?>
	<div class="alert <?php echo $tipoMensaje;?>">
	<a class="close" data-dismiss="alert">×</a>
	<?php echo $mensaje;?>
	</div>
<?php endif;?>


 
<div class="row">
<div class="span8">
<?php echo form_open_multipart('abmc_reservas_hoteles/buscar_hoteles'); echo "\n";?>

<script>
	$(function() {
	$( "#datepicker_desde" ).datepicker();
	$( "#datepicker_hasta" ).datepicker();
	});
</script>



<?php

if (!isset($eventoOrador)) $default =  set_value('eventoOrador'); else $default = $eventoOrador;
$eventoSel = $default;
if (!isset($orador)) $default =  set_value('orador'); else $default = $orador;
$oradorSel = $default;
$errorEvento = form_error('eventoOrador', '<span class="help-inline">', '</span>');
$errorOrador = form_error('orador', '<span class="help-inline">', '</span>');
$errorFechaDesde = form_error('fecha_desde', '<span class="help-inline">', '</span>');
$errorFechaHasta = form_error('fecha_hasta', '<span class="help-inline">', '</span>');
$errorDistribucion = form_error('cantidad_personas', '<span class="help-inline">', '</span>');
$errorRadio = form_error('radio', '<span class="help-inline">', '</span>');
 

	$valorNombre = set_value('nombre','');
	$valorDescripcion = set_value('descripcion','');
	$valorFechaDesde =  set_value('fecha_desde',date('d-m-Y'));
	$valorFechaHasta =  set_value('fecha_hasta',date('d-m-Y'));
	$valorRadio =  set_value('radio',0.25);
	$valorDistribucion =  set_value('cantidad_personas',1);
	


?>



<h4>Información principal</h4><br/>
<div class="row">
<div class="span5">
	<table class = "table">
	<tbody>
	<tr> 
		<td>
		<input type="hidden" id="urlGetOradoresEvento" value="<?= site_url('combos/get_oradores_por_evento')?>" />
		<div class="control-group<?= $errorEvento != '' ? ' error' : ''?>">
				<input type="hidden" name="eventoSel" id="eventoSel" value="<?= $eventoSel?>"/>
				<label class="control-label" for="pais"><h6>Evento <span style="color:red;">*</span></h6></label>
				<div class="controls">
					<select class="input-xlarge" id="eventoOrador" name="eventoOrador">
						<option value="" <?= set_select('eventoOrador','Ninguno')?>>Ninguno</option>
						<?php for ($i=0; $i < count($eventos); $i++) { ?>
							<option <?= $eventos[$i]->id_evento==$eventoSel?'selected':''?> value="<?=$eventos[$i]->id_evento?>"><?= $eventos[$i]->nombre?></option>
							
						<?php } ?>
					</select>
					<?=$errorEvento?>
				</div>
			</div>
			</td>
			<td>
			<div class="control-group<?= $errorOrador != '' ? ' error' : ''?>" >
				<input type="hidden" name="oradorSel" id="oradorSel" value="<?= $oradorSel?>"/>
				<label class="control-label" for="orador"><h6>Orador <span style="color:red;">*</span></h6></label>
				<div class="controls">
					<select class="input-xlarge" id="orador" name="orador" value="<?$orador?>">
					</select>
				<?=$errorOrador?>
				</div>
			</div>
			</td>
		</tr>
		
	
	
		<tr>
			<td>
				<div class="control-group <?= $errorFechaDesde ? 'error' :''?>">
					<label class="control-label"  for="fecha_desde"><h6>Fecha desde <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<div class="input-append date" id="datepicker_desde" data-date="<?=$valorFechaDesde?>" data-date-format="dd-mm-yyyy">
							<input class="span2" size="16" type="text" id="fecha_desde" name="fecha_desde" value="<?=$valorFechaDesde?>">
							<span class="add-on"><i class="icon-th"></i></span>
						</div>
						<?= $errorFechaDesde?>
					</div>
				</div>
			</td>	
			<td>
				<div class="control-group <?= $errorFechaHasta ? 'error' :''?>">
					<label class="control-label"  for="fecha_hasta"><h6>Fecha hasta <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<div class="input-append date" id="datepicker_hasta" data-date="<?=$valorFechaHasta?>" data-date-format="dd-mm-yyyy">
							<input class="span2" size="16" type="text" id="fecha_hasta" name="fecha_hasta" value="<?=$valorFechaHasta?>">
							<span class="add-on"><i class="icon-th"></i></span>
						</div>
						<?= $errorFechaHasta?>
					</div>
				</div>
			</td>	
		</tr>

<tr>
			<td>
				<div class="control-group  <?= $errorDistribucion ? 'error' :''?>">
					<label class="control-label"  for="cantidad_personas"><h6>Cantidad de personas <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<input class="input-mini" type="number" id="cantidad_personas" name="cantidad_personas" value="<?=$valorDistribucion?>" >
						
					</div>
					<?=$errorDistribucion?>
				</div>
			</td>	
			<td>
			<div class="control-group <?= $errorRadio ? 'error' :''?>">
					<label class="control-label"  for="radio"><h6>Radio&nbsp;(km)<span style="color:red;">*</span></h6></label>
					<div class="controls">
						<input class="input-mini" type="text" id="radio" name="radio" value="<?=$valorRadio?>" >
						
					</div>
					<?=$errorRadio?>
				</div>
			</td>
			
			
			
		</tr>		
	</tbody>
	</table>
	</div>
</div>

	<button type="submit" name="buscarHoteles" class="btn btn-primary" value="1">Buscar Hoteles</button>
	<span style="color:red;">(*) Campos obligatorios</span>
	<br><br>
	
	<?php if ($reservas):?>
	<table class="table">
		<thead>
			<tr>
				<th>Hotel </th>
				<th>Dirección</th>
				<th>Precio</th>
				<th>Acciones </th>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach($reservas as $key=>$reserva): ?>
				<tr>
					<td><a href="#hotelModal" data-toggle="modal" id="nombre<?=$key?>" onclick="mostrarDatosHotel(<?=$key?>)"><?php echo $reserva->nombre?></a></td>
					<td><span id="direccion<?=$key?>"><?php echo $reserva->direccion ?></span> <a href="#mapaModal" data-toggle="modal" onclick=actualizarMapa(<?=$key?>) name="actualizarMapa"><i class="icon-map-marker"></i></a></td>
					<td><span id="moneda<?=$key?>"><?php echo $reserva->moneda ?></span><span id="precio<?=$key?>"> <?php echo round($reserva->precio,2); ?></span></td>
					<td><a type="button" onclick="reservar(<?=$key?>)" name="Reservar" class="btn" value="1">Reservar </button></td>
					<input type="hidden" id="longitud<?=$key?>" value="<?=$reserva->longitud?>" />
					<input type="hidden" id="latitud<?=$key?>" value="<?=$reserva->latitud?>" />
					<input type="hidden" id="descripcion<?=$key?>" value="<?=$reserva->descripcion?>" />
					<input type="hidden" id="telefono<?=$key?>" value="<?=$reserva->telefono?>" />
					<input type="hidden" id="amenities<?=$key?>" value="<?=$reserva->amenities?>" />
					<input type="hidden" id="estrellas<?=$key?>" value="<?=$reserva->estrellas?>" />
					<input type="hidden" id="checkin<?=$key?>" value="<?=$reserva->check_in?>" />
					<input type="hidden" id="checkout<?=$key?>" value="<?=$reserva->check_out?>" />
					<input type="hidden" id="foto<?=$key?>" value="<?=$reserva->foto ?>" />
					<input type="hidden" id="idhotel<?=$key?>" value="<?=$reserva->id_hotel?>" />
					<input type="hidden" id="regimen<?=$key?>" value="<?=$reserva->regimen?>" />
					
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	
<br/><br/>
<?php else:?>
	<p>No hay hoteles para mostrar que coincidan con la búsqueda realizada.</p>
<?php endif;?>
	
	
<div class="modal hide" id="mapaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Ubicación</h3>
				</div>
				<div class="modal-body">
				<div id="mapa" name="mapa"></div>
				</div>
				<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
	</div>
</div>

<div class="modal hide" id="hotelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Datos del hotel</h3>
				</div>
				<div class="modal-body">
				<div id="datosHotel" name="datosHotel"></div>
				</div>
				<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
	</div>
</div>


<input type="hidden" id="desde_submit" value="<?=isset($fecha_desde)?$fecha_desde:''?>" />
<input type="hidden" id="hasta_submit" value="<?=isset($fecha_hasta)?$fecha_hasta:''?>" />
<input type="hidden" id="distribucion_submit" value="<?=isset($distribucion)?$distribucion:''?>" />


<?=form_close();?>
</div>

</div>

<?php echo form_open_multipart('abmc_reservas_hoteles/reservar',array('id' => 'reservar_form')); echo "\n";?>

<input type="hidden" id="id_evento_reserva" name="id_evento_reserva" />
<input type="hidden" id="id_orador_reserva" name="id_orador_reserva" />
<input type="hidden" id="fecha_desde_reserva" name="fecha_desde_reserva" />
<input type="hidden" id="fecha_hasta_reserva" name="fecha_hasta_reserva" />

<input type="hidden" id="id_hotel_reserva" name="id_hotel_reserva" />


<input type="hidden" id="distribucion_reserva" name="distribucion_reserva" />
<input type="hidden" id="precio_reserva" name="precio_reserva" />
<input type="hidden" id="moneda_reserva" name="moneda_reserva" />
<input type="hidden" id="regimen_reserva" name="regimen_reserva" />
<input type="hidden" id="longitud_reserva" name="longitud_reserva" />
<input type="hidden" id="latitud_reserva" name="latitud_reserva" />
<input type="hidden" id="nombre_hotel_reserva" name="nombre_hotel_reserva" />
<input type="hidden" id="checkin_reserva" name="checkin_reserva" />
<input type="hidden" id="checkout_reserva" name="checkout_reserva" />
<input type="hidden" id="direccion_reserva" name="direccion_reserva" />



<?=form_close();?>
<?php } ?>

