<?php 
session_start();
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }
require 'cfg_eserwis.php';
require 'phplot.php'; 
$sql_p = "SELECT * FROM $dbname.serwis_plot WHERE belongs_to=$es_filia ORDER BY plot_x1 DESC";
$result_p = mysql_query($sql_p, $conn) or die($k_b);
while ($konfiguracja = mysql_fetch_array($result_p))
{
	$typ	 	= $konfiguracja['plot_name'];
	$typ_x1 	= $konfiguracja['plot_x1'];
	$typ_x2 	= $konfiguracja['plot_x2'];
	$typ_x3 	= $konfiguracja['plot_x3'];
	$typ_x4		= $konfiguracja['plot_x4'];
	$typ_color	= $konfiguracja['plot_color'];

	if ($typ!='') $data[] = array($typ, $typ_x1);
}
		$plot = new PHPlot(1024, 768);
		$plot->SetImageBorderType('plain');
		$plot->SetPlotType('bars');
		$plot->SetDataType('text-data');
		$plot->SetDataValues($data);
		
		$plot->SetXTickLabelPos('none');
		$plot->SetXTickPos('none');
		
		$plot->SetFileFormat(jpg);
		$plot->SetBrowserCache(false);
		$plot->SetYDataLabelPos('plotin');
		$plot->SetXDataLabelPos('plotdown');
		$plot->SetYTickCrossing(0);

		$plot->SetXLabelAngle(90);

		$plot->SetFont(generic, generic, 12);
		$plot->SetPlotAreaWorld(NULL, 0);

		$plot->SetYTitle('Sztuk');
		$plot->SetDataColors(array($typ_color)); 		

		switch ($rodzaj) {
			case '1' : $plot->SetTitle('Zestawienie ilosciowe sprzetu komputerowego');
						break;
			case '2' : $plot->SetTitle('Zestawienie ilosciowe drukarek');
						break;
			case '3' : $plot->SetTitle('Zestawienie ilosciowe monitorow');
						break;
			case '4' : $plot->SetTitle('Zestawienie ilosciowe oprogramowania');
						break;
		}
		$plot->DrawGraph();
?>