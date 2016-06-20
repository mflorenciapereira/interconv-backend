<div class="row">
	<div class="span9">
		<h4>Descipción</h4>
		<?php echo $evento->descripcion;?>
	</div>
</div>
<div class="row">
	<div class="span3">
		<h4>Fecha desde </h4><?php echo $evento->fecha_desde;?>
	</div>
	<div class="span3">
		<h4>Fecha hasta </h4><?php echo $evento->fecha_hasta;?>
	</div>
	<div class="span3">
		<h4>Estado </h4><?php echo $evento->estado;?>
	</div>
</div>
<h4>Precio </h4><?php echo '$'.$evento->precio;?>
<h4>Centro </h4><?php echo $evento->centro;?>
<h4>Charlas </h4><?php 
if ($evento->charlas){
	foreach ($evento->charlas as $charla){
		echo '<a href="'.site_url('/abmc_charlas/ver_charla/'.$charla->id_charla).'">'.$charla->nombre.'</a><br/>';
	}
} else {
	echo 'No hay charlas para este evento.';
}
?>
<h4>Fotos </h4>
<ul class="thumbnails">
	<?php foreach($evento->fotos as $pathFoto):?>
		<li class="span2">
			<div class="thumbnail">
				<a href="<?=$pathFoto?>">
					<img src="<?=$pathFoto?>" alt="">
				</a>
			</div>
		</li>
	<?php endforeach;?>
	<?php
		if (sizeOf($evento->fotos) == 0){
			echo '<div class="span4">';
			echo 'No hay fotos cargadas para este evento.';
			echo '</div>';
		}
	?>
</ul>
<h4>Costos:</h4>
<div class="row">
	<div class="span2">
		<h5>Alquiler centro </h5>
	</div>
	<div class="span3">
		<h5>Alojamiento y pasajes de avión para los oradores </h5>
	</div>
	<div class="span2">
		<h5>Publicidad </h5>
	</div>
	<div class="span2">
		<h5>Alquiler equipamiento en general </h5>
	</div>
</div>
<div class="row">
	<div class="span2">
		<?php echo '$'.$evento->alquiler_centro;?>
	</div>
	<div class="span3">
		<?php echo '$'.$evento->alojamiento_pasajes;?>
	</div>
	<div class="span2">
		<?php echo '$'.$evento->publicidad;?>
	</div>
	<div class="span2">
		<?php echo '$'.$evento->alquiler_equip;?>
	</div>
</div>