<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {

	function unset_field_data(){
		unset($this->_field_data);
		$this->_field_data = array();
	}

    function error_count() {
        return count($this->_error_array);
    }
    
    function add_error($error) {
		$this->_error_array['custom_error'] = $error;
	}

	public function is_unique_pro($str, $param)
	{
		list($field, $id)=explode(',', $param);
		list($table, $field)=explode('.', $field);
		if ($id == 0)
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
		else
			$query = $this->CI->db->limit(1)->from($table)->where($field, $str)->where("id <> ", $id)->get();

		return $query->num_rows() === 0;
    }
	
	function decimal2($value)
    {
        $CI =& get_instance();
        $CI->form_validation->set_message('decimal2',
            'El campo %s debe ser numérico, con 2 decimales y usar el punto (.) como separador.');
 
        $regx = '/^[-+]?[0-9]*\.?[0-9][0-9]?$/';
        if(preg_match($regx, $value))
            return true;
        return false;
    }
}
