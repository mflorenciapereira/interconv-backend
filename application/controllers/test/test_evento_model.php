<?php
require_once('application/libraries/MY_TestController.php');

class Test_evento_model extends MY_TestController {
	public $id_evento;
	public $id_centro;
	
	function Test_evento_model() {
		parent::MY_TestController("Evento_model");
	}
  
	function testAlta() {
		$this->load->model("Centro_model");
		$centro = new Centro_model();
		$centro->nombre = "Primer centro";
		$centro->calle = "Humberto 1";
		$centro->altura = 390;
		$centro->ciudad = "San Telmo";
		$centro->provincia = "Capital Federal";
		$centro->pais = "Argentina";
		$this->id_centro = $this->Centro_model->alta($centro);
		$this->load->model("Evento_model");
		$evento = new Evento_model();
		$evento->nombre = 'Primer evento';
		$evento->descripcion = 'Descripcion primer evento';
		$evento->fecha_desde = '2012-10-01';
		$evento->fecha_hasta = '2012-10-30';
		$evento->estado = 'No comenzado';
		$evento->id_centro = $this->id_centro;
		$this->id_evento = $this->Evento_model->alta($evento);
		$this->unit->run($this->id_evento, 'is_int', 'Alta de evento Primer evento');
	}
	
	function testConsulta() {
		$this->load->model("Evento_model");
		$evento = $this->Evento_model->obtenerEvento($this->id_evento);
		$this->unit->run($evento->nombre,"Primer evento", 'Consulta - nombre');
		$this->unit->run($evento->descripcion,'Descripcion primer evento','Consulta - descripcion');
		$this->unit->run($evento->fecha_desde,'01-10-2012','Consulta - Fecha desde');
		$this->unit->run($evento->fecha_hasta,'30-10-2012','Consulta - Fecha hasta');
		$this->unit->run($evento->id_centro,$this->id_centro,'Consulta - centro');
	}
	
	function testModificacion() {
		$evento = new Evento_model();
		$evento->nombre = 'Primer evento modificado';
		$evento->descripcion = 'Descripcion primer evento modificado';
		$evento->fecha_desde = '2012-12-01';
		$evento->fecha_hasta = '2012-12-30';
		$evento->estado = 'No comenzado';
		$this->load->model("Centro_model");
		$centro = new Centro_model();
		$centro->nombre = "Segundo centro";
		$centro->calle = "Paseo Colon";
		$centro->altura = 750;
		$centro->ciudad = "San Telmo";
		$centro->provincia = "Capital Federal";
		$centro->pais = "Argentina";
		$id_centro_anterior = $this->id_centro;
		$this->id_centro = $this->Centro_model->alta($centro);
		$evento->id_centro = $this->id_centro;
		$this->unit->run($this->Evento_model->modificacion($evento,$this->id_evento), true, 'Modificación de evento Primer evento');
		$this->unit->run($this->Centro_model->eliminar($id_centro_anterior),true,'Eliminar centro sin eventos asociados');
	}
	
	function testConsultaDespuesModificacion() {
		$this->load->model("Evento_model");
		$evento = $this->Evento_model->obtenerEvento($this->id_evento);
		$this->unit->run($evento->nombre,"Primer evento modificado", 'Consulta - nombre');
		$this->unit->run($evento->descripcion,'Descripcion primer evento modificado','Consulta - descripcion');
		$this->unit->run($evento->fecha_desde,'01-12-2012','Consulta - Fecha desde');
		$this->unit->run($evento->fecha_hasta,'30-12-2012','Consulta - Fecha hasta');
		$this->unit->run($evento->id_centro,$this->id_centro,'Consulta - centro');
	}
	
	function testEliminarEventoSinCharlasAsociadas(){
		$this->load->model("Evento_model");
		$this->unit->run($this->Evento_model->eliminar($this->id_evento),true,'Eliminar evento sin charlas asociadas');
		$this->load->model("Centro_model");
		$this->unit->run($this->Centro_model->eliminar($this->id_centro),true,'Eliminar centro sin eventos asociados');
	}
	
	function testEliminarEventoConCharlasAsociadas(){
		$this->load->model("Centro_model");
		$centro = new Centro_model();
		$centro->nombre = "Primer centro";
		$centro->calle = "Humberto 1";
		$centro->altura = 390;
		$centro->ciudad = "San Telmo";
		$centro->provincia = "Capital Federal";
		$centro->pais = "Argentina";
		$this->id_centro = $this->Centro_model->alta($centro);
		$this->load->model("Evento_model");
		$evento = new Evento_model();
		$evento->nombre = 'Primer evento';
		$evento->descripcion = 'Descripcion primer evento';
		$evento->fecha_desde = '2012-10-01';
		$evento->fecha_hasta = '2012-10-30';
		$evento->estado = 'No comenzado';
		$evento->id_centro = $this->id_centro;
		$this->id_evento = $this->Evento_model->alta($evento);
		$this->load->model("Charla_model");
		$charla['nombre'] = 'Primer charla';
		$charla['descripcion'] = 'Descripcion de la primer charla';
		$charla['fecha'] = '2012-12-12';
		$charla['hora_desde'] = '09:00';
		$charla['hora_hasta'] = '10:00';
		$charla['contiene_multimedia'] = 'No';
		$charla['sala'] = 'Sala 1';
		$charla['id_evento'] = $this->id_evento;
		$id_charla = $this->Charla_model->agregar($charla);
		$this->unit->run($this->Evento_model->eliminar($this->id_evento),false,'Intentar eliminar evento con charlas asociadas');
		$this->Charla_model->eliminar($id_charla);
		$this->unit->run($this->Evento_model->eliminar($this->id_evento),true,'Eliminar evento sin charlas asociadas');
		$this->unit->run($this->Centro_model->eliminar($this->id_centro),true,'Eliminar centro sin eventos asociados');
	}
	
}

?> 