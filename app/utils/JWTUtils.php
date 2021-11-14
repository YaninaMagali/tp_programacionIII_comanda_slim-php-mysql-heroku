<?php
use Psr\Http\Message\ServerRequestInterface;

function ObtenerBearearTokenDelHeader(Request $request){

    $bearer =  $request->getHeaderLine('Authorization');
    $aux = explode(" ", $bearer);
    return $aux[1];

}

?>