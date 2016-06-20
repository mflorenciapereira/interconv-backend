<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Foto_model extends CI_Model{

	public function imagen_valida($campo,$regla){
		if (!$_FILES[$campo]['name'])
			return true;
			
		$imagen = $_FILES[$campo];
		
		if (substr($imagen['type'],0,5) != 'image'){
			$this->form_validation->set_message($regla, 'El archivo que seleccionó no es una imagen.');
			return false;
		}
		
		$error = '';
		
		if ($imagen['size']/1024 > 300)
			$error = $error.'El tamaño no puede ser mayor a 300Kb.<br />';
		
		$info = getimagesize($imagen['tmp_name']);
				
		if ($info[2] != 1 && $info[2] != 2 && $info[2] != 3) //1 = GIF, 2 = JPG, 3 = PNG
			$error = $error.'El formato tiene que ser gif, jpg o png.<br />';
		
		if ($error != ''){
			$this->form_validation->set_message($regla, 'La imagen seleccionada no es válida:<br />'.$error);
			return false;
		}
		
		return true;
	}

	function eliminarFotosCentro($id_centro){
		$dir = './uploads/fotos_centros/'.$id_centro;
		if (is_dir($dir)){
			return delete_files($dir);
		} else {
			return true;
		}
	}
	
	function eliminarFotoCharla($id_charla){
		$this->load->helper('file');
		$dir = './uploads/fotos_charlas/'.$id_charla;
		if (is_dir($dir)){
			return delete_files($dir);
		} else {
			return true;
		}
	}
	
	function eliminarFotosEvento($id_evento){
		$dir = './uploads/fotos_eventos/'.$id_evento;
		if (is_dir($dir)){
			return delete_files($dir);
		} else {
			return true;
		}
	}
	
	function eliminarFotosOradores($id_usuario){
		$dir = './uploads/fotos_eventos/'.$id_usuario;
		if (is_dir($dir)){
			return delete_files($dir);
		} else {
			return true;
		}
	}
	
	function eliminarFotoCentro($nombreFoto,$id_centro){
		$foto = './uploads/fotos_centros/'.$id_centro.'/'.$nombreFoto;
		if (is_file($foto)){
			return unlink($foto);
		} else {
			return true;
		}
	}
	
	function eliminarFotoEvento($nombreFoto,$id_evento){
		$foto = './uploads/fotos_eventos/'.$id_evento.'/'.$nombreFoto;
		if (is_file($foto)){
			return unlink($foto);
		} else {
			return true;
		}
	}
	
	function eliminarFotoOrador($id_orador){
		$this->load->helper('file');
		$dir = 'uploads/fotos_oradores/'.$id_orador;
		if (file_exists($dir)) {
			delete_files($dir.'/');
			rmdir($dir);
		}
	}
	
	function setConfigFotos($dir){
		$this->load->library('upload');
		
		$config['upload_path'] = $dir;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '300';
		
		$this->upload->initialize($config);
	}
	
	function prepararDir($id_centro,$dir){		
		if (!is_writable($dir))
			return false;
		else {
			$dir = $dir.'/'.$id_centro;
		
			$dirCreado = file_exists($dir);
		
			if (!$dirCreado)
				$dirCreado = mkdir($dir,0777,true);
			
			return $dirCreado;
		}
	}
	
	function nuevaFotoCentro($key,$id_centro){
		$baseDir = './uploads/fotos_centros/';
		$this->nuevaFoto($key,$id_centro,$baseDir);
	}
	
	function nuevaFotoCharla($key,$id_charla){
		$baseDir = './uploads/fotos_charlas/';
		return $this->nuevaFoto($key,$id_charla,$baseDir);
	}
	
	function nuevaFotoEvento($key,$id_evento){
		$baseDir = './uploads/fotos_eventos/';
		$this->nuevaFoto($key,$id_evento,$baseDir);
	}
	
	function nuevaFotoOrador($key,$id_orador){
		//me fijo las fotos que habia 
		$this->load->helper('file');
		$fotos_existentes = get_filenames('uploads/fotos_oradores/'.$id_orador);
		$baseDir = './uploads/fotos_oradores/';
		$mensaje = $this->nuevaFoto($key,$id_orador,$baseDir);
		if ($mensaje['exito'] == true){
			if (is_array($fotos_existentes) && (count($fotos_existentes)>0)){
				foreach ($fotos_existentes as $foto) {
					unlink('uploads/fotos_oradores/'.$id_orador.'/'.$foto);
				}
			}	
		}
	
	}
	
	function nuevaFoto($key,$id,$baseDir){
		$value = $_FILES[$key]['name'];
		$value = utf8_decode($value);
		$value = strtr($value, utf8_decode('ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝÑñàáâãäåçèéêëìíîïðòóôõöùúûüýÿ'), 'AAAAAACEEEEIIIIOOOOOUUUUYNnaaaaaaceeeeiiiioooooouuuuyy');
		$value = preg_replace('/( +)/i', '_', $value);
		$value = strtolower($value);
		$_FILES[$key]['name'] = $value;  
		
		$archivo = $_FILES[$key];
		$mensaje = array(
			'hayMensaje' => true,
			'tipoMensaje' => 'alert-success',
			'mensaje' => 'Se guardo la imagen '.$archivo['name'],
			'exito' => true,
		);
			
		$dirCreado = $this->prepararDir($id,$baseDir);
		$dir = $baseDir.$id;
		if (!$dirCreado){
			$mensaje['tipoMensaje'] = 'alert-error';
			$mensaje['mensaje'] = 'Error al crear el directorio '.$dir.'.';
			$mensaje['exito'] = false;
		} else {
			if (file_exists($dir.'/'.$archivo['name'])) {
				$mensaje['tipoMensaje'] = 'alert-error';
				$mensaje['mensaje'] = 'La imagen '.$dir.'/'.$archivo['name'].' ya está cargada.';
				$mensaje['exito'] = false;
			} else {
				$this->setConfigFotos($dir);
					
				if (!$this->upload->do_upload($key)){
					$mensaje['tipoMensaje'] = 'alert-error';
					$mensaje['mensaje'] = 'Error al subir la imagen: '.$this->upload->display_errors();
					$mensaje['exito'] = false;
				}
			}
		}
		return $mensaje;
	}
}