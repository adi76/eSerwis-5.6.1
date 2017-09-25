<?php include_once('header.php'); ?>
<body>
<?php
if ($_GET[newwindow]!='1') br();
pageheader("Informacje o ustawieniach strony - tak aby dobrze drukowały się protokoły");
pageheader("Ustawienia wydruków w Internet Explorer");
startbuttonsarea("center"); echo "<img src=img/ust_ie.gif border=0>"; endbuttonsarea();
br();
pageheader("Ustawienia wydruków w FireFox'ie");
startbuttonsarea("center"); echo "<img src=img/ust_ff.gif border=0>"; endbuttonsarea();
/*
br();
pageheader("Ustawienia wydruków w Operze");
startbuttonsarea("center"); echo "<img src=img/ust_opera.gif border=0>"; endbuttonsarea();
*/
br();
if ($_GET[newwindow]=='1') {
startbuttonsarea("right");
hr();
addbuttons("zamknij");
endbuttonsarea();
}
?>
</body>
</html>