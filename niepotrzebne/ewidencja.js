var checkobj;

function check_all() 
{
  document.ewidlok.esk_rola_check.checked=true;
  document.ewidlok.esk_lok_check.checked=true;
  document.ewidlok.esk_user_check.checked=true;
  document.ewidlok.esk_nrpok_check.checked=true;
  document.ewidlok.esk_nrinwz_check.checked=true;
  document.ewidlok.esk_nazwak_check.checked=true;
  document.ewidlok.esk_opisk_check.checked=true;
  document.ewidlok.esk_nrsk_check.checked=true;
  document.ewidlok.esk_nrip_check.checked=true;
  document.ewidlok.esk_endpoint_check.checked=true;
  document.ewidlok.esk_nazwam_check.checked=true;
  document.ewidlok.esk_nrsm_check.checked=true;
  document.ewidlok.esk_nazwad_check.checked=true;
  document.ewidlok.esk_nrsd_check.checked=true;
  document.ewidlok.esk_nrinwd_check.checked=true;
  document.ewidlok.esk_uwagi_check.checked=true;
//  document.ewidlok.esk_status_check.checked=true;
  document.ewidlok.esk_opcje_check.checked=true;  
  document.ewidlok.esk_konf_check.checked=true;  
  document.ewidlok.esk_opcje_check.checked=true;
  
  enable_rola();
  enable_lok();
  enable_user();
  enable_nrpok();
  enable_nrinwz();
  enable_nazwak();
  enable_opisk();
  enable_nrsk();
  enable_nrip();
  enable_endpoint();
  enable_nazwam();
  enable_nrsm();
  enable_nazwad();
  enable_nrsd();
  enable_nrinwd();
  enable_konf();
  enable_opt();
}

function uncheck_all() 
{
	  document.ewidlok.esk_rola_check.checked=false;
	  document.ewidlok.esk_lok_check.checked=false;
	  document.ewidlok.esk_user_check.checked=false;
	  document.ewidlok.esk_nrpok_check.checked=false;
	  document.ewidlok.esk_nrinwz_check.checked=false;
	  document.ewidlok.esk_nazwak_check.checked=false;
	  document.ewidlok.esk_opisk_check.checked=false;
	  document.ewidlok.esk_nrsk_check.checked=false;
	  document.ewidlok.esk_nrip_check.checked=false;
	  document.ewidlok.esk_endpoint_check.checked=false;
	  document.ewidlok.esk_nazwam_check.checked=false;
	  document.ewidlok.esk_nrsm_check.checked=false;
	  document.ewidlok.esk_nazwad_check.checked=false;
	  document.ewidlok.esk_nrsd_check.checked=false;
	  document.ewidlok.esk_nrinwd_check.checked=false;
	  document.ewidlok.esk_uwagi_check.checked=false;
//	  document.ewidlok.esk_status_check.checked=false;
	  document.ewidlok.esk_opcje_check.checked=false;  
	  document.ewidlok.esk_konf_check.checked=false;  
  
	document.ewidlok.esk_rola.disabled=!document.ewidlok.esk_rola_check.checked;
	document.ewidlok.esk_lok.disabled=!document.ewidlok.esk_lok_check.checked;
	document.ewidlok.esk_user.disabled=!document.ewidlok.esk_user_check.checked; 
	document.ewidlok.esk_nrpok.disabled=!document.ewidlok.esk_nrpok_check.checked; 
	document.ewidlok.esk_nrinwz.disabled=!document.ewidlok.esk_nrinwz_check.checked; 
	document.ewidlok.esk_nazwak.disabled=!document.ewidlok.esk_nazwak_check.checked; 
	document.ewidlok.esk_opisk.disabled=!document.ewidlok.esk_opisk_check.checked;
	document.ewidlok.esk_nrsk.disabled=!document.ewidlok.esk_nrsk_check.checked;
	document.ewidlok.esk_nrip.disabled=!document.ewidlok.esk_nrip_check.checked;
	document.ewidlok.esk_endpoint.disabled=!document.ewidlok.esk_endpoint_check.checked;
	document.ewidlok.esk_nazwam.disabled=!document.ewidlok.esk_nazwam_check.checked;
	document.ewidlok.esk_nrsm.disabled=!document.ewidlok.esk_nrsm_check.checked;
	document.ewidlok.esk_nazwad.disabled=!document.ewidlok.esk_nazwad_check.checked;
	document.ewidlok.esk_nrsd.disabled=!document.ewidlok.esk_nrsd_check.checked;
	document.ewidlok.esk_nrinwd.disabled=!document.ewidlok.esk_nrinwd_check.checked;  
	document.ewidlok.esk_konf.disabled=!document.ewidlok.esk_konf_check.checked;  
	document.ewidlok.esk_opt.disabled=!document.ewidlok.esk_opcje_check.checked;	
	clear_disable();
}

