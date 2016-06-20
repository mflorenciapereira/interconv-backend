<script>	
	$(document).ready(function() {
		$('.eliminarEvento').click(function (event) {
			if (!confirm('¿Desea eliminar el evento?'))
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
<form action="<?=site_url('abmc_eventos/busqueda')?>" class="form-search pull-right">
	<input id="busqueda" name="busqueda" type="text" class="input-medium search-query">
	<button type="submit" class="btn">Buscar</button>
</form>

<?php if ($eventos):?>
	<table class="table" width="100%">
		<thead>
			<tr>
				<th>Nombre
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=nombre&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=nombre&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Descripcion</th>
				<th>Desde
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=fecha_desde&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=fecha_desde&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Hasta
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=fecha_hasta&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=fecha_hasta&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Estado
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=estado&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_eventos?busqueda='.$busqueda.'&orden=estado&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($eventos as $evento): ?>
				<tr>
					<td width="15%"><?php echo $evento->nombre;?></td>
					<td width="35%"><?php echo substr($evento->descripcion,0,200)."...";?></td>
					<td width="12.5%"><?php echo $evento->fecha_desde;?></td>
					<td width="12.5%"><?php echo $evento->fecha_hasta;?></td>
					<td width="15%"><?php echo $evento->estado;?></td>
					<td width="10%">
						<a href="<?=site_url('abmc_eventos/verEvento/'.$evento->id_evento)?>"><i class="icon-eye-open"></i></a>
						&nbsp;
						<a href="<?=site_url('abmc_eventos/editarEvento/'.$evento->id_evento)?>"><i class="icon-pencil"></i></a>
						&nbsp;
						<a class="eliminarEvento" href="<?=site_url('abmc_eventos/eliminar/'.$evento->id_evento)?>"><i class="icon-remove"></i></a>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="pull-right"><?=$paginacion?></div>
<?php else:?>
	<p>No hay eventos cargados o ningún evento coincide con su búsqueda.</p>
<?php endif;?>
<br/><br/>
<a href="<?=site_url('/abmc_eventos/altaEvento');?>" class="btn">Agregar Evento</a>