<?php
include('inc_encrypt_key.php');
	
function get_rnd_iv($iv_len) {
// finalnie ta funkcja ma zosta�
    $iv = '';
    while ($iv_len-- > 0) {
        $iv .= chr(mt_rand() & 0xff);
    }
    return $iv;
}

function crypt_md5($plain_text, $password, $iv_len = 16) {
// finalnie ta funkcja ma zosta�
    $plain_text .= "\x13";
    $n = strlen($plain_text);
    if ($n % 16) $plain_text .= str_repeat("\0", 16 - ($n % 16));
    $i = 0;
    $enc_text = get_rnd_iv($iv_len);
    $iv = substr($password ^ $enc_text, 0, 512);
    while ($i < $n) {
        $block = substr($plain_text, $i, 16) ^ pack('H*', md5($iv));
        $enc_text .= $block;
        $iv = substr($block . $iv, 0, 512) ^ $password;
        $i += 16;
    }
    return base64_encode($enc_text);
}

function decrypt_md5($enc_text, $password, $iv_len = 16) {
// finalnie ta funkcja ma zosta�
	$enc_text = base64_decode($enc_text);
    $n = strlen($enc_text);
    $i = $iv_len;
    $plain_text = '';
    $iv = substr($password ^ substr($enc_text, 0, $iv_len), 0, 512);
    while ($i < $n) {
        $block = substr($enc_text, $i, 16);
        $plain_text .= $block ^ pack('H*', md5($iv));
        $iv = substr($block . $iv, 0, 512) ^ $password;
        $i += 16;
    }
    return preg_replace('/\\x13\\x00*$/', '', $plain_text);
}

?>