<?php 

 function sendResponse(array $dataToBeSend) : void
 {
    $json= json_encode($dataToBeSend);
    echo $json;
 } 
?>