<?php

/*******************************************************************************

  clsJSPHP version 0.12 alpha 
  copyright (c) 2005 by Artur Heinze
  
  - published under the LGPL license -
*******************************************************************************/

define('JSPHP_INC', 1);

//PREVENT BROWSER-CACHING
//------------------------------------------------------------------------------
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");


//JAVASCRIPT OUTPUT
//------------------------------------------------------------------------------
if(isset($_GET['jsphp'])){
?>


//public functions
function jsphp_gcontent(url,prm){
  var jp = new jsPHP('void(0)');
  jp.cmd(url,prm,false);
  return jp.getdata();
}

function jsphp_svalue(id,url,prm,async){
  var jp = new jsPHP('changevalue("'+id+'")');
  jp.cmd(url,prm);
}

function jsphp_shtml(id,url,prm,async){
  var jp = new jsPHP('changehtml("'+id+'")');
  jp.cmd(url,prm,async);
}

function jsphp_exec(url,prm,async){
  var jp = new jsPHP('runjs()');
  jp.cmd(url,prm,async);
}
  
//ajax-handler class
function jsPHP(efunc){
  
  var req;
  var data = null;
  var exefunction = efunc;
  
  this.cmd = cmd;
  this.getdata = getdata;

  // if Mozilla, Safari etc
  if (window.XMLHttpRequest) 
    req = new XMLHttpRequest()
  // if IE
  else if (window.ActiveXObject){ 
    try {
      req = new ActiveXObject("Msxml2.XMLHTTP")
    } 
    catch (e){
      try{ req = new ActiveXObject("Microsoft.XMLHTTP") }catch (e){}
    }
  }else{
    alert('This Browser do not support AJAX-Technology!');
    return ;
  }
  
  //handle status
  req.onreadystatechange = reqreadystatechange;
  
  function reqreadystatechange() {
      if (req.readyState != 4)return;
        if (req.status == 200) {
          data = req.responseText;
          _showstatus(false);
          try {
            eval(exefunction);
          }catch (e){
            alert('ERROR executing result!');
          }
        }else{
          alert('ERROR loading data :-(');
        }
  }
  
  function cmd(url,prm,async){
    if(!prm) prm='';
    if(async!=false && async!=true) async=true;
    
    _doajax(url,prm,async);
  }
  
  function getdata(){
    return data;
  }
  
  function changevalue(id){
    document.getElementById(String(id)).value = data;
  }
  
  function changehtml(id){
    document.getElementById(String(id)).innerHTML = data;
  }
  
  function runjs(){
    eval(data);
  }
  
  function _showstatus(on){
    if(on){
      window.defaultStatus = 'Loading...';
      showstatus();
    }else{
      window.defaultStatus = '';
      closestatus();
    }
    
    return true;
  }
  
  function _doajax(url,prm,async) {
    
    data = null;
    
    if(!url || url==''){
      alert('Requestfile is not set!');
      return;
    }else{ url = 'phpinc='+url ;}
    
    if(prm!='') url += '&' + prm;
    
    _showstatus(true);
    req.open('POST', '<?=$_GET['jsphp'];?>' , async);
    
    req.setRequestHeader("Method", "POST " + '<?=$_GET['jsphp'];?>' + " HTTP/1.1");
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    req.send(url);

  }
  //statusbar functions
  //---------------------------------------------------------------------
  var verticalpos="frombottom" //enter "fromtop" or "frombottom"
  var startX =30;var startY=30;
  
  function _createstatusbar(){
    //statusbar div
    var uiStatus = document.createElement("div");
        uiStatus.setAttribute("style","visibility:hidden;position:absolute;width:400px;left:0px;top:0px;font:normal 9px Tahoma;background:#ffffca;padding:4px;color:#330000;border:1px #999 solid;z-index:100;");
        uiStatus.setAttribute("id","uiStatus");
        
    var statusText = document.createTextNode("Loading...");
        uiStatus.appendChild(statusText);
    
        window.document.documentElement.appendChild(uiStatus);
    
      
  }
  
  function iecompattest(){
  return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
  }
  
  function closestatus(){
    document.getElementById("uiStatus").style.visibility="hidden"
  }
  
  function showstatus(){
  	if(!document.getElementById("uiStatus")) _createstatusbar();
    
    barheight=document.getElementById("uiStatus").offsetHeight
  	var ns = (navigator.appName.indexOf("Netscape") != -1) || window.opera;
  	var d = document;
  	function ml(id){
  		var el=d.getElementById(id);
  		el.style.visibility="visible"
  		if(d.layers)el.style=el;
  		el.sP=function(x,y){this.style.left=x+"px";this.style.top=y+"px";};
  		el.x = startX;
  		if (verticalpos=="fromtop")
  		el.y = startY;
  		else{
  		el.y = ns ? pageYOffset + innerHeight : iecompattest().scrollTop + iecompattest().clientHeight;
  		el.y -= startY;
  		}
  		return el;
  	}
  	window.stayTopLeft=function(){
  		if (verticalpos=="fromtop"){
  		var pY = ns ? pageYOffset : iecompattest().scrollTop;
  		ftlObj.y += (pY + startY - ftlObj.y)/8;
  		}
  		else{
  		var pY = ns ? pageYOffset + innerHeight - barheight: iecompattest().scrollTop + iecompattest().clientHeight - barheight;
  		ftlObj.y += (pY - startY - ftlObj.y)/8;
  		}
  		ftlObj.sP(ftlObj.x, ftlObj.y);
  		setTimeout("stayTopLeft()", 10);
  	}
  	ftlObj = ml("uiStatus");
  	stayTopLeft();
  }


}

<?
exit();
}

//CLASS DEFINITION
//------------------------------------------------------------------------------

  class clsJSPHP
  { 
  
    var $strJS = '';
    
    function clsJSPHP(){
      
    
    }
    
    function innerHTML($targetid,$text){
      
      $js.= 'document.getElementById("'.$targetid.'").innerHTML="'.$text.'";';
      $this->addScript($js);
    
    }
    
    function setStyle($targetid,$attribute,$style){
      
      $js.= 'document.getElementById("'.$targetid.'").style.'.$attribute.'="'.$style.'";';
      
      $this->addScript($js);
    }
    
    function setAttributte($targetid,$attribute,$value){
      
      $js.= 'document.getElementById("'.$targetid.'").'.$attribute.'="'.$value.'";';
      
      $this->addScript($js);
    }
    
    function addOption($selectid,$optiontext,$optionvalue){
      
      $js = 'var objOption = new Option("'.$optiontext.'","'.$optionvalue.'");';
      $js.= 'document.getElementById("'.$selectid.'").options.add(objOption);';
      
      $this->addScript($js);
    }
    
    function addAlert($msg){
      $js = 'alert("'.$msg.'");';
      $this->addScript($js);
    }
    
    function addScript($script){
      $this->strJS.=$script;
    }
    
    function output(){
      echo $this->strJS;
    }
    
  } // END class 
  
  //HANDLE  REQUEST
  if(isset($_REQUEST['phpinc'])){
    
    $jsphp = & new clsJSPHP();
    
    // Start the output buffer for storing away all generated stuff
    ob_start('gz_handler');
    ob_implicit_flush(0);
    
      include_once($_REQUEST['phpinc']);
  
    $contents = ob_get_contents();
    ob_end_clean();
    
    echo $contents;
    
  }

?>
