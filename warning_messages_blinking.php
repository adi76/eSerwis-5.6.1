<?php if ($wlacz_miganie_ostrzegawcze) { ?>
<script>
	var blink2 = function() { $('.blinking').css("background-color","white").css("color","red"); };
	var blink1 = function() { $('.blinking').css("background-color","red").css("color","white"); };
	var blink3 = function() { $('.blinking2').css("background-color","transparent").css("color","#FFFF00"); };
	var blink4 = function() { $('.blinking3').css("background-color","transparent").css("color","#00FFFF"); };
	
	<?php if ($__add_refresh==1) { ?>
		var refr1 = function() { self.location.reload(true); };
	<?php } ?>

	$(document).ready(function() { 
		setInterval(blink1, 1000); 
		setInterval(blink3, 1000); 
		setInterval(blink4, 1500);
		
		setInterval(blink2, 1500);
	
		<?php if ($__add_refresh==1) { ?>
		setInterval(refr1,300000);
		<?php } ?>

	});
</script>
<?php } ?>
