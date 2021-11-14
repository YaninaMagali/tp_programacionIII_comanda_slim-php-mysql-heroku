<?php

function GenerarCodigoAlfanumerico($longitud){
    return bin2hex(random_bytes($longitud));          
   }

?>