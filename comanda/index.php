

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
$app->setBasePath('/comanda');
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

  $app->get('[/]', function (Request $request, Response $response) {    
    $response->getBody()->write("
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='icon' type='image/x-icon' href='../Assets/test_page/icon/taiIcono.png'>
        <link rel='stylesheet' href='../css/styles.css' defer>
        <link rel='stylesheet' href='../css/animation.css' defer>
        <link rel='stylesheet' href='../css/bootstrap.css' defer>
        <script src='../js/script.js' defer></script>
        <title>Comanda</title>
    </head>

    <body class='dark-mode'>
        <header>
            <!-- TOP RIGHT BLACK RIBBON -->
            <div class='github-fork-ribbon-wrapper right'>
                <div class='github-fork-ribbon'>
                    <a href='#' target='_blank'>Comanda</a>
                </div>
            </div>

            <section>    
                <nav class='nav1'>
                    <div class='nav-container'>
                        <!-- TOP RIGHT ICON MENU -->
                    <div class='menuIcon' id='open'>
                        <span>&#9776;</span>
                    </div>

                    <div class='logo'>
                        <img src='../Assets/test_page/beer_Logo.jpg' width='100px' alt='Car Logo'>
                    </div>

                    <div class='myLinks' id='myLinks'>
                        <a href='./' id='enlace-inicio' class='btn-header'>Home</a>
                        <a href='#' id='enlace-inicio' class='btn-header'>Fake Login</a>
                        <a href='#' id='enlace-variety' class='btn-header'>Our Menu</a>
                        <a href='#' id='enlace-servicio' class='btn-header'>Photos</a>
                        <a href='#' id='enlace-contacto' class='btn-header'>Contact Us</a>
                    </div>
                </div>

                </nav>
            </section>
            
            <section class='bounceInDown animated header'>
                <div class='container-fluid'>
                    <div class='row justify-content-center'>
                        <h1 align='center'>
                            Comanda - Programaci&oacute;n III - Facundo Falcone
                        </h1>
                        <div class='imgBody row justify-content-center'>
                            
                        </div>
                    </div>
                    
                </div>
                
            </section>
        </header>
        <main>
            <div align='center' id='spinner'>
                <!-- <h1 align='center'>No se ve pero gira -> O </h1> -->
            </div>
            <div class='container-fluid'>
                <div align='center'>
                    <img src='https://github.com/caidevOficial/Resume/blob/main/media/pm/pageImgs/banner.gif?raw=true' width='600px'/><br>
                    <h1>Hi 👋, I'm Facu!</h1>
                    <h3>Pisces♓ | Developer👨‍💻 | Python<img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/python/python-original.svg' alt='python' width='40' height='28'/> | GCP <img src='https://www.vectorlogo.zone/logos/google_cloud/google_cloud-icon.svg?raw=true' alt='GCP' width='30' height='30'> | Java <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/java/java-original.svg' alt='java' width='30' height='30'/> | C# <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/csharp/csharp-original.svg' alt='csharp' width='25' height='25'/> | Dreamer 💖 | Teacher👨‍🏫| A bit nerd🤓</h3><br>
                    <h3>Programming Student & Assistant Professor at the <strong>National Technological University [UTN]</strong> 👨‍💻</h3>
                    <h3>Backend programmer at <strong>Accenture</strong> 👨‍💻</h3>
                </div>
                <div>
                    <img src='https://hit.yhype.me/github/profile?user_id=12877139' alt=''>
                </div>
                <p align='center'>
                    <img src='https://komarev.com/ghpvc/?username=caidevoficial&label=Profile%20views&color=0e75b6&style=plastic' alt='caidevoficial' />
                </p>
                
                <p align='center'>
                    <a href='https://github.com/CaidevOficial'>
                        <img src='https://github-profile-trophy.vercel.app/?username=caidevoficial&theme=nord&column=7' alt='caidevoficial' />
                    </a>
                </p>
                <section>
                    <div align='center' class='justify-content-center'>
                        <table>
                            <theader>
                              <tr align='center'>
                                <td>
                                  <img class='circular' alt='Facu' src='https://avatars1.githubusercontent.com/u/12877139?s=400&u=d369ee24466653d9bbeeb9654930e3ff1c67b76a&v=4' width='80px' height='80px' />
                                </td>
                              </tr>
                              <th><center>🤴 Facu Falcone - Junior Developer</center></th>
                            </theader>
                            <tbody>
                                <tr align='center'>
                                    <td>
                                      <a href='https://github.com/caidevOficial/'>
                                        <img alt='GitHub' src='https://img.shields.io/badge/GitHub-%2312100E.svg?&style=for-the-badge&logo=Github&logoColor=white' width='125px' height='30px' />
                                      </a>
                                    </td>
                                  </tr>
                                  <tr align='center'>
                                    <td>
                                      <a href='https://www.linkedin.com/in/facundo-falcone/'>
                                        <img alt='LinkedIn' src='https://img.shields.io/badge/linkedin-%230077B5.svg?&style=for-the-badge&logo=linkedin&logoColor=white' width='125px' height='30px' />
                                      </a>
                                    </td>
                                  </tr>
                                  <tr align='center'>
                                    <td>
                                      <a href='https://cafecito.app/caidevoficial/'>
                                        <img alt='Invitame un café en cafecito.app' srcset='https://cdn.cafecito.app/imgs/buttons/button_5.png 1x, https://cdn.cafecito.app/imgs/buttons/button_5_2x.png 2x, https://cdn.cafecito.app/imgs/buttons/button_5_3.75x.png 3.75x' src='https://cdn.cafecito.app/imgs/buttons/button_5.png' width='125px' height='30px' />
                                      </a>
                                    </td>
                                  </tr>
                                  <tr align='center'>
                                    <td>
                                      <a href='https://ko-fi.com/P5P74JBOH' target='_blank'>
                                        <img width='125px' height='30px' style='border:0px;height:36px;' src='https://cdn.ko-fi.com/cdn/kofi1.png?v=2' border='0' alt='Buy Me a Coffee at ko-fi.com' />
                                      </a>
                                    </td>
                                  </tr>
                                <tr align='center'>
                                    <td>
                                        <div align='center'>
                                            <h3>Languages and Tools: ⚙️</h3>
                                            <p> 
                                                <a href='https://www.python.org' target='_blank'>
                                                    <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/python/python-original.svg' alt='python' width='40' height='40'/>
                                                </a> 
                                                <a href='https://pandas.pydata.org/' target='_blank'> 
                                                    <img src='https://github.com/devicons/devicon/blob/master/icons/pandas/pandas-original-wordmark.svg?raw=true' alt='Pandas' width='40' height='40' /> 
                                                </a>
                                                <a href='https://numpy.org/' target='_blank'> 
                                                    <img src='https://caidevoficial.github.io/FF_Resume/media/icons/numpy/numpy_logo.svg?raw=true' alt='NumPy' width='40' height='40' /> 
                                                </a>
                                                <a href='https://matplotlib.org/' target='_blank'>
                                                    <img src='https://camo.githubusercontent.com/9f609b65162567643c396ef42e9ccc2f755906847714389cbc1dcd707b234ebb/68747470733a2f2f6d6174706c6f746c69622e6f72672f5f7374617469632f6c6f676f325f636f6d707265737365642e7376673f7261773d74727565' alt='Matplotlib' width='40' height='40' /> 
                                                </a>
                                                <a href='https://www.djangoproject.com/' target='_blank'>
                                                    <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/django/django-original.svg' alt='django' width='40' height='40'/>
                                                </a> 
                                                <a href='https://www.java.com' target='_blank'>
                                                    <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/java/java-original.svg' alt='java' width='40' height='40'/>
                                                </a> 
                                                <a href='https://www.w3schools.com/cs/' target='_blank'>
                                                    <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/csharp/csharp-original.svg' alt='csharp' width='40' height='40'/>
                                                </a> 
                                                <a href='https://www.cprogramming.com/' target='_blank'>
                                                    <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/c/c-original.svg' alt='c' width='40' height='40'/>
                                                </a> 
                                                <a href='https://www.arduino.cc/' target='_blank'>
                                                    <img src='https://cdn.worldvectorlogo.com/logos/arduino-1.svg' alt='arduino' width='40' height='40'/>
                                                </a> 
                                                <a href='https://dotnet.microsoft.com/' target='_blank'>
                                                    <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/dot-net/dot-net-original-wordmark.svg' alt='dotnet' width='40' height='40'/>
                                                </a> 
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                        <p>
                                            <a href='https://www.apachefriends.org/es/index.html/' target='_blank'>
                                                <img src='https://github.com/devicons/devicon/blob/master/icons/php/php-original.svg?raw=true' alt='php' width='40' height='40'/>
                                            </a> 
                                            <a href='http://tomcat.apache.org/' target='_blank'>
                                                <img src='https://github.com/caidevOficial/Resume/blob/main/media/icons/tomcat/tomcat-original.svg?raw=true' alt='TomCat' width='40' height='40'/>
                                            </a> 
                                            <a href='https://insomnia.rest/download' target='_blank'> 
                                                <img src='https://insomnia.rest/images/insomnia-logo.svg?raw=true' alt='Insomnia' width='100' height='40' /> 
                                            </a>
                                            <a href='https://www.postman.com/' target='_blank'> 
                                                <img src='https://github.com/caidevOficial/Resume/blob/main/media/icons/postman/getpostman-icon.svg?raw=true' alt='Postman' width='40' height='40' /> 
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                        <p>
                                            <a href='https://cloud.google.com/' target='_blank'> 
                                                <img src='https://www.vectorlogo.zone/logos/google_cloud/google_cloud-icon.svg?raw=true' alt='GCP' width='40' height='40'> 
                                            </a>
                                            <a href='https://cloud.google.com/bigquery' target='_blank'> 
                                                <img src='https://www.vectorlogo.zone/logos/google_bigquery/google_bigquery-icon.svg' alt='BigQuery' width='40' height='40'> 
                                            </a>
                                            <a href='https://mariadb.org/' target='_blank'>
                                                <img src='https://www.vectorlogo.zone/logos/mariadb/mariadb-icon.svg' alt='mariadb' width='40' height='40'/>
                                            </a> 
                                            <a href='https://www.mysql.com/' target='_blank'>
                                                <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original-wordmark.svg' alt='mysql' width='40' height='40'/>
                                            </a> 
                                            <a href='https://www.sqlite.org/' target='_blank'>
                                                <img src='https://www.vectorlogo.zone/logos/sqlite/sqlite-icon.svg' alt='sqlite' width='40' height='40'/>
                                            </a> 
                                            <a href='https://www.microsoft.com/es-es/sql-server/sql-server-downloads/' target='_blank'>
                                                <img src='https://caidevoficial.github.io/FF_Resume/media/icons/mssql/microsoft-sql-server.svg?raw=true' alt='mssql' width='40' height='40'/>
                                            </a> 
                                        </p>
                                    </td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                        <p>
                                            <a href='https://git-scm.com/' target='_blank'>
                                                <img src='https://www.vectorlogo.zone/logos/git-scm/git-scm-icon.svg' alt='git' width='40' height='40'/>
                                            </a> 
                                            <a href='https://developer.mozilla.org/en-US/docs/Web/JavaScript' target='_blank'>
                                                <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/javascript/javascript-original.svg' alt='javascript' width='40' height='40'/>
                                            </a> 
                                            <a href='https://getbootstrap.com' target='_blank'>
                                                <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/bootstrap/bootstrap-plain-wordmark.svg' alt='bootstrap' width='40' height='40'/>
                                            </a> 
                                            <a href='https://www.w3schools.com/css/' target='_blank'>
                                                <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/css3/css3-original-wordmark.svg' alt='css3' width='40' height='40'/>
                                            </a> 
                                            <a href='https://www.w3.org/html/' target='_blank'>
                                                <img src='https://raw.githubusercontent.com/devicons/devicon/master/icons/html5/html5-original-wordmark.svg' alt='html5' width='40' height='40'/>
                                            </a> 
                                        </p>
                                    </td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                        <p>
                                            <a href='https://www.eclipse.org/' target='_blank'>
                                                <img src='https://github.com/caidevOficial/Logos/blob/master/Lenguajes/logo-eclipse.png?raw=true' alt='Eclipse' width='40' height='40' />
                                            </a>
                                            <a href='https://netbeans.apache.org/download/' target='_blank'>
                                                <img src='https://netbeans.apache.org/images/apache-netbeans.svg?raw=true' alt='Netbeans' width='40' height='40' />
                                            </a>
                                            <a href='https://www.jetbrains.com/es-es/pycharm/' target='_blank'>
                                                <img src='https://github.com/devicons/devicon/blob/master/icons/pycharm/pycharm-original.svg?raw=true' alt='PyCharm' width='40' height='40'/>
                                            </a>
                                            <a href='https://code.visualstudio.com/' target='_blank'>
                                                <img src='https://raw.githubusercontent.com/github/explore/80688e429a7d4ef2fca1e82350fe8e3517d3494d/topics/visual-studio-code/visual-studio-code.png?raw=true' alt='visualStudio' width='40' height='40'/>
                                            </a>
                                            <a href='https://code.visualstudio.com/' target='_blank'>
                                                <img src='https://github.com/devicons/devicon/blob/master/icons/visualstudio/visualstudio-plain.svg?raw=true' alt='visualStudio' width='40' height='40'/>
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                        <p>
                                            <a href='https://azure.microsoft.com/en-in/' target='_blank'>
                                                <img src='https://www.vectorlogo.zone/logos/microsoft_azure/microsoft_azure-icon.svg' alt='azure' width='40' height='40'/>
                                            </a> 
                                            <a href='https://slack.com/intl/es-ar/' target='_blank'>
                                                <img src='https://github.com/devicons/devicon/blob/master/icons/slack/slack-original.svg?raw=true' alt='Slack' width='40' height='40' />
                                            </a>
                                            <a href='https://trello.com/es' target='_blank'>
                                                <img src='https://github.com/devicons/devicon/blob/master/icons/trello/trello-plain-wordmark.svg?raw=true' alt='Trello' width='40' height='40' />
                                            </a>
                                        </p>
                                    </td>
                                </tr>
                                <tr align='center'>
                                    <td>
                                        <p>
                                            <a href='https://www.linux.org/' target='_blank'>
                                                <img src='https://github.com/devicons/devicon/blob/master/icons/linux/linux-original.svg?raw=true' alt='linux' width='40' height='40'/>
                                            </a>
                                            <a href='https://www.debian.org/' target='_blank'>
                                                <img src='https://www.debian.org/Pics/openlogo-50.png?raw=true' alt='Debian' width='40' height='40' />
                                            </a>
                                            <a href='https://www.deepin.org/' target='_blank'>
                                                <img src='https://caidevoficial.github.io/FF_Resume/media/pm/deepin-logo.svg?raw=true' alt='Deepin' width='40' height='40' />
                                            </a>
                                            <a href='https://www.ubuntu.org/' target='_blank'>
                                                <img src='https://github.com/caidevOficial/Resume/blob/main/media/icons/ubuntu/ubuntu-plain-wordmark.svg?raw=true' alt='Ubuntu' width='40' height='40' />
                                            </a>
                                            <a href='https://www.microsoft.com/es-ar/windows/' target='_blank'>
                                                <img src='https://github.com/caidevOficial/Logos/blob/master/Lenguajes/windows.svg?raw=true' alt='Windows' width='40' height='40' />
                                            </a>
                                        </p> 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
        
        
        <!-- Footer -->
        <footer class='footer'>
            <!-- Coffee -->
            <aside class='aside animated bounceInRight'>
                <h2>Some Promotional ads</h2>
                <!-- Sale un cafecito ;) -->
                <a href='https://cafecito.app/caidevoficial' rel='noopener' target='_blank'>
                    <img srcset='https://cdn.cafecito.app/imgs/buttons/button_6.png 1x, 
                    https://cdn.cafecito.app/imgs/buttons/button_6_2x.png 2x, 
                    https://cdn.cafecito.app/imgs/buttons/button_6_3.75x.png 3.75x' src='https://cdn.cafecito.app/imgs/buttons/button_6.png' alt='Invitame un café en cafecito.app' />
                </a>
                <a href='https://ko-fi.com/P5P74JBOH' target='_blank'>
                    <img width='125px' height='30px' style='border:0px;height:36px;' src='https://cdn.ko-fi.com/cdn/kofi1.png?v=2' border='0' alt='Buy Me a Coffee at ko-fi.com' />
                </a>
            </aside>
            <section class='bounceInUp'>
                <nav class='nav1 sameLine'>
                    <div class='myLinks nav1' id='myLinks'>
                      <a href='./' id='enlace-inicio' class='btn-header'>Home</a>
                      <a href='#' id='enlace-inicio' class='btn-header'>Fake Login</a>
                      <a href='#' id='enlace-variety' class='btn-header'>Our Menu</a>
                      <a href='#' id='enlace-servicio' class='btn-header'>Photos</a>
                      <a href='#' id='enlace-contacto' class='btn-header'>Contact Us</a>
                    </div>
                    <p id='copyright'><h4>Facu Falcone &COPY; 2021 all rights reserveds</h4></p>
                </nav>
            </section>
        </footer>
    </body>
    </html>");
    return $response;
  });
  
// Run app
$app->run();
?>
