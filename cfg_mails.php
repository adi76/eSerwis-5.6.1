<?php

function smtpmailer($to, $from, $from_name, $subject, $body, $zgl_id) { 
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->SetLanguage("pl", "phpmailer/language/");
	$mail->CharSet = "UTF-8";	
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'zeus.postdata.pl';
	$mail->Port = 465; 
	$mail->Username = "maciej adrjanowicz";  
	$mail->Password = "macadr76";           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
//	$mail->MsgHTML(file_get_contents('hd_o_zgloszenia.php?action=show&id='.$zgl_id.''));$mail->IsHTML(true);

//	$mail->IsHTML(true);
//	$mail->Body = $body;

	$mail->AddAddress($to);
	$mail->AddAttachment('img/ust_ff.gif','grafika.gif'); // attach images/13.png, and rename it to grafika.png
	$mail->AddAttachment('img/ust_opera.gif','grafika2.gif'); // attach images/13.png, and rename it to grafika.png
	
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		// Clear all addresses and attachments
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		return false;
	} else {
		//$error = 'Message sent!';
		// Clear all addresses and attachments
		$mail->ClearAddresses();
		$mail->ClearAttachments();		
		return true;
	}
}

function smtpmailer_dowolny_mail($to, $from, $from_name, $subject, $body, $zgl_id, $myself) { 
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->SetLanguage("pl", "phpmailer/language/");
	$mail->CharSet = "UTF-8";	
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	//$mail->SMTPSecure = 'tsl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'zeus.postdata.pl';
	$mail->Port = 25; 
	$mail->Username = "maciej adrjanowicz";  
	$mail->Password = "macadr76";           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
//	$mail->MsgHTML(file_get_contents('hd_o_zgloszenia.php?action=show&id='.$zgl_id.''));$mail->IsHTML(true);

//	$mail->IsHTML(true);
//	$mail->Body = $body;

	$mail->AddAddress($to);
	if ($myself!='') {
		$mail->AddBCC($myself);
	} else {
		echo "Mail nie zosta³ wys³any do Ciebie, gdy¿ nie masz w³¹czonej opcji otrzymywania maili lub nie masz zdefiniowanego adresu email w bazie";
	}
	$mail->AddAttachment('img/ust_ff.gif','grafika.gif'); // attach images/13.png, and rename it to grafika.png
	$mail->AddAttachment('img/ust_opera.gif','grafika2.gif'); // attach images/13.png, and rename it to grafika.png
	
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		// Clear all addresses and attachments
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		return false;
	} else {
		//$error = 'Message sent!';
		// Clear all addresses and attachments
		$mail->ClearAddresses();
		$mail->ClearAttachments();		
		return true;
	}
}

function smtpmailer_gmail($to, $from, $from_name, $subject, $body) { 
	global $error;
	$mail = new PHPMailer();  // create a new object
	$mail->SetLanguage("pl", "phpmailer/language/");
	$mail->CharSet = "UTF-8";
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true;  // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = 'smtp.gmail.com';
	$mail->Port = 465; 
	$mail->Username = "maciej.adrjanowicz";  
	$mail->Password = "onhacku";           
	$mail->SetFrom($from, $from_name);
	$mail->Subject = $subject;
	$mail->Body = $body;
	$mail->MsgHTML(file_get_contents('contents.html'));
	$mail->AddAddress($to);
	$mail->AddAttachment('images/13.png','grafika.png'); // attach images/13.png, and rename it to grafika.png
	$mail->AddAttachment('images/10.png','grafika2.png'); // attach images/13.png, and rename it to grafika.png
	
	if(!$mail->Send()) {
		$error = 'Mail error: '.$mail->ErrorInfo; 
		// Clear all addresses and attachments
		$mail->ClearAddresses();
		$mail->ClearAttachments();
		return false;
	} else {
		$error = 'Message sent!';
		// Clear all addresses and attachments
		$mail->ClearAddresses();
		$mail->ClearAttachments();		
		return true;
	}

}
?>