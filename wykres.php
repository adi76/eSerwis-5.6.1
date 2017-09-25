<html>
<head>
 
<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF("open-flash-chart.swf", "my_chart", "800", "500", "9.0.0", "expressInstall.swf", {"data-file":"chart-data-<?php echo $_GET[filia]; ?>-<?php echo $_GET[whoid]; ?>.php","loading":"Trwa generowanie wykresu"} );
</script>
 
</head>
<body>
 
<div id="my_chart"></div>
 
</body>
</html>