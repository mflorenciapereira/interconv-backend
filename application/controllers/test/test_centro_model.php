<?php
require_once('application/libraries/MY_TestController.php');

class Test_centro_model extends MY_TestController {
	public $id_centro;
	
	function Test_centro_model() {
		parent::MY_TestController("Centro_model");
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
		$this->unit->run($this->id_centro, 'is_int', 'Alta de centro Primer centro');
	}
	
	function testConsulta() {
		$this->load->model("Centro_model");
		$centro = $this->Centro_model->obtenerCentro($this->id_centro);
		$this->unit->run($centro->nombre,"Primer centro", 'Consulta - nombre');
		$this->unit->run($centro->direccion->calle,"Humberto 1",'Consulta - calle');
		$this->unit->run($centro->direccion->altura,390,'Consulta - altura');
		$this->unit->run($centro->direccion->ciudad,"San Telmo",'Consulta - ciudad');
		$this->unit->run($centro->direccion->provincia,"Capital Federal",'Consulta - provincia');
		$this->unit->run($centro->direccion->pais,"Argentina",'Consulta - pais');
	}
	
	function testModificacion() {
		$this->load->model("Centro_model");
		$centroOriginal = $this->Centro_model->obtenerCentro($this->id_centro);
		$centro = new Centro_model();
		$centro->nombre = 'Primer centro modificado';
		$centro->calle = "Paseo colon";
		$centro->altura = 850;
		$centro->ciudad = $centroOriginal->direccion->ciudad;
		$centro->provincia = $centroOriginal->direccion->provincia;
		$centro->pais = $centroOriginal->direccion->pais;
		$this->unit->run($this->Centro_model->modificacion($centro,$centroOriginal), true, 'Modificación de centro Primer centro');
	}
	
	function testConsultaDespuesModificacion() {
		$this->load->model("Centro_model");
		$centro = $this->Centro_model->obtenerCentro($this->id_centro);
		$this->unit->run($centro->nombre,"Primer centro modificado", 'Consulta despues modificacion - nombre');
		$this->unit->run($centro->direccion->calle,"Paseo colon",'Consulta despues modificacion - calle');
		$this->unit->run($centro->direccion->altura,850,'Consulta despues modificacion - altura');
		$this->unit->run($centro->direccion->ciudad,"San Telmo",'Consulta despues modificacion - ciudad');
		$this->unit->run($centro->direccion->provincia,"Capital Federal",'Consulta despues modificacion - provincia');
		$this->unit->run($centro->direccion->pais,"Argentina",'Consulta despues modificacion - pais');
	}
	
	function testEliminarCentroSinEventosAsociados(){
		$this->load->model("Centro_model");
		$this->unit->run($this->Centro_model->eliminar($this->id_centro),true,'Eliminar centro sin eventos asociados');
	}
	
	function testEliminarCentroConEventosAsociado(){
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
		$evento->fecha_desde = '2018-10-04';
		$evento->fecha_hasta = '2018-10-10';
		$evento->estado = 'No comenzado';
		$evento->id_centro = $this->id_centro;
		$id_evento = $this->Evento_model->alta($evento);
		$this->unit->run($this->Centro_model->eliminar($this->id_centro),false,'Intentar eliminar centro con eventos asociados');
		$this->Evento_model->eliminar($id_evento);
		$this->unit->run($this->Centro_model->eliminar($this->id_centro),true,'Eliminar centro sin eventos asociados');
	}
}

?> 