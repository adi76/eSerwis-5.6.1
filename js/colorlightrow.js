var clicked5="";var remember="";function rowOver_color(_1,_2,_3){var _4=document.getElementById(_1);if(_1!=clicked5){if(_2==1){_4.style.backgroundColor="#FFFFFF";}else{_4.style.backgroundColor=_3;}}}function resetRow_color(_5,_6){var _7=document.getElementById(_5);_7.style.backgroundColor=_6;}function changeSelect_color(_8){var _9=document.getElementById(_8);_9.style.backgroundColor="#FFFFFF";_9.onMouseOver="";_9.onMouseOut="";}function selectRow_color(_a,_b){if(clicked5!=""){resetRow_color(clicked5);clicked5=_a;changeSelect_color(_a);remember=_a;}else{if(clicked5==""){clicked5=_a;changeSelect_color(_a);}}}function deSelectRow_color(_c,_d){resetRow_color(_c);clicked5="";}