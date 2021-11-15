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

    public static function ConsultarMesa($codigo){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM mesas WHERE codigo = :codigo;");
            $query->bindValue(':codigo', $codigo, PDO::PARAM_STR);
            if($query->execute())
            {
                return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
            }
            else
            {
                return false;
            } 
        }
        catch(Exception $e){
            throw $e;
        }
    }

    public static function ActualizarEstadoMesa($estado, $codigo){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("UPDATE mesas SET estado = :estado WHERE codigo = :codigo;");
            $query->bindValue(':estado', $estado, PDO::PARAM_STR);
            $query->bindValue(':codigo', $codigo, PDO::PARAM_STR);

            if($query->execute())
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        catch(Exception $e){
            throw $e;
        }
    }

}
?>