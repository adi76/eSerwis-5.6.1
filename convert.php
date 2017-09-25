<?php
  function win2utf(){
   $tabela = Array(
    "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
    "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
    "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
    "\x9f" => "\xc5\xbc", "\xaf" => "\xc5\xbb", "\xbf" => "\xc5\xba", "\xac" => "\xc5\xb9",
    "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83");
   return $tabela;
  }

  function iso2utf(){
   $tabela = Array(
    "\xb1" => "\xc4\x85", "\xa1" => "\xc4\x84", "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
    "\xea" => "\xc4\x99", "\xca" => "\xc4\x98", "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
    "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93", "\xb6" => "\xc5\x9b", "\xa6" => "\xc5\x9a",
    "\xbc" => "\xc5\xbc", "\xac" => "\xc5\xbb", "\xbf" => "\xc5\xba", "\xaf" => "\xc5\xb9",
    "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83");
   return $tabela;
  }
 
  function ISO88592_2_UTF8($tekst){
   return strtr($tekst, iso2utf());
  }

  function UTF8_2_ISO88592($tekst){
   return strtr($tekst, array_flip(iso2utf()));
  }

  function WIN1250_2_UTF8($tekst){
   return strtr($tekst, win2utf());
  }

  function UTF8_2_WIN1250($tekst){
   return strtr($tekst, array_flip(win2utf()));
  }

  function ISO88592_2_WIN1250($tekst){
   return strtr($tekst, "\xa1\xa6\xac\xb1\xb6\xbc", "\xa5\x8c\x8f\xb9\x9c\x9f");
  }

  function WIN1250_2_ISO88592($tekst){
   return strtr($tekst, "\xa5\x8c\x8f\xb9\x9c\x9f", "\xa1\xa6\xac\xb1\xb6\xbc");

  }
  
  
function win2utf8($text) {
/*
ą	¹
ś	œ
ć	æ
ż	¿
ź	Ÿ
ę	ê
ń	ñ
ł	³ 
ó	ó

Ś	Œ
Ż	¯
Ź
Ę	Ê
Ń	Ñ
Ł	£
Ó	Ó

*/
    $utf = array ("ą","ś","ć","ż","ź","ę","ń","ł","ó","Ś","Ż","Ę","Ń","Ł","Ó");
    $win = array ("¹","œ","æ","¿","Ÿ","ê","ñ","³","ó","Œ","¯","Ê","Ñ","£","Ó");
    return str_replace($win, $utf, $text);
}

function utf82win($text) {
/*
ą	¹
ś	œ
ć	æ
ż	¿
ź	Ÿ
ę	ê
ń	ñ
ł	³ 
ó	ó

Ś	Œ
Ż	¯
Ź
Ę	Ê
Ń	Ñ
Ł	£
Ó	Ó

*/
    $utf = array ("ą","ś","ć","ż","ź","ę","ń","ł","ó","Ś","Ż","Ę","Ń","Ł","Ó");
    $win = array ("¹","œ","æ","¿","Ÿ","ê","ñ","³","ó","Œ","¯","Ê","Ñ","£","Ó");
    return str_replace($win, $utf, $text);
}

?>