<?php
require_once('application/libraries/MY_TestController.php');



class Test_charla_model extends MY_TestController
{
  function __construct()
  {
    parent::MY_TestController("Charla_model");
	$this->load->model('Charla_model');
	//uso una base de datos de prueba
	$this->Charla_model->db = $this->load->database('test', TRUE);
			
  }
  
  function test_crear_charla()
  {
	
	$charla = new Charla_model();
    $this->unit->run($charla, 'is_object', 'CrearCharla');
  }
  
  function test_contar_todos(){
	
	$expectedResult = 4;
    $actualResult = $this->Charla_model->contar_todos();
    $this->unit->run($actualResult, $expectedResult,'ContarTodos');
	  
  }
  
	function test_contar_busqueda(){
		$busqueda='buscada';	
		$expectedResult = 1;
		$actualResult = $this->Charla_model->contar_busqueda($busqueda);
		$this->unit->run($actualResult, $expectedResult,'ContarBusqueda ');
  		
	}
	
	function test_get_object(){
		$id=1;
		$charla = $this->Charla_model->get($id);
		$this->unit->run($charla, 'is_object','GetCharlaObjeto');
			
	}
	
	// function test_get_object_mal(){
		// $id=55;
		// $charla = $this->Charla_model->get($id);
		// $this->unit->run($charla, null,'GetCharlaObjetoMal');
			
	// }
	
	function test_get_nombre(){
		$id=1;
		$charla = $this->Charla_model->get($id)->row();
		$this->unit->run($charla->nombre, 'charla 1','GetCharlaNombre');
			
	}
	
	
	function test_agregar(){
		$charla=array(
					'nombre' => 'Ejemplo',
					'descripcion' => 'Descripcion',
					'contiene_multimedia' => 0,
					'sala' => 'Sala',
					'id_evento' => 8,
					
					
		);
		$id=$this->Charla_model->agregar($charla);
		$charla = $this->Charla_model->get($id)->row();
		$this->unit->run($charla->nombre, 'Ejemplo','AgregarCharlaNombre');
		$this->unit->run($charla->descripcion, 'Descripcion','AgregarCharlaDescripcion');
		$this->unit->run($charla->contiene_multimedia, 0,'AgregarContieneMultimedia');
		$this->unit->run($charla->sala, 'Sala','AgregarCharlaSala');
		$this->unit->run($charla->id_evento, 8,'AgregarCharlaIdEvento');
		
	}
	
	
	function test_actualizar(){
		$charla = $this->Charla_model->get(1)->row();
		$charla->nombre='Modificado';
		$this->Charla_model->actualizar($charla->id_charla,$charla);
		$charlamodif= $this->Charla_model->get(1)->row();
		$this->unit->run($charlamodif->nombre, 'Modificado','ActualizarCharla');
		
	}
	
	
	function test_eliminar(){
		$this->Charla_model->eliminar(1);
		$elim= $this->Charla_model->get(1)->row();
		$this->unit->run($elim,false,'EliminarCharla');
			
	}
	
	function test_eliminar_asociaciones(){
		$this->Charla_model->eliminar(2);
		$elim= $this->Charla_model->get_oradores_por_charla(2)->result();
		$this->unit->run(count($elim),0,'EliminarCharlaAsociaciones');
			
	}
	
	function test_obtener_busqueda_orden(){
	
		$result= $this->Charla_model->obtener_busqueda_orden('ejemplo','id_charla','asc',1,0);
		foreach($result as $row){
			$this->unit->run(strpos($row->nombre,'Ejemplo'),'is_numeric','BusquedaEnOrden');
		};
	
	}
	
	function test_orden(){
		$result= $this->Charla_model->obtener_orden('id_charla','asc',2,0);
		$i=0;
		$ids[]=array();
		foreach($result as $row){
			$ids[$i]=$row->id_charla;
			$i++;
		};
		$this->unit->run($ids[0]<$ids[1],true,'ListaEnOrden');
		
	}
	
	/* ------ ASOCIACIONES ------ */
	
	function test_oradores_charla(){
	
		$result= $this->Charla_model->get_oradores_por_charla(3)->result();
		foreach($result as $row){
			$id_charla=$row->id_charla;
			$id_orador=$row->id_usuario;
		};
		$this->unit->run($id_charla,3,'OradoresCharlaIdCharla');	
		$this->unit->run($id_orador,3265412,'OradoresCharlaIdOrador');	
	
	
	}
	function test_asociar(){
	
		$this->Charla_model->asociar(3,32564789);
		$result= $this->Charla_model->get_oradores_por_charla(3)->result();
		foreach($result as $row){
			$id_charla=$row->id_charla;
			$id_orador=$row->id_usuario;
		};
		$this->unit->run($id_charla,3,'AsociarIdCharla');	
		$this->unit->run($id_orador,32564789,'AsociarIdOrador');	
	}

	function test_eliminar_asociacion(){
		$this->Charla_model->eliminar_asociacion(3,3265412);
		$result= $this->Charla_model->get_oradores_por_charla(3)->result();
		$this->unit->run(count($result),1,'EliminarAsocacion');	
	
	}
	
	function test_eliminar_varias_asociaciones(){
		$this->Charla_model->eliminar_asociaciones(3);
		$result= $this->Charla_model->get_oradores_por_charla(3)->result();
		$this->unit->run(count($result),0,'EliminarAsocaciones');	
	
	
	}
	
	function test_registrados(){
	
		$cantidad=$this->Charla_model->contar_registrados(4);
		$this->unit->run($cantidad,3,'ContarRegistrados');	
	
	}
	function test_confirmados(){
	
		$cantidad=$this->Charla_model->contar_confirmados(4);
		$this->unit->run($cantidad,1,'ContarConfirmados');	
	
	}
	
	
	
	
}

?> 