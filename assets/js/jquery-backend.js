//-------------------- COSAS PARA LOS COMBOBOX DE UBICACIONES ------------------//
function get_provinces(sufijo,pais, successProvinces, errorProvinces) {
	$.ajax({
		url: $('#urlGetProvincias').val(),
		data: {
			'pais': pais
		},
		success: function(data) {
			successProvinces(sufijo,eval(data));
		},
		error: function() {
			errorProvinces();
		}
	});
}

function get_cities(sufijo,provincia, pais, successCities, errorCities) {
	$.ajax({
		url: $('#urlGetCiudades').val(),
		data: {
			'pais': pais,
			'provincia': provincia
		},
		success: function(data) {
			successCities(sufijo,eval(data));
		},
		error: function() {
			errorCities();
		}
	});
}

function get_oradores_evento(sufijo, evento, id, successOradoresEvento, errorOradoresEvento) {
	console.log(evento);
	$.ajax({
		url: $('#urlGetOradoresEvento').val(),
		data: {
			'evento': id
		},
		success: function(data) {
			successOradoresEvento(sufijo,eval(data));
		},
		error: function() {
			errorOradoresEvento();
		}
	});
}
success_function_provincias = function(sufijo,cities) {
	$.each(cities, function(key, value) {
		if (value.ciudad != "") {
			$('#ciudad'+sufijo)
				.append($("<option></option>")
				.attr("value",value.ciudad)
				.text(value.ciudad));
		}
	});

	if (sufijo == '') {
		$('#ciudad')
				.append($("<option></option>")
				.attr("value", "Otra")
				.text("Otra"));
		$('#ciudad')
				.prepend($("<option></option>")
				.attr("value", "Ninguna")
				.text("Ninguna"));
	}

	$('#ciudad_container'+sufijo).fadeIn();

	ciudad_selected = $('#ciudadSel'+sufijo).val();

	firstCity = null;
	if (ciudad_selected != null) {
		firstCity = ciudad_selected;
		$('#ciudadSel'+sufijo).val('');
	} else {
		firstCity = $('#ciudad'+sufijo+' option:first').val();
	}
	
	$('#ciudad'+sufijo).removeAttr("disabled").val(firstCity).change();
	
	$('#ciudad'+sufijo).trigger("liszt:updated");
};

success_function_paises = function(sufijo,provinces) {
	$.each(provinces, function(key, value) {
		$('#provincia'+sufijo)
				.append($("<option></option>")
				.attr("value",value.provincia)
				.text(value.provincia));
	});

	if (sufijo == ''){
		$('#provincia')
				.append($("<option></option>")
				.attr("value", "Otra")
				.text("Otra"));
		$('#provincia')
				.prepend($("<option></option>")
				.attr("value", "Ninguna")
				.text("Ninguna"));
	}

	provincia_selected = $('#provinciaSel'+sufijo).val();
	
	firstProvince = null;
	if (provincia_selected!=null) {
		firstProvince = provincia_selected;
		$('#provinciaSel'+sufijo).val('');
	} else {
		firstProvince = $('#provincia'+sufijo+' option:first').val();
	}
	
	$('#provincia'+sufijo).removeAttr("disabled").val(firstProvince).change();
	
	$('#provincia'+sufijo).trigger("liszt:updated");
	$('#ciudad'+sufijo).trigger("liszt:updated");
}

$('#provincia, #provincia-busqueda').attr("disabled", "disabled");
$('#ciudad, #ciudad-busqueda').attr("disabled", "disabled");

success_function_oradoresEvento = function(sufijo,oradores) {
	console.log("holasssssamigos");
	$.each(oradores, function(key, value) {
		var nombre = value.usuario.nombre + " " + value.usuario.apellido;
		if (value.id_usuario != "" && nombre!="") {
			$('#orador'+sufijo)
				.append($("<option></option>")
				.attr("value",value.id_usuario)
				.text(nombre));
		}
	});

	orador_selected = $('#oradorSel'+sufijo).val();

	firstOrador = null;
	if (orador_selected != null) {
		firstOrador = orador_selected;
		$('#oradorSel'+sufijo).val('');
	} else {
		firstOrador = $('#orador'+sufijo+' option:first').val();
	}
	
	$('#orador'+sufijo).removeAttr("disabled").val(firstOrador).change();
	
	$('#orador'+sufijo).trigger("liszt:updated");
};



