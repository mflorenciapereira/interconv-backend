<?php if (!$permisosDir):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error crítico: No se tienen permisos para crear directorios y guardar archivos en ./uploads/fotos_eventos/. No se van a poder subir fotos.
	</div>
<?php endif;?>
<div class="row">
<div class="span8">
<?php echo form_open_multipart($accion); echo "\n";?>

<script>
	$(function() {
	$( "#datepicker_desde" ).datepicker();
	$( "#datepicker_hasta" ).datepicker();
	});
</script>

<?php
$errorNombre = form_error('nombre', '<span class="help-inline">', '</span>');
$errorDescripcion = form_error('descripcion', '<span class="help-inline">', '</span>');
$errorFechaDesde = form_error('fecha_desde', '<span class="help-inline">', '</span>');
$errorFechaHasta = form_error('fecha_hasta', '<span class="help-inline">', '</span>');
$errorCentro = form_error('centros', '<span class="help-inline">', '</span>');
$errorEstado = form_error('estado', '<span class="help-inline">', '</span>');
$errorPrecio = form_error('precio', '<span class="help-inline">', '</span>');
$errorFoto1 = form_error('foto1', '<span class="help-inline">', '</span>');
$errorFoto2 = form_error('foto2', '<span class="help-inline">', '</span>');
$errorFoto3 = form_error('foto3', '<span class="help-inline">', '</span>');
$errorFoto4 = form_error('foto4', '<span class="help-inline">', '</span>');
$errorFoto5 = form_error('foto5', '<span class="help-inline">', '</span>');
if ($tipoAccion == 'editar'){
	$valorNombre = set_value('nombre',$evento->nombre);
	$valorDescripcion = set_value('descripcion',$evento->descripcion);
	$valorFechaDesde = set_value('fecha_desde',$evento->fecha_desde);
	$valorFechaHasta = set_value('fecha_hasta',$evento->fecha_hasta);
	$valorEstado = set_value('estado',$evento->estado);
	$valorPrecio = set_value('precio',$evento->precio);
	$valorFotos = $evento->fotos;
	echo '<input type="hidden" id="id_evento" name="id_evento" value="'.$evento->id_evento.'" />';
} else {
	$valorNombre = set_value('nombre','');
	$valorDescripcion = set_value('descripcion','');
	$valorFechaDesde =  set_value('fecha_desde',date('d-m-Y'));
	$valorFechaHasta =  set_value('fecha_hasta',date('d-m-Y'));
	$valorPrecio =  set_value('precio','0.00');
	$valorEstado = "No publicado";
	$valorFotos = array();
}
?>
<h4>Información principal</h4><br/>
<div class="row">
<div class="span5">
	<div class="control-group <?= $errorNombre ? 'error' :''?>">
		<label class="control-label" for="nombre"><h6>Nombre <span style="color:red;">*</span></h6></label>
		<div class="controls">
			<input type="text" name="nombre" id="nombre" class="input-xlarge" value="<?=$valorNombre?>"/>
			<?= $errorNombre?>
		</div>
	</div>
	<div class="control-group <?= $errorDescripcion ? 'error' :''?>">
		<label class="control-label"  for="descripcion"><h6>Descripci&oacute;n</h6></label>
		<div class="controls">
			<textarea class="input-xlarge" name="descripcion" id="descripcion" rows="3"><?=$valorDescripcion?></textarea>
			<?= $errorDescripcion?>
		</div>
	</div>
	<div class="control-group <?= $errorCentro ? 'error' :''?>">
		<label class="control-label" for="centro"><h6>Centro <span style="color:red;">*</span></h6></label>
		<div class="controls">
			<select class="input-xlarge" id="centros" name="centros">
				<?php for ($i=0; $i < count($centros); $i++) { ?>
				<option value="<?=$centros[$i]->id_centro?>" <?php if ($tipoAccion == 'editar'){ if ($centros[$i]->id_centro == $evento->id_centro) echo 'selected="selected"'; }else{ set_select("centro", $centros[$i]->id_centro);}?>><?= $centros[$i]->nombre?></option>
				<?php } ?>
			</select>
			<?= $errorCentro?>
		</div>
	</div>
