<?php 
require_once __DIR__ . '/../Http/Util/Notification.php';

function ReadCepHandle(): array
{
   $pathToFile = __DIR__ . '/../../Data/ceps.json';

    if(file_exists($pathToFile) == false)
    {
        sendNotification(500,"Não foi possível ler o arquivo.");

        return [];
    }

    $ceps = json_decode(file_get_contents($pathToFile));

    if(!is_array($ceps)) {
        $ceps = []; 
    }

    return $ceps;
}

?>