<?php if ($eventos==false):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error: No se podrá realizar ninguna reserva porque no existen eventos en la base de datos.
	</div>
<?php endif;?>

<script>

function deshabilitar(id){
    $("#"+id+"Auto").attr("readonly", "readonly");
};

function habilitar(id){
    $("#"+id+"Auto").removeAttr("readonly");
	$("#"+id+"Auto").val('');
	$("#"+id).val('');
};

	function cargarDatos(tipo,id){
	
	
		$("#"+tipo+'_aerop_salida').val($("#aerop_salida"+id).text());
		$("#"+tipo+'_aerop_llegada').val($("#aerop_llegada"+id).text());
		$("#"+tipo+'_codigo_salida').val($("#codigo_salida"+id).val());
		$("#"+tipo+'_codigo_llegada').val($("#codigo_llegada"+id).val());
		$("#"+tipo+'_aerolinea').val($("#aerolinea"+id).text());
		$("#"+tipo+'_clase').val($("#clase"+id).text());
		$("#"+tipo+'_escalas').val($("#escalas"+id).text());
		$("#"+tipo+'_precio').val($("#precio"+id).val());
		$("#"+tipo+'_moneda').val($("#moneda"+id).val());
		$("#"+tipo+'_hora_salida').val($("#hora_salida"+id).val());
		$("#"+tipo+'_hora_llegada').val($("#hora_llegada"+id).val());
		$("#"+tipo+'_id_despegar').val($("#id_despegar"+id).val());
		$("#"+tipo+'_numero_vuelo').val($("#numero_vuelo"+id).val());
		$("#"+tipo+'_tipo').val($("#tipo"+id).val());
		
	
	};
	
	
	function idaVuelta(id){
	
		var idsig=id+1;
		if($("#id_despegar"+idsig).val()==undefined) return false;
		return $("#id_despegar"+id).val()==$("#id_despegar"+idsig).val();
	
	};
		
		
	function reservar(id){
	
	
		
		
		
		$("#id_evento").val($("#eventoOradorHotel").val());
		$("#id_usuario").val($("#oradorHotel").val());
		$("#desde").val($("#desde_submit").val());
		$("#hasta").val($("#hasta_submit").val());
		$("#cantidad_personas_res").val($("#distribucion_submit").val());
		
		cargarDatos("ida",id);
		var idsig=id+1;
		if(idaVuelta(id)) cargarDatos("vuelta",idsig);
		
		
		$("#reservar_form").submit();
		
		
	
	}
	
	function completarDireccionEvento(id){
		if(mapaDireccionesEventos[id]!=undefined)
			$( "#direccionEvento" ).html(mapaDireccionesEventos[id]);
		else
			$( "#direccionEvento" ).html('');
	}
	
	function completarDireccionOrador(id){
		if(mapaDireccionesOradores[id]!=undefined)
			$( "#direccionOrador" ).html(mapaDireccionesOradores[id]);
		else
			$( "#direccionOrador" ).html('');
	}
	
	function actualizarReservas(idevento,idusuario){
		if(idevento==undefined) return;
		if(idusuario==undefined) return;
	
		$.ajax({
				url: "<?php echo site_url().'/abmc_reservas_vuelos/obtener_reservas_asociadas' ;?>",
				type:'POST',
				dataType: 'json',
				data: {
					evento: idevento,
					usuario: idusuario
				},
				success: function(output_string){
					$("#reservasHoteles").html(output_string);
					},
				error: function(request, error){
					console.error("ERROR");
				}
			}); 
	
	}
	
	$(function() {
		$( "#datepicker_desde" ).datepicker();
		$( "#datepicker_hasta" ).datepicker();
		
		mapaDireccionesEventos=<?=$mapaDireccionesEventos?>;
		mapaDireccionesOradores=<?=$mapaDireccionesOradores?>;
		
		
		success_function_oradoresEvento = function(oradores) {
				$.each(oradores, function(key, value) {
				var nombre = value.usuario.nombre + " " + value.usuario.apellido;
				if (value.id_usuario != "" && nombre!="") {
					$('#oradorHotel')
						.append($("<option></option>")
						.attr("value",value.id_usuario)
						.text(nombre));
				}
				});

			orador_selected = $('#oradorSel').val();
			firstOrador = null;
			if (orador_selected != null) {
				firstOrador = orador_selected;
				$('#oradorSel').val('');
			} else {
				firstOrador = $('#oradorHotel option:first').val();
			}
	
			$('#oradorHotel').removeAttr("disabled").val(firstOrador).change();
	
			$('#oradorHotel').trigger("liszt:updated");
		};

		
		
		
		function get_oradores_evento(id, successOradoresEvento, errorOradoresEvento) {
		
			$.ajax({
				url: $('#urlGetOradoresEvento').val(),
				data: {
					'evento': id
				},
				success: function(data) {
					successOradoresEvento(eval(data));
				},
				error: function() {
					errorOradoresEvento();
				}
			});
		}
		
		
		$("#eventoOradorHotel").chosen().change(function () {
		var idAsignar=$("#eventoOradorHotel option:selected").val();
		completarDireccionEvento(idAsignar);
		
		$('#eventoOradorHotel option:selected').each(function() {
			$('#oradorHotel').empty();
			var oradores = get_oradores_evento(idAsignar,
				success_function_oradoresEvento,
				function() {
					console.error('error buscando los oradores');
				}
			);
		});
		}).change();
	
		$("#oradorHotel").trigger("liszt:updated");
		
		
					
		
		$("#oradorHotel").chosen().change(function () {
			var idorador=$("#oradorHotel option:selected").val();
			var idevento=$("#eventoOradorHotel option:selected").val();
			completarDireccionOrador(idorador);
			actualizarReservas(idevento,idorador);
		}).change();
		
		//$("#origen").chosen();
		//$("#destino").chosen();
		
		
		var acOptions = {
    minChars: 3,
    max: 100,
    dataType: 'json', // this parameter is currently unused
    extraParams: {
        format: 'json' // pass the required context to the Zend Controller
    },
    parse:function(data) {
                       return $.map(data, function(item) {
                               return {
                                       data: item,
                                       value: item.value,
                                       result: item.label+' ('+item.value+')'
                               }
                       });
               },
    formatItem: function(item) {
        return item.label;
    }
};
		
		 $('#origenAuto')
        .autocomplete('<?php echo site_url().'/abmc_reservas_vuelos/get_ciudades' ;?>', acOptions)
		.result(function(event, item) {
			$('#origen').val(item.value);
			deshabilitar('origen');
			
        });
		
		 $('#destinoAuto')
        .autocomplete('<?php echo site_url().'/abmc_reservas_vuelos/get_ciudades' ;?>', acOptions)
		.result(function(event, item) {
			$('#destino').val(item.value);
			deshabilitar('destino');
        });
		
		
		
	
		
	});
	
	
	
	
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
<?php echo form_open_multipart('abmc_reservas_vuelos/buscar_vuelos'); echo "\n";?>





