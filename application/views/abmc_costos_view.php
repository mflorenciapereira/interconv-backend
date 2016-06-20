<?php if (isset($hayMensaje))
	if ($hayMensaje):?>
	<div class="alert <?php echo $tipoMensaje;?>">
	<a class="close" data-dismiss="alert">×</a>
	<?php echo $mensaje;?>
	</div>
<?php endif;?>
<form action="<?=site_url('abmc_costos/busqueda')?>" class="form-search pull-right">
	<input id="busqueda" name="busqueda" type="text" class="input-medium search-query">
	<button type="submit" class="btn">Buscar por nombre</button>
</form>

<?php if ($eventos):?>
	<table class="table" width="100%">
		<thead>
			<tr>
				<th>Nombre
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=nombre&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=nombre&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Alq. centro
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=alquiler_centro&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=alquiler_centro&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Alojamiento y pasajes
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=alojamiento_pasajes&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=alojamiento_pasajes&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Publicidad
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=publicidad&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=publicidad&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Alq. equipamiento
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=alquiler_equip&sentido=asc')?>"><i class="icon-chevron-up"></i></a>
					<a href="<?=site_url('abmc_costos?busqueda='.$busqueda.'&orden=alquiler_equip&sentido=desc')?>"><i class="icon-chevron-down"></i></a>
				</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($eventos as $evento): ?>
				<tr>
					<td width="15%"><?php echo $evento->nombre;?></td>
					<td width="15%"><?php echo $evento->alquiler_centro;?></td>
					<td width="25%"><?php echo $evento->alojamiento_pasajes;?></td>
					<td width="15%"><?php echo $evento->publicidad;?></td>
					<td width="25%"><?php echo $evento->alquiler_equip;?></td>
					<td width="5%">
						<a href="<?=site_url('abmc_eventos/verEvento/'.$evento->id_evento)?>"><i class="icon-eye-open"></i></a>
						&nbsp;
						<a href="<?=site_url('abmc_costos/editarCostos/'.$evento->id_evento)?>"><i class="icon-pencil"></i></a>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="pull-right"><?=$paginacion?></div>
<?php else:?>
	<p>No hay eventos cargados o ningún evento coincide con su búsqueda.</p>
<?php endif;?>