</div>
<div class="span3">
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
	<div class="control-group <?= $errorEstado ? 'error' :''?>">
		<label class="control-label" for="estado"><h6>Estado <span style="color:red;">*</span></h6></label>
		<div class="controls">
			<select class="input-xlarge" id="estado" name="estado">
				<option <?php echo set_select('estado','No publicado',$valorEstado == 'No publicado'); ?> value="No publicado">No publicado</option>
				<option <?php echo set_select('estado','No comenzado',$valorEstado == 'No comenzado'); ?> value="No comenzado">No comenzado</option>
				<option <?php echo set_select('estado','En curso',$valorEstado == 'En curso'); ?> value="En curso">En curso</option>
				<option <?php echo set_select('estado','Cancelado',$valorEstado == 'Cancelado');?> value="Cancelado">Cancelado</option>
				<?php if ($tipoAccion == 'editar'):?>
				<option <?php echo set_select('estado','Finalizado',$valorEstado == 'Finalizado');?> value="Finalizado">Finalizado</option>
				<?php endif;?>
			</select>
			<?= $errorEstado?>
		</div>
	</div>
	<div class="control-group <?= $errorPrecio ? 'error' :''?>">
		<label class="control-label" for="precio"><h6>Precio <span style="color:red;">*</span></h6></label>
		<div class="controls">
			<input type="text" name="precio" id="precio" class="input-medium" value="<?=$valorPrecio?>"/>
			<?= $errorPrecio?>
		</div>
	</div>
</div>
</div>
<h4>Fotos</h4><br/>
<div class="row">
<div class="span8">
	<?php if ($tipoAccion == 'editar'):?>
	<ul class="thumbnails">
		<?php foreach($valorFotos as $pathFoto):?>
			<li class="span2">
				<input type="checkbox" name="fotoActual[]" value="<?=substr($pathFoto,strrpos($pathFoto,'/')+1)?>"/> Eliminar
				<div class="thumbnail">
					<a href="<?=$pathFoto?>">
						<img src="<?=$pathFoto?>" alt="">
					</a>
				</div>
			</li>
		<?php endforeach;?>
		<?php
			if (sizeOf($valorFotos) == 0){
				echo '<div class="span4">';
				echo 'No hay fotos cargadas para este evento.';
				echo '</div>';
			}
		?>
	</ul>
	<?php endif;?>
</div>
</div>
<div class="row">
<div class="span3">
	<div class="control-group<?= ($errorFoto1  || $errorFoto2 || $errorFoto3 || $errorFoto4 || $errorFoto5) ? ' error' : ''?>">
	<div class="controls">
		<input type="file" id="foto1" name="foto1" size="20"/>
		<input type="file" class="<?= $errorFoto2 == '' ? 'foto' : ''?>" id="foto2" name="foto2" size="20" />
		<input type="file" class="<?= $errorFoto3 == '' ? 'foto' : ''?>" id="foto3" name="foto3" size="20" />
		<input type="file" class="<?= $errorFoto4 == '' ? 'foto' : ''?>" id="foto4" name="foto4" size="20" />
		<input type="file" class="<?= $errorFoto5 == '' ? 'foto' : ''?>" id="foto5" name="foto5" size="20" />
		<?php
			if ($errorFoto1  || $errorFoto2 || $errorFoto3 || $errorFoto4 || $errorFoto5){
				$error = 'Error en la(s) foto(s) número: ';
				$errorMas = '';
				if ($errorFoto1) {
					$error .= '1 ';
					$errorMas = $errorFoto1;
				}
				if ($errorFoto2){
					$error .= '2 ';
					$errorMas = $errorFoto2;
				}
				if ($errorFoto3){
					$error .= '3 ';
					$errorMas = $errorFoto3;
				}
				if ($errorFoto4){
					$error .= '4 ';
					$errorMas = $errorFoto4;
				}
				if ($errorFoto5){
					$error .= '5';
					$errorMas = $errorFoto5;
				}
				echo '<span style="color:#B94A48;">'.$error.'</span><br/>'.$errorMas;
			}
		?>
	</div>
	</div>
</div>
<div class="span5">
	<p>Formatos permitidos: GIF, JPG/JPEG y PNG. Tamaño máximo: 300KB.</p>
	<p>Cualquier archivo que no cumpla estas características se ignorará.</p>
	<p>Puede ingresar de a 5 imagenes, para ingresar mas edite el evento.</p>
</div>
</div>
<span style="color:red;">(*) Campos obligatorios</span>

<div class="form-actions">		
	<button type="submit" id="botonAltaModifEvento" name="guardar" class="btn btn-primary" value="1">Guardar</button>
	<button type="submit" name="cancelar" class="btn" value="1">Cancelar</button>
</div>
<?=form_close();?>
</div>
</div>