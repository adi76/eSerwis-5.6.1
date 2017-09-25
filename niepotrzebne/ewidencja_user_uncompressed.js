var checkobj;

function check_all() 
{
var c = document.lok;
  c.esk_rola_check.checked=true;
  c.esk_lok_check.checked=true;
  c.esk_user_check.checked=true;
  c.esk_nrpok_check.checked=true;
  c.esk_nrinwz_check.checked=true;
  c.esk_nazwak_check.checked=true;
  c.esk_opisk_check.checked=true;
  c.esk_nrsk_check.checked=true;
  c.esk_nrip_check.checked=true;
  c.esk_endpoint_check.checked=true;
  c.esk_nazwam_check.checked=true;
  c.esk_nrsm_check.checked=true;
  c.esk_nazwad_check.checked=true;
  c.esk_nrsd_check.checked=true;
  c.esk_nrinwd_check.checked=true;
  c.esk_uwagi_check.checked=true;
//  c.esk_status_check.checked=true;
  c.esk_opcje_check.checked=true;  
  c.esk_konf_check.checked=true;  
  c.esk_opcje_check.checked=true;
  
  e_rola();
  e_lok();
  e_user();
  e_nrpok();
  e_nrinwz();
  e_nazwak();
  e_opisk();
  e_nrsk();
  e_nrip();
  e_endpoint();
  e_nazwam();
  e_nrsm();
  e_nazwad();
  e_nrsd();
  e_nrinwd();
  e_konf();
  e_opt();
}

function uncheck_all() 
{
var u = document.lok;
	u.esk_rola_check.checked=false;
	u.esk_lok_check.checked=false;
	u.esk_user_check.checked=false;
	u.esk_nrpok_check.checked=false;
	u.esk_nrinwz_check.checked=false;
	u.esk_nazwak_check.checked=false;
	u.esk_opisk_check.checked=false;
	u.esk_nrsk_check.checked=false;
	u.esk_nrip_check.checked=false;
	u.esk_endpoint_check.checked=false;
	u.esk_nazwam_check.checked=false;
	u.esk_nrsm_check.checked=false;
	u.esk_nazwad_check.checked=false;
	u.esk_nrsd_check.checked=false;
	u.esk_nrinwd_check.checked=false;
	u.esk_uwagi_check.checked=false;
//	  u.esk_status_check.checked=false;
	u.esk_opcje_check.checked=false;  
	u.esk_konf_check.checked=false;  
  
	u.esk_rola.disabled=!u.esk_rola_check.checked;
	u.esk_lok.disabled=!u.esk_lok_check.checked;
	u.esk_user.disabled=!u.esk_user_check.checked; 
	u.esk_nrpok.disabled=!u.esk_nrpok_check.checked; 
	u.esk_nrinwz.disabled=!u.esk_nrinwz_check.checked; 
	u.esk_nazwak.disabled=!u.esk_nazwak_check.checked; 
	u.esk_opisk.disabled=!u.esk_opisk_check.checked;
	u.esk_nrsk.disabled=!u.esk_nrsk_check.checked;
	u.esk_nrip.disabled=!u.esk_nrip_check.checked;
	u.esk_endpoint.disabled=!u.esk_endpoint_check.checked;
	u.esk_nazwam.disabled=!u.esk_nazwam_check.checked;
	u.esk_nrsm.disabled=!u.esk_nrsm_check.checked;
	u.esk_nazwad.disabled=!u.esk_nazwad_check.checked;
	u.esk_nrsd.disabled=!u.esk_nrsd_check.checked;
	u.esk_nrinwd.disabled=!u.esk_nrinwd_check.checked;  
	u.esk_konf.disabled=!u.esk_konf_check.checked;  
	u.esk_opt.disabled=!u.esk_opcje_check.checked;	
	clear_disable();
}