<?php

if (!isset($eventoOradorHotel)) $default =  set_value('eventoOradorHotel'); else $default = $eventoOradorHotel;
$eventoSel = $default;
if (!isset($oradorHotel)) $default =  set_value('oradorHotel'); else $default = $oradorHotel;
$oradorSel = $default;
if (!isset($origen)) $default =  set_value('origen'); else $default = $origen;
$origenSel = $default;
if (!isset($destino)) $default =  set_value('destino'); else $default = $destino;
$destinoSel = $default;

$errorEvento = form_error('eventoOradorHotel', '<span class="help-inline">', '</span>');
$errorOrador = form_error('oradorHotel', '<span class="help-inline">', '</span>');
$errorOrigen = form_error('origen', '<span class="help-inline">', '</span>');
$errorDestino = form_error('destino', '<span class="help-inline">', '</span>');
$errorFechaDesde = form_error('fecha_desde', '<span class="help-inline">', '</span>');
$errorFechaHasta = form_error('fecha_hasta', '<span class="help-inline">', '</span>');
$errorDistribucion = form_error('cantidad_personas', '<span class="help-inline">', '</span>');
$errorRadio = form_error('radio', '<span class="help-inline">', '</span>');
 
	
	
	$valorNombre = set_value('nombre','');
	$valorDescripcion = set_value('descripcion','');
	if (!isset($fecha_desde)) $default =  set_value('fecha_desde',date('d-m-Y')); else $default = $fecha_desde;
	$valorFechaDesde =  $default;
	if (!isset($fecha_hasta)) $default =  set_value('fecha_hasta',date('d-m-Y')); else $default = $fecha_hasta;
	$valorFechaHasta =  $default;
	
	
	$valorOrigenAuto=  set_value('origenAuto');
	$valorDestinoAuto=  set_value('destinoAuto');
	
	$valorOrigen=  set_value('origen');
	$valorDestino=  set_value('destino');
	
	$valorDistribucion =  set_value('cantidad_personas',1);
	
	


?>



