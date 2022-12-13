

<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';

require_once './controllers/ComidaController.php'; 
require_once './controllers/EmpleadoController.php'; 
require_once './controllers/FileController.php';
require_once './controllers/LoginController.php'; 
require_once './controllers/PedidoController.php'; 
require_once './controllers/EncuestaController.php'; 
require_once './controllers/UsuarioController.php'; 
require_once './controllers/MesaController.php';

require_once './db/AccesoDatos.php'; 

require_once './middlewares/Logger.php';
require_once './middlewares/AuthJWT.php';
require_once './middlewares/MWAccess.php';

date_default_timezone_set('America/Argentina/Buenos_Aires');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
//$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/app');
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);


$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->post('[/]', \UsuarioController::class . ':CargarUno'); 
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
    $group->post('/login/', \UsuarioController::class . ':Login');
  })->add(\MWAccess::class . ':isAdmin');


$app->group('/empleados', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \EmpleadoController::class . ':TraerTodos');
    $group->post('[/]', \EmpleadoController::class . ':CargarUno');
    $group->delete('/{id}', \EmpleadoController::class . ':BorrarUno');
})->add(\MWAccess::class . ':isAdmin');


  $app->group('/comida', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ComidaController::class . ':TraerTodos')->add(\MWAccess::class . ':isempleado');
  
    $group->post('[/]', \ComidaController::class . ':CargarUno')->add(\MWAccess::class . ':isWaitress');
    $group->put('/modify', \ComidaController::class . ':ModificarUno')->add(\MWAccess::class . ':isempleado');

  });


  $app->group('/pedido', function (RouteCollectorProxy $group) 
  {
    $group->get('[/]', \PedidoController::class . ':TraerTodos')->add(\MWAccess::class . ':isWaitress');

    $group->get('/list/byTime', \PedidoController::class . ':TraerPedidosTiempo')->add(\MWAccess::class . ':isAdmin');

    $group->post('[/]', \PedidoController::class . ':CargarUno')->add(\MWAccess::class . ':isWaitress');
    $group->put('/', \PedidoController::class . ':ModificarUno')->add(\MWAccess::class . ':isempleado');
  });

  $app->group('/mesa', function (RouteCollectorProxy $group)  
  {
    $group->get('[/]', \MesaController::class . ':TraerTodos') 
      ->add(\MWAccess::class . ':isWaitress');
     $group->get('/admin/list', \MesaController::class . ':TraerTodos') 
      ->add(\MWAccess::class . ':isAdmin');
    $group->put('/bill', \MesaController::class . ':CobrarUno') 
      ->add(\MWAccess::class . ':isWaitress');
    $group->put('/modify', \MesaController::class . ':ModificarUno') 
      ->add(\MWAccess::class . ':isWaitress');
    $group->put('/closemesa', \MesaController::class . ':ModificarUnoAdmin') 
    ->add(\MWAccess::class . ':isAdmin');
  });


  $app->group('/customer', function (RouteCollectorProxy $group)
  {
    $group->get('/mesa/{mesa_code}/{pedido_id}/', \MesaController::class . ':TraerDemoraPedidoMesa'); 
    $group->post('/encuesta', \EncuestaController::class . ':createEncuesta'); 
  });

  $app->group('/login', function (RouteCollectorProxy $group) 
  {
    $group->post('[/]', \LoginController::class . ':verifyUsuario');
  });


  $app->group('/Admin', function (RouteCollectorProxy $group)
   {
    $group->post('/getEncuestas', \EncuestaController::class . ':getEncuestasMasAltas');
    $group->post('/downloadReports', \FileController::class . ':DownloadPdf');
  })->add(\MWAccess::class . ':isAdmin');

  $app->group('/fileManager', function (RouteCollectorProxy $group) 
  {
    $group->get('/write_csv', \FileController::class . ':Write');
    $group->get('/read_csv', \FileController::class . ':Read');
  })->add(\MWAccess::class . ':isAdmin');


  
// Run app
$app->run();
?>
