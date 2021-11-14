<?php
require_once './utils/Utils.php';

class Mesa{

    public $id;
    public $codigo;
    public $estado;

    public function CrearMesa(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO mesas (codigo, estado) VALUES (:codigo, :estado)");
            $codigoAux = GenerarCodigoAlfanumerico(5);
            $query->bindValue(':codigo', $codigoAux, PDO::PARAM_STR);
            $query->bindValue(':estado', $this->estado, PDO::PARAM_STR);
            $query->execute();
    
            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ListarMesas(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM mesas;");
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }
        catch(Exception $e){
            throw $e;
        }
    }

}
?>