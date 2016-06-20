<script>	
	$(document).ready(function() {
		$('.eliminarUsuario').click(function (event) {
			if (!confirm('¿Desea eliminar el usuario?'))
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

<?= form_open_multipart('abmc_usuarios/busqueda',array ('class' => "form-search pull-right" )); echo "\n";?>
<?php if (!isset($volver) ){ ?>	
	<?php echo form_input(array('name'=>"busqueda",'id'=>"busqueda",'class'=>"input-medium search-query"));?>
	<button type="submit" class="btn" id="boton_busqueda" name="boton_busqueda" value="busqueda" >Buscar</button>
<?php } else {?>
	<button type="submit" class="btn" id="boton_busqueda" name="boton_busqueda" value="volver">Volver</button>
<?php } ?>
<?= form_close();?>

<?php if ($oradores):?>
	<table class="table">
		<thead>
			<tr>
				<th>Nombre 
						<a href="<?=site_url('/abmc_usuarios/index?columna=usuario.nombre&orden=asc');?>"><i class="icon-chevron-up" ></i> 
						<a href="<?=site_url('/abmc_usuarios/index?columna=usuario.nombre&orden=desc');?>"><i class="icon-chevron-down"></i>
				</th>
				<th>Apellido 
						<a href="<?=site_url('/abmc_usuarios/index?columna=usuario.apellido&orden=asc');?>"><i class="icon-chevron-up" ></i> 
						<a href="<?=site_url('/abmc_usuarios/index?columna=usuario.apellido&orden=desc');?>"><i class="icon-chevron-down"></i>
				</th>
				<th>DNI 
						<a href="<?=site_url('/abmc_usuarios/index?columna=usuario.id_usuario&orden=asc');?>"><i class="icon-chevron-up" ></i> 
						<a href="<?=site_url('/abmc_usuarios/index?columna=usuario.id_usuario&orden=desc');?>"><i class="icon-chevron-down"></i>
				</th>
				<th>Mail </th>
				<th>Teléfono </th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($oradores as $orador): ?>
				<tr>
					<td><?php echo $orador->usuario->nombre?></td>
					<td><?php echo $orador->usuario->apellido?></td>
					<td><?php echo $orador->usuario->id_usuario?></td>
					<td><?php echo $orador->usuario->email?></td>
					<td><?php echo $orador->usuario->telefono?></td>
					<td>
						<a href="<?=site_url('/abmc_usuarios/vista_usuario?id='.$orador->id_usuario);?>"><i class="icon-eye-open"></i></a>
						&nbsp;
						<a href="<?=site_url('/abmc_usuarios/modificacion_usuario?id='.$orador->id_usuario);?>"><i class="icon-pencil"></i></a>
						&nbsp;
						<a class="eliminarUsuario" href="<?=site_url('/abmc_usuarios/eliminar_usuario?id='.$orador->id_usuario);?>"><i class="icon-remove"></i></a>
					</td>
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="pull-right"><?php echo $paginacion;?></div>
<?php else:?>
	<p>No hay oradores para mostrar.</p>
<?php endif;?>
<br/><br/>
<a href="<?=site_url('/abmc_usuarios/alta_usuario?id=0');?>" class="btn">Agregar orador</a>