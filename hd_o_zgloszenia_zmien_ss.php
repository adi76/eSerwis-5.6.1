<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body>";

$sql555 = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_czas_start_stop='$_REQUEST[newSS]' WHERE (zgl_szcz_id='$_REQUEST[kid]') LIMIT 1";

if (mysql_query($sql555, $conn_hd)) {
		?>
		<script>
			newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?nr=<?php echo $_REQUEST[nr]; ?>');
			if (opener) opener.location.reload(true); self.close(); 
		</script>
		<?php

} else { 
	?><script>alert('Wystąpił błąd podczas zapisywania zmian'); </script><?php 
} ?>
</body>
</html>