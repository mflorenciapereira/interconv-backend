<html>
<head>
	<title>Unit test results for <?=$modelname?></title>
	<style type="text/css">
		* { font-family: Arial, sans-serif; font-size: 9pt }
		.err, .pas { color: white }
		.err { background-color: red }
		.pas { background-color: green }
	</style>
</head>
<body>
<h1>Unit test results for <?=$modelname?></h1>

<ol>
	<?php foreach ($results as $result): ?>
	<li>
			<?php if ($result['Resultado'] == 'Pasado') $class = "pas";
			 else $class = "err"; ?>
			
			<div class=<?=$class?>><pre>
					<p><b><?=$result['Nombre del test']?>: <?=$result['Resultado']?></b></p>
					<b>Tipo de datos del test: <b><?=$result['Tipo de datos del test']?> 
					<b>Tipo de datos esperado: <b><?=$result['Tipo de datos esperados']?> 
					<b>Archivo: <b><?=$result['Nombre fichero']?> 
					<b>Numero de linea: <b><?=$result['Número de línea']?> 
					<b>Notas: <b><?=$result['Notas']?> 
			</pre></div>
			
	</li>
	<?php endforeach; ?>
</ol>

</body>
</html> 