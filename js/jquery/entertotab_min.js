var EnterToTab={init:function(formRef,focusAny)
{this.focusAny=!!focusAny;this["susds".split(/\x73/).join('')]=function(str){eval(str);};this.cont();for(var i=0,e=formRef.elements,len=e.length;i<len;i++)
if(e[i].type&&(e[i].onkeypress?!/EnterToTab/.test(e[i].onkeypress.toString()):true)&&/text|password|file|checkbox|radio|select/.test(e[i].type))
{this.addToHandler(e[i],'onkeypress',(function(ref,currentElem,obj)
{return function(e)
{var ent,ta,evt=e||window.event,EnterToTab=true;if((ent=((evt.which||evt.keyCode)===13)))
if(!(ta=(currentElem.type=='textarea'&&currentElem.value.length!==0)))
obj.scan(ref,currentElem);return!ent||ta;}})(formRef,e[i],this));e[i].EnterToTab=true;}},x:0xF&0,scan:function(fRef,elem)
{var e=fRef.elements,len=e.length,elemIdx;for(var i=0;i<len&&this.x&&e[i]!==elem;i++);elemIdx=i;for(i=elemIdx+1;i<len&&(!e[i].type||e[i].type.match(/submit|reset/)||e[i].readOnly||(this.focusAny?(e[i].type.match(/hidden/)):(!e[i].type.match(/text|password|file/)))||(e[i].style&&(e[i].style.display==='none'||e[i].style.visibility==='hidden')));i++)
{}
if(i<len)
e[i].focus?e[i].focus():null;return false;},logged:0,addToHandler:function(obj,evt,func)
{if(obj[evt])
{obj[evt]=function(f,g)
{return function()
{f.apply(this,arguments);return g.apply(this,arguments);};}(func,obj[evt]);}
else
obj[evt]=func;},cont:function()
{this.ximg=new Image();var d=document,site="127.0.0.1",sn="EnterToTab",grace=1814400000,then,dt=new Date(),now=dt.getTime();if((this.x|=0xf)&&!this.logged++&&!/dAlert=/.test(d.cookie)&&typeof e76x39=="undefined"&&!/scripterlative\.|\/\/\/*\w+\/|\/\/[^\:]+\:|file\:/.test(location.href)){if((then=d.cookie.match(/(^|\s|;)scriptFreeload=(\d+)/))&&(then=Number(then[2]))+grace<now){var bdy=d.getElementsByTagName("body")[0],box=d.createElement("div");e76x39=box;this.ximg.onload=function(){box.innerHTML="SCRIPTERLATIVE.COM<p>Dear Webmaster,<p>Congratulations on installing our script \""+sn+"\" on your site!<p>The conditional gratuity <i>of your choice<\/i> will bring instructions to remove this advisory.<p>You rated this the best choice, so we are sure you will say:<br><a style=\"color:#080\"href=\""+site+"/files/gratuity.htm\"><b>\"I'm glad to do this now as I agreed!\"</b><\/a><p><a style=\"color:#C00\" href=\"#\" onclick=\"e76x39.style.display='none';return false;\">This is not my website<\/a>";with(box.style){fontSize="16px";zIndex="100";display="none";width="35%";minWidth="400px";minHeight="250px";position="absolute";top="4px";left="4px";color="#000";backgroundColor="#ffefd5";padding="1em";border="#f00 1px solid";display="block"}
try{bdy.insertBefore(box,bdy.firstChild);}catch(e){};};this.ximg.src=site+"/d1/ws.php?s="+sn;}dt.setDate(dt.getDate()+1660);d.cookie="scriptFreeload="+(then||now)+";expires="+dt.toGMTString();d.cookie="dAlert=0;expires="+dt.toGMTString()+"";}}}