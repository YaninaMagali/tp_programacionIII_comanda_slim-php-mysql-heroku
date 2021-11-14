<?php
require_once './model/Menu.php';

class MenuController{

    public function CrearItemMenu($request, $response, $args){
        /*Voy a validar en el MDW
        - que existan todos los datos
        - que el usuario aun no este registrado
        - que el sector sea valido
        */
    
        $parametros = $request->getParsedBody();
        $item = new Menu();
        $item->descripcion = $parametros['descripcion'];
        $item->precio = $parametros['precio'];
        $item->sector = $parametros['sector'];
        $item->tiempoEstimadoPreparacion = $parametros['tiempoEstimadoPreparacion'];

        try{
            $item->CrearItemMenu();
            $payload = json_encode(array("mensaje" => "Item de menu creado con exito"));
        }
        catch(Exceptionn $e){
            $payload = json_encode(array("Error" => $e));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ListarItemsMenu($request, $response, $args){

        try{
            $itemsMenu = Menu::ListarItemsMenu();
            $payload = json_encode(array("Items menu" => $itemsMenu));
        }
        catch(Exception $e){
            $payload = json_encode(array("Error" => $e));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>