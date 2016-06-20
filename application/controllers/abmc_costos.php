<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abmc_costos extends MY_Controller{

	function index(){
		$this->recargar();
	}
	
	function recargar($data = array()){
		$data['titulo'] = 'Administración de Costos de Eventos';
		$data['contenido'] = 'abmc_costos_view.php';
		$this->load->model('Costos_model');
		$this->load->model('Evento_model');
		
		$this->load->library('pagination');
		$cantPP = $this->input->get('cantPP',TRUE);
		if (!$cantPP) {
			$cantPP = 5;
		}
		$busqueda = $this->input->get('busqueda',TRUE);
		if (!$busqueda){
			if (isset($data['busqueda'])) $busqueda = $data['busqueda'];
		}
		$data['busqueda'] = $busqueda;
		$orden = $this->input->get('orden',TRUE);
		if (!$orden){
			if (isset($data['orden'])) $orden = $data['orden'];
			else $orden = 'nombre';
		}
		$sentido = $this->input->get('sentido',TRUE);
		if (!$sentido){
			if (isset($data['sentido'])) $sentido = $data['sentido'];
			else $sentido = 'asc';
		}
		if ($busqueda){
			$config['base_url'] = site_url('abmc_costos').'?busqueda='.$busqueda.'&orden='.$orden.'&sentido='.$sentido.'&cantPP='.$cantPP;
			$config['total_rows'] = $this->Costos_model->obtenerCantidadEventosConBusqueda($busqueda);
		} else {
			$config['base_url'] = site_url('abmc_costos').'?orden='.$orden.'&sentido='.$sentido.'&cantPP='.$cantPP;
			$config['total_rows'] = $this->Evento_model->obtenerCantidadEventos();
		}
		$config['per_page'] = $cantPP;
		$config['page_query_string'] = TRUE;
		$config['first_link'] = 'Primero';
		$config['last_link'] = 'Último';
		$config['next_link'] = '<i class="icon-chevron-right"></i>';
		$config['prev_link'] = '<i class="icon-chevron-left"></i>';
		
		$this->pagination->initialize($config);
		$data['paginacion'] = $this->pagination->create_links();
		$per_page = $this->input->get('per_page',TRUE);
		if (!$per_page) {
			$per_page = 0;
		}
		if ($busqueda){
			$data['eventos'] = $this->Costos_model->busqueda($busqueda,$cantPP,$per_page,$orden,$sentido);
		} else {
			$data['eventos'] = $this->Evento_model->obtenerEventos($cantPP,$per_page,$orden,$sentido);
		}
		
		$this->cargarContenido($data,'eventos');
	}
		
	function prepararCostos(){
		$costos = array('alquiler_centro' => $this->input->post('alquiler_centro'),
						'alojamiento_pasajes' => $this->input->post('alojamiento_pasajes'),
						'publicidad' => $this->input->post('publicidad'),
						'alquiler_equip' => $this->input->post('alquiler_equip'));	
		return $costos;
	}
	
	function ejecutarEdicionCostos(){
		$data = array();
		$data = array('hayMensaje' => false,'tipoMensaje' =>'alert-success');
		if ($this->input->post('cancelar')) {
			redirect('abmc_costos','refresh');
		}

		$id_evento = $this->input->post('id_evento');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('alquiler_centro', 'Alquiler centro', 'required|decimal');
		$this->form_validation->set_rules('alojamiento_pasajes', 'Alojamiento y pasajes de avión', 'required|decimal');
		$this->form_validation->set_rules('publicidad', 'Publicidad', 'required|decimal');
		$this->form_validation->set_rules('alquiler_equip', 'Alquiler de equipamiento', 'required|decimal');
		$this->form_validation->set_message('required', 'El campo %s es obligatorio.');
		$this->form_validation->set_message('decimal', 'El campo %s debe ser un número decimal.');
		
		if ($this->form_validation->run() == true){
			$this->load->model('Costos_model');
				$this->Costos_model->modificacion($this->prepararCostos(),$id_evento);
			if ($data['tipoMensaje'] == 'alert-error'){
				$this->editarCostos($id_evento,$data);
			} else {
				$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Costos del evento editados exitosamente.');
				$this->form_validation->unset_field_data();  
				$this->recargar($data);
			}
		} else {
			$this->editarCostos($id_evento,$data);
		}
	}
		
	function editarCostos($id_evento,$data = array()){
		$this->load->model('Evento_model');
		$evento = $this->Evento_model->obtenerEvento($id_evento);
		if ($evento){
			$data['evento'] = $evento;
			$data['titulo'] = 'Edición de costos del evento "'.$evento->nombre.'"';
			$data['contenido'] = 'editarCostos_view.php';
			$this->cargarContenido($data,'eventos');
		} else {
			$this->recargar();
		}
	}
	
	function busqueda(){
		$data['busqueda'] = $this->input->post('busqueda');
		$this->recargar($data);
	}
}