<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
unlink($path);
?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
} else {
errorheader("Czy napewno chcesz usunąć załącznik o nazwie<br /><br /><font color=white>".$_GET[nazwapliku]."</font>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input name=submit type=submit class=buttons value='TAK'>";
echo "<input type=button class=buttons value='NIE' onClick='self.close();'>";
echo "<input type=hidden name=path value='$_GET[path]'>";
endbuttonsarea();
_form();
}
?>
</body>
</html>