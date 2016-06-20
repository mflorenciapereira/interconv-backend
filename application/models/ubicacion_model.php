<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ubicacion_model extends CI_Model {
	
	function get_paises(){
		$this->db->select('pais');
		$this->db->from('paises');
		$query = $this->db->get();
		$paises = array();
		foreach($query->result_array() as $nombre)
			array_push($paises,$nombre['pais']);
		return $paises;
	}
	
    function get_provincias_por_pais($pais) {
		$this->db->select('provincia');
		$this->db->from('provincias');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('pais',$pais);
		$this->db->group_by('provincia');
		$query = $this->db->get();
		return $query->result();
	}
	
	function get_ciudades_por_pais_y_provincia($pais, $provincia) {
		$this->db->select('ciudad');
		$this->db->from('ciudades');
		$this->db->join('provincias','provincias.idprovincias = ciudades.idprovincia');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('pais',$pais);
		$this->db->where('provincia',$provincia);
		$this->db->group_by('ciudad');
		$query = $this->db->get();
		return $query->result();
	}
	
	//Devuelve el id de ciudad para los nombres de pais, provincia y ciudad dados
	//Todos tienen que tener algún valor
	function getID($pais,$provincia,$ciudad){
		$this->db->select('idciudades');
		$this->db->from('ciudades');
		$this->db->join('provincias','provincias.idprovincias = ciudades.idprovincia');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('pais',$pais);
		$this->db->where('provincia',$provincia);
		$this->db->where('ciudad',$ciudad);
		$query = $this->db->get();
		
		if ($query->num_rows() == 0)
			return null;
		else {
			$ubicacion = $query->first_row('array');
			return $ubicacion['idciudades'];
		}
	}
	
	//Devuelve los ids de ciudades para los nombres de pais, provincia y ciudad dados
	//Pueden faltar parametros.
	function getIDs($pais,$provincia,$ciudad){
		$this->db->select('idciudades');
		$this->db->from('ciudades');
		$this->db->join('provincias','provincias.idprovincias = ciudades.idprovincia');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		if ($pais)
			$this->db->where('pais',$pais);
		if ($provincia)
			$this->db->where('provincia',$provincia);
		if ($ciudad)
			$this->db->where('ciudad',$ciudad);
		
		$query = $this->db->get();

		if ($query->num_rows() == 0)
			return null;
		else
			$res = $query->result_array();
			$ids = array();
			foreach($res as $id)
				array_push($ids,$id['idciudades']);
			return $ids;
	}
	
	function getPorID($id){
		$this->db->select('pais,provincia,ciudad');
		$this->db->from('ciudades');
		$this->db->join('provincias','provincias.idprovincias = ciudades.idprovincia');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('idciudades',$id);
		$query = $this->db->get();
		
		if ($query->num_rows() == 0)
			return null;
		else
			return $ubicacion = $query->first_row('array');
	}
	
	function getArrayPorID($id){
		$this->db->select('pais,provincia,ciudad');
		$this->db->from('ciudades');
		$this->db->join('provincias','provincias.idprovincias = ciudades.idprovincia');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('idciudades',$id);
		$query = $this->db->get();
		echo print_r($query->result());
		if ($query->num_rows() == 0)
			return null;
		else
			return $query->first_row()->result();
	}
	
	function nueva($pais,$provincia,$ciudad){
		//Inserto un pais nuevo si no existe
		$this->db->from('paises');
		$this->db->where('pais',$pais);
		$query = $this->db->get();
		$id = 0;
		if ($query->num_rows() == 0) {
			$this->db->insert('paises',array('pais' => $pais));
			$id = $this->db->insert_id();
		} else {
			$pais = $query->first_row('array');
			$id = $pais['idpaises'];
		}
		
		//Inserto una provincia nueva si no existe esa provincia en el pais 
		$this->db->from('provincias');
		$this->db->where('idpais',$id);
		$this->db->where('provincia',$provincia);
		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			$this->db->insert('provincias',array('provincia' => $provincia,'idpais' => $id));
			$id = $this->db->insert_id();
		} else {
			$provincia = $query->first_row('array');
			$id = $provincia['idprovincias'];
		}
		
		//Inserto una ciudad nueva
		$this->db->insert('ciudades',array('ciudad' => $ciudad,'idprovincia' => $id));
		$id = $this->db->insert_id();

		return $id;
	}
	
	function getPaisesConProvincias(){
		$this->db->select('pais');
		$this->db->from('provincias');
		$this->db->join('paises', 'paises.idpaises = provincias.idpais');
		$this->db->group_by('pais');
		$query = $this->db->get();
		$paises = array();
		foreach($query->result_array() as $nombre)
			array_push($paises,$nombre['pais']);
		return $paises;
	}
	
	function getProvincias($pais){
		$this->db->select('provincia');
		$this->db->from('provincias');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('pais',$pais);
		$this->db->group_by('provincia');
		$query = $this->db->get();
		$provincias = array();
		foreach($query->result_array() as $nombre)
			array_push($provincias,$nombre['provincia']);
		return $provincias;
	}
	
	function armarArboles(){
		$listaPaises = array();
		$paises = $this->get_paises();
		foreach ($paises as $pais) {
			$provincias = $this->get_provincias_por_pais($pais);
			$arbolesPais = array();
			$listaProvincias = array();
			foreach ($provincias as $provincia){
				$ciudades = $this->get_ciudades_por_pais_y_provincia($pais, $provincia->provincia);
				$listaCiudades = array();
				foreach($ciudades as $ciudad)
					array_push($listaCiudades,$ciudad->ciudad);
				array_push($listaProvincias,array($provincia->provincia,$listaCiudades));
			}
			if (sizeof($listaProvincias) > 0)
				array_push($listaPaises,array($pais,$listaProvincias));
		}
		return $listaPaises;
	}
	
	function buscada($pais,$provincia,$ciudad){
		if ($pais != null){
			if ($provincia != null){
				if ($ciudad != null){
					$aux = $this->getCiudad($pais,$provincia,$ciudad);
					$id = $aux['idciudades'];
					$tabla = 'ciudades';
				} else {
					$aux = $this->getProvincia($pais,$provincia);
					$id = $aux['idprovincias'];
					$tabla = 'provincias';
				}
			} else {
				$aux = $this->getPais($pais);
				$id = $aux['idpaises'];
				$tabla = 'paises';
			}
		}
		$cant = $aux['cant_busquedas'] + 1;
		$this->db->where('id'.$tabla, $id);
		$this->db->update($tabla,array('cant_busquedas' => $cant));
	}
	
	function getCiudad($pais,$provincia,$ciudad){
		$this->db->select('idprovincias');
		$this->db->from('provincias');
		$this->db->join('paises','paises.idpaises = provincias.idpais');
		$this->db->where('pais',$pais);
		$this->db->where('provincia',$provincia);
		$query = $this->db->get();
		$provincia = $query->first_row('array');
		
		$this->db->from('ciudades');
		$this->db->where('ciudad',$ciudad);
		$this->db->where('idprovincia',$provincia['idprovincias']);
		$query = $this->db->get();
		return $query->first_row('array');
	}
	
	function getProvincia($pais,$provincia){
		$this->db->select('idpaises');
		$this->db->from('paises');
		$this->db->where('pais',$pais);
		$query = $this->db->get();
		$pais = $query->first_row('array');
		
		$this->db->from('provincias');
		$this->db->where('provincia',$provincia);
		$this->db->where('idpais',$pais['idpaises']);
		$query = $this->db->get();
		return $query->first_row('array');
	}
	
	function getPais($pais){
		$this->db->from('paises');
		$this->db->where('pais',$pais);
		$query = $this->db->get();
		return $query->first_row('array');
	}
	
	function getMasBuscadas($cant = 10){
		$this->db->select('idciudades,cant_busquedas');
		$this->db->from('ciudades');
		$this->db->where('cant_busquedas >',0);
		$this->db->order_by('cant_busquedas','desc');
		$query = $this->db->get();
		$ciudades = $query->result_array();
		
		$this->db->select('idprovincias,cant_busquedas');
		$this->db->from('provincias');
		$this->db->where('cant_busquedas >',0);
		$this->db->order_by('cant_busquedas','desc');
		$query = $this->db->get();
		$provincias = $query->result_array();
		
		$this->db->select('pais,cant_busquedas');
		$this->db->from('paises');
		$this->db->where('cant_busquedas >',0);
		$this->db->order_by('cant_busquedas','desc');
		$query = $this->db->get();
		$paises = $query->result_array();
		
		//Busco los $cant mas buscados de entre las ciudades, provincias y paises
		$resultado = array();
		$seguir = true;
		while ($seguir){
			$ciudad = sizeof($ciudades) > 0 ? $ciudades[0] : null;
			$provincia = sizeof($provincias) > 0 ? $provincias[0] : null;
			$pais = sizeof($paises) > 0 ? $paises[0] : null;
			
			if ($ciudad == null && $provincia == null && $pais == null)
				$seguir = false;
			else {
				$bCiudad = $ciudad != null ? $ciudad['cant_busquedas'] : 0;
				$bProvincia = $provincia != null ? $provincia['cant_busquedas'] : 0;
				$bPais = $pais != null ? $pais['cant_busquedas'] : 0;
				
				if ($bCiudad >= $bProvincia && $bCiudad >= $bPais) {
					array_push($resultado,$this->getNombreCiudad($ciudad['idciudades']));
					array_shift($ciudades);
				} else
					if ($bProvincia >= $bCiudad && $bProvincia >= $bPais) {
						array_push($resultado,$this->getNombreProvincia($provincia['idprovincias']));
						array_shift($provincias);
					} else {
						array_push($resultado,$pais);
						array_shift($paises);
					}
			}
			
			if (sizeof($resultado) == $cant)
				$seguir = false;
		}
		
		return $resultado;
	}
	
	function getNombreCiudad($id){
		$ubicacion = $this->getPorID($id);
		return $ubicacion['pais'].'/'.$ubicacion['provincia'].'/'.$ubicacion['ciudad'];
	}
	
	function getNombreProvincia($id){
		$this->db->select('provincia,idpais');
		$this->db->from('provincias');
		$this->db->where('idprovincias',$id);
		$query = $this->db->get();
		$provincia = $query->first_row('array');
		
		$this->db->select('pais');
		$this->db->from('paises');
		$this->db->where('idpaises',$provincia['idpais']);
		$query = $this->db->get();
		$pais = $query->first_row('array');
		
		return $pais['pais'].'/'.$provincia['provincia'];
	}
	
	function getNombrePais($id){		
		$this->db->select('pais');
		$this->db->from('paises');
		$this->db->where('idpaises',$id);
		$query = $this->db->get();
		$pais = $query->first_row('array');
		
		return $pais['pais'];
	}
}

?>