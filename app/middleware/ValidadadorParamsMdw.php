<?php
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;

class ValidadadorParamsMdw{

    public function ValidarParamsLogin(Request $request, RequestHandler $handler){
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $response = new Response();
        if($usuario && $clave){
            $response = $handler->handle($request);
        }
        else{
            $data = array('error_code' => '200', 'message' => 'Debe ingresar el usuario');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        }

        return $response;
    }


    public function ValidarToken(Request $request, RequestHandler $handler){

        $token =  $request->getHeaderLine('Authorization');
        $response = new Response();
        if($token != null){
            $response = $handler->handle($request);
        }
        else{
            $data = array('error_code' => '404', 'message' => 'No esta autenticado');
            $payload = json_encode($data);
            $response->getBody()->write($payload);
        }

        return $response;
    }
    


}
?>