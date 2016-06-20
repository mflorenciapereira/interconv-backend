<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{


		
		
    function __construct()
    {
        parent::__construct();
    }
	
	
	    //Page header
    public function Header() {
	
	
		$blanco=array(255,255,255);
		$verde=array(170,240,209);
		$coords = array(0, 0, 1, 0);
		

        $this->LinearGradient(0, 0, 300, 15, $blanco, $verde, $coords);
        $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => '10,20,5,10', 'phase' => 10, 'color' => array(255, 0, 0));
		
		$style = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
		$this->Line(0, 15, 300, 15, $style);
		
		$this->SetTextColor(0, 0, 0); 
		$this->setY(3);
		$this->setX(3);
		$this->SetFont('helvetica', '', 20);		
		$this->Write('', utf8_encode('InterConv'), '', 0, 'L', 1, 0, false, false, 0);
		
		$this->setY(1);
		$this->setX(170);
		$this->SetFont('helvetica', '', 10);		
		$this->Write('', utf8_encode('www.interconv.com'), '', 0, 'R', 1, 0, false, false, 0);
		
	
        
        
    }

    // Page footer
    public function Footer() {
		$this->SetY(-15);
        $this->SetFont('helvetica', 'I', 10);
        $this->Cell(0, 10, utf8_encode('Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages()), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

?>
