<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
$_POST=sanitize($_POST);
	if (mysql_query("UPDATE $dbname.serwis_admin SET admin_value=$_POST[a] WHERE (admin_id=2) LIMIT 1", $conn)) { 
		$es_block=$_POST[a];
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji zapisu do bazy'); self.close(); </script><?php
	}
} else { ?>
<?php

if ($a==1) { 
	errorheader("Czy napewno chcesz przełączyć bazę eSerwis w tryb konserwacji ?"); 
} else { 
	errorheader("Czy napewno chcesz przełączyć bazę eSerwis w tryb pracy normalnej ?");
}
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$id>";
echo "<input type=hidden name=a value=$a>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
} 
?>
</body>
</html>