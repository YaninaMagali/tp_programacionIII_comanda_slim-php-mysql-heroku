<?php
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;
require_once './utils/AutenticadorJWT.php';
require_once './utils/JWTUtils.php';
require_once './controller/ValidadorSectoresController.php';

class ValidardorSectorMdw{

    function ValidarSiEsSocio(Request $request, RequestHandler $handler){

        $bearer =  $request->getHeaderLine('Authorization');
        $aux = explode(" ", $bearer);
        $token = $aux[1];
        $response = new Response();
        try{
            $payload = AutenticadorJWT::ObtenerPayLoad($token);
            if(ValidadorSectoresController::ValidarSector($payload->data, 'SOCIO')){
                $response = $handler->handle($request);
            }
            else{
                $data = array('error_code' => '404', 'message' => 'No autorizado');
                $payload = json_encode($data);
                $response->getBody()->write($payload);
            }
        }
        catch(Exception $e){
            var_dump($e);
        }
        return $response;
    }

    function ValidarSiEsMozo(Request $request, RequestHandler $handler){

        //$token = ObtenerBearearTokenDelHeader($request);
        
        $bearer =  $request->getHeaderLine('Authorization');
        $aux = explode(" ", $bearer);
        $token = $aux[1];
        $response = new Response();
        try{
            $payload = AutenticadorJWT::ObtenerPayLoad($token);
            if(ValidadorSectoresController::ValidarSector($payload->data, 'MOZO')){
                $response = $handler->handle($request);
            }
            else{
                $data = array('error_code' => '404', 'message' => 'No autorizado');
                $payload = json_encode($data);
                $response->getBody()->write($payload);
            }
        }
        catch(Exception $e){
            var_dump($e);
        }
        return $response;
    }
}
?>