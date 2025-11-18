<?php 
function regExHelper(string $pattern, string $input)
{
    return preg_match($pattern, $input);
}


?>