<script>	
	$(document).ready(function() {
		$('.eventos_asociados').popover();
		$('.eliminarCentro').click(function (event) {
			if (!confirm('¿Desea eliminar el centro?'))
				event.preventDefault();
		});
	});
</script>

<?php if (isset($hayMensaje))
	if ($hayMensaje):?>
	<div class="alert <?php echo $tipoMensaje;?>">
	<a class="close" data-dismiss="alert">×</a>
	<?php echo $mensaje;?>
	</div>
<?php endif;?>
<form action="<?=site_url('/abmc_centros_de_convenciones/busqueda')?>" class="form-search pull-right">
	<input type="text" class="input-medium search-query" id="busqueda" name="busqueda">
	<button type="submit" class="btn">Buscar</button>
</form>
<?php if ($centros):?>
	<table class="table">
		<thead>
			<tr>
				<th>Nombre
					<a href="<?=site_url('abmc_centros_de_convenciones?busqueda='.$busqueda.'&orden=nombre&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_centros_de_convenciones?busqueda='.$busqueda.'&orden=nombre&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Fotos</th>
				<th>Dirección</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($centros as $centro): ?>
				<tr id="<?php echo 'centro_'.$centro->id_centro;?>">
					<td><?php echo $centro->nombre;?></td>
					<td>
						<?php if (sizeof($centro->fotos) > 0):?>
						<a href="#myModal_<?=$centro->id_centro?>" data-toggle="modal">Ver Fotos</a>
						 
						<div class="modal hide" id="myModal_<?=$centro->id_centro?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Fotos centro <?php echo $centro->nombre;?></h3>
						</div>
						<div class="modal-body">
						<!--<ul class="thumbnails span">-->
						<?php
						foreach ($centro->fotos as $dirFoto){
							//echo '<li class="span2">';
						//	echo '<div class="thumbnail">';
							echo '<img src="'.$dirFoto.'"></img>';
						//	echo '</div>';
						//	echo '</li>';
						}
						?>
						<!--</ul>-->
						</div>
						<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
						</div>
						</div>
						<?php else:?>
						<p>No</p>
						<?php endif;?>
					</td>
					<td><?php echo $centro->direccion_string;?></td>
					<td>
						<a class="eventos_asociados" data-original-title="Eventos asociados" href="#"
						rel="popover" data-placement="bottom" data-html="true" data-content="
						<?php
							if ($centro->eventos){
								foreach ($centro->eventos as $evento){
									echo "<a href='".site_url('abmc_eventos/verEvento/'.$evento->id_evento)."'  style='cursor:hand;'>".$evento->nombre."</a><br/>";
								}
							} else {
								echo 'No hay eventos asociados.';
							}
						?>
						"><i class="icon-eye-open"></i></a>
						&nbsp;
						<a href="<?=site_url('/abmc_centros_de_convenciones/editarCentro/'.$centro->id_centro);?>"><i class="icon-pencil"></i></a>
						&nbsp;
						<a class="eliminarCentro" href="<?php echo site_url('/abmc_centros_de_convenciones/eliminar/'.$centro->id_centro);?>"><i class="icon-remove"></i></a>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="pull-right"><?php echo $paginacion;?></div>
<?php else:?>
	<p>No hay centros cargados o ningún centro coincide con su búsqueda.</p>
<?php endif;?>
<br/><br/>
<a href="<?=site_url('/abmc_centros_de_convenciones/altaCentro');?>" class="btn">Agregar Centro</a>