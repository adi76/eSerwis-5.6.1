<?php include_once('header.php'); ?>
<body>
<?php
switch ($id) {
	case "1"			: 	infoheader("Pomoc");
							echo "Możliwe akceptowalne formy wpisania adresu IP do blokady:<br />";
							echo "1. 192.168.0.1 - blokada jednego IP<br />";
							echo "2. 192.168.*.* - blokada wszystkich adresów IP z podsieci 192.168<br />";
							echo "3. *.*.*.100 - blokada wszystkich komputerów których ostatni oktet=100<br />";
							break;

}

br();
hr();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>