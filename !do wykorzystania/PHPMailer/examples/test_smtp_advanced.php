<html>
<head>
<title>PHPMailer - SMTP advanced test with authentication</title>
</head>
<body>

<?php

require_once('../class.phpmailer.php');
require_once('mailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

if (smtpmailer('maciej.adrjanowicz@postdata.pl', 'maciej.adrjanowicz@wp.pl', 'Maciej Adrjanowicz', 'łóąśżłąś', 'ąśęęąśółóżćż')) {
	// do something
}
if (!empty($error)) echo $error;

?>

</body>
</html>
