//<![CDATA[
	var clicked5 = '';
	var remember = '';
	function rowOver_color(which5, what5, kolor) {
			var changed5 = document.getElementById(which5);
			if (which5 != clicked5) {
					if (what5 == 1) changed5.style.backgroundColor = '#FFFFFF';
					else changed5.style.backgroundColor = kolor;
			}
	}

	function resetRow_color(which5,kolor) {
			var changed5 = document.getElementById(which5);
			changed5.style.backgroundColor = kolor;	
	}
	
	function changeSelect_color(which5) {
			var changed5 = document.getElementById(which5);
			changed5.style.backgroundColor = '#FFFFFF';
			changed5.onMouseOver = '';
			changed5.onMouseOut = '';
	}
	
	function selectRow_color(which5,kolor) { 
			if (clicked5 != '') {
					resetRow_color(clicked5);
					clicked5 = which5;
					changeSelect_color(which5);

					remember = which5;
			} else if (clicked5 == '') {
					clicked5 = which5;
					changeSelect_color(which5);
					
			}
	}
	
	function deSelectRow_color(which5,kolor) {
			resetRow_color(which5);
			clicked5 = '';
	
	}
//]]>