<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');




class Estadisticas_controller extends MY_Controller{
	

	function Estadisticas_controller(){
		parent::__construct();		
		$this->load->model('Charla_model');
		$this->load->model('Evento_model');
		$this->load->model('Centro_model');
		$this->load->model('Direccion_model');
		$this->load->helper('url');
		$this->load->library('pdf');
		
		

	}
	
	function index(){
	
		
		$data['titulo'] = 'Estad&iacutestica de asistencia a charlas';
		$data['contenido'] = 'estadistica_asistencia_view.php';
		$data['eventos'] = $this->Evento_model->obtenerEventos();
		$this->cargarContenido($data);
	
	}
	
	
	function generar_pdf_basico(){
		$pdf=new pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetCreator("InterConv");
		$pdf->SetAuthor('InterConv');
		$pdf->SetTitle('Estad&iacute;stica de asistencia al evento');
		$pdf->SetSubject('Comparación de personas interesadas, confirmadas y capacidad de la sala.');
		$pdf->SetMargins(20, 20, 20);
		$pdf->SetAutoPageBreak(TRUE, 20);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		
		return $pdf;
	
	}
	
	function set_reglas(){
		$this->form_validation->set_rules('evento', 'Evento', 'required');
		
	}
	

	
	function obtener_estadisticas_asistencia(){
	
		switch ( $_SERVER ['REQUEST_METHOD'] )	{
			case 'GET':
				$this->index();
			break;
							
			case 'POST':
				$this->load->helper(array('form', 'url'));
				$this->load->library('form_validation');
				$this->set_reglas();
				if ($this->form_validation->run() == FALSE) return $this->index();
				
				$idevento = $this->input->post('evento',TRUE);
								
				$pdf = $this->generar_pdf_basico();		
				$pdf->AddPage('P');		 
				 
				//Evento
				$evento='';
				$evento=$this->Evento_model->obtenerEvento($idevento);
				
				$pdf->SetFont('helvetica', 'B', 25);		
				$pdf->Write(20, utf8_encode('Estadística de asistencia por charla'), '', 0, 'C', 1, 0, false, false, 0);

				$pdf->SetTextColor(0, 102, 102);
				$pdf->SetFont('helvetica', 'BI', 12);		
				$pdf->Write(20, 'Datos del evento', '', 0, 'L', 1, 0, false, false, 0);
				
				
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('helvetica', 'B', 10);	
				$pdf->setY(55);
				$pdf->setX(20);		
				$pdf->Write(10, 'Nombre', '', 0, 'L', 1, 0, false, false, 0);
				$pdf->SetFont('helvetica', '', 10);	
				$pdf->setX(20);
				$pdf->Write(1, $evento->nombre, '', 0, 'L', 1, 0, false, false, 0);
				
				$pdf->SetFont('helvetica', 'B', 10);	
				$pdf->setY(55);
				$pdf->setX(80);		
				$pdf->Write(10, 'Fecha de inicio', '', 0, 'L', 1, 0, false, false, 0);
				$pdf->SetFont('helvetica', '', 10);	
				$pdf->setX(80);
				$pdf->Write(1, $evento->fecha_desde, '', 0, 'L', 1, 0, false, false, 0);
				
				$pdf->SetFont('helvetica', 'B', 10);	
				$pdf->setY(55);
				$pdf->setX(130);		
				$pdf->Write(10, utf8_encode('Fecha de finalización'), '', 0, 'L', 1, 0, false, false, 0);
				$pdf->SetFont('helvetica', '', 10);	
				$pdf->setX(130);
				$pdf->Write(1, $evento->fecha_desde, '', 0, 'L', 1, 0, false, false, 0);
				
				$pdf->Ln(3);
				$pdf->SetFont('helvetica', 'B', 10);	
				$pdf->setX(20);
				$pdf->Write(10, utf8_encode('Descripción'), '', 0, 'L', 1, 0, false, false, 0);
				$pdf->SetFont('helvetica', '', 10);	
				$pdf->setX(20);
				$pdf->Write(1, $evento->descripcion, '', 0, 'L', 1, 0, false, false, 0);
				
				
				//Centro
				$pdf->Ln(10);
				$pdf->SetTextColor(0, 102, 102);
				$pdf->SetFont('helvetica', 'BI', 12);		
				$pdf->Write(10, 'Datos del centro', '', 0, 'L', 1, 0, false, false, 0);
				
			
				$centro=$this->Centro_model->obtenerCentro($evento->id_centro);
				
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetFont('helvetica', 'B', 10);	
				
				$pdf->setX(20);		
				$pdf->Write(10, 'Nombre', '', 0, 'L', 1, 0, false, false, 0);
				$pdf->SetFont('helvetica', '', 10);	
				$pdf->setX(20);
				$pdf->Write(1, $centro->nombre, '', 0, 'L', 1, 0, false, false, 0);
				
				$pdf->Ln(3);
				$pdf->setX(20);
				$pdf->SetFont('helvetica', 'B', 10);	
				$pdf->Write(10, utf8_encode('Dirección'), '', 0, 'L', 1, 0, false, false, 0);
				
				$pdf->setX(20);
				$pdf->SetFont('helvetica', '', 10);	
				$pdf->Write(1, $centro->direccion_string, '', 0, 'L', 1, 0, false, false, 0);
				
				
				$pdf->Ln(5);
				$pdf->SetTextColor(0, 102, 102);
				$pdf->SetFont('helvetica', 'BI', 12);		
				$pdf->Write(20, 'Asistencia a las charlas', '', 0, 'L', 1, 0, false, false, 0);
				
				
				//Asistencia a salas
					
				$pdf->SetFont('helvetica', '', 10);
				
			   
					$data=$this->Charla_model->obtener_asistencia_charla_sala($idevento);
					if(count($data)>0){
						$tabla='<table cellspacing="0" cellpadding="5" border="1">
									 <tr style="font-family:sans-serif;color:#FFFFFF;font-size:30px;">
										<th bgcolor="#006666">Nombre</th>
										<th bgcolor="#006666">Sala</th>
										<th bgcolor="#006666">Interesados</th>
										<th bgcolor="#006666">Asistentes</th>
										<th bgcolor="#006666">Capacidad</th>
									</tr>';
						
						foreach($data as $row) {
							$tabla.='<tr style="font-family:sans-serif;color:#000000;font-size:30px;">';
								$tabla.='<td >'.$row->nombre.'</td>';
								$tabla.='<td >'.$row->sala.'</td>';
								$tabla.='<td >'.$row->interesados.'</td>';
								$tabla.='<td >'.$row->asistentes.'</td>';
								if($row->capacidad)
									$tabla.='<td >'.$row->capacidad.'</td>';
								else
									$tabla.='<td >0</td>';
									
							$tabla.='</tr>';
							
							
						}
						$tabla.='</table>';
					
						$pdf->writeHTML($tabla, true, false, false, false, '');
					
					//gráfico
					
						$this->load->library('jpgraph');
						$cantidad_graficos = $this->jpgraph->barchart_chunk($data); 
						ob_clean();
						for($i=0;$i<$cantidad_graficos;$i++){
							$pdf->AddPage('L');	
							$pdf->Image("./grafico".$i.".png", 20, 20,250 , 160, 'PNG', '', '', true, 300, '', false, false, 1, false, false, false);
						};
					}else{
						$pdf->SetTextColor(0, 0, 0);				
						$pdf->Write(0, 'El evento no posee charlas asociadas.', '', 0, 'L', 1, 0, false, false, 0);
					};				
				$pdf->Output('estadisticaAsistencia_'.date('Y-m-d_H_i_s'), 'D');
				redirect('estadisticas_controller','refresh');
				
				break;
			};
	}
	
	
}
