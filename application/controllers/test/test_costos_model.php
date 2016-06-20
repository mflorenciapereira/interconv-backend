<?php
require_once('application/libraries/MY_TestController.php');

class Test_costos_model extends MY_TestController {
	public $id_evento;
	
	function Test_costos_model() {
		parent::MY_TestController("Costos_model");
	}
  
	function testModificacionCostos() {
		$this->load->model("Evento_model");
		$eventos = $this->Evento_model->obtenerEventos();
		$evento = array_pop($eventos);
		$this->id_evento = $evento->id_evento;
		$this->load->model("Costos_model");
		$costos = array('alquiler_centro' => 1,'alojamiento_pasajes' => 2,'publicidad' => 3,'alquiler_equip' => 4);
		$this->Costos_model->modificacion($costos,$this->id_evento);
		$this->load->model("Evento_model");
		$evento = $this->Evento_model->obtenerEvento($this->id_evento);
		$this->unit->run($evento->alquiler_centro,1, 'Consulta - alquiler centro');
		$this->unit->run($evento->alojamiento_pasajes,2,'Consulta - alojamiento y pasajes');
		$this->unit->run($evento->publicidad,3,'Consulta - publicidad');
		$this->unit->run($evento->alquiler_equip,4,'Consulta - alquiler equipamiento');
	}
}

?> 