<h4>Información principal</h4><br/>
<div class="row">
<div class="span8">
	<table class = "table">
	<tbody>
		<tr>
			<td colspan="2">
			<h6>Reservas de hotel asociadas:</h6>
			<div id="reservasHoteles"><span style="font-size : smaller;">No hay reservas de hotel asociadas.</span></div>
			</td>
		
		</tr>
	<tr> 
		<td>
		<input type="hidden" id="urlGetOradoresEvento" value="<?= site_url('combos/get_oradores_por_evento')?>" />
		<div class="control-group<?= $errorEvento != '' ? ' error' : ''?>">
				<input type="hidden" name="eventoSel" id="eventoSel" value="<?= $eventoSel?>"/>
				<label class="control-label" for="pais"><h6>Evento <span style="color:red;">*</span></h6></label>
				<div class="controls">
					<select class="input-xlarge" id="eventoOradorHotel" name="eventoOradorHotel">
						<option value="" <?= set_select('eventoOradorHotel','Ninguno')?>>Ninguno</option>
						<?php for ($i=0; $i < count($eventos); $i++) { ?>
							<option <?= $eventos[$i]->id_evento==$eventoSel?'selected':''?> value="<?=$eventos[$i]->id_evento?>"><?= $eventos[$i]->nombre?></option>
							
						<?php } ?>
					</select>
					<?=$errorEvento?><br>
					<small><span id="direccionEvento"></span></small>
				</div>
			</div>
			</td>
			<td>
			<div class="control-group<?= $errorOrador != '' ? ' error' : ''?>" >
				<input type="hidden" name="oradorSel" id="oradorSel" value="<?= $oradorSel?>"/>
				<label class="control-label" for="orador"><h6>Orador <span style="color:red;">*</span></h6></label>
				<div class="controls">
					<select class="input-xlarge" id="oradorHotel" name="oradorHotel" value="<?$orador?>">
					</select>
				<?=$errorOrador?><br>
				<small><span id="direccionOrador"></span></small>
				</div>
			</div>
			</td>
		</tr>
		
		
	
		
	
	
		<tr>
			<td>
				<div class="control-group <?= $errorFechaDesde ? 'error' :''?>">
					<label class="control-label"  for="fecha_desde"><h6>Fecha ida <span style="color:red;">*</span></h6></label>
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
				<div class="control-group">
					<label class="control-label"  for="fecha_hasta"><h6>Fecha regreso </h6></label>
					<div class="controls">
						<div class="input-append date" id="datepicker_hasta" data-date="<?=$valorFechaHasta?>" data-date-format="dd-mm-yyyy">
							<input class="span2" size="16" type="text" id="fecha_hasta" name="fecha_hasta" value="<?=$valorFechaHasta?>">
							<span class="add-on"><i class="icon-th"></i></span>
						</div>
						
					</div>
				</div>
			</td>	
		</tr>
		
		
		
		
		<tr>
			<td>
				<div class="control-group  <?= $errorOrigen ? 'error' :''?>">
					<label class="control-label"  for="origen"><h6>Origen <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<input class="input-xlarge" id="origenAuto" onclick="habilitar('origen')" name="origenAuto" value="<?=$valorOrigenAuto?>"  >
						<input type="hidden" id="origen" name="origen" value="<?=$valorOrigen?>"  />
					</div>
					<?=$errorOrigen?>
				</div>
			</td>	
			<td>
				<div class="control-group  <?= $errorDestino ? 'error' :''?>">
					<label class="control-label"  for="destino"><h6>Destino <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<input class="input-xlarge" id="destinoAuto" onclick="habilitar('destino')" name="destinoAuto" value="<?=$valorDestinoAuto?>"  >
						<input type="hidden" id="destino" name="destino"  value="<?=$valorDestino?>"/>
					</div>
					<?=$errorDestino?>
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
			&nbsp;
			</td>
			
			
			
		</tr>		
	</tbody>
	</table>
	</div>
