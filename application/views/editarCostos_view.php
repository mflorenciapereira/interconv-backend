<?php echo form_open('/abmc_costos/ejecutarEdicionCostos'); echo "\n";?>

<?php
$errorAlquilerCentro = form_error('alquiler_centro', '<span class="help-inline">', '</span>');
$errorAlojamientoPasajes = form_error('alojamiento_pasajes', '<span class="help-inline">', '</span>');
$errorPublicidad = form_error('publicidad', '<span class="help-inline">', '</span>');
$errorAlquilerEquip = form_error('alquiler_equip', '<span class="help-inline">', '</span>');

$valorAlquilerCentro = set_value('descripcion',$evento->alquiler_centro);
$valorAlojamientoPasajes = set_value('alojamiento_pasajes',$evento->alojamiento_pasajes);
$valorPublicidad = set_value('publicidad',$evento->publicidad);
$valorAlquilerEquip = set_value('alquiler_equip',$evento->alquiler_equip);

echo '<input type="hidden" id="id_evento" name="id_evento" value="'.$evento->id_evento.'" />';
?>
<div class="control-group <?= $errorAlquilerCentro ? 'error' :''?>">
	<label class="control-label" for="alquiler_centro"><h6>Alquiler del centro</h6></label>
	<div class="controls">
		<input type="text" name="alquiler_centro" id="alquiler_centro" class="input-medium" value="<?=$valorAlquilerCentro?>"/>
		<?= $errorAlquilerCentro?>
	</div>
</div>
<div class="control-group <?= $errorAlojamientoPasajes ? 'error' :''?>">
	<label class="control-label" for="alojamiento_pasajes"><h6>Alojamiento y pasajes de avión</h6></label>
	<div class="controls">
		<input type="text" name="alojamiento_pasajes" id="alojamiento_pasajes" class="input-medium" value="<?=$valorAlojamientoPasajes?>"/>
		<?= $errorAlojamientoPasajes?>
	</div>
</div>
<div class="control-group <?= $errorPublicidad ? 'error' :''?>">
	<label class="control-label" for="publicidad"><h6>Publicidad</h6></label>
	<div class="controls">
		<input type="text" name="publicidad" id="publicidad" class="input-medium" value="<?=$valorPublicidad?>"/>
		<?= $errorPublicidad?>
	</div>
</div>
<div class="control-group <?= $errorAlquilerEquip ? 'error' :''?>">
	<label class="control-label" for="alquiler_equip"><h6>Alquiler de equipamiento</h6></label>
	<div class="controls">
		<input type="text" name="alquiler_equip" id="alquiler_equip" class="input-medium" value="<?=$valorAlquilerEquip?>"/>
		<?= $errorAlquilerEquip?>
	</div>
</div>
<div class="form-actions">
<button type="submit" id="editarCostos" name="editarCostos" value="1" class="btn btn-primary">Guardar</button>
<button type="submit" id="cancelar" name="cancelar" value="1" class="btn">Cancelar</button>
</div>
<?=form_close();?>