$(document).ready(function() {
	//-------------------- COSAS PARA LOS COMBOBOX DE UBICACIONES ------------------//
	var paisElegido;
	var paisElegidoBusqueda;
	
	$("#pais").val($('#paisSel').val());
	
	$("#pais,#pais-busqueda").chosen().change(function () {
		var sufijo = '';
		if ($(this).attr('id') == "pais-busqueda") sufijo = '-busqueda';
		$('#pais' + sufijo + ' option:selected').each(function() {
			$('#provincia' + sufijo + ',#ciudad' + sufijo).empty();
			
			var pais = '';
			if (sufijo == ''){
				paisElegido = $(this).text();
				pais = paisElegido;
			}
			if (sufijo == '-busqueda'){
				paisElegidoBusqueda = $(this).text();
				pais = paisElegidoBusqueda;
			}
			get_provinces(sufijo,pais,
				success_function_paises,
				function() {
					console.error('error buscando las provincias');
				}
			);
		});
	}).change();

	$('#provincia, #provincia-busqueda').chosen().change(function() {
		var sufijo = '';
		if ($(this).attr('id') == "provincia-busqueda") sufijo = '-busqueda';
		$('#ciudad'+sufijo).empty();
		
		if ($(this).val() != null) {
			$('#provincia'+ sufijo +' option:selected').each(function() {
				is_last = $('#provincia'+sufijo).prop("selectedIndex") == $('#provincia'+sufijo+' option').size() - 1;

				if (sufijo != "-busqueda" && is_last) {
					$("#otra_provincia_container").fadeIn();
					success_function_provincias(sufijo,new Array());
				} else {
					if (sufijo != "-busqueda") $("#otra_provincia_container").fadeOut();
					var pais = '';
					if (sufijo == '')
						pais = paisElegido;
					if (sufijo == '-busqueda')
						pais = paisElegidoBusqueda;
					
					get_cities(sufijo,$(this).text(), pais,
						success_function_provincias,
						function() {
							console.error('Error consiguiendo ciudades');
						}
					);
				}
			});
		}
	}).change();

	$('#ciudad-busqueda').chosen();
	
	$('#ciudad').chosen().change(function() {
		if ($(this).val() != null) {
			$('#ciudad option:selected').each(function() {
				is_last = $('#ciudad').prop("selectedIndex") == $('#ciudad option').size() - 1;
				if (is_last) {
					$("#otra_ciudad_container").fadeIn();
				} else {
					$("#otra_ciudad_container").fadeOut();
				}
			});
		}
	}).change();

	
	$("#otra_provincia_container, #otra_ciudad_container").hide();
	
	success_function_eventos = function(sufijo) {
	
	}
	
	success_function_orador = function(sufijo) {
	
	}
	
	
	// -------- EVENTOS ---------
	
function get_eventos(sufijo,evento, successEventos, errorEventos) {
	$.ajax({
		url: $('#urlGetEventos').val(),
		data: {
			'evento': evento
		},
		success: function(data) {
			successEventos(sufijo);
		},
		error: function() {
			errorEventos();
		}
	});
}	

	$("#eventoCharla").val($('#eventoSel').val());
	
	$("#eventoCharla,#evento-busqueda").chosen().change(function () {
		var sufijo = '';
		if ($(this).attr('id') == "evento-busqueda") sufijo = '-busqueda';
		$('#eventoCharla' + sufijo + ' option:selected').each(function() {
						
			var evento = '';
			if (sufijo == ''){
				eventoElegido = $(this).text();
				evento = eventoElegido;
			}
			if (sufijo == '-busqueda'){
				eventoElegidoBusqueda = $(this).text();
				evento = eventoElegidoBusqueda;
			}
			get_eventos(sufijo,evento,success_function_eventos,
				function() {
					console.error('error buscando los eventos');
				}
			);
		});
	}).change();
	
	//-------- ORADORES -------
	
	function errorOradores(){
		alert("No hay oradores en la base de datos.");
	
	};
	
	function get_oradores(sufijo,orador, successOradores, errorOradores) {
	$.ajax({
		url: $('#urlGetOradores').val(),
		data: {
			'orador': orador
		},
		success: function(data) {
			successOradores(sufijo);
		},
		error: function() {
			errorOradores();
		}
	});
}	

	$("#orador").val($('#oradorSel').val());
	
	$("#orador,#orador-busqueda").chosen().change(function () {
		var sufijo = '';
		
		if ($(this).attr('id') == "orador-busqueda") sufijo = '-busqueda';
		$('#orador' + sufijo + ' option:selected').each(function() {
			
			var orador = '';
			if (sufijo == ''){
				oradorElegido = $(this).text();
				orador = oradorElegido;
			}
			if (sufijo == '-busqueda'){
				oradorElegidoBusqueda = $(this).text();
				orador = oradorElegidoBusqueda;
			}
			get_oradores(sufijo,orador,success_function_orador,
				function() {
					console.error('error buscando los oradores');
				}
			);
		});
	}).change();
	
	$("#orador").trigger("liszt:updated");
	
//-------------------- COSAS PARA LAS FOTOS ---------------------//
	$(".foto").hide();
	$("#foto1").change(function () {
		$("#foto2").fadeIn('slow');
	});
	$("#foto2").change(function () {
		$("#foto3").fadeIn('slow');
	});
	$("#foto3").change(function () {
		$("#foto4").fadeIn('slow');
	});
	$("#foto4").change(function () {
		$("#foto5").fadeIn('slow');
	});
//-------------------- OBTENER VARIABLES DE LA URL -----------------------------//
//-------------------- USO: $.getUrlVar(<nombre_variable>); --------------------//
	$.extend({
	  getUrlVars: function(){
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++)
		{
		  hash = hashes[i].split('=');
		  vars.push(hash[0]);
		  vars[hash[0]] = hash[1];
		}
		return vars;
	  },
	  getUrlVar: function(name){
		return $.getUrlVars()[name];
	  }
});


//-------------------- COSAS PARA EL COMBO DE ORADORES POR EVENTO -----------------------------//


	var eventoElegido;
	var eventoElegidoBusqueda;
	
	$("#eventoOrador,#evento-busqueda").chosen().change(function () {
		var idAsignar=$("#eventoOrador option:selected").val();
		var sufijo = '';
		if ($(this).attr('id') == "evento-busqueda") sufijo = '-busqueda';
		$('#eventoOrador' + sufijo + ' option:selected').each(function() {
			$('#orador' + sufijo).empty();
			console.log("para cada evento: Vacio la lista");
			var evento = '';
			if (sufijo == ''){
				eventoElegido = $(this).text();
				evento = eventoElegido;
				console.log("evento: ",evento);
			}
			console.log("llamo a get_oradores_evento. Con evento: ",evento, ", id: ",idAsignar);
			var oradores = get_oradores_evento(sufijo, evento,idAsignar,
				success_function_oradoresEvento,
				function() {
					console.error('error buscando los oradores');
				}
			);
		});
	}).change();
	
	$("#eventoOrador").trigger("liszt:updated");

});





