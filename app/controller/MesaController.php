<?php
require_once './model/Mesa.php';

class MesaController{

    public function CrearMesa($request, $response, $args){
    
        $parametros = $request->getParsedBody();
        $mesa = new Mesa();
        $mesa->estado = $parametros['estado'];
        try{
            $mesa->CrearMesa();
            $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarMesas($request, $response, $args){
        try{
            $itemsMesas = Mesa::ListarMesas();
            $payload = json_encode(array("Mesas" => $itemsMesas));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


}


?>