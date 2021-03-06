<script>
      
	 $(document).ready(function() {
		$(".eliminarReserva").click(function (event) {
		
			var cancelar = $(this).attr("cancelar");   
			var mensaje="";
			if(cancelar!=1) mensaje+="La reserva seleccionada no se encuentra vigente. Solamente se dará de baja la reserva en el sistema. \n¿Desea eliminar "
			else mensaje+="¿Desea cancelar";
			mensaje+='la reserva? La acción no puede deshacerse.';
			if (!confirm(mensaje))
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

<?php if ($eventos!=false) {?>
<?= form_open_multipart('abmc_reservas_hoteles/filtrar',array ('class' => "form-search pull-right", 'method' => "post" )); echo "\n";?>
	<label class="control-label" for="evento"><h5>Evento: </h5></label>
		<select class="input-large" id="evento" name="evento" >
			<option <?= set_select('evento','Todos')?> value=''>Todos</option>
		<?php for ($i=0; $i < count($eventos); $i++) { ?>
			<option <?= set_select('evento', $eventos[$i]->nombre)?> value=<?=$eventos[$i]->id_evento?> ><?= $eventos[$i]->nombre?></option>
		<?php } ?>
		</select>
	<button type="submit" id="filtro" name="filtro" class="btn	" value='1'>Filtrar </button>	
</form>
<?= form_close();?>
<?php }?>

<?php if ($reservas):?>
	<table class="table">
		<thead>
			<tr>
				<th>ID Reserva</th>
				<th>Nombre 
				</th>
				<th>Apellido 
				</th>
				<th>Hotel 
				</th>
				<th>Fecha Desde </th>
				<th>Fecha Hasta </th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
		<?php $hoy = date("Y-m-d");?>
			<?php foreach($reservas as $reserva): ?>
				<tr>
					<td><?php echo $reserva->id_despegar?></td>
					<td><?php echo $reserva->usuario->nombre?></td>
					<td><?php echo $reserva->usuario->apellido?></td>
					<td><?php echo $reserva->nombre?></td>
					<td><?php echo $reserva->fecha_desde?></td>
					<td><?php echo $reserva->fecha_hasta?></td>
					<td>
					
					<a href="<?=site_url('/abmc_reservas_hoteles/vista_reserva?id='.$reserva->id_reserva_hotel);?>"><i class="icon-eye-open"></i></a>
					&nbsp;
					<?php echo anchor("abmc_reservas_hoteles/do_eliminar/$reserva->id_reserva_hotel", '<i class="icon-remove"></i>', array('class' => 'eliminarReserva','cancelar' => strtotime($reserva->fecha_desde)>strtotime($hoy))); ?>	
					</td>
					
				</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="pull-right"><?php echo $paginacion;?></div>
<?php else:?>
	<p>No hay reservas para mostrar.</p>
<?php endif;?>
<br/><br/>
<a href="<?=site_url('/abmc_reservas_hoteles/alta_reserva?id=0');?>" class="btn">Nueva Reserva</a>