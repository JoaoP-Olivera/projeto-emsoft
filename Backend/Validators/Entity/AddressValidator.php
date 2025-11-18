<?php 
require_once __DIR__ .'/../../Helpers/Helpers.php';
require_once __DIR__ . '/../../Handlers/ReadCepsHandler.php';
require_once __DIR__ . '/../../Http/Util/Notification.php';

/**
 * Verifica se o CEP já existe
 */
function thisCepExists(string $cep): bool
{
    $ceps = ReadCepHandle(); 

    foreach ($ceps as $registro) {
        if (isset($registro->cep) && $registro->cep === $cep) {
            return true;
        }
    }

    return false;
}

/**
 * Valida o CEP
 */
function isCepValid(string $cepString): bool
{
    if (strlen($cepString) !== 8) {
        sendNotification(400, "O CEP deve conter exatamente 8 números.");
        return false;
    }

    if (!preg_match('/^\d{8}$/', $cepString)) {
        sendNotification(400, "O CEP só deve conter números.");
        return false;
    }

    if (thisCepExists($cepString)) {
        sendNotification(400, "Já existe um endereço cadastrado com esse CEP.");
        return false;
    }

    return true;
}

/**
 * Valida campos de texto com mínimo de caracteres
 */
function isTextValid(string $text, string $fieldName, int $minLength = 4): bool
{
    if (strlen($text) < $minLength) {
        sendNotification(400, "O campo {$fieldName} deve ter {$minLength} ou mais caracteres.");
        return false;
    }
    return true;
}

/**
 * Valida estado (2 letras apenas)
 */
function isEstadoValid(string $estado): bool
{
    if (strlen($estado) !== 2 || !preg_match('/^[a-zA-Z]{2}$/', $estado)) {
        sendNotification(400, "O estado deve conter exatamente 2 letras.");
        return false;
    }
    return true;
}

/**
 * Valida país (somente letras, mínimo de 4 caracteres)
 */
function isPaisValid(string $pais): bool
{
    if (strlen($pais) < 4 || !preg_match('/^[a-zA-Z]+$/', $pais)) {
        sendNotification(400, "O país deve conter 4 ou mais letras.");
        return false;
    }
    return true;
}


function validateAddress($request): bool
{
    $validations = [
        'cep' => 'isCepValid',
        'endereco' => fn($v) => isTextValid($v, 'endereço'),
        'bairro' => fn($v) => isTextValid($v, 'bairro'),
        'cidade' => fn($v) => isTextValid($v, 'cidade'),
        'estado' => 'isEstadoValid',
        'pais' => 'isPaisValid',
    ];

    foreach ($validations as $field => $validator) {
        if (!isset($request->$field) || !$validator($request->$field)) {
            return false;
        }
    }

    return true; 
}
?>
