 <?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 function extraer_nombre_charla($item){
		return wordwrap($item->nombre, 10, "\n");
 
 }
 
  function extraer_interesados($item){
		return $item->interesados;
 
 }
 
   function extraer_asistentes($item){
		return $item->asistentes;
 
 }
 
   function extraer_capacidad($item){
		
		return $item->capacidad?$item->capacidad:0;
 
 }
 
 

class Jpgraph {

	function barchart_chunk($data=''){
	
		$chunks = array_chunk($data, 10, false);
		$i=0;
		foreach ($chunks as $subdata) {
			$this->barchart($subdata,$i);
			$i++;
		
		}
		return $i;
	}
	
	
	


    function barchart($data='',$i)
    {		
			if($data=='') return;	
			require_once ('jpgraph/jpgraph.php');
			require_once ('jpgraph/jpgraph_bar.php');
			 
			$datay1=array_map("extraer_interesados", $data);
			$datay2=array_map("extraer_asistentes", $data);
			$datay3=array_map("extraer_capacidad", $data);
			 
			$graph = new Graph(800,600,'auto');    
			$graph->SetScale("textlin");
			$graph->SetShadow();
			$graph->img->SetMargin(50,20,50,30);
			
			$graph->xaxis->SetTickLabels(array_map("extraer_nombre_charla", $data));
			 
			$graph->xaxis->title->Set('Charlas');
			$graph->xaxis->SetTitleMargin(50);
			$graph->yaxis->title->Set('Cantidad de personas');
			$graph->yaxis->SetTitleMargin(32);
			$graph->xaxis->title->SetFont(FF_ARIAL,FS_BOLD);
			 
			$graph->title->Set('Estadística de asistencia a charlas');
			$graph->title->SetFont(FF_ARIAL,FS_BOLD);
			 
			$bplot1 = new BarPlot($datay1);
			$bplot2 = new BarPlot($datay2);
			$bplot3 = new BarPlot($datay3);
			 
			$bplot1->SetFillColor("orange");
			$bplot2->SetFillColor("brown");
			$bplot3->SetFillColor("darkgreen");
			 
			$bplot1->SetShadow();
			$bplot2->SetShadow();
			$bplot3->SetShadow();
			 
			$bplot1->SetShadow();
			$bplot2->SetShadow();
			$bplot3->SetShadow();
			
			
			$graph->xaxis->SetTickSize(10,10);
			 
			$gbarplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3));
			$gbarplot->SetWidth(0.6);
			
			
			
			$bplot1->SetLegend("Interesados");
			$bplot2->SetLegend("Asistentes");
			$bplot3->SetLegend("Capacidad de la sala");
			
			
			
			
			$graph->Add($gbarplot);

			
			 
			// Finally stroke the graph
			$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
			$graph->img->Stream("./grafico".$i.".png");


    }
}  