<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asistencia_model extends CI_Model{
	
	function setIntencionAsistir($id_usuario,$id_charla){
		if (!$this->getIntencionAsistir($id_usuario,$id_charla)){
			$intencion = array('id_usuario' => $id_usuario,
								'id_charla' => $id_charla,
								'asistio' => 0);
			if ($this->db->insert('usuario_charla',$intencion))
				return 1;
			else
				return 0;
		}
		return 1;
	}
	
	function getIntencionAsistir($id_usuario,$id_charla){
		$this->db->from('usuario_charla');
		$this->db->where('id_usuario',$id_usuario);
		$this->db->where('id_charla',$id_charla);
		$this->db->where('asistio',0);
		$query = $this->db->get();
		if ($query->num_rows() == 1) return 1;
		return 0;
	}
	
	function getParticipa($id_usuario,$id_charla){
		$this->db->from('usuario_charla');
		$this->db->where('id_usuario',$id_usuario);
		$this->db->where('id_charla',$id_charla);
		$this->db->where('asistio',1);
		$query = $this->db->get();
		if ($query->num_rows() == 1) return 1;
		return 0;
	}
	
	function setIntencionNOAsistir($id_usuario,$id_charla){
		$intencion = array('id_usuario' => $id_usuario,
							'id_charla' => $id_charla);
		$this->db->where('asistio',0);
		if ($this->db->delete('usuario_charla',$intencion))
			return 1;
		else
			return 0;
	}
	
	function setAsistio($id_usuario,$id_charla){
		$this->db->where('id_usuario',$id_usuario);
		$this->db->where('asistio',1);
		$query = $this->db->get('usuario_charla');
		
		if ($query->num_rows() > 0) {
			$this->load->model('Charla_model');
			foreach ($query->result() as $row)
				if (!$this->Charla_model->yaTermino($row->id_charla)) {
					$this->db->where('id_usuario',$row->id_usuario);
					$this->db->where('id_charla',$row->id_charla);
					$this->db->delete('usuario_charla');
				}
		}
		
		$this->db->where('id_usuario',$id_usuario);
		$this->db->where('id_charla',$id_charla);
		$this->db->where('asistio',0);
		$this->db->delete('usuario_charla');
		
		$datos = array('id_usuario' => $id_usuario,
						'id_charla' => $id_charla,
						'asistio' => 1);
		if ($this->db->insert('usuario_charla',$datos))
			return 1;
		else
			return 0;
	}
	
	function getInteresadosCharla($id_charla){
		$this->db->from('usuario_charla');
		$this->db->join('usuario','usuario_charla.id_usuario = usuario.id_usuario');
		$this->db->select('usuario_charla.id_usuario, nombre, apellido');
		$this->db->where('id_charla',$id_charla);
		$this->db->where('asistio',0);
		$query = $this->db->get();
		if ($query->num_rows() > 0) return $query->result();
		return false;
	}
	
	function getParticipantesCharla($id_charla){
		$this->db->from('usuario_charla');
		$this->db->join('usuario','usuario_charla.id_usuario = usuario.id_usuario');
		$this->db->select('usuario_charla.id_usuario, nombre, apellido');
		$this->db->where('id_charla',$id_charla);
		$this->db->where('asistio',1);
		$query = $this->db->get();
		if ($query->num_rows() > 0) return $query->result();
		return false;
	}	
		
	function estaAsistiendo($id_usuario,$id_charla){
		$this->db->where('id_usuario',$id_usuario);
		$this->db->where('id_charla',$id_charla);
		$this->db->where('asistio',1);
		$query = $this->db->get('usuario_charla');
		return $query->num_rows() > 0;
	}
}