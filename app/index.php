<?php
//phpinfo();
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Http\UploadedFile;

require __DIR__ . '/../vendor/autoload.php';
require_once './controller/EmpleadoController.php';
require_once './controller/MenuController.php';
require_once './controller/MesaController.php';
require_once './controller/PedidoController.php';
require_once './controller/LoginController.php';
require_once './middleware/ValidadadorParamsMdw.php';
require_once './middleware/ValidardorSectorMdw.php';
require_once './middleware/ValidardorEstadosMdw.php';
require_once './database/DAO.php';
require_once './errorLog.php';



// Load ENV
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Instantiate App
$app = AppFactory::create();

// $container = $app->getContainer();
// $container['upload_directory'] = __DIR__ . './cargas';

// Add error middleware
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();

// peticiones
$app->group('/empleados', function (RouteCollectorProxy $group) {
    $group->get('[/]', \EmpleadoController::class . ':ListarEmpleados');
    $group->get('/{usuario}', \EmpleadoController::class . ':ObtenerEmpleado');
    $group->post('[/]', \EmpleadoController::class . ':CrearEmpleado');
    $group->post('/{csv}', \EmpleadoController::class . ':CrearEmpleadosDesdeCSV');
  })->add(\ValidardorSectorMdw::class . ':ValidarSiEsSocio')->add(\ValidadadorParamsMdw::class . ':ValidarToken');

$app->group('/menu', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MenuController::class . ':ListarItemsMenu');
  $group->post('[/]', \MenuController::class . ':CrearItemMenu');
})->add(\ValidadadorParamsMdw::class . ':ValidarToken');

$app->group('/mesa', function (RouteCollectorProxy $group) {
  $group->get('[/]', \MesaController::class . ':ListarMesas')->add(\ValidardorSectorMdw::class . ':ValidarSiEsSocio');
  $group->post('[/]', \MesaController::class . ':CrearMesa');
  $group->put('[/]', \MesaController::class . ':CambiarEstadoMesa')->add(\ValidardorEstadosMdw::class . ':ValidarEstadosMesa');
})->add(\ValidadadorParamsMdw::class . ':ValidarToken');

$app->group('/pedido', function (RouteCollectorProxy $group) {
  $group->get('[/]', \PedidoController::class . ':ListarPedidosPendientesPorSector')->add(\ValidadadorParamsMdw::class . ':ValidarToken');
  $group->get('/{codigo}', \PedidoController::class . ':BuscarPedidoPorCodigo');
  $group->post('[/]', \PedidoController::class . ':CrearPedido')->add(\JsonBodyParserMiddleware::class . ':process')->add(\ValidardorSectorMdw::class . ':ValidarSiEsMozo')->add(\ValidadadorParamsMdw::class . ':ValidarToken');
  $group->put('[/]', \PedidoController::class . ':EditarEstadoPedido')->add(\ValidardorEstadosMdw::class . ':ValidarEstadoPedido')->add(\ValidadadorParamsMdw::class . ':ValidarToken');
});


$app->group('/login', function (RouteCollectorProxy $group) {
  $group->post('[/]', \LoginController::class . ':Login')->add(\ValidadadorParamsMdw::class . ':ValidarParamsLogin');
});


$app->run();