function check_default() 
{
var d=document.lok;
  d.esk_rola_check.checked=true;
  d.esk_lok_check.checked=true;
  d.esk_user_check.checked=false;
  d.esk_nrpok_check.checked=false;
  d.esk_nrinwz_check.checked=true;
  d.esk_nazwak_check.checked=false;
  d.esk_opisk_check.checked=true;
  d.esk_nrsk_check.checked=false;
  d.esk_nrip_check.checked=false;
  d.esk_endpoint_check.checked=false;
  d.esk_nazwam_check.checked=true;
  d.esk_nrsm_check.checked=false;
  d.esk_nazwad_check.checked=true;
  d.esk_nrsd_check.checked=false;
  d.esk_nrinwd_check.checked=false;
  d.esk_uwagi_check.checked=true;
//  d.esk_status_check.checked=false;
  d.esk_opcje_check.checked=true;  
  d.esk_konf_check.checked=false;  
  
  e_rola();
  e_lok();
  e_user();
  e_nrpok();
  e_nrinwz();
  e_nazwak();
  e_opisk();
  e_nrsk();
  e_nrip();
  e_endpoint();
  e_nazwam();
  e_nrsm();
  e_nazwad();
  e_nrsd();
  e_nrinwd();
  e_konf();
  e_opt();
  
}
function e_rola()	{ var e=document.lok; e.esk_rola.disabled=!e.esk_rola_check.checked; e.esk_rola.focus();}
function e_lok()	{ var e=document.lok; e.esk_lok.disabled=!e.esk_lok_check.checked; e.esk_lok.focus(); }
function e_user()	{ var e=document.lok; e.esk_user.disabled=!e.esk_user_check.checked; e.esk_user.focus();}
function e_nrpok()	{ var e=document.lok; e.esk_nrpok.disabled=!e.esk_nrpok_check.checked; e.esk_nrpok.focus();}
function e_nrinwz(){ var e=document.lok; e.esk_nrinwz.disabled=!e.esk_nrinwz_check.checked; e.esk_nrinwz.focus(); }
function e_nazwak(){ var e=document.lok; e.esk_nazwak.disabled=!e.esk_nazwak_check.checked; e.esk_nazwak.focus();}
function e_opisk()	{ var e=document.lok; e.esk_opisk.disabled=!e.esk_opisk_check.checked;e.esk_opisk.focus();}
function e_nrsk()	{ var e=document.lok; e.esk_nrsk.disabled=!e.esk_nrsk_check.checked;e.esk_nrsk.focus();}
function e_nrip()	{ var e=document.lok; e.esk_nrip.disabled=!e.esk_nrip_check.checked;e.esk_nrip.focus();}
function e_endpoint(){ var e=document.lok; e.esk_endpoint.disabled=!e.esk_endpoint_check.checked;e.esk_endpoint.focus();}
function e_nazwam(){ var e=document.lok; e.esk_nazwam.disabled=!e.esk_nazwam_check.checked;e.esk_nazwam.focus();}
function e_nrsm()	{ var e=document.lok; e.esk_nrsm.disabled=!e.esk_nrsm_check.checked;e.esk_nrsm.focus();}
function e_nazwad(){ var e=document.lok; e.esk_nazwad.disabled=!e.esk_nazwad_check.checked;e.esk_nazwad.focus();}
function e_nrsd()	{ var e=document.lok; e.esk_nrsd.disabled=!e.esk_nrsd_check.checked;e.esk_nrpok.focus();}
function e_nrinwd(){ var e=document.lok; e.esk_nrinwd.disabled=!e.esk_nrinwd_check.checked;e.esk_nrinwd.focus();}
function e_konf()	{ var e=document.lok; e.esk_konf.disabled=!e.esk_konf_check.checked;e.esk_konf.focus(); }
function e_opt()	{ var e=document.lok; e.esk_opt.disabled=!e.esk_opcje_check.checked; }
function d_user()		{ var d=document.lok; d.esk_user_ch.checked=(d.esk_user==""); }
function d_nrpok()	{ var d=document.lok; d.esk_nrpok_ch.checked=(d.esk_nrpok==""); }
function d_nrinwz()	{ var d=document.lok; d.esk_nrinwz_ch.checked=(d.esk_nrinwz==""); }
function d_nazwak()	{ var d=document.lok; d.esk_nazwak_ch.checked=(d.esk_nazwak==""); }
function d_opisk()	{ var d=document.lok; d.esk_opisk_ch.checked=(d.esk_opisk==""); }
function d_nrsk()		{ var d=document.lok; d.esk_nrsk_ch.checked=(d.esk_nrsk==""); }
function d_nrip()		{ var d=document.lok; d.esk_nrip_ch.checked=(d.esk_nrip==""); }
function d_endpoint()	{ var d=document.lok; d.esk_endpoint_ch.checked=(d.esk_endpoint==""); }
function d_nazwam()	{ var d=document.lok; d.esk_nazwam_ch.checked=(d.esk_nazwam==""); }
function d_nrsm()		{ var d=document.lok; d.esk_nrsm_ch.checked=(d.esk_nrsm==""); }
function d_nazwad()	{ var d=document.lok; d.esk_nazwad_ch.checked=(d.esk_nazwad==""); }
function d_nrsd()		{ var d=document.lok; d.esk_nrsd_ch.checked=(d.esk_nrsd==""); }
function d_nrinwd()	{ var d=document.lok; d.esk_nrinwd_ch.checked=(d.esk_nrinwd==""); }
function d_konf()		{ var d=document.lok; d.esk_konf_ch.checked=(d.esk_konf==""); }

function clear_disable() {
var c=document.lok;
	c.esk_user_ch.checked;
	c.esk_nrpok_ch.checked=true;
	c.esk_nrinwz_ch.checked=true;
	c.esk_nazwak_ch.checked=true;
	c.esk_opisk_ch.checked=true;
	c.esk_nrsk_ch.checked=true;
	c.esk_nrip_ch.checked=true;
	c.esk_endpoint_ch.checked=true;
	c.esk_nazwam_ch.checked=true;
	c.esk_nrsm_ch.checked=true;
	c.esk_nazwad_ch.checked=true;
	c.esk_nrsd_ch.checked=true;
	c.esk_nrinwd_ch.checked=true;
	c.esk_konf_ch.checked=true;
}