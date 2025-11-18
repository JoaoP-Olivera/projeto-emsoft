<?php 
require_once __DIR__ . '/Validators/Request/RequestValidator.php';
require_once __DIR__ . '/Handlers/SaveAddressHandler.php';
require_once __DIR__ . '/Validators/Request/RequestValidator.php';
require_once __DIR__ . '/Http/Util/Notification.php';
require_once __DIR__ . '/Http/Request/Request.php';
header('Content-Type: application/json');

function save($request)
{
    saveAddressHandle($request);
}

$isValidRequest = validateRequest();

if($isValidRequest)
{
    $request = receiveRequest();
     save($request);
} else {
    $statusCode = 400;
    $message = "Requisição inválida";
    sendNotification($statusCode,$message);
}
  

  


?>
