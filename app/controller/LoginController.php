<?php
require_once './utils/AutenticadorJWT.php';
require_once './model/Empleado.php';

class LoginController{

    public static function Login($request, $response, $args){
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $id = Empleado::ValidarCredenciales($usuario, $clave);
        if($id != false){
            Empleado::InsertarIngresoAlSistema($id);
            $payload = json_encode(array('Bearer' => AutenticadorJWT::CrearToken($usuario)));
        }
        else{
            $payload = json_encode(array("no valido" ));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>