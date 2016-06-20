<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_TestController extends CI_Controller
{
  
  public $modelname;

  function MY_TestController($name)
  {
    parent::__construct();
    $this->load->library('unit_test');
    $this->modelname = $name;
  }

  function index()
  {
    $this->_runAll();
    $data['modelname'] = $this->modelname;
    $data['results'] = $this->unit->result();
    if ($_SERVER['SCRIPT_NAME'] == 'cmdline.php') { // "Aah! What is this?" you ask. Wait for part II, grasshopper.
      $this->load->view('test/simpleResults', $data);
    }
    else {
      $this->load->view('test/results', $data);
    }
  }

  function _runAll()
  {
    foreach ($this->_getTestMethods() as $method) {
      $this->$method();
    }
  }

  function _getTestMethods()
  {
    $methods = get_class_methods($this);
    $testMethods = array();
    foreach ($methods as $method) {
      if (substr(strtolower($method), 0, 4) == 'test') {
        $testMethods[] = $method;
      }
    }
    return $testMethods;
  }
}

?> 