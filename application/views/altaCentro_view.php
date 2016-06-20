<script>
	$(document).ready(function (){
		$('#actualizarMapa').click(function (){
			var ciudad = $("#ciudad").val();
			if (ciudad == "Otra") ciudad = $("#otra_ciudad").val();
			var provincia = $("#provincia").val();
			if (provincia == "Otra") provincia = $("#otra_provincia").val();
			$('#mapa').replaceWith('<img id="mapa" name="mapa" border="0" src="https://maps.google.com/maps/api/staticmap?zoom=14' + 
			'&size=512x400&markers=color:red%7C' + 
			$("#calle").val().replace(" ", "+") + '+' + $("#altura").val().replace(" ", "+") + ',' +
			ciudad.replace(" ", "+") + ',' + 
			provincia.replace(" ", "+").replace('-GBA','') + ',' + 
			$("#pais").val().replace(" ", "+") + '&maptype=roadmap&sensor=false" alt="Mapa" />');
		});
	});
</script>
<?= form_open_multipart($accion); echo "\n";?>

<?php
$tabActivo = 'principal';
$errorNombre = form_error('nombre', '<span class="help-inline">', '</span>');
$errorCalle = form_error('calle', '<span class="help-inline">', '</span>');
$errorAltura = form_error('altura', '<span class="help-inline">', '</span>');
$errorFoto1 = form_error('foto1', '<span class="help-inline">', '</span>');
$errorFoto2 = form_error('foto2', '<span class="help-inline">', '</span>');
$errorFoto3 = form_error('foto3', '<span class="help-inline">', '</span>');
$errorFoto4 = form_error('foto4', '<span class="help-inline">', '</span>');
$errorFoto5 = form_error('foto5', '<span class="help-inline">', '</span>');
$errorPais = form_error('pais', '<span class="help-inline">', '</span>');
$errorProvincia = form_error('provincia', '<span class="help-inline">', '</span>');
$errorOtraProvincia = form_error('otra_provincia', '<span class="help-inline">', '</span>');
$errorCiudad = form_error('ciudad', '<span class="help-inline">', '</span>');
$errorOtraCiudad = form_error('otra_ciudad', '<span class="help-inline">', '</span>');
$errorCodigoPostal = form_error('codigo_postal', '<span class="help-inline">', '</span>');
if ($tipoAccion == 'editar'){
	$valorNombre = set_value('nombre',$centro->nombre);
	if ($paisSel == '') $valorPais = $centro->direccion->pais;
	else $valorPais = $paisSel;
	if ($provinciaSel == '') $valorProvincia = $centro->direccion->provincia;
	else $valorProvincia = $provinciaSel;
	if ($ciudadSel == '') $valorCiudad = $centro->direccion->ciudad;
	else $valorCiudad = $ciudadSel;
	$valorCP = set_value('codigo_postal',$centro->direccion->codigo_postal);
	$valorCalle = set_value('calle',$centro->direccion->calle);
	$valorAltura = set_value('altura',$centro->direccion->altura);
	$valorFotos = $centro->fotos;
	echo '<input type="hidden" id="id_centro" name="id_centro" value="'.$centro->id_centro.'" />';
} else {
	$valorNombre = set_value('nombre','');
	$valorPais = $paisSel;
	$valorProvincia = $provinciaSel;
	$valorCiudad = $ciudadSel;
	$valorCP = set_value('codigo_postal','');
	$valorCalle = set_value('calle','');
	$valorAltura = set_value('altura','');
	$valorFotos = array();
}
?>

<?php if (!$permisosDir):?>
	<div class="alert alert-error">
	<a class="close" data-dismiss="alert">×</a>
	Error crítico: No se tienen permisos para crear directorios y guardar archivos en ./uploads/fotos_centros/. No se van a poder subir fotos.
	</div>
<?php endif;?>
<ul class="nav nav-tabs">
	<?php if (!isset($tabActivo)) $tabActivo='principal';?>
	<li class="<?php  if ($tabActivo == 'principal') echo 'active'; ?>"><a href="#1" data-toggle="tab">Principal<?= $errorNombre ? ' (contiene errores)' : ''?></a></li>
	<li class="<?php  if ($tabActivo == 'fotos') echo 'active'; ?>"><a href="#2" data-toggle="tab">Fotos<?= ($errorFoto1 || $errorFoto2 || $errorFoto3 || $errorFoto4 || $errorFoto5) ? ' (contiene errores)' : ''?></a></li>
