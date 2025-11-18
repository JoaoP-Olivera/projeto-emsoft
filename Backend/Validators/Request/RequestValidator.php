<?php 
function validateRequest() : bool 
 {
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        return true;
    }

    return false;
 }

 

?>