<?php 
require_once __DIR__ .'/../Response/Response.php';

  function sendNotification( $statusCode, $message)
  {
    $notification = [
        'statusCode' =>$statusCode,
        'message' => $message
    ];

    sendResponse($notification);
    exit;
    
  }; 

  

?>