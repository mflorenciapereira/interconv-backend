<?php if ($noHayEventos):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error: No se podr&aacute; dar de alta ninguna charla porque no existen eventos en la base de datos.
	</div>
<?php endif;?>
<?php if (!$permisosDir):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error crítico: No se tienen permisos para crear directorios y guardar archivos en ./uploads/fotos_charlas/. No se van a poder subir fotos.
	</div>
<?php endif;?>
<?php if (!$noHayEventos){?>

<?php
$tabActivo='principal';
if (!isset($charla)) $default =  set_value('id_evento'); else $default = $charla->id_evento;
$eventoSel = $default;
if (!isset($charla)) $default =  set_value('id_charla'); else $default = $charla->id_charla;
$idcharla = $default;
$oradorSel=-1;
$errorNombre = form_error('nombre');
$errorEvento = form_error('id_evento');
$errorHoraDesde = form_error('hora_desde');
$errorHoraHasta = form_error('hora_hasta');
$errorFecha = form_error('fecha');
$errorCapacidad = form_error('capacidad');
$errorFoto1 = form_error('foto1', '<span class="help-inline">', '</span>');
if (isset($charla)) $pathFoto = $charla->foto; else $pathFoto = '';
?>


<script>
	var oradores = [];
	
	function asignarOrador(){
		idAsignar=$("select#orador").val();
		idCharla = "<?php echo $idcharla;?>";
		if (idCharla == ''){
			if ($.inArray(idAsignar, oradores) == -1){
				oradores.push(idAsignar);
				getOradoresAsociados();
			} else {
				alert('El orador seleccionado ya fue asignado a la charla.');
			}
		} else {
			$.ajax({
				url: "<?php echo site_url().'/abmc_charlas/asignar_charla?idcharla='.$idcharla ;?>",
				type:'POST',
				dataType: 'json',
				data: "idusuario="+idAsignar,
				success: function(output_string){
					getOradoresAsociados();
					},
				error: function(){
					alert('El orador seleccionado ya fue asignado a la charla.');
				}
			}); 
		}
	};

	function getOradoresAsociados(){
		idCharla = "<?php echo $idcharla;?>";
		if (idCharla == ''){
			length = oradores.length;
			if (length == 0){
				$("#resultado").html('No hay oradores asignados.');
			} else {
				html = '<table class="table"><thead><tr><th>DNI</th><th>Nombre</th><th>Especialidad</th><th>Eliminar</th></tr></thead><tbody>';
				$.each(oradores,function(index,value){
					$.ajax({
						url: "<?php echo site_url().'/abmc_charlas/get_orador';?>",
						type:'POST',
						dataType: 'json',
						async: false,
						data: "idAsignar="+value,
						success: function(output_string){
							html += output_string;
						},
						error: function(){
							alert('El orador seleccionado ya fue asignado a la charla.');
						}
					}); 
				});			
				$("#resultado").html(html + '</tbody></table>');
			}
		} else {
			$.ajax({
				url: "<?php echo site_url().'/abmc_charlas/get_oradores_por_charla?idcharla='.$idcharla ;?>",
				type:'POST',
				dataType: 'json',
				success: function(output_string){
						$("#resultado").html(output_string);
					},
				error: function(){
				alert('No hay oradores en la base de datos.');
				}
			}); 
		}		
	};
	
	function eliminarAsociacion(idusuario){
		idCharla = "<?php echo $idcharla;?>";
		if (idCharla == ''){
			oradores = jQuery.grep(oradores, function(value) {
			  return value != idusuario;
			});
			getOradoresAsociados();
		} else {
			$.ajax({
				url: "<?php echo site_url().'/abmc_charlas/eliminar_asociacion_charla?idcharla='.$idcharla ;?>",
				type:'POST',
				dataType: 'json',
				data: "idusuario="+idusuario,
				success: function(output_string){
					getOradoresAsociados();
				} 
			}); 
		}
	};

    $(function() {
		$( "#datepicker" ).datepicker();
		getOradoresAsociados();
    });
</script>


<?php if (!isset($charla)) $id =  set_value('id_charla'); else $id = $charla->id_charla;?>
<?php echo $id!=''?form_open_multipart('abmc_charlas/do_editar_charla'):form_open_multipart('abmc_charlas/do_alta_charla');?>

<ul class="nav nav-tabs">
	<?php if (!isset($tabActivo)) $tabActivo='principal';?>
	<li class="<? if ($tabActivo == 'principal') echo 'active'; ?>"><a href="#1" data-toggle="tab">Principal<?= $errorNombre|| $errorCapacidad || $errorEvento? ' (contiene errores)' : ''?></a></li>
	<li class="<? if ($tabActivo == 'oradores') echo 'active'; ?>"><a href="#2" data-toggle="tab">Oradores</a></li>
	<li class="<? if ($tabActivo == 'foto') echo 'active'; ?>"><a href="#3" data-toggle="tab">Foto</a></li>
</ul>

<div class="tab-content" style="overflow:inherit;">
<div class="tab-pane <? if ($tabActivo == 'principal') echo 'active'; ?>" id="1">
	<div class="row show-grid">
			<div class="span5">
				<div class="control-group<?= $errorNombre != '' ? ' error' : ''?>">
					<label class="control-label" for="nombre"><h6>Nombre <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('nombre'); else $default = $charla->nombre;?>
						<?php echo form_input(array('name'=>"nombre",'id'=>"nombre",'class'=>"input-xlarge",'value'=>$default));?>
						<small><span style="color:red;"><?php echo $errorNombre ;?></span></small></label></td>
					</div>
				</div>
					
				<div class="control-group">
					<label class="control-label"  for="descripcion"><h6>Descripci&oacute;n</h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('descripcion'); else $default = $charla->descripcion;?>
						<?php echo form_textarea(array('name'=>"descripcion",'id'=>"descripcion",'class'=>"input-large",'rows'=>5,'value'=>$default));?>
						
					</div>
				</div>

				<div class="control-group">
					<input type="hidden" name="eventoSel" id="eventoSel" value="<?= $eventoSel?>" />
					<label class="control-label" for="evento"><h6>Evento <span style="color:red;">*</span></h6></label>

					<div class="controls">
					<input type="hidden" id="urlGetEventos" value="<?= site_url('combos/get_eventos')?>" />
						<select class="input-xlarge" id="eventoCharla" name="id_evento">
							<option  value="" <?= set_select('id_evento','')?>>Ninguno</option>
							<?php for ($i=0; $i < count($eventos); $i++) { ?>
							<option <?php echo set_select("id_evento", $eventos[$i]->id_evento,$eventos[$i]->id_evento==$eventoSel) ;?> value="<?= $eventos[$i]->id_evento?>"><?= $eventos[$i]->nombre?></option>
							<?php } ?>
						</select>
					</div>
					<small><span style="color:red;"><?php echo $errorEvento ;?></span></small>
				</div>
					
				<div class="control-group">
					<label class="control-label"  for="sala"><h6>Sala</h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('sala'); else $default = $charla->sala;?>
						<?php echo form_input(array('name'=>"sala",'id'=>"sala",'class'=>"input-xlarge",'value'=>$default));?>
													
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label"  for="capacidad"><h6>Capacidad</h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('capacidad'); else $default = $charla->capacidad;?>
						<?php echo form_input(array('name'=>"capacidad",'id'=>"capacidad",'class'=>"input-small",'value'=>$default));?>
						<small><span style="color:red;"><?php echo $errorCapacidad ;?></span></small></label></td>							
					</div>
				</div>
				
			</div>
			<div class="span3">
				<div class="control-group<?= $errorFecha != '' ? ' error' : ''?>">
					<label class="control-label"  for="fecha"><h6>Fecha <span style="color:red;">*</span></h6></label>
					<?php if (!isset($charla)) $default =  set_value('fecha'); else $default = $charla->fecha;?>
					<div class="input-append date" id="datepicker" data-date="<?php echo date('d-m-Y');?>" data-date-format="dd-mm-yyyy">
						
						<?php echo form_input(array('name'=>"fecha",'id'=>"fecha",'class'=>"span2",'size'=>16,'value'=>$default));?>
						<span class="add-on"><i class="icon-th"></i></span>
					</div>
					<small><span style="color:red;"><?php echo $errorFecha ;?></span></small></label></td>
				</div>
				
				<div class="control-group<?= $errorHoraDesde != '' ? ' error' : ''?>">
					<label class="control-label"  for="hora_desde"><h6>Hora desde <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('hora_desde'); else $default = $charla->hora_desde;?>
						<?php echo form_input(array('name'=>"hora_desde",'id'=>"hora_desde",'class'=>"input-small",'value'=>$default));?>
					</div>
					<small><span style="color:red;"><?php echo $errorHoraDesde ;?></span></small></label></td>
				</div>
				
				<div class="control-group<?= $errorHoraHasta != '' ? ' error' : ''?>">
					<label class="control-label"  for="hora_hasta"><h6>Hora hasta <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('hora_hasta'); else $default = $charla->hora_hasta;?>
						<?php echo form_input(array('name'=>"hora_hasta",'id'=>"hora_hasta",'class'=>"input-small",'value'=>$default));?>
					</div>
					<small><span style="color:red;"><?php echo $errorHoraHasta ;?></span></small></label></td>
				</div>
				
				<div class="control-group">
					<label class="control-label"  for="registrados"><h6>Registrados</h6></label>
						<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('registrados'); else $default = $charla->registrados;?>
						<?php echo form_input(array('name'=>"registrados",'id'=>"registrados",'class'=>"input-small",'value'=>$default,'readonly'    => 'readonly'));?>
						</div>
				</div>
				<div class="control-group">
					<label class="control-label"   for="confirmados"><h6>Confirmados</h6></label>
					<div class="controls">
						<?php if (!isset($charla)) $default =  set_value('confirmados'); else $default = $charla->confirmados;?>
						<?php echo form_input(array('name'=>"confirmados",'id'=>"confirmados",'class'=>"input-small",'value'=>$default, 'readonly' => 'readonly'));?>
					</div>
				</div>
				
				

			</div>
		</div>
	</div>


<div class="tab-pane <? if ($tabActivo == 'oradores') echo 'active'; ?>" id="2">
	<div class="row show-grid">
			<div class="span5">
			<span class="hidden">3</span>
			<div class="control-group">
					<input type="hidden" name="oradorSel" id="oradorSel" />
					<label class="control-label" for="orador"><h6>Orador</span></h6></label>

					<div class="controls">
					<input type="hidden" id="urlGetOradores" value="<?= site_url('combos/get_oradores')?>" />
					<?php if ($oradores):?>
					<select class="input-xlarge" id="orador" name="id_usuario">
					
							<?php for ($i=0; $i < count($oradores); $i++) { ?>
								<option <?php echo set_select("id_usuario", $oradores[$i]->usuario->id_usuario,$oradores[$i]->usuario->id_usuario==$oradorSel) ;?> value="<?= $oradores[$i]->usuario->id_usuario?>"><?= $oradores[$i]->usuario->id_usuario.' - '.$oradores[$i]->usuario->apellido.', '.$oradores[$i]->usuario->nombre?></option>
							<?php } ?>
					</select>
					<?php else:?>
					No hay oradores disponibles.
					<?php endif;?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" value="Asignar" class="btn" valign="top" onclick="asignarOrador()">
					</div>
			</div>
			<div id="resultado">
			</div>
		</div>
	</div>
</div>
<div class="tab-pane <?php  if ($tabActivo == 'fotos') echo 'active'; ?>" id="3">
	<label><h6>Foto Actual</h6></label>
	<input type="hidden" id="fotoActual" name="fotoActual" value="<?=$pathFoto?>"/>
	<ul class="thumbnails">
		<?php if ($pathFoto != ''){?>
			<li class="span">
				<div class="thumbnail">
					<a href="<?=$pathFoto?>">
						<img src="<?=$pathFoto?>" alt="">
					</a>
				</div>
			</li>
		<?php } else
			{
				echo '<div class="span4">';
				echo 'No hay foto cargada.';
				echo '</div>';
			}
		?>
	</ul>
	<h6>Modificar foto</h6>
	<table>
		<tr>
			<td width='40'>
				<div class="control-group<?= ($errorFoto1) ? ' error' : ''?>">
				<div class="controls">
					<input type="file" id="foto1" name="foto1" size="20"/>
						<?php
						if ($errorFoto1){
							$error = 'Error en la foto';
						}
					?>
				</div>
				</div>
			</td></tr>
			<tr><td>
				<p>Formatos permitidos: GIF, JPG/JPEG y PNG. Tamaño máximo: 300KB.</p>
				<p>Cualquier archivo que no cumpla estas características se ignorará.</p>
			</td>
		</tr>
	</table>
</div>
</div>
<?php echo form_hidden('id_charla',$id);?>

<span style="color:red;">(*) Campos obligatorios</span>

<div class="form-actions">		
	<button type="submit" id="botonModificarAnuncio" name="guardar" class="btn btn-primary" value="1">Guardar</button>
	<button class="btn" name="cancelar" value="1">Cancelar</button>
</div>
<?php form_close();?>

<?php } ?>