function check_default() 
{
  document.ewidlok.esk_rola_check.checked=true;
  document.ewidlok.esk_lok_check.checked=true;
  document.ewidlok.esk_user_check.checked=false;
  document.ewidlok.esk_nrpok_check.checked=false;
  document.ewidlok.esk_nrinwz_check.checked=true;
  document.ewidlok.esk_nazwak_check.checked=false;
  document.ewidlok.esk_opisk_check.checked=true;
  document.ewidlok.esk_nrsk_check.checked=false;
  document.ewidlok.esk_nrip_check.checked=false;
  document.ewidlok.esk_endpoint_check.checked=false;
  document.ewidlok.esk_nazwam_check.checked=true;
  document.ewidlok.esk_nrsm_check.checked=false;
  document.ewidlok.esk_nazwad_check.checked=true;
  document.ewidlok.esk_nrsd_check.checked=false;
  document.ewidlok.esk_nrinwd_check.checked=false;
  document.ewidlok.esk_uwagi_check.checked=true;
//  document.ewidlok.esk_status_check.checked=false;
  document.ewidlok.esk_opcje_check.checked=true;  
  document.ewidlok.esk_konf_check.checked=false;  
  
  enable_rola();
  enable_lok();
  enable_user();
  enable_nrpok();
  enable_nrinwz();
  enable_nazwak();
  enable_opisk();
  enable_nrsk();
  enable_nrip();
  enable_endpoint();
  enable_nazwam();
  enable_nrsm();
  enable_nazwad();
  enable_nrsd();
  enable_nrinwd();
  enable_konf();
  enable_opt();
  
}
function enable_rola()	{ document.ewidlok.esk_rola.disabled=!document.ewidlok.esk_rola_check.checked; document.ewidlok.esk_rola.focus();}
function enable_lok()	{ document.ewidlok.esk_lok.disabled=!document.ewidlok.esk_lok_check.checked; document.ewidlok.esk_lok.focus(); }
function enable_user()	{ document.ewidlok.esk_user.disabled=!document.ewidlok.esk_user_check.checked; document.ewidlok.esk_user.focus();}
function enable_nrpok()	{ document.ewidlok.esk_nrpok.disabled=!document.ewidlok.esk_nrpok_check.checked; document.ewidlok.esk_nrpok.focus();}
function enable_nrinwz(){ document.ewidlok.esk_nrinwz.disabled=!document.ewidlok.esk_nrinwz_check.checked; document.ewidlok.esk_nrinwz.focus(); }
function enable_nazwak(){ document.ewidlok.esk_nazwak.disabled=!document.ewidlok.esk_nazwak_check.checked; document.ewidlok.esk_nazwak.focus();}
function enable_opisk()	{ document.ewidlok.esk_opisk.disabled=!document.ewidlok.esk_opisk_check.checked;document.ewidlok.esk_opisk.focus();}
function enable_nrsk()	{ document.ewidlok.esk_nrsk.disabled=!document.ewidlok.esk_nrsk_check.checked;document.ewidlok.esk_nrsk.focus();}
function enable_nrip(){ document.ewidlok.esk_nrip.disabled=!document.ewidlok.esk_nrip_check.checked;document.ewidlok.esk_nrip.focus();}
function enable_endpoint(){ document.ewidlok.esk_endpoint.disabled=!document.ewidlok.esk_endpoint_check.checked;document.ewidlok.esk_endpoint.focus();}
function enable_nazwam(){ document.ewidlok.esk_nazwam.disabled=!document.ewidlok.esk_nazwam_check.checked;document.ewidlok.esk_nazwam.focus();}
function enable_nrsm(){ document.ewidlok.esk_nrsm.disabled=!document.ewidlok.esk_nrsm_check.checked;document.ewidlok.esk_nrsm.focus();}
function enable_nazwad(){ document.ewidlok.esk_nazwad.disabled=!document.ewidlok.esk_nazwad_check.checked;document.ewidlok.esk_nazwad.focus();}
function enable_nrsd(){ document.ewidlok.esk_nrsd.disabled=!document.ewidlok.esk_nrsd_check.checked;document.ewidlok.esk_nrpok.focus();}
function enable_nrinwd(){ document.ewidlok.esk_nrinwd.disabled=!document.ewidlok.esk_nrinwd_check.checked;document.ewidlok.esk_nrinwd.focus();}
function enable_konf(){ document.ewidlok.esk_konf.disabled=!document.ewidlok.esk_konf_check.checked;document.ewidlok.esk_konf.focus(); }
function enable_opt()	{ document.ewidlok.esk_opt.disabled=!document.ewidlok.esk_opcje_check.checked; }


