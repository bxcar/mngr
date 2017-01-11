<?php

$sendto  = 'malanchukdima@mail.ru'; //Адреса, куда будут приходить письма

$name  = $_POST['name'];
$email  = $_POST['email'];

// Формирование заголовка письма

$subject  = '[Новая заявка - Manager]';
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
// Формирование тела письма

$msg  = "<html><body style='font-family:Arial,sans-serif;'>";
$msg .= "<h2 style='font-weight:bold;border-bottom:1px dotted #ccc;'>Новая заявка - Manager</h2>\r\n";
$msg .= "<p><strong>Имя:</strong> ".$name."</p>\r\n";
$msg .= "<p><strong>Email:</strong> ".$email."</p>\r\n";
$msg .= "</body></html>";

// отправка сообщения
if(@mail($sendto, $subject, $msg, $headers)) {
	header("Location: thanks.html");
} else {
	header("Location: error.html");
}

?>