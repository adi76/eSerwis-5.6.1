﻿<?php

// ąćęłńóśźżĄĆĘŁŃÓŚŹŻ
// ¹æê³ñóœŸ¿¥ÆÊ£ÑÓŒ¯

function encode_pl_to_latin1($inputtext) {
	$pattern = array('/ą/','/ć/','/ę/','/ł/','/ń/','/ó/','/ś/','/ź/','/ż/','/Ą/','/Ć/','/Ę/','/Ł/','/Ń/','/Ó/','/Ś/','/Ź/','/Ż/');
	$replace = array('¹','æ','ê','³','ñ','ó','œ','Ÿ','¿','¥','Æ','Ê','£','Ñ','Ó','Œ','','¯');
	return preg_replace($pattern, $replace, $inputtext);
}

?>