<script>
function volver()
	{
		document.location = <?=base_url().'abmc_charlas' ?>
	};
	
</script>

<?php 
$tabActivo='principal'; 
$mostrar_imprimir = false
?>

<ul class="nav nav-tabs">
	<?php if (!isset($tabActivo)) $tabActivo='principal';?>
		<li class="<?php  if ($tabActivo == 'principal') echo 'active'; ?>"><a href="#1" data-toggle="tab">Principal</a></li>
		<li class="<?php  if ($tabActivo == 'qr') echo 'active'; ?>"><a href="#2" data-toggle="tab">Código QR</a></li>
</ul>


<div class="tab-content" style="overflow:inherit;">
	<div class="tab-pane <?php  if ($tabActivo == 'principal') echo 'active'; ?>" id="1">
		<div class="row show-grid">
			<div class="tab-content" style="overflow:inherit;">
				<div class="span8">	
				<div class="row">
					<div class="span8">
						<h4>Nombre</h4>
						<?php echo $charla->nombre;?>
					</div>
				</div>			
				<div class="row">
					<div class="span8">
						<h4>Descripcion</h4>
						<?php echo $charla->descripcion;?>
					</div>
				</div>
				<div class="row">
					<div class="span8">
						<h4>Foto</h4>
						<?php
							if ($charla->foto == ''){
								echo 'No hay foto cargada para esta charla.';
							} else {
						?>
						<ul class="thumbnails">
							<li class="span2">
								<div class="thumbnail">
									<a href="<?=$charla->foto?>">
										<img src="<?=$charla->foto?>" alt="">
									</a>
								</div>
							</li>
						</ul>
						<?php }?>
					</div>
				</div>

				<div class="row">
					<div class="span2">
						<h4>Fecha</h4><?php echo $charla->fecha;?>
					</div>
					<div class="span2">
						<h4>Hora desde</h4><?php echo $charla->hora_desde;?>
					</div>
					<div class="span2">
						<h4>Hora hasta </h4><?php echo $charla->hora_hasta;?>
					</div>
				</div>
				<div class="row">
					<div class="span2">
					<h4>Sala </h4><?php echo $charla->sala;?>
					</div>
					<div class="span2">
					<h4>Capacidad </h4><?php echo $charla->capacidad;?>
					</div>
				</div>
				<h4>Evento </h4><?php echo $charla->nombre_evento;?>
				<h4>Oradores </h4>

				<table class="table">
							<thead>
							<tr>
								<th>DNI</th>
								<th>Nombre</th>
								<th>Especialidad</th>
								
								
							</tr>
							</thead><tbody>
							
							<?php foreach($oradores as $orador):?>
								<tr>
									<td><?php echo $orador->usuario->id_usuario ; ?></td>
									<td><?php echo $orador->usuario->nombre.' '.$orador->usuario->apellido; ?></td>
									<td><?php echo $orador->especialidad ; ?></td>
								
								</tr>
							
								
					<?php endforeach;?>
							<tr>
						</tbody></table>
		
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane <? if ($tabActivo == 'qr') echo 'active'; ?>" id="2">		
		<div class="row show-grid">
				<div class="tab-content" style="overflow:inherit;">
					<div class="span8">		
						<div class="div-qr">
						<img name="qr" id="qr" src="https://chart.googleapis.com/chart?chf=bg,s,ffffff&cht=qr&chs=220x220&chl=<?=$charla->id_charla?>&chld=|0" alt="qr" height="400" width="400"/>
						</div>				
					</div>
			</div>
		</div>
	<button type="submit" onclick="window.print()" id="imprimir" name="imprimir" class="btn btn-primary" value="imprimir">Imprimir</button>
	<br>
	<br>
	</div>
</div>
		<form class="form" action="<?php echo site_url('/abmc_charlas') ;?>">
		
		<button class="btn" name="volver" type="submit" value="Volver">Volver</button>
		<!--Este boton quedó diferente, así que lo cambio...-->
		<!--<input class= "button" type="submit" name="volver" id="volver" value="Volver"></input>-->
		
		</form>