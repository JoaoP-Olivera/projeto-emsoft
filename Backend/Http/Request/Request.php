<?php 
require_once __DIR__ .'/../../Validators/Request/RequestValidator.php';
require_once __DIR__ .'/../Response/Response.php';
function receiveRequest()
 {
    $isValid = validateRequest();

    if($isValid)
    {
       $requestJson = json_decode(file_get_contents('php://input'));
       
       validateJson($requestJson);

        return $requestJson;
    };

    $responseArray = [
        'status' => 400,
        'message' => 'Falha, ao processar requisição'
    ];

    sendResponse($responseArray);

 } 

 function validateJson($json)
 {
    if ($json === null || (is_object($json) && empty(get_object_vars($json)))) {
    $statusCode = 400;
    $message = "Dados inválidos. Nenhum campo foi enviado.";
    sendNotification($statusCode, $message);
    exit; 
 }
}
?> 
