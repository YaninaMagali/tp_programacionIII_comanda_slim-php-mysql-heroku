<?php
use Slim\Psr7\Environment;

class DAO
{
    public $objetoPDO;

    public function __construct()
    {
        //$this->objetoPDO = new PDO('mysql:host=localhost;dbname=comanda;charset=utf8', 'root', '', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->objetoPDO = new PDO('mysql:host='.$_ENV['MYSQL_HOST'].';dbname='.$_ENV['MYSQL_DB'].';charset=utf8', $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASS'], array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $this->objetoPDO->exec("SET CHARACTER SET utf8");
    }

    public function PrepararConsulta($sql)
    { 
        return $this->objetoPDO->prepare($sql); 
    }

    public function obtenerUltimoId()
    {
        return $this->objetoPDO->lastInsertId();
    }
}
?>