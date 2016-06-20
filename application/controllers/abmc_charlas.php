<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Abmc_charlas extends MY_Controller{
	private $directorioFoto = './uploads/fotos_charlas';

	function Abmc_charlas(){
		parent::__construct();		
		$this->load->model('Charla_model');
		$this->load->model('Evento_model');
		$this->load->model('Orador_model');
		$this->load->helper('url');
		$this->load->library('form_validation');

	}
	
	function cambiar_horario($charla){
		$charla->hora_desde=substr($charla->hora_desde,0,5);
		$charla->hora_hasta=substr($charla->hora_hasta,0,5);
	}

/* -------- BUSQUEDA, ORDEN Y PAGINADO -------- */

	function busqueda(){
		$data['busqueda'] = $this->input->post('q');
		$this->recargar($data);
	}
		
	function set_busqueda(){
		$busqueda=$this->input->get('q',TRUE);
		if (!$busqueda){
			if (isset($data['busqueda'])) $busqueda = $data['busqueda'];
		}
		return $busqueda;
	}

	function set_columna(){
		$columna = $this->input->get('columna',TRUE);
		if (!$columna){
			if (isset($data['columna'])) $columna = $data['columna'];
			else $columna = 'nombre';
		};
		
		return $columna;
	}
	
	function set_orden(){
		$orden = $this->input->get('orden',TRUE);
		if (!$orden){
			if (isset($data['orden'])) $orden = $data['orden'];
			else $orden = 'asc';
		};
		
		return $orden;
	}
	
	function index(){
		$this->recargar();
	}
	
	function recargar($data = array()){
		$data['titulo'] = 'Administración de Charlas';
		$data['contenido'] = 'abmc_charlas_view.php';
		
		$data['busqueda']=$this->set_busqueda();
		$data['columna']=$this->set_columna();
		$data['orden']=$this->set_orden();
				
		$opciones_paginado = $this->paginar($data['columna'],$data['orden'],$data['busqueda']);
		
		if(isset($data['busqueda'])){
			$charlas=$this->Charla_model->obtener_busqueda_orden($data['busqueda'],$data['columna'],$data['orden'],$opciones_paginado['cantPP'],$opciones_paginado['per_page']);
		}else{
			$charlas=$this->Charla_model->obtener_orden($data['columna'],$data['orden'],$opciones_paginado['cantPP'],$opciones_paginado['per_page']);
		}
		
		foreach ($charlas as $micharla) {
			if($micharla->fecha!=null){
				$fechaphp = strtotime($micharla->fecha);
				$micharla->fecha=date('d-m-Y', $fechaphp);
			}else{
				$micharla->fecha='';
			}
			$this->cambiar_horario($micharla);
			$evento=$this->Evento_model->obtenerEvento($micharla->id_evento);
			$micharla->nombre_evento=$evento->nombre;
			$micharla->registrados=$this->Charla_model->contar_registrados($micharla->id_charla);
		}
			
		$data['charlas']=$charlas;
		$data['paginacion'] = $opciones_paginado['paginacion'];
		$this->cargarContenido($data,'charlas'); 	
	}

	function paginar($columna,$orden,$busqueda='') {
		$this->load->library('pagination');
		$cantPP = $this->input->get('cantPP',TRUE);
		if (!$cantPP) {
			$cantPP = 5;
		}
		
		if($busqueda!=''){
			$config['base_url'] = site_url('abmc_charlas').'?q='.$busqueda.'&cantPP='.$cantPP.'&columna='.$columna.'&orden='.$orden;
			$config['total_rows']=$this->Charla_model->contar_busqueda($busqueda);	
		}else{
			$config['base_url'] = site_url('abmc_charlas').'?cantPP='.$cantPP.'&columna='.$columna.'&orden='.$orden;
			$config['total_rows']=$this->Charla_model->contar_todos();
		}
		
		$config['per_page'] = $cantPP;
		$config['page_query_string'] = TRUE;
		$config['first_link'] = 'Primero';
		$config['last_link'] = 'Último';
		$config['next_link'] = '<i class="icon-chevron-right"></i>';
		$config['prev_link'] = '<i class="icon-chevron-left"></i>';
		$this->pagination->initialize($config);
		$paginacion = $this->pagination->create_links();
		$per_page = $this->input->get('per_page',TRUE);
		if (!$per_page) {
			$per_page = 0;
		}
		return array ('per_page' => $per_page, 'cantPP' => $cantPP, 'paginacion' => $paginacion);
	}
	
	
	/* -------- ALTA, EDICIÓN Y ELIMINACIÓN -------- */
	
	function existen_eventos(){
		$cantidad=$this->Evento_model->obtenerCantidadEventos()>0;
		return $cantidad==0;
	}
	
	function completar_campos(){
		$capacidad = $this->input->post('capacidad');
		if ($capacidad=='') $capacidad=0;
		return array(
					'nombre' => $this->input->post('nombre'),
					'descripcion' => $this->input->post('descripcion'),
					'fecha' => $this->input->post('fecha')!=''?date('Y-m-d', strtotime($this->input->post('fecha'))):null,
					'hora_desde' => $this->input->post('hora_desde')!=''?$this->input->post('hora_desde'):null,
					'hora_hasta' => $this->input->post('hora_hasta')!=''?$this->input->post('hora_hasta'):null,
					'contiene_multimedia' => $this->input->post('contiene_multimedia'),
					'sala' => $this->input->post('sala'),
					'capacidad' => $capacidad,
					'id_evento' => $this->input->post('id_evento'),
					'id_charla' => $this->input->post('id_charla')!=''?$this->input->post('id_charla'):null
		);
	}
	
	function alta_charla(){
		$data['titulo'] = 'Agregar nueva Charla';
		$data['contenido'] = 'charla_view.php';
		$this->load->model('Charla_model');
		$this->load->model('Orador_model');
		$data['eventos'] = $this->Charla_model->get_Eventos();
		$data['noHayEventos']=$this->existen_eventos();
		$data['oradores'] = $this->Orador_model->obtener_oradores_en_orden(-1,-1,'nombre','asc');
		$data['permisosDir'] = is_writable($this->directorioFoto);
		$this->cargarContenido($data,'charlas');
	}
	
	function edicion_charla($id){
		
		
		$data['titulo'] = 'Editar una Charla';
		$data['contenido'] = 'charla_view.php';
		$charla = $this->Charla_model->get($id)->row();
		if($charla->fecha!='0000-00-00'){
			$fechaphp = strtotime($charla->fecha);
			$charla->fecha=date('d-m-Y', $fechaphp);
			
		}else{
			$charla->fecha='';
		}
		$this->cambiar_horario($charla);
		$charla->registrados=$this->Charla_model->contar_registrados($charla->id_charla);
		$charla->confirmados=$this->Charla_model->contar_confirmados($charla->id_charla);
		
		$data['charla'] = $charla;	
	
		$data['eventos'] = $this->Charla_model->get_eventos();
		$data['noHayEventos']=$this->existen_eventos();
		$data['oradores'] = $this->Orador_model->obtener_oradores_en_orden(-1,-1,'nombre','asc');
		//agrego path a la foto
		$charla->foto = '';
		$this->load->helper('file');
		$fotos = get_filenames('uploads/fotos_charlas/'.$charla->id_charla);
		if (is_array($fotos)){
			$charla->foto = base_url('/uploads/fotos_charlas/'.$charla->id_charla.'/'.urlencode(array_pop($fotos)));
		}
		$data['permisosDir'] = is_writable($this->directorioFoto);
		$this->cargarContenido($data,'charlas');
	}
	
	function ver_charla($id){
		$charla = $this->Charla_model->get($id)->row();
		$data['titulo'] = $charla->nombre;//'Ver una Charla';
		$data['contenido'] = 'ver_charla_view.php';
		if($charla->fecha!='0000-00-00'){
			$fechaphp = strtotime($charla->fecha);
			$charla->fecha=date('d-m-Y', $fechaphp);
		}else{
			$charla->fecha='';
		};
		$this->cambiar_horario($charla);
		$charla->registrados=$this->Charla_model->contar_registrados($id);
		$charla->confirmados=$this->Charla_model->contar_confirmados($id);
		$evento=$this->Evento_model->obtenerEvento($charla->id_evento);
		$charla->nombre_evento=$evento->nombre;
		$query = $this->Charla_model->get_oradores_por_charla($id);
		$oradores = $this->Orador_model->levantar_oradores($query->result());
		
		//agrego path a la foto
		$this->load->helper('file');
		$fotos = get_filenames('uploads/fotos_charlas/'.$charla->id_charla);
		$charla->foto = '';
		if (is_array($fotos)){
			$charla->foto = base_url('/uploads/fotos_charlas/'.$charla->id_charla.'/'.urlencode(array_pop($fotos)));
		}
		
		$data['oradores']=$oradores;
		$data['charla'] =$charla;	
		$this->cargarContenido($data,'charlas');
	}
	
	function do_alta_charla(){
		$this->do_accion_charla();
	}
	
	function do_editar_charla(){
		$this->do_accion_charla($this->input->post('id_charla'));
	}
	
	function do_accion_charla($id_charla = null){
		switch ( $_SERVER ['REQUEST_METHOD'] )	{
			case 'GET':
				$this->index();
			break;
							
			case 'POST':
				$data = array('hayMensaje' => false,'tipoMensaje' =>'alert-success');
				if ($this->input->post('cancelar')) {
					redirect('abmc_charlas','refresh');
				}
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				$this->set_reglas();
				$charla = $this->completar_campos();
			
				if ($this->form_validation->run() == FALSE){
					$data['titulo'] = 'Editar una Charla';
					$data['contenido'] = 'charla_view.php';
					$data['eventos'] = $this->Charla_model->get_Eventos();	
					$data['noHayEventos']=$this->existen_eventos();	
					$data['oradores'] = $this->Orador_model->obtener_oradores_en_orden(-1,-1,'nombre','asc');						
					$data['permisosDir'] = is_writable($this->directorioFoto);
					$this->cargarContenido($data,'charlas');
				} else {
					if ($id_charla){
						if(!$this->Charla_model->actualizar($id_charla,$charla)){
							$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se pudo acceder a la base de datos para realizar la modificación de la charla.');
						}else{
							$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Modificación de charla exitosa.');
							//guardo foto si hay una nueva
							if (is_writable($this->directorioFoto)) {
								$this->load->model('Foto_model');
								foreach($_FILES as $key => $archivo){
									if ($archivo['name']){
										//elimino foto actual
										if (!$this->Foto_model->eliminarFotoCharla($id_charla)){
											$data['tipoMensaje'] = 'alert-error';
											$data['hayMensaje'] = false;
											$data['mensaje'] = 'Error al modificar la foto.';
										} else {
											$data = $this->Foto_model->nuevaFotoCharla($key,$id_charla);
										}
									}
								}
							}
						}
					} else {
						$id_charla = $this->Charla_model->agregar($charla);
						if(!$id_charla){
							$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se pudo acceder a la base de datos para realizar el alta de la charla.');
						}else{
							//asigno oradores
							$ids_oradoresAAsociar = $this->input->post('oradores');
							if ($ids_oradoresAAsociar){
								foreach ($ids_oradoresAAsociar as $id_orador){
									$this->Charla_model->asociar($id_charla,$id_orador);
								}
							}
							//guardo foto
							if (is_writable($this->directorioFoto)) {
								$this->load->model('Foto_model');
								foreach($_FILES as $key => $archivo)
									if ($archivo['name']){
										$data = $this->Foto_model->nuevaFotoCharla($key,$id_charla);
									}
							}
							$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Alta de charla exitosa.');
						}
					}
					$this->form_validation->unset_field_data();  
					$this->recargar($data);
				};
			break;
		}
	}
	
	function do_eliminar_charla($id){
		if(!$this->Charla_model->eliminar($id)){
			$data = array('hayMensaje' => true, 'tipoMensaje' => 'alert-error' , 'mensaje' => 'No se pudo acceder a la base de datos para eliminar la charla.');
		}else{
			$data = array('tipoMensaje'=>'alert-success','hayMensaje' => true,'mensaje' => 'Eliminación de charla exitosa.');
		}
		$this->recargar($data);
	}

	/* -------- ASIGNACIÓN DE ORADORES -------- */
	
	function get_orador(){
		$id_orador=$_POST['idAsignar'];
		$orador = $this->Orador_model->levantar_orador($id_orador);
        if($orador){
            $header = false;
            $output_string = "";
			$output_string .= "<tr>";
			$output_string .= "<td>".$orador->usuario->id_usuario."</td>";
			$output_string .= "<td>".$orador->usuario->apellido.', '.$orador->usuario->nombre."</td>";
			$output_string .= "<td>".$orador->especialidad."</td>";
			$output_string .= '<td><input type="hidden" id="oradores[]" name="oradores[]" value="'.$orador->usuario->id_usuario.'"/><a href="#"><i class="icon-remove" onclick="eliminarAsociacion('.$orador->usuario->id_usuario.')"></i></a></td>';
			$output_string .= "</tr>";                
        }
        echo json_encode($output_string);
    }
	
	function get_oradores_por_charla(){
		$idcharla=$_GET['idcharla'];
		$query = $this->Charla_model->get_oradores_por_charla($idcharla);
        if($query->num_rows > 0){
            $header = false;
            $output_string = "";
            $output_string .=  '
			<table class="table">
			<thead>
			<tr>
				<th>DNI</th>
				<th>Nombre</th>
				<th>Especialidad</th>
				<th>Eliminar</th>
				
			</tr>
			</thead>';
            foreach ($query->result() as $row){
				$orador = $this->Orador_model->levantar_orador($row->id_usuario);
                $output_string .= "<tr>\n";
                $output_string .= "<td>".$orador->usuario->id_usuario."</td>";
				$output_string .= "<td>".$orador->usuario->apellido.', '.$orador->usuario->nombre."</td>";
				$output_string .= "<td>".$orador->especialidad."</td>";
				$output_string .= '<td><span class="hidden">'.$orador->usuario->id_usuario.'</span><a href="#"><i class="icon-remove" onclick="eliminarAsociacion('.$orador->usuario->id_usuario.')"></i></a></td>';
                $output_string .= "</tr>\n";             
            }                  
            $output_string .= "</table>\n";
        }
        else{
            $output_string = "No hay oradores asignados.";
        }
        echo json_encode($output_string);
    }

	function eliminar_asociacion_charla(){
		$idcharla=$_GET['idcharla'];
		$idusuario=$_POST['idusuario'];
		$this->Charla_model->eliminar_asociacion($idcharla,$idusuario);
		$this->get_oradores_por_charla();
    }
	
	function asignar_charla(){
		$idcharla=$_GET['idcharla'];
		$idusuario=$_POST['idusuario'];
		$this->Charla_model->asociar($idcharla,$idusuario);
		$this->get_oradores_por_charla();
    }
	
	/* -------- VALIDACIONES -------- */
	
	function set_reglas(){
		$this->form_validation->set_rules('nombre', 'Nombre', 'required');
		$this->form_validation->set_rules('id_evento', 'Evento', 'required');
		$this->form_validation->set_rules('descripcion', 'Descripcion');
		$this->form_validation->set_rules('fecha', 'Fecha','required|callback_validar_fecha|callback_validar_fecha_respecto_evento');
		$this->form_validation->set_rules('hora_desde', 'Hora Desde','required|callback_validar_formato_horario[hora_desde]');
		$this->form_validation->set_rules('hora_hasta', 'Hora Hasta','required|callback_validar_formato_horario[hora_hasta]|callback_validar_horario');
		$this->form_validation->set_rules('contiene_multimedia', 'Contiene Multimedia');
		$this->form_validation->set_rules('sala', 'Sala');
		$this->form_validation->set_rules('capacidad', 'Capacidad','integer');
		$this->form_validation->set_rules('id_charla', 'Charla');
		$this->form_validation->set_rules('foto1', 'Foto','check_imagen[foto1]');
		$this->form_validation->set_message('validar_formato_horario', 'El formato del horario ingresado es incorrecto (hh:mm)');
		$this->form_validation->set_message('validar_horario', 'El horario de finalizaci&oacute;n es menor que el de inicio.');
		$this->form_validation->set_message('validar_fecha', 'El formato de la fecha es incorrecto (dd-mm-aaa) o ingres&oacute; una fecha que no existe.');
		$this->form_validation->set_message('check_imagen', 'La foto seleccionada no cumple los requisitos.');
		$this->form_validation->set_message('validar_fecha_respecto_evento', 'La fecha debe estar dentro del rango de fechas del evento.');
	}
	
	public function check_imagen($str,$campo){
		if ($_FILES[$campo]['name']){
			$this->load->model('Foto_model');
			return $this->Foto_model->imagen_valida($campo,'check_imagen');
		}
		return true;
	}
	
	function validar_horario(){
		$hora_desde = $this->input->post('hora_desde');
		$hora_hasta = $this->input->post('hora_hasta');
		if(($hora_desde!='')&&($hora_hasta!=''))
			return (strtotime($hora_desde) <= strtotime($hora_hasta));
	}
	
	function validar_formato_horario($valor,$campo){
		$hora = $this->input->post($campo);
		if($hora=='') return true;
		$horas=substr($hora, 0, 2);
		$minutos=substr($hora, 3, 2);
		$separador=substr($hora, 2, 1);
		if($separador!=':') return false;
		if((-1<$horas)&&($horas<24)&&(-1<$minutos)&&($minutos<60)) return true;
		else return false;
	}
	
	function validar_fecha(){
		$fecha = $this->input->post('fecha');
		if($fecha=='') return true;
	
		if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $fecha, $parts)){
    
        if(checkdate($parts[2],$parts[1],$parts[3]))
          return true;
        else
         return false;
		}
		else
			return false;
	}	
	
	function validar_fecha_respecto_evento(){
		$this->load->model('Evento_model');
		$evento = $this->Evento_model->obtenerEvento($this->input->post('id_evento'));
		$fecha_charla = strtotime($this->input->post('fecha'));
		if ($evento){
			if ((strtotime($evento->fecha_desde) <= $fecha_charla) && (strtotime($evento->fecha_hasta) >= $fecha_charla))
				return true;
			else
				return false;
		} else {
			return true;
		}
	}
}
