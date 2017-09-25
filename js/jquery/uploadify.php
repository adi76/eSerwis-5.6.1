<?php
/* 
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

function bezPL($string) {
	$string = strtr($string,'ęóąśłżźćń ','__________');
	return (strtr($string, 'ĘÓĄŚŁŻŹĆŃ','_________' )); 
}

// Define a destination
$targetFolder = '/serwis/attachements_kb'; // Relative to the root

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	
	$newFileName = $_POST['pyt_id'].'_'.($_FILES['Filedata']['name']);
	$newFileName = bezPL($newFileName);
	
	
	$targetFile = rtrim($targetPath,'/') . '/' . $newFileName;
	
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','zip','rar','7z','txt','doc','pdf'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Invalid file type.';
	}
}
?>