function disable_user()		{  document.ewidlok.esk_user_checkw.checked=(document.ewidlok.esk_user==""); }
function disable_nrpok()	{  document.ewidlok.esk_nrpok_checkw.checked=(document.ewidlok.esk_nrpok==""); }
function disable_nrinwz()	{  document.ewidlok.esk_nrinwz_checkw.checked=(document.ewidlok.esk_nrinwz==""); }
function disable_nazwak()	{  document.ewidlok.esk_nazwak_checkw.checked=(document.ewidlok.esk_nazwak==""); }
function disable_opisk()	{  document.ewidlok.esk_opisk_checkw.checked=(document.ewidlok.esk_opisk==""); }
function disable_nrsk()		{  document.ewidlok.esk_nrsk_checkw.checked=(document.ewidlok.esk_nrsk==""); }
function disable_nrip()		{  document.ewidlok.esk_nrip_checkw.checked=(document.ewidlok.esk_nrip==""); }
function disable_endpoint()	{  document.ewidlok.esk_endpoint_checkw.checked=(document.ewidlok.esk_endpoint==""); }
function disable_nazwam()	{  document.ewidlok.esk_nazwam_checkw.checked=(document.ewidlok.esk_nazwam==""); }
function disable_nrsm()		{  document.ewidlok.esk_nrsm_checkw.checked=(document.ewidlok.esk_nrsm==""); }
function disable_nazwad()	{  document.ewidlok.esk_nazwad_checkw.checked=(document.ewidlok.esk_nazwad==""); }
function disable_nrsd()		{  document.ewidlok.esk_nrsd_checkw.checked=(document.ewidlok.esk_nrsd==""); }
function disable_nrinwd()	{  document.ewidlok.esk_nrinwd_checkw.checked=(document.ewidlok.esk_nrinwd==""); }
function disable_konf()		{  document.ewidlok.esk_konf_checkw.checked=(document.ewidlok.esk_konf==""); }

function clear_disable() 
{
document.ewidlok.esk_user_checkw.checked;
document.ewidlok.esk_nrpok_checkw.checked=true;
document.ewidlok.esk_nrinwz_checkw.checked=true;
document.ewidlok.esk_nazwak_checkw.checked=true;
document.ewidlok.esk_opisk_checkw.checked=true;
document.ewidlok.esk_nrsk_checkw.checked=true;
document.ewidlok.esk_nrip_checkw.checked=true;
document.ewidlok.esk_endpoint_checkw.checked=true;
document.ewidlok.esk_nazwam_checkw.checked=true;
document.ewidlok.esk_nrsm_checkw.checked=true;
document.ewidlok.esk_nazwad_checkw.checked=true;
document.ewidlok.esk_nrsd_checkw.checked=true;
document.ewidlok.esk_nrinwd_checkw.checked=true;
document.ewidlok.esk_konf_checkw.checked=true;
}