</div>

	<button type="submit" name="buscarVuelos" class="btn btn-primary" value="1">Buscar Vuelos</button>
	<span style="color:red;">(*) Campos obligatorios</span>
	<br><br>
	
	<?php if ($reservas):?>
	<table class="table">
		<thead>
			<tr>
				<th>Aeropuerto origen </th>
				<th>Aeropuerto destino</th>
				<th>Aerolinea</th>
				<th>Clase</th>
				<th>Escalas </th>
				<th>Precio </th>
				<th></th>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach($reservas as $key=>$reserva): ?>
				<tr>
					<td><span id="aerop_salida<?=$key?>"><?php echo $reserva->aerop_salida?></span></td>
					<td><span id="aerop_llegada<?=$key?>"><?php echo $reserva->aerop_llegada?></span></td>
					<td><span id="aerolinea<?=$key?>"><?php echo $reserva->aerolinea?></span></td>
					<td><span id="clase<?=$key?>"><?php echo $reserva->clase?></span></td>
					<td><span id="escalas<?=$key?>"><?php echo $reserva->escalas==0?'NO':'SI'?></span></td>
				<?php if (isset($reservas[$key+1])&&($reservas[$key+1]->id_despegar==$reservas[$key]->id_despegar)):?>
					<td rowspan="2"><?php echo $reserva->moneda.' '.$reserva->precio?></td>
					<td rowspan="2"><a type="button" onclick="reservar(<?=$key?>)" name="Reservar" class="btn" value="1">Reservar </a></td>
					
				<?php elseif((!isset($reservas[$key-1]))||($reservas[$key-1]->id_despegar!=$reservas[$key]->id_despegar)):?>
					<td ><?php echo $reserva->moneda.' '.$reserva->precio?></td>
					<td ><a type="button" onclick="reservar(<?=$key?>)" name="Reservar" class="btn" value="1">Reservar </a></td>
				<?php endif;?>
				
				<input type="hidden" id="precio<?=$key?>" value="<?=$reserva->precio?>" />
				<input type="hidden" id="moneda<?=$key?>" value="<?=$reserva->moneda?>" />
				<input type="hidden" id="hora_salida<?=$key?>" value="<?=$reserva->hora_salida?>" />
				<input type="hidden" id="hora_llegada<?=$key?>" value="<?=$reserva->hora_llegada?>" />
				<input type="hidden" id="id_despegar<?=$key?>" value="<?=$reserva->id_despegar?>" />
				<input type="hidden" id="numero_vuelo<?=$key?>" value="<?=$reserva->numero_vuelo?>" />
				<input type="hidden" id="tipo<?=$key?>" value="<?=$reserva->tipo?>" />
				<input type="hidden" id="codigo_llegada<?=$key?>" value="<?=$reserva->codigo_llegada?>" />
				<input type="hidden" id="codigo_salida<?=$key?>" value="<?=$reserva->codigo_salida?>" />
				
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	
<br/><br/>
<?php else:?>
	<p>No hay vuelos para mostrar que coincidan con la búsqueda realizada.</p>
<?php endif;?>
	


<input type="hidden" id="desde_submit" value="<?=isset($fecha_desde)?$fecha_desde:''?>" />
<input type="hidden" id="hasta_submit" value="<?=isset($fecha_hasta)?$fecha_hasta:''?>" />
<input type="hidden" id="distribucion_submit" value="<?=isset($distribucion)?$distribucion:''?>" />


<?=form_close();?>
</div>

</div>

<?php echo form_open_multipart('abmc_reservas_vuelos/reservar',array('id' => 'reservar_form')); echo "\n";?>




<input type="hidden" id="ida_aerop_salida" name="ida_aerop_salida" />
<input type="hidden" id="ida_aerop_llegada" name="ida_aerop_llegada" />
<input type="hidden" id="ida_codigo_salida" name="ida_codigo_salida" />
<input type="hidden" id="ida_codigo_llegada" name="ida_codigo_llegada" />
<input type="hidden" id="ida_aerolinea" name="ida_aerolinea" />
<input type="hidden" id="ida_clase" name="ida_clase" />
<input type="hidden" id="ida_escalas" name="ida_escalas" />
<input type="hidden" id="ida_precio" name="ida_precio" />
<input type="hidden" id="ida_moneda" name="ida_moneda" />
<input type="hidden" id="ida_hora_salida" name="ida_hora_salida" />
<input type="hidden" id="ida_hora_llegada" name="ida_hora_llegada" />
<input type="hidden" id="ida_id_despegar" name="ida_id_despegar" />
<input type="hidden" id="ida_numero_vuelo" name="ida_numero_vuelo" />
<input type="hidden" id="ida_tipo" name="ida_tipo" />

<input type="hidden" id="vuelta_aerop_salida" name="vuelta_aerop_salida" />
<input type="hidden" id="vuelta_aerop_llegada" name="vuelta_aerop_llegada" />
<input type="hidden" id="vuelta_codigo_salida" name="vuelta_codigo_salida" />
<input type="hidden" id="vuelta_codigo_llegada" name="vuelta_codigo_llegada" />
<input type="hidden" id="vuelta_aerolinea" name="vuelta_aerolinea" />
<input type="hidden" id="vuelta_clase" name="vuelta_clase" />
<input type="hidden" id="vuelta_escalas" name="vuelta_escalas" />
<input type="hidden" id="vuelta_precio" name="vuelta_precio" />
<input type="hidden" id="vuelta_moneda" name="vuelta_moneda" />
<input type="hidden" id="vuelta_hora_salida" name="vuelta_hora_salida" />
<input type="hidden" id="vuelta_hora_llegada" name="vuelta_hora_llegada" />
<input type="hidden" id="vuelta_id_despegar" name="vuelta_id_despegar" />
<input type="hidden" id="vuelta_numero_vuelo" name="vuelta_numero_vuelo" />
<input type="hidden" id="vuelta_tipo" name="vuelta_tipo" />


<input type="hidden" id="id_evento" name="id_evento" />
<input type="hidden" id="id_usuario" name="id_usuario" />
<input type="hidden" id="desde" name="desde" />
<input type="hidden" id="hasta" name="hasta" />
<input type="hidden" id="cantidad_personas_res" name="cantidad_personas_res" />



<?=form_close();?>

<?php } ?>