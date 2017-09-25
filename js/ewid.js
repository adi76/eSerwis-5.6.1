function ewid(w) 
{
	document.getElementById('lokalizacja1').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja2').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja3').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja4').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja5').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja6').style.display=(w!=0) ? '' : 'none';

	document.getElementById('lokalizacja7').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja8').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja9').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja10').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja11').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja12').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja13').style.display=(w!=0) ? '' : 'none';
	document.getElementById('lokalizacja14').style.display=(w!=0) ? '' : 'none';
	
	// W=1 KOMPUTER, W=2 SERWER, W=18 NOTEBOOK
	document.getElementById('zestaw1').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	document.getElementById('zestaw2').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	document.getElementById('zestaw3').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	
	document.getElementById('zestaw4').style.display=((w==1) || (w==2) || (w==18) || (w==17) || (w==30) || (w==10) || (w==11) || (w==29) || (w==4)) ? '' : 'none';
	
	document.getElementById('zestaw5').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	
	document.getElementById('zestaw6').style.display=((w==1) || (w==2) || (w==18) || (w==17) || (w==30) || (w==10) || (w==11) || (w==29)) ? '' : 'none';
	document.getElementById('zestaw7').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	
	document.getElementById('zestaw8').style.display=((w==1) || (w==2) || (w==18) || (w==17) || (w==30) || (w==10) || (w==11) || (w==29) || (w==4)) ? '' : 'none';
	
	document.getElementById('zestaw9').style.display=((w==1) || (w==2) || (w==18) || (w==3)) ? '' : 'none';
	document.getElementById('zestaw10').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	document.getElementById('zestaw11').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	document.getElementById('zestaw12').style.display=((w==1) || (w==2) || (w==18)) ? '' : 'none';
	document.getElementById('zestaw13').style.display=((w==1) || (w==2)) ? '' : 'none';
	document.getElementById('zestaw14').style.display=((w==1) || (w==2)) ? '' : 'none';
	document.getElementById('zestaw15').style.display=((w==1) || (w==2)) ? '' : 'none';

	if ((w==1) || (w==2) || (w==18)) {
//	wlacz_slownik();
	document.addewid.sk.style.display='';
	document.addewid.konf_proc.style.display='none';
	document.addewid.konf_ram.style.display='none';
	document.addewid.konf_hdd.style.display='none';
	} else 
	{
		document.getElementById('wpiszsam').style.display='none';
		document.getElementById('slownik').style.display='none';
	}
		
	//  W=3 DRUKARKA
	document.getElementById('druk1').style.display=(w==3) ? '' : 'none';
	document.getElementById('druk2').style.display=(w==3) ? '' : 'none';
	document.getElementById('druk3').style.display=(w==3) ? '' : 'none';
	document.getElementById('druk4').style.display=(w==3) ? '' : 'none';
	document.getElementById('druk5').style.display=(w==3) ? '' : 'none';
	document.getElementById('druk6').style.display=(w==3) ? '' : 'none';
	
	//  W=4 CZYTNIK
	document.getElementById('czytnik1').style.display=(w==4) ? '' : 'none';
}