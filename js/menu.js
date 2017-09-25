<script type="text/javascript">
//<![CDATA[

var listMenu = new FSMenu('listMenu', true, 'display', 'block', 'none');
listMenu.showDelay = 100;
listMenu.switchDelay = 125;
listMenu.hideDelay = 300;
listMenu.cssLitClass = 'highlighted';
//listMenu.showOnClick = 1;
listMenu.animInSpeed = 0.5;
listMenu.animOutSpeed = 0.5;

function animClipDown(ref, counter)
{
 var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
 ref.style.clip = (counter==100 ? (window.opera ? '': 'rect(auto, auto, auto, auto)') :
  'rect(0, ' + ref.offsetWidth + 'px, '+(ref.offsetHeight*cP)+'px, 0)');
};

function animFade(ref, counter)
{
 var f = ref.filters, done = (counter==100);
 if (f)
 {
  if (!done && ref.style.filter.indexOf("alpha") == -1)
   ref.style.filter += ' alpha(opacity=' + counter + ')';
  else if (f.length && f.alpha) with (f.alpha)
  { 
   if (done) enabled = false;
   else { opacity = counter; enabled=true }
  }
 }
 else ref.style.opacity = ref.style.MozOpacity = counter/100.1;
};

// I'm applying them both to this menu and setting the speed to 20%. Delete this to disable.
//listMenu.animations[listMenu.animations.length] = animFade;
//listMenu.animations[listMenu.animations.length] = animClipDown;
//listMenu.animations[listMenu.animations.length] = FSMenu.animFade;
//listMenu.animations[listMenu.animations.length] = FSMenu.animSwipeDown;

//listMenu.animSpeed = 500;

var arrow = null;
if (document.createElement && document.documentElement)
{
 arrow = document.createElement('img');
 arrow.src = 'img/menu.gif';
 arrow.style.borderWidth = '0';
 arrow.className = 'subind';
}

addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot1", arrow)'));

//]]>
</script>
