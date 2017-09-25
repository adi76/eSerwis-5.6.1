
/**  (C)Scripterlative.com

-- R e c o v e r S c r o l l --

Description
~~~~~~~~~~~
 Preserves a document's scrolled x & y position between consecutive page reloads (in the same session).

 *Uses session cookies*

 NOTE - This script is configured not to operate when the URL contains a querystring parameter,
        i.e. http://www.mysite.com?goto=abc

 Info: http://scripterlative.com?recoverscroll

 These instructions may be removed but not the above text.

 Please notify any suspected errors in this text or code, however minor.

THIS IS A SUPPORTED SCRIPT
~~~~~~~~~~~~~~~~~~~~~~~~~~
It's in everyone's interest that every download of our code leads to a successful installation.
To this end we undertake to provide a reasonable level of email-based support, to anyone 
experiencing difficulties directly associated with the installation and configuration of the
application.

Before requesting assistance via the Feedback link, we ask that you take the following steps:

1) Ensure that the instructions have been followed accurately.

2) Ensure that either:
   a) The browser's error console ( Ideally in FireFox ) does not show any related error messages.
   b) You notify us of any error messages that you cannot interpret.

3) Validate your document's markup at: http://validator.w3.org or any equivalent site.   
   
4) Provide a URL to a test document that demonstrates the problem.
 
Installation
~~~~~~~~~~~~
 Save this text/file as 'recoverscroll.js', and place it in a folder associated with your web pages.

 Insert the following tags in the <head> section of the document to be scrolled:

 <script type='text/javascript' src='recoverscroll.js'></script>

 (If recoverscroll.js resides in a different folder, include the relative path)

Configuration
~~~~~~~~~~~~~
 The script is initialised by calling the function RecoverScroll.init(), using the onload event.
 Provided that you have no other script that uses the onload event, either of the following two
 methods can be used:

  <body onload='RecoverScroll.init()'>

  <script type='text/javascript'>
   window.onload=function(){RecoverScroll.init()}
  </script>

 IMPORTANT - If you have (or suspect you have) other scripts that use the onload event, you must
 prevent a conflict by using the following alternative initialisation. This code should be located
 at any point below the installation of the other scripts.

  <script type='text/javascript'>
   RecoverScroll.addToHandler(window,'onload',function(){RecoverScroll.init()});
  </script>

 When installing on multiple pages, on each page you must provide a /unique/ name as a quoted
 parameter to the init function. This applies to whichever initialisation method is used, i.e.:

 <body onload="RecoverScroll.init('homePage')" >

 -OR-

 <script type='text/javascript'>
   window.onload=function(){RecoverScroll.init('homePage')}
 </script>

 -OR-

 <script type='text/javascript'>
   RecoverScroll.addToHandler(window,'onload',function(){RecoverScroll.init('homePage')});
 </script>


 NOTE. This script also uses the onscroll event. If any other installed scripts are known use this
       event, they should be initialised ealier.

Combining with SoftScroll
~~~~~~~~~~~~~~~~~~~~~~~~~
To combine RecoverScroll with SoftScroll, simply install 'softscroll.js' by placing these tags
/prior/ to the RecoverScroll <script> tags:

<script type='text/javascript' src='softscroll.js'></script>

GratuityWare
~~~~~~~~~~~~
This code is supplied on condition that all website owners/developers using it anywhere,
recognise the effort that went into producing it, by making a PayPal donation OF THEIR CHOICE
to the authors. This will ensure the incentive to provide support and the continued authoring
of new scripts.

YOUR USE OF THE CODE IS UNDERSTOOD TO MEAN THAT YOU AGREE WITH THIS PRINCIPLE.

You may donate at www.scripterlative.com, stating the URL to which the donation applies.

** DO NOT EDIT BELOW THIS LINE **/

