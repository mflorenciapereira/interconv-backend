<?= form_open_multipart('abmc_usuarios/guardar_usuario'); echo "\n";?>

<script>
     $(function() {
       $( "#datepicker" ).datepicker();
	   
     });
</script>

<?php if (!isset($orador)) {
	$default_nombre =  set_value('nombre');
	$default_apellido =  set_value('apellido');
	$default_dni =  set_value('id_usuario');
	$default_sexo =  set_value('sexo');
	$default_fecha_nacimiento =  set_value('fecha_nacimiento');
	$default_estado_civil =  set_value('estado_civil');
	$default_telefono =  set_value('telefono');
	$default_email =  set_value('email');
	$default_profesion =  set_value('profesion');
	$default_especialidad =  set_value('especialidad');
} else {
	$default_nombre = $orador->get_usuario()->get_nombre();
	$default_apellido = $orador->get_usuario()->get_apellido();
	$default_dni = $orador->get_usuario()->get_dni();
	$default_sexo = $orador->get_usuario()->get_sexo();
	$default_fecha_nacimiento = $orador->get_usuario()->get_fecha_nacimiento();
	$default_estado_civil = $orador->get_usuario()->get_estado_civil();
	$default_telefono = $orador->get_usuario()->get_telefono();
	$default_email = $orador->get_usuario()->get_email();
	$default_profesion = $orador->get_usuario()->get_profesion();
	$default_especialidad = $orador->get_especialidad();

}
$tabActivo='principal'; 
?>

<ul class="nav nav-tabs">
	<?php if (!isset($tabActivo)) $tabActivo='principal';?>
		<li class="<?php  if ($tabActivo == 'principal') echo 'active'; ?>"><a href="#1" data-toggle="tab">Principal</a></li>
		<li class="<?php  if ($tabActivo == 'ubicacion') echo 'active'; ?>"><a href="#2" data-toggle="tab">Dirección</a></li>
</ul>

<div class="tab-content" style="overflow:inherit;">
	<div class="tab-pane <?php  if ($tabActivo == 'principal') echo 'active'; ?>" id="1">
		<div class="row show-grid">
			<div class="tab-content" style="overflow:inherit;">
				<div class="span8">	
				<h2>Información principal</h2>
				<table class = "table">
				<tbody>
						<tr>
							<td><h4>Nombre </h4>				
								<?php echo $default_nombre ?>
							</td>
							<td><h4>Apellido </h4>				
								<?php echo $default_apellido ?>
							</td>
						</tr>
						
						<tr>
							<td><h4>DNI </h4>				
								<?php echo $default_dni ?>
							</td>
							<td><h4>Fecha de Nacimiento </h4>				
								<?php echo $default_fecha_nacimiento ?>
							</td>
						</tr>
						<tr>
							<td><h4>Sexo </h4>				
								<?php
									if (strtolower($default_sexo) == 'f')
										echo 'Femenino';
									else echo 'Masculino'?>
							</td>
							<td><h4>Estado Civil </h4>				
								<?php echo $default_estado_civil ?>
							</td>
						</tr>
						
						<tr>
							<td><h4>Teléfono </h4>				
								<?php echo $default_telefono?>
							</td>
							<td><h4>E-Mail </h4>				
								<?php echo $default_email ?>
							</td>
						</tr>
						
						<tr>
							<td><h4>Profesión </h4>				
								<?php echo $default_profesion?>
							</td>
							<td><h4>Especialidad </h4>				
								<?php echo $default_especialidad ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="tab-pane <? if ($tabActivo == 'ubicacion') echo 'active'; ?>" id="2">
		<div class="row show-grid">
			<div class="tab-content" style="overflow:inherit;">
				<div class="span8">	
				<h2>Direccion</h2>
				<table class = "table">
				<tbody>
						<tr><td>
							<h4>País </h4>				
								<?php if (isset($ubicacion['pais'])) echo $ubicacion['pais'];
										else echo "No se cargó información del campo";?>
						</td></tr>	
						<tr><td><h4>Provincia </h4>				
								<?php if (isset($ubicacion['provincia'])) echo $ubicacion['provincia'];
										else echo "No se cargó información del campo";?>
						</tr></td>
						<tr><td>
							<h4>Ciudad </h4>				
								<?php if (isset($ubicacion['ciudad'])) echo $ubicacion['ciudad'];
										else echo "No se cargó información del campo";?>
						</tr></td>
						<tr><td><h4>Dirección </h4>				
								<?php if (($direccion->calle)!='') echo $direccion->calle;
										else echo "No se cargó información del campo";?>
						</tr></td>
						<tr><td>
							<h4>Altura </h4>				
								<?php if (($direccion->altura)!=0) echo $direccion->altura;
										else echo "No se cargó información del campo";?>
						</tr></td>
						<tr><td><h4>Código Postal </h4>				
								<?php if (($direccion->codigo_postal!='')) echo $direccion->codigo_postal;
										else echo "No se cargó información del campo";?>
						</tr></td>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="form-actions">	

		<button class="btn" name="cancelar" value="1">Volver</button>
</div>

<?= form_close();?>