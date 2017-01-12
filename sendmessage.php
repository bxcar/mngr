<?php

$sendto  = 'malanchukdima@mail.ru'; //Адреса, куда будут приходить письма mk@makintour.com

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

$data = [
	'email'     => $email,
	'status'    => 'subscribed',
	'firstname' => $name,
	'lastname'  => 'doe'
];

syncMailchimp($data);

function syncMailchimp($data) {
	$apiKey = '50d8d9d5f632edf41927bd8ee636eb94-us12';
	$listId = '46f9f4fd80';

	$memberId = md5(strtolower($data['email']));
	$dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
	$url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

	$json = json_encode([
		'email_address' => $data['email'],
		'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
		'merge_fields'  => [
			'FNAME'     => $data['firstname'],
			'LNAME'     => $data['lastname']
		]
	]);

	$ch = curl_init($url);

	curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $apiKey);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

	$result = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	return $httpCode;
}

// отправка сообщения
if(@mail($sendto, $subject, $msg, $headers)) {
	header("Location: thanks.html");
} else {
	header("Location: error.html");
}

?>