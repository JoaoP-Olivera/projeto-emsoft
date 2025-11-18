<?php 
require_once __DIR__ . '/ReadCepsHandler.php';
require_once __DIR__ . '/../Validators/Entity/AddressValidator.php';
require_once __DIR__ . '/../Http/Util/Notification.php';;
function saveAddressHandle($request)
{
   $ceps = ReadCepHandle();
   
   $isValid = validateAddress($request);

   if($isValid == false )
   {
     exit;
   }

   $newAddress = [
    'cep' => $request->cep,
    'endereco' => $request->endereco,
    'cidade' => $request->cidade,
    'estado' => $request->estado,
    'pais' => $request->pais,
    'bairro' => $request->bairro,
    'dataHora' => $request->dataHora
   ];
   
   $ceps = ReadCepHandle();

   $ceps[] = $newAddress;

   // Converte o array para JSON
   $finalCepList = json_encode($ceps, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

     $pathToFile = __DIR__ . '/../../Data/ceps.json';

   // Salva no arquivo
   file_put_contents($pathToFile, $finalCepList);

   //evitando o uso de magic numbers
     $statusCode = 201;
     $message = "Novo registro criado com sucesso";

     sendNotification($statusCode,$message);
}

?> 