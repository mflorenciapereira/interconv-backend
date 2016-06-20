<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf8">
		<title><?php if (isset($titulo)) { echo $titulo; } else { echo 'Página principal';}?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="Backend InterConv">
		<meta name="author" content="InterConv">
		
		<meta property='og:title' content='<?php if (isset($titulo)) { echo $titulo; } else { echo 'Página principal';}?> | InterConv' /> 
		<meta property='og:type' content='website' /> 
		<meta property='og:url' content='<?=base_url();?>' /> 
		<meta property='og:site_name' content='InterConv' /> 
		<meta property='og:country-name' content='Argentina' /> 
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		
		<?php echo css_asset('bootstrap.css');?>
		<?php echo css_asset('bootstrap-responsive.css');?>
		<?php echo css_asset('chosen.css');?>
		<?php echo css_asset('datepicker.css');?>
		
		<?php echo css_asset('jquery.autocomplete.css');?>
		
		<!-- Scritps de JQuery -->
		<?php echo js_asset('jquery-1.8.2.min.js');?>
		<?php echo js_asset('chosen.jquery.min.js');?>
		<?php echo js_asset('jquery-backend.js');?>
		<?php echo js_asset('bootstrap.min.js');?>
		<?php echo js_asset('bootstrap-datepicker.js');?>
		
		<?php echo js_asset('jquery.autocomplete.js');?>
		<!-- Fin scritps de JQuery -->
		
		<style>

		.container > footer p {
			text-align: center; /* center align it with the container */
		}
		.comprimir {
			width: 900px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
		}

		/* The white background content wrapper */
		.container > .content {
			background-color: #fff;
			padding: 20px;
			margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
			-webkit-border-radius: 0 0 6px 6px;
			   -moz-border-radius: 0 0 6px 6px;
					border-radius: 0 0 6px 6px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
			   -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
					box-shadow: 0 1px 2px rgba(0,0,0,.15);
		}

		/* Page header tweaks */
		.page-header {
			background-image: -moz-linear-gradient(left, #FFFFFF 0%, #AAF0D1 100%);
			padding: 20px 20px 10px;
			margin: -20px -20px 20px;
		}

		body {
			padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
			background-color:#F0FFF0;
		}
		
		.pager {
			margin-bottom:0px;
			text-aling:left;
		}
		
		@media print {
			body {
				text-align: center;
			}
			#t5 {
				display: none;
			}
			.navbar {
				display: none;
			}
			.nav {
				display: none;
			}
			.tab-content > .tab-pane,
			.pill-content > .pill-pane {
				display: inline-block;
				text-align: left;
			}
			.btn {
				display: none;
			}
			.qr {
				margin:0 auto;
				text-align: center;
			}	
		}

		</style>

		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Le fav and touch icons -->
		<!--
		<link rel="shortcut icon" href="../assets/ico/favicon.ico">
		-->
		<!--
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
		-->
		<!--
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
		-->
		<!--
		<link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
		-->
	</head>

	<body>
		<!-- Barra de navegacion superior -->
		
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href=<?=site_url("/principal")?>>InterConv</a>
					<div class="nav-collapse">
						<ul class="nav">
							<li><a href=<?=site_url()?>><i class="icon-home "></i></a></li>
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#menu1">
									Eventos
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href=<?=site_url('/abmc_eventos')?>>Administración de Eventos</a></li>
									<li><a href="<?=site_url('/abmc_costos')?>">Administración de Costos</a></li>
								</ul>
							</li>
							<li <?php if ($seccion == 'charlas') echo 'class="active"' ?>><a href="<?=site_url('/abmc_charlas')?>">Charlas</a></li>
							<li <?php if ($seccion == 'centros') echo 'class="active"' ?>><a href="<?=site_url('/abmc_centros_de_convenciones')?>">Centros de convenciones</a></li>
							<li <?php if ($seccion == 'usuarios') echo 'class="active"' ?>><a href="<?=site_url('/abmc_usuarios')?>">Oradores</a></li>
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#menu2">
										Reservas
										<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href=<?=site_url('/abmc_reservas_hoteles')?>>Reservas de Hoteles</a></li>
									<li><a href=<?=site_url('/abmc_reservas_vuelos')?>>Reservas de Vuelos</a></li>
								</ul>
							</li>
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#menu2">
										Estad&iacute;sticas
										<b class="caret"></b>
								</a>
								<ul class="dropdown-menu">
									<li><a href=<?=site_url('/estadisticas_controller')?>>Asistencia a charlas</a></li>
									
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<!-- Fin barra de navegacion superior -->

		<!-- Cuerpo -->
		
		<div class="container comprimir">
			<div class="content">
				<div class="page-header">
					<h1>
						<?php if (isset($foto)) { ?> <img src="<?=$foto?>" alt="199" width='160'> <?php }?>
						<?php if (!isset($titulo)) { echo 'Bienvenido'; } else { echo $titulo; }?>
						<small><?php if (isset($tituloSmall)) { echo $tituloSmall; }?></small>
					</h1>
				</div>
				<?php if (isset($contenido)) include($contenido); else echo 'Bienvenido al sistema de administración InterConv'?>
			</div>
			<footer>
				<p>&copy; InterConv</p>
			</footer>
		</div>
		<!-- Fin cuerpo -->
		
	</body>
</html>
