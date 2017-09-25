<?php 
ob_start();

include 'php-ofc-library/open-flash-chart.php';

$data = array();

$bar = new bar_value($_GET[p1]);
$bar->set_colour( '#F73B3B' );
$bar->set_tooltip( 'Ilość awarii krytycznych<br>#val#' );
$data[] = $bar;

$bar = new bar_value($_GET[p2]);
$bar->set_colour( '#FF7F2A' );
$bar->set_tooltip( 'Ilość awarii zwykłych<br>#val#' );
$data[] = $bar;

$bar = new bar_value($_GET[p3]);
$bar->set_colour( '#AD7D5B' );
$bar->set_tooltip( 'Prace wg umowy<br>#val#' );
$data[] = $bar;

$bar = new bar_value($_GET[p4]);
$bar->set_colour( '#B7516B' );
$bar->set_tooltip( 'Konsultacje<br>#val#' );
$data[] = $bar;

$bar = new bar_value($_GET[p5]);
$bar->set_colour( '#51BB5A' );
$bar->set_tooltip( 'Prace zlecone poza umową<br>#val#' );
$data[] = $bar;

if ($_GET[ran]=='all') {
	if ($_GET[who]!='1') {
		$title = new title( "Wszystkie zgłoszenia z bazy helpdesk dla ".$_GET[who]." (Łączna ilość : ".$_GET[razem].")");
	} else {
		$title = new title( "Wszystkie zgłoszenia z bazy helpdesk (Łączna ilość : ".$_GET[razem].")" );
	}
} 
if ($_GET[ran]=='notclosed') {
	if ($_GET[who]!='1') {
		$title = new title( "Zgłoszenia nie zamknięte z bazy helpdesk dla ".$_GET[who]." (Łączna ilość : ".$_GET[razem].")" );
	} else {
		$title = new title( "Zgłoszenia nie zamknięte z bazy helpdesk (Łączna ilość : ".$_GET[razem].")" );
	}
}

$bar = new bar_3d();
$bar->set_values( $data );
$bar->colour = '#1E5873';

$bar->set_on_show('grow_up', '1', '1');

$x_labels = new x_axis_labels();
$x_labels->rotate(90);
$x_labels->set_labels(array( 'Awarie krytyczne','Awarie zwykłe', 'Prace wg umowy','Konsultacje', 'Prace zlecone poza umową' ) );

$x_axis = new x_axis();
$x_axis->set_3d( 6 );
$x_axis->colour = '#909090';
//$x_axis->set_labels_from_array( array

$x_axis->set_labels( $x_labels );

$y = new y_axis();
$maxY = $_GET[maxv];

$y->set_range( 0, $maxY , 5 );

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar );
$chart->set_x_axis( $x_axis );
$chart->set_y_axis( $y );
$chart->set_bg_colour( '#FFFFFF' );

echo $chart->toPrettyString();

$page = ob_get_contents();
ob_end_flush();
$fp = fopen("chart-data-".$_GET[filia]."-".$_GET[whoid].".php","w");
fwrite($fp,$page);
fclose($fp);

?>
<script>
self.location.href="wykres.php?filia=<?php echo "$_GET[filia]"; ?>&whoid=<?php echo "$_GET[whoid]"; ?>";
</script>

</body>
</html>