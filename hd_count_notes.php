<?php 

include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

//$dd = Date('Y-m-d');
$result11 = mysql_query("SELECT COUNT(note_id) FROM $dbname_hd.hd_notes WHERE (belongs_to=$es_filia) and (note_status=1) AND ((note_user_id='$es_nr') or (note_creator='$es_nr'))", $conn_hd) or die($k_b);
$newArr = mysql_fetch_array($result11);
//$RazemNotes = minutes2hours($newArr[0],'short');
$RazemNotes = $newArr[0];

?><script>
document.getElementById("count_notatki_moje").innerHTML='<font color=blue>&nbsp;<?php echo $RazemNotes; ?></font>';
</script>
<?php
if ($RazemNotes>0) {
?>
<script>
document.getElementById("linia_oddzielajaca").style.display='';
</script>
<?php 
} else { 
?>
<script>
document.getElementById("linia_oddzielajaca").style.display='none';
</script>
<?php } ?>