</ul>
<div class="tab-content" style="overflow:inherit;">
	<div class="tab-pane <?php  if ($tabActivo == 'principal') echo 'active'; ?>" id="1">
		<div class="row show-grid">
			<div class="span5">
				<h2>Información principal</h2>
				<div class="control-group<?= $errorNombre != '' ? ' error' : ''?>">
					<label class="control-label" for="nombre"><h6>Nombre <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<input type="text" name="nombre" id="nombre" class="input-xlarge" value="<?=$valorNombre?>"/>
						<?=$errorNombre?>
					</div>
				</div>
			</div>
			<!-- Ubicación -->
			<div class="span3">
				<h2>Ubicación</h2>
				<input type="hidden" id="urlGetProvincias" value="<?= site_url('combos/get_provincias_por_pais')?>" />
				<input type="hidden" id="urlGetCiudades" value="<?= site_url('combos/get_ciudades_por_provincia_y_pais')?>" />
				<div class="control-group<?= $errorPais != '' ? ' error' : ''?>">
					<input type="hidden" name="paisSel" id="paisSel" value="<?=$valorPais?>" />
					<label class="control-label" for="pais"><h6>País <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<select class="input-xlarge" id="pais" name="pais">
							<option <?= set_select('pais','Ninguno')?>>Ninguno</option>
							<?php for ($i=0; $i < count($paises); $i++) { ?>
							<option <?= set_select("pais", $paises[$i])?>><?= $paises[$i]?></option>
							<?php } ?>
						</select>
						<?=$errorPais?>
					</div>
				</div>
				<div class="control-group<?= $errorProvincia != '' ? ' error' : ''?>" >
					<input type="hidden" name="provinciaSel" id="provinciaSel" value="<?= $valorProvincia?>" />
					<label class="control-label" for="provincia"><h6>Provincia <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<select class="input-xlarge" id="provincia" name="provincia">
						</select>
						<?=$errorProvincia?>
					</div>
				</div>
				<div class="control-group<?= $errorOtraProvincia != '' ? ' error' : ''?>" id="otra_provincia_container">
					<label class="control-label" for="otra_provincia">¿Qué provincia? <span style="color:red;">*</span></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="otra_provincia" name="otra_provincia" value="<?= set_value('otra_provincia') ?>">
						<?=$errorOtraProvincia?>
					</div>
				</div>
				<div class="control-group<?= $errorCiudad != '' ? ' error' : ''?>"  id="ciudad_container">
					<input type="hidden" name="ciudadSel" id="ciudadSel" value="<?= $valorCiudad?>" />
					<label class="control-label" for="ciudad"><h6>Ciudad <span style="color:red;">*</span></h6></label>
					<div class="controls">
						<select class="input-xlarge" id="ciudad" name="ciudad">
						</select>
						<?=$errorCiudad?>
					</div>
				</div>
				<div class="control-group<?= $errorOtraCiudad != '' ? ' error' : ''?>" id="otra_ciudad_container">
					<label class="control-label" for="otra_ciudad">¿Qué ciudad? <span style="color:red;">*</span></label>
					<div class="controls">
						<input type="text" class="input-xlarge" id="otra_ciudad" name="otra_ciudad" value="<?= set_value('otra_ciudad') ?>"/>
						<?=$errorOtraCiudad?>
					</div>
				</div>
				<div class="row">
					<div class="span2">
						<div class="control-group<?= $errorCalle != '' ? ' error' : ''?>">	
							<label class="control-label" for="calle"><h6>Calle <span style="color:red;">*</span></h6></label>
							<div class="controls">
								<input type="text" name="calle" id="calle" class="input-medium" value="<?=$valorCalle?>"/>
								<?=$errorCalle?>
							</div>
						</div>
					</div>
					<div class="span1">
						<div class="control-group<?= $errorAltura != '' ? ' error' : ''?>">	
							<label class="control-label" for="altura"><h6>Altura <span style="color:red;">*</span></h6></label>
							<div class="controls">
								<input type="text" name="altura" id="altura" class="input-small" value="<?=$valorAltura?>"/>
								<?=$errorAltura?>
							</div>
						</div>
					</div>
				</div>
				<div class="control-group<?= $errorCodigoPostal != '' ? ' error' : ''?>">
					<label class="control-label" for="codigo_postal"><h6>Código postal</h6></label>
					<div class="controls">
						<input type="text" name="codigo_postal" id="codigo_postal" class="input-xlarge" value="<?= $valorCP?>">
						<?=$errorCodigoPostal?>
					</div>
				</div>

				<a href="#mapaModal" data-toggle="modal" id="actualizarMapa" name="actualizarMapa">Ver ubicación en mapa</a>
				 
				<div class="modal hide" id="mapaModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Ubicación</h3>
				</div>
				<div class="modal-body">
				<div id="mapa" name="mapa"></div>
				</div>
				<div class="modal-footer">
				<button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
				</div>
				</div>
				<!-- Fin Ubicación -->
			</div>
		</div>
	</div>
	<div class="tab-pane <?php  if ($tabActivo == 'fotos') echo 'active'; ?>" id="2">
		<label><h6>Fotos</h6></label>
		<ul class="thumbnails">
			<?php foreach($valorFotos as $pathFoto):?>
				<li class="span2">
					<input type="checkbox" name="fotoActual[]" value="<?=substr($pathFoto,strrpos($pathFoto,'/')+1)?>"/> Eliminar
					<div class="thumbnail">
						<a href="<?=$pathFoto?>">
							<img src="<?=$pathFoto?>" alt="">
						</a>
					</div>
				</li>
			<?php endforeach;?>
			<?php
				if (sizeOf($valorFotos) == 0){
					echo '<div class="span4">';
					echo 'No hay fotos cargadas para este centro.';
					echo '</div>';
				}
			?>
		</ul>
		<h6>Agregar fotos</h6>
		<table>
			<tr>
				<td width='40'>
					<div class="control-group<?= ($errorFoto1  || $errorFoto2 || $errorFoto3 || $errorFoto4 || $errorFoto5) ? ' error' : ''?>">
					<div class="controls">
						<input type="file" id="foto1" name="foto1" size="20"/>
						<input type="file" class="<?= $errorFoto2 == '' ? 'foto' : ''?>" id="foto2" name="foto2" size="20" />
						<input type="file" class="<?= $errorFoto3 == '' ? 'foto' : ''?>" id="foto3" name="foto3" size="20" />
						<input type="file" class="<?= $errorFoto4 == '' ? 'foto' : ''?>" id="foto4" name="foto4" size="20" />
						<input type="file" class="<?= $errorFoto5 == '' ? 'foto' : ''?>" id="foto5" name="foto5" size="20" />
						<?php
							if ($errorFoto1  || $errorFoto2 || $errorFoto3 || $errorFoto4 || $errorFoto5){
								$error = 'Error en la(s) foto(s) número: ';
								$errorMas = '';
								if ($errorFoto1) {
									$error .= '1 ';
									$errorMas = $errorFoto1;
								}
								if ($errorFoto2){
									$error .= '2 ';
									$errorMas = $errorFoto2;
								}
								if ($errorFoto3){
									$error .= '3 ';
									$errorMas = $errorFoto3;
								}
								if ($errorFoto4){
									$error .= '4 ';
									$errorMas = $errorFoto4;
								}
								if ($errorFoto5){
									$error .= '5';
									$errorMas = $errorFoto5;
								}
								echo '<span style="color:#B94A48;">'.$error.'</span><br/>'.$errorMas;
							}
						?>
					</div>
					</div>
				</td>
				<td>
					<p>Formatos permitidos: GIF, JPG/JPEG y PNG. Tamaño máximo: 300KB.</p>
					<p>Cualquier archivo que no cumpla estas características se ignorará.</p>
					<p>Puede ingresar de a 5 imagenes, para ingresar mas edite el evento.</p>
				</td>
			</tr>
		</table>
	</div>
</div>

<span style="color:red;">(*) Campos obligatorios</span>

<div class="form-actions">		
	<button type="submit" id="botonAltaModifCentro" name="guardar" class="btn btn-primary" value="1">Guardar</button>
	<button type="submit" name="cancelar" class="btn" value="1">Cancelar</button>
</div>
<?= form_close();?>