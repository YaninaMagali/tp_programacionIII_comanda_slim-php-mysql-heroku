<?php

class Menu{

    public $id;
    public $descripcion;
    public $sector;
    public $precio;
    public $tiempoEstimadoPreparacion;

    public function CrearItemMenu(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("INSERT INTO menu (descripcion, sector, precio, tiempoEstimadoPreparacion) VALUES (:descripcion, :sector, :precio, :tiempoEstimadoPreparacion)");
            $query->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
            $query->bindValue(':sector', $this->sector, PDO::PARAM_STR);
            $query->bindValue(':precio', $this->precio, PDO::PARAM_INT);
            $query->bindValue(':tiempoEstimadoPreparacion', $this->tiempoEstimadoPreparacion, PDO::PARAM_STR);
            $query->execute();
    
            return $dao->obtenerUltimoId();
        }
        catch(Exception $e){
            throw $e;
        }

    }

    public static function ListarItemsMenu(){

        try{
            $dao = new DAO();
            $query = $dao->prepararConsulta("SELECT * FROM menu;");
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Menu');
        }
        catch(Exception $e){
            throw $e;
        }

    }
}

?>