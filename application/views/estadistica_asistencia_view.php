
<script>
$(function() {
	
			$('#evento').chosen();
		});

</script>

<?php if(!($eventos)):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">x</a>
	Error: No se podr&aacute;n obtener estad&iacute;sticas de ning&uacute;n evento porque no hay eventos creados.
	</div>
<?php endif;?>
<?php if($eventos):?>
<?php echo form_open_multipart('estadisticas_controller/obtener_estadisticas_asistencia/'); echo "\n";?>

			<div class="control-group">
				
				<label class="control-label" for="pais"><h6>Evento <span style="color:red;">*</span></h6></label>
				<div class="controls">
					<select class="input-xlarge" id="evento" name="evento">
						<?php for ($i=0; $i < count($eventos); $i++) { ?>
							<option value="<?=$eventos[$i]->id_evento?>"><?= $eventos[$i]->nombre?></option>
							
						<?php } ?>
					</select>
					
				</div>
			</div>
			
			<button type="submit" name="obtenerEstadistica" class="btn btn-primary" value="1">Obtener estad&iacute;stica</button>
<?=form_close();?>
<?php endif;?>

