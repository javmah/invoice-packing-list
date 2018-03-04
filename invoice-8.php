<?php
// header("Content-type: application/pdf");
// $headers['Content-Type'] = 'application/pdf; charset=utf-8';
// header("Content-Disposition: inline; filename=invoice.pdf");
// @readfile('http://localhost/componentsource/wp-content/uploads/2018/03/invoice.pdf');
// exit(); 

// header('Content-Disposition: attachment; filename="invoice.pdf"');
// header('Content-Type: application/force-download');
// header('Content-Type: application/octet-stream');
// header('Content-Type: application/download');
// header('Content-Description: File Transfer');
// header('Content-Length: ' . filesize('http://localhost/componentsource/wp-content/uploads/2018/03/invoice.pdf'));
// echo file_get_contents('http://localhost/componentsource/wp-content/uploads/2018/03/invoice.pdf');

//read and display the cached file

// header('Content-type: application/pdf');
// header('Content-Disposition: inline; filename="invoice.pdf"');
// header('Content-Transfer-Encoding: binary');
// header('Content-Length: ' . filesize('http://localhost/componentsource/wp-content/uploads/2018/03/invoice.pdf'));
// header('Accept-Ranges: bytes');
// readfile('http://localhost/componentsource/wp-content/uploads/2018/03/invoice.pdf');

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="invoice.pdf" ');
readfile('http://localhost/componentsource/wp-content/uploads/2018/03/invoice.pdf');



?>