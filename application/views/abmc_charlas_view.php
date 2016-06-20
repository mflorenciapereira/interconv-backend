
<script>
      
	 $(document).ready(function() {
		$(".eliminarCharla").click(function (event) {
			if (!confirm('¿Desea eliminar la charla? La acción no puede deshacerse y eliminará las asociaciones entre la charla seleccionada y los oradores.'))
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

<form action="<?=site_url('abmc_charlas/busqueda')?>" class="form-search pull-right">
	<input id="busqueda" name="q" type="text" class="input-medium search-query">
	<button type="submit" class="btn">Buscar</button>
</form>
<?php if ($charlas):?>
	<table class="table">
		<thead>
			<tr>
				<th>Nombre
				<a href="<?=site_url('abmc_charlas?q='.$busqueda.'&columna=nombre&orden=asc')?>"><i class="icon-chevron-up"></i></a>
				<a href="<?=site_url('abmc_charlas?q='.$busqueda.'&columna=nombre&orden=desc')?>"><i class="icon-chevron-down"></i></a></th>
				<th>Fecha</th>
				<th>Desde</i></th>
				<th>Hasta </i></th>
				<th>Evento</i></th>
				<th>Registrados</i></th>
				<th>Contiene multimedia</i></th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($charlas as $charla): ?>
				<tr>
					<td width="150"><?php echo $charla->nombre?></td>
					<td><?php echo $charla->fecha?></td>
					<td><?php echo $charla->hora_desde?></td>
					<td><?php echo $charla->hora_hasta?></td>
					<td width="200"><?php  echo $charla->nombre_evento?></td>
					<td><?php echo $charla->registrados?></td>
					<td><?php echo $charla->contiene_multimedia?'Sí':'No'?></td>
					<td width="100">
						<?php echo anchor("abmc_charlas/ver_charla/".$charla->id_charla, '<i class="icon-eye-open"></i>'); ?>
						<a href="#"></a>
						&nbsp;
						<?php echo anchor("abmc_charlas/edicion_charla/$charla->id_charla", '<i class="icon-pencil"></i>'); ?>
						&nbsp;
						<?php echo anchor("abmc_charlas/do_eliminar_charla/$charla->id_charla", '<i class="icon-remove"></i>', array('class' => 'eliminarCharla')); ?>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="pull-right"><?php echo $paginacion; ?></div>
<?php else:?>
	<p>No hay charlas para mostrar.</p>
<?php endif;?>
<br/><br/>
<a href="<?=site_url('/abmc_charlas/alta_charla');?>" class="btn">Agregar Charla</a>