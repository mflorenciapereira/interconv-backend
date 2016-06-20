<?php if (!$permisosDir):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error crítico: No se tienen permisos para crear directorios y guardar archivos en ./uploads/fotos_oradores/. No se van a poder subir fotos.
	</div>
<?php endif;?>
<div class="row">
<div class="span8">

<?= form_open_multipart('abmc_usuarios/guardar_usuario'); echo "\n";?>

<script>
     $(function() {
       $( "#datepicker" ).datepicker();
	   
     });
</script>

<?php
$readonly='';
$tabActivo='principal';

$errorFoto1 = form_error('foto1', '<span class="help-inline">', '</span>');
$errorNombre = form_error('nombre', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorApellido = form_error('apellido', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorDNI = form_error('id_usuario', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorContrasenia = form_error('contrasenia', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorFechaNac = form_error('fecha_nacimiento', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorSexo = form_error('sexo', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorEstCivil = form_error('estado_civil', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorTelefono = form_error('telefono', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorEMail = form_error('email', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorProfesion = form_error('profesion', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorEspecialidad = form_error('especialidad', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorDireccion = form_error('direccion', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorSeleccionCategoria = form_error('seleccionCategoria', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorAgregarFotos = form_error('agregarFotos', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorFotoPortada = form_error('fotoPortada', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorPais = form_error('pais', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorProvincia = form_error('provincia', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorCiudad = form_error('ciudad', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorOtraProvincia = form_error('otra_provincia', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorOtraCiudad = form_error('otra_ciudad', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorCodigoPostal = form_error('codigopostal', '<small><span class="help-inline" style="color:red">', '</span></small>');
$errorAltura = form_error('altura', '<small><span class="help-inline" style="color:red">', '</span></small>');

?>

<ul class="nav nav-tabs">
	<?php if (!isset($tabActivo)) $tabActivo='principal';?>
	<li class="<? if ($tabActivo == 'principal') echo 'active'; ?>"><a href="#1" data-toggle="tab">Principal<?= $errorNombre|| $errorApellido || $errorDNI || $errorFechaNac || $errorSexo || $errorEstCivil || $errorTelefono || $errorEMail || $errorProfesion || $errorEspecialidad ? ' (contiene errores)' : ''?></a></li>
	<li class="<? if ($tabActivo == 'ubicacion') echo 'active'; ?>"><a href="#2" data-toggle="tab">Dirección<?= $errorPais || $errorProvincia || $errorOtraProvincia || $errorCiudad || $errorOtraCiudad || $errorDireccion || $errorCodigoPostal || $errorAltura ? ' (contiene errores)' : ''?></a></li>
	<li class="<?php  if ($tabActivo == 'fotos') echo 'active'; ?>"><a href="#3" data-toggle="tab">Foto de Perfil<?= ($errorFoto1) ? ' (contiene errores)' : ''?></a></li>
</ul>

<?php if (isset($vista)) {
	$readonly='disabled';
	}?>
	
<?php $readonlydni = $readonly;
$readonlycontrasenia = $readonly;
if(isset($modifica)) $readonlydni="readonly";
if(isset($modifica)) $readonlycontrasenia="readonly";
?>

<?php 
if (isset($ubicacion))	{
	$paisSel = $ubicacion['pais'];
	$provinciaSel = $ubicacion['provincia'];
	$ciudadSel = $ubicacion['ciudad'];
}	else {
	$paisSel = set_value('pais');
	$provinciaSel = set_value('provincia');
	$ciudadSel = set_value('ciudad');
}

if (isset($direccion))	{
	$default_calle = $direccion->calle;
	$default_altura = $direccion->altura;
	$default_codigo_postal = $direccion->codigo_postal;
}	else {
	$default_calle = set_value('direccion');
	$default_altura = set_value('altura');
	$default_codigo_postal = set_value('codigopostal');
	
}

if (!isset($orador)) {
	$default_nombre =  set_value('nombre');
	$default_apellido =  set_value('apellido');
	$default_dni =  set_value('id_usuario');
	$default_contrasenia =  set_value('contrasenia');
	$default_sexo =  set_value('sexo');
	$default_fecha_nacimiento =  set_value('fecha_nacimiento');
	$default_estado_civil =  set_value('estado_civil');
	$default_telefono =  set_value('telefono');
	$default_email =  set_value('email');
	$default_profesion =  set_value('profesion');
	$default_especialidad =  set_value('especialidad');
	//$pathFoto = array();
} else {
	$default_nombre = set_value('nombre',$orador->get_usuario()->get_nombre());
	$default_apellido = set_value('apellido',$orador->get_usuario()->get_apellido());
	$default_dni = set_value('id_usuario',$orador->get_usuario()->get_dni());
	$default_contrasenia = set_value('contrasenia',$orador->get_usuario()->get_contrasenia());
	$default_sexo = set_value('sexo',$orador->get_usuario()->get_sexo());
	$default_fecha_nacimiento = set_value('fecha_nacimiento',$orador->get_usuario()->get_fecha_nacimiento());
	$default_estado_civil = set_value('estado_civil',$orador->get_usuario()->get_estado_civil());
	$default_telefono = set_value('telefono',$orador->get_usuario()->get_telefono());
	$default_email = set_value('email',$orador->get_usuario()->get_email());
	$default_profesion = set_value('profesion',$orador->get_usuario()->get_profesion());
	$default_especialidad = set_value('especialidad',$orador->get_especialidad());
	$pathFoto=$orador->foto;
}?>

<div class="tab-content" style="overflow:inherit;">
	<div class="tab-pane <?php  if ($tabActivo == 'principal') echo 'active'; ?>" id="1">
		<div class="row show-grid">
			<div class="tab-content" style="overflow:inherit;">
				<div class="span8">	
				<h2>Información principal</h2>
				<table class = "table">
				<tbody>
						<tr> <td>
						<div class="control-group<?= $errorNombre != '' ? ' error' : ''?>">
						<label class="control-label" for="nombre"><h6>Nombre <span style="color:red;">*</span></h6></label>
						<div class="controls">
								<?php echo form_input(array('name'=>"nombre",'id'=>"nombre",'class'=>"input-xlarge",'value'=>$default_nombre, strval($readonly)=>$readonly,));?>
								<?=$errorNombre?></label>
							</div>
						</div> </td>
						
						<td> <div class="control-group<?= $errorApellido != '' ? ' error' : ''?>">
							<label class="control-label" for="apellido"><h6>Apellido <span style="color:red;">*</span></h6></label>
							<div class="controls">
								<?php echo form_input(array('name'=>"apellido",'id'=>"apellido",'class'=>"input-xlarge",'value'=>$default_apellido, strval($readonly)=>$readonly ));?>
								<?=$errorApellido?></label>
							</div>
						</div> </td>
						</tr>
						
						<tr>
						<td>
						<div class="control-group<?= $errorDNI != '' ? ' error' : ''?>">
							<label class="control-label" for="id_usuario"><h6>DNI <span style="color:red;">*</span></h6></label>
							<div class="controls">
								<?php echo form_input(array('name'=>"id_usuario",'id'=>"id_usuario",'class'=>"input-xlarge",'value'=>$default_dni, strval($readonlydni)=>$readonlydni ));?>
								<?=$errorDNI?></label>
							</div>
						</div>
						<div class="control-group<?= $errorContrasenia != '' ? ' error' : ''?>">
							<label class="control-label" for="contrasenia"><h6>Contraseña <span style="color:red;">*</span></h6></label>
							<div class="controls">
								<?php echo form_input(array('name'=>"contrasenia",'id'=>"contrasenia",'class'=>"input-xlarge",'value'=>$default_contrasenia, strval($readonlycontrasenia)=>$readonlycontrasenia ));?>
								<?=$errorContrasenia?></label>
							</div>
						</div>
						</td>
						
						<td><div class="control-group<?= $errorFechaNac != '' ? ' error' : ''?>">
							<label class="control-label" for="fecha_nacimiento"><h6>Fecha de Nacimiento <span style="color:red;">*</span></h6></label>
							
							
								<div class="input-append date" id="datepicker" data-date="12-02-2012" data-date-format="dd-mm-yyyy">
								
								<input class="span2" name="fecha_nacimiento" id="fecha_nacimiento" size="16" type="text" value="12-02-2012" <?php echo $readonly ?>>
								<span class="add-on"><i class="icon-th"></i></span>
								<?=$errorFechaNac?></label>
								</div>
							
						</div></td>
						</tr>
						
						<tr><td>
						<div class="control-group<?= $errorSexo != '' ? ' error' : ''?>">
							<label class="control-label" for="sexo"><h6>Sexo </h6></label>
							<div class="controls">	
										<?php 
										if (isset ($orador)) {
											if (strtolower($default_sexo) == 'f') $femenino = TRUE; else $femenino = FALSE;
										} else  $femenino = set_value('sexo');
										?>
							
										<?php echo form_radio('sexo', 'Femenino', $femenino, strval($readonly).'='.$readonly)?> &nbsp; Femenino &nbsp;
										<?php echo form_radio('sexo', 'Masculino', !$femenino, strval($readonly).'='.$readonly)?> &nbsp; Masculino
								<?=$errorSexo?></label></td>
							</div>
						</div></td>
						
						<td><div class="control-group<?= $errorEstCivil != '' ? ' error' : ''?>">
							<label class="control-label" for="estado_civil"><h6>Estado Civil </h6></label>
							<div class="controls">	
							<?php		$options = array(
									'soltero'  => 'Soltero',
									'casado'    => 'Casado',
									'viudo'   => 'Viudo',
									); ?>		
								
								<?php echo form_dropdown('estado_civil', $options, $default_estado_civil, strval($readonly).'='.$readonly);?>
								
								<?=$errorEstCivil?></label>
							</div>
						</div></td>
						</tr>
						
						<tr><td>
						<div class="control-group<?= $errorTelefono != '' ? ' error' : ''?>">
							<label class="control-label" for="telefono"><h6>Teléfono <span style="color:red;">*</span></h6></label>
							<div class="controls">
								
								<?php echo form_input(array('type'=>'tel','name'=>"telefono",'id'=>"telefono",'class'=>"input-xlarge",'value'=>$default_telefono, strval($readonly)=>$readonly ));?>
						
								<?=$errorTelefono?></label>
							</div>
						</div></td>
						
						<td><div class="control-group<?= $errorEMail != '' ? ' error' : ''?>">
							<label class="control-label" for="email"><h6>E-Mail <span style="color:red;">*</span></h6></label>
							<div class="controls">
								<?php echo form_input(array('name'=>"email",'id'=>"email",'class'=>"input-xlarge",'value'=>$default_email, strval($readonly)=>$readonly ));?>
								<?=$errorEMail?></label>
							</div>
						</div></td>
						</tr>
						
						<tr><td>
						<div class="control-group<?= $errorProfesion != '' ? ' error' : ''?>">
							<label class="control-label" for="profesion"><h6>Profesión<span style="color:red;">*</span></h6></label>
							<div class="controls">
								
								<?php echo form_input(array('name'=>"profesion",'id'=>"profesion",'class'=>"input-xlarge",'value'=>$default_profesion, strval($readonly)=>$readonly ));?>
								<?=$errorProfesion?></label>
							</div>
						</div></td>
						<td><div class="control-group<?= $errorEspecialidad != '' ? ' error' : ''?>">
							<label class="control-label" for="especialidad"><h6>Especialidad<span style="color:red;">*</span></h6></label>
							<div class="controls">
						
								<?php echo form_input(array('name'=>"especialidad",'id'=>"especialidad",'class'=>"input-xlarge",'value'=>$default_especialidad, strval($readonly)=>$readonly ));?>
								
								<?=$errorEspecialidad?></label>
							</div>
						</div></td>
						</tr>
						
						</tbody>
					</table>
				
				</div>
			</div>
		</div>
	</div>
		
	<div class="tab-pane <? if ($tabActivo == 'ubicacion') echo 'active'; ?>" id="2">
		<!-- Cosas para levantar con javascript -->
		<input type="hidden" id="urlGetProvincias" value="<?= site_url('combos/get_provincias_por_pais')?>" />
		<input type="hidden" id="urlGetCiudades" value="<?= site_url('combos/get_ciudades_por_provincia_y_pais')?>" />
		<div class="control-group<?= $errorPais != '' ? ' error' : ''?>">
			<input type="hidden" name="paisSel" id="paisSel" value="<?= $paisSel?>"/>
			<label class="control-label" for="pais"><h6>País <span style="color:red;"></span></h6></label>
			<div class="controls">
				<select class="input-xlarge" id="pais" name="pais" <?php echo $readonly ?>>
					<option <?= set_select('pais','Ninguno')?>>Ninguno</option>
					<?php for ($i=0; $i < count($paises); $i++) { ?>
							<option <?= set_select("pais", $paises[$i])?>><?= $paises[$i]?></option>
					<?php } ?>
				</select>
				<?$errorPais?>
			</div>
		</div>
		<div class="control-group<?= $errorProvincia != '' ? ' error' : ''?>" >
			<input type="hidden" name="provinciaSel" id="provinciaSel" value="<?= $provinciaSel?>"/>
			<label class="control-label" for="provincia"><h6>Provincia <span style="color:red;"></span></h6></label>
			<div class="controls" <?php echo $readonly ?>>
				<select class="input-xlarge" id="provincia" name="provincia" <?php echo $readonly ?> value="<?$provincia?>">
				</select>
			<?=$errorProvincia?>
			</div>
		</div>
		<div class="control-group<?= $errorOtraProvincia != '' ? ' error' : ''?>" id="otra_provincia_container">
			<label class="control-label" for="otra_provincia">¿Qué provincia? <span style="color:red;">*</span></label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="otra_provincia" name="otra_provincia" value="<?= set_value('otra_provincia') ?>" <?php echo $readonly ?>>
				<?=$errorOtraProvincia?>
			</div>
		</div>
		<div class="control-group<?= $errorCiudad != '' ? ' error' : ''?>"  id="ciudad_container">
			<input type="hidden" name="ciudadSel" id="ciudadSel" value="<?= $ciudadSel?>"  />
			<label class="control-label" for="ciudad"><h6>Ciudad <span style="color:red;"></span></h6></label>
			<div class="controls">
				<select class="input-xlarge" id="ciudad" name="ciudad" <?php echo $readonly ?> value="<?$ciudad?>">
				</select>
				<?=$errorCiudad?>
			</div>
		</div>
		<div class="control-group<?= $errorOtraCiudad != '' ? ' error' : ''?>" id="otra_ciudad_container" <?php echo $readonly ?>>
			<label class="control-label" for="otra_ciudad">¿Qué ciudad? <span style="color:red;">*</span></label>
			<div class="controls">
				<input class="input-xlarge" id="otra_ciudad" name="otra_ciudad" value="<?= set_value('otra_ciudad') ?>">
				<?=$errorOtraCiudad?>
			</div>
		</div>
		<div class="control-group<?= $errorDireccion != '' ? ' error' : ''?>">	
			<label class="control-label" for="direccion"><h6>Calle </h6></label>
			<div class="controls">
				<?php echo form_input(array('name'=>"direccion",'id'=>"direccion",'class'=>"input-xlarge",'value'=>$default_calle, strval($readonly)=>$readonly ));?>
				<?=$errorDireccion?>
			</div>
		</div>
		<div class="control-group<?= $errorAltura != '' ? ' error' : ''?>">	
			<label class="control-label" for="altura"><h6>Altura </h6></label>
			<div class="controls">
				<?php echo form_input(array('name'=>"altura",'id'=>"altura",'class'=>"input-xlarge",'value'=>$default_altura, strval($readonly)=>$readonly ));?>
				<?=$errorAltura?>
			</div>
		</div>
		<div class="control-group<?= $errorCodigoPostal != '' ? ' error' : ''?>">
			<label class="control-label" for="codigopostal"><h6>Código postal</h6></label>
			<div class="controls">
				<?php echo form_input(array('name'=>"codigopostal",'id'=>"codigopostal",'class'=>"input-xlarge",'value'=>$default_codigo_postal, strval($readonly)=>$readonly ));?>
				<?=$errorCodigoPostal?>
			</div>
		</div>
	</div>		
	
	<div class="tab-pane <?php  if ($tabActivo == 'fotos') echo 'active'; ?>" id="3">
		<label><h6>Foto Actual</h6></label>
		<ul class="thumbnails">
			<?php if (isset($pathFoto)){?>
				<li class="span">
					<div class="thumbnail">
						<a href="<?=$pathFoto?>">
							<img src="<?=$pathFoto?>" alt="">
						</a>
					</div>
				</li>
			<?php } else
				{
					echo '<div class="span4">';
					echo 'No hay foto de perfil cargada.';
					echo '</div>';
				}
			?>
		</ul>
		<h6>Cambiar foto</h6>
		<table>
			<tr>
				<td width='40'>
					<div class="control-group<?= ($errorFoto1) ? ' error' : ''?>">
					<div class="controls">
						<input type="file" id="foto1" name="foto1" size="20"/>
							<?php
							if ($errorFoto1){
								$error = 'Error en la foro';
							}
						?>
					</div>
					</div>
				</td></tr>
				<tr><td>
					<p>Formatos permitidos: GIF, JPG/JPEG y PNG. Tamaño máximo: 300KB.</p>
					<p>Cualquier archivo que no cumpla estas características se ignorará.</p>
				</td>
			</tr>
		</table>
	</div>
	
	
	
	
<span style="color:red;">(*) Campos obligatorios</span>

<div class="form-actions">	

 <?php if(! isset($vista)) 
 { ?>
		<?php if (isset($modifica)) $value =2; else $value=1;?>
		<button type="submit" id="guardar_usuario" name="guardar" class="btn btn-primary" value="<?=$value?>">Guardar</button>
		<button class="btn" name="cancelar" value="1">Cancelar</button>
 <?php } else {?>
		<button class="btn btn-primary" name="volver" value="1">Volver</button>
 <?php } ?>
</div>

<?= form_close();?>