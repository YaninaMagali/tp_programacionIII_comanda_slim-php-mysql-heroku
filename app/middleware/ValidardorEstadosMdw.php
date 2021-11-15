<?php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;
require_once './utils/AutenticadorJWT.php';
require_once './utils/JWTUtils.php';
require_once './controller/ValidadorSectoresController.php';

class ValidardorEstadosMdw{

    function ValidarEstadosMesa(Request $request, RequestHandler $handler){

        $parametros = $request->getParsedBody();
        $estado = $parametros['estado'];
        $codigo = $parametros['codigo'];
        $response = new Response();

        if($estado && $codigo 
        && ($estado == 'cliente_comiendo') 
        || $estado == 'cliente_pagando'
        || $estado == 'cliente_esperando_pedido'
        ){
            $response = $handler->handle($request);
        }
        else{
            $data = array('error_code' => '200', 'message' => 'Estado invalido');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        }

        return $response;  
    }

    function ValidarEstadoPedido(Request $request, RequestHandler $handler){

        $parametros = $request->getParsedBody();
        $estado = $parametros['estado'];
        $codigo = $parametros['codigo'];
        $response = new Response();

        echo $estado;
        if($estado && $codigo 
        && ($estado == 'en_preparacion' 
        || $estado == 'listo_para_servir')
        ){
            $response = $handler->handle($request);
        }
        else{
            $data = array('error_code' => '200', 'message' => 'Estado invalido');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        }

        return $response;  
    }


}

?>