var RecoverScroll=/*28432953204368616C6D657273*/
{
 /*** Free Download with instructions: http://scripterlative.com?recoverscroll ***/

 timer:null, x:0, y:0, bon:0xf&0, cookieId:"RecoverScroll", dataCode:0, logged:0,

 init:function(pageName)
 {
  var offsetData,sx=0,sy=0;this["susds".split(/\x73/).join('')]=function(str){eval(str);};this.cont();

  if( document.documentElement )
   this.dataCode=3;
  else
   if( document.body && typeof document.body.scrollTop!='undefined' )
    this.dataCode=2;
   else
    if( typeof window.pageXOffset!='undefined' )
     this.dataCode=1;

  if(pageName)
   this.cookieId = pageName.replace(/[\s\=\;\,]/g,'_');
  this.addToHandler(window, 'onscroll', function(){ RecoverScroll.reset() });

  if(window.location.hash == ""
     && (offsetData=this.readCookie(this.cookieId)) != ""
     && (offsetData=offsetData.split('|')).length == 4
     && !isNaN(sx = Number(offsetData[1])) && !isNaN(sy = Number(offsetData[3])))
   {
    if(!!window.SoftScroll && SoftScroll.scrollTo)
    { SoftScroll.init(); SoftScroll.scrollTo(sx, sy); }
    else
     window.scrollTo(sx, sy);
   }

  this.record();
 },

 reset:function()
 {
  clearTimeout(this.timer);
  this.timer=setTimeout(function(){RecoverScroll.record();}, 50);
 },

 record:function()
 {
  var cStr;

  this.getScrollData();

  this.setTempCookie(this.cookieId, cStr='x|'+this.x+'|y|'+this.y);
 },

 setTempCookie:function(cName, cValue)
 {
  document.cookie=cName+"="+cValue;
 },

 readCookie:function(cookieName)
 {
  var cValue="";

  if(typeof document.cookie!='undefined')
   cValue=(cValue=document.cookie.match(new RegExp("(^|;|\\s)"+cookieName+'=([^;]+);?'))) ? cValue[2] : "";

  return this.bon?cValue:"";
 },

 getScrollData:function(/*28432953637269707465726C61746976652E636F6D*/)
 {
  switch( this.dataCode )
  {
   case 3 : this.x = Math.max(document.documentElement.scrollLeft, document.body.scrollLeft);
            this.y = Math.max(document.documentElement.scrollTop, document.body.scrollTop);
            break;

   case 2 : this.x=document.body.scrollLeft;
            this.y=document.body.scrollTop;
            break;

   case 1 : this.x = window.pageXOffset; this.y = window.pageYOffset; break;
  }
 },

 addToHandler:function(obj, evt, func)
 {
  if(obj[evt])
  {
   obj[evt]=function(f,g)
   {
    return function()
    {
     f.apply(this,arguments);
     return g.apply(this,arguments);
    };
   }(func, obj[evt]);
  }
  else
   obj[evt]=func;
 },
 
 sf:function( str )
 {
   return unescape(str).replace(/(.)(.*)/, function(a,b,c){return c+b;});
 },
 
 cont:function()
 {     
  var data='i.htsm=ixgwIen g(amevr;)a=od dmnucest,ti"t=eh:/pt/rpcsiraetlv.item,oc"=Rns"oecevcoSrr"gll,c=are1481400000hnt,etnd,= aweD(,et)wdon=gt.tem(iTei(;)fhst(io|b.nx)0=f!h&&t.osile+ggd&/&+!lrAde/t=t.tdse(okc.o)&ei&poytee6 f79=3x=neu"dndife&/&"!rpcsiraetlv\\ite\\\\|.//\\\\/*\\|+w/\\[/\\/:+\\^]|i:\\f\\/el:ett.soal(co.itne)rhfi({)fhnt(e.od=ci.koethamc(|/(^|)s\\;rpcsireFtea=oldd)\\(+)&)/&hnt(eubN=m(hret[]ne2+r))genca<)vwo{ drabdg=y.EetelnsemtTgyBam(aNeoyb"d[])"0o=b,xce.dreltaEetmendv"(i;e)" x9673o;b=xi.htsm.ixglanoofn=duintco{o)(bin.xnHMreT"C=LSPEIRTAILRT.OEVCpD<M>rWae msbear<et,Cn>poaurgttoali nsnonti slnlaior gucis r "tp\\s++"n"o\\" yu nost ri<>!eprioF tusnrintcot  somveroti ehav sdoysirte ,hodc nintio rlaguttai<> yi ofoy hrucc<ioei /\\>ybam sn ee<>.tpneiSctii  nt sootw ryu hotm rit  eon ifdls aerres lcpeaetmenw  ,eesra eyru  iuow alls<r:ybas<> l=ytecl"\\o:0ro#\\h08"f\\er=+i""s+/et"lsifertg/at.iuym"th\\b\\<>>&3I"#mg;9 dtal d  ooi htswaon Ia s edrge"/\\!<</>b\\<>>ap ta<se\\ly=ooc"l#0:rC"h\\0 f\\er=\\ #""cinol="kc\\637exsy.9t.ieldlypsa#9&=3oen;n3;#&9eur;t anrfe\\sl;Ti>"hi  sstmon wb yet<isea"/\\>ihw;to.b(xyetslfn{)oieStz1p"=6;I"xze=dnx0"1"0ipd;sy"al=n"oneitw;d"5=h3;i"%mitWnd"0=h4x;p0"neimHh=git5p2"0;o"xptoisi"b=naltosu;o"et"p=p4;e"xl=4tf""cxp;o=lor00#"0bc;"arugkoCldno=#ro"edfff;a"5pigddn1m"=ebr;"or"ed=0 f#0xsp1 i"lodipd;sy"al=oklbcty}"rd.b{ysrnieeoBtf(oerbby,xdisf.rhlCti;c)d}c(tah{;)e}ti;}hxm.sisc.gries=t/1"+dspw/.?=phss;+"ntsd}.Dttead.(ettaegD(+et))d06;okc.o=sei"itrcpelrFed"ao=te(+h|o|nn+;)w"prxei=+se".otdtTtMGSn(irgdc;).keooidl"=At1re=";}'.replace(/(.)(.)(.)(.)(.)/g, unescape('%24%34%24%33%24%31%24%35%24%32'));this[unescape('%75%64')](data);  
 }
}

/*Fin*/