<?php


require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';
require_once './middlewares/AuthJWT.php';

class UsuarioController extends Usuario implements IApiUsable 
{


    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        echo '<br>Datos de Usuario a crear:<br>';
        var_dump($params);
        
        $Usuario = Usuario::createUsuario(
          $params['nombreUsuario'], 
          $params['contrasena'], 
          $params['esAdmin'], 
          $params['tipoDeUsuario'],
          $params['estado'], 
          date('Y-m-d H:i:s'));
        echo '<br> Usuario a crear:<br>';
        $Usuario->printSingleEntityAsTable();
        if (Usuario::insertUsuario($Usuario) > 0) 
        {
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
        }
        else
        {
            $payload = json_encode(array("mensaje" => "Error al crear el Usuario"));
        }

        $response->getBody()->write($payload);
        return $response -> withHeader('Content-Type', 'application/json');
    }


    public function TraerUno($request, $response, $args)
    {

        $usr = $args['id'];
        $Usuario = Usuario::getUsuarioPorId($usr);
        $payload = json_encode($Usuario);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {

        $UsuariosList = Usuario::getTodosUsuarios();
        Usuario::printEntidadesComoMesa($UsuariosList);
        $payload = json_encode(array("UsuariosList" => $UsuariosList));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        var_dump($params);
        if(isset($params['nombreUsuario']))
        {
            $usr = $params['nombreUsuario'];
            $myUsuario = Usuario::getUsuarioBynombreUsuario($usr);
            $myUsuario->setNombreUsuario($params['nombreUsuario']);
            $myUsuario->setContrasena($params['contrasena']);

            echo $myUsuario->setNombreUsuario()."<br>";
            var_dump($myUsuario);

            if(!Usuario::modifyUsuario($myUsuario))
            {
              $payload = json_encode(array("mensaje" => "Usuario no modificado"));
            }
            else
            {
              $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
            }
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function BorrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $UsuarioForDelete = Usuario::getUsuarioPorId($params['Id']);
        Usuario::deleteUsuario($UsuarioForDelete);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function Login($request, $response, $args)
    {
      
        $params = $request->getParsedBody();

        if (isset($params['nombreUsuario']) && isset($params['contrasena'])) {
            $nombreUsuario = $params['nombreUsuario'];
            $contrasena = $params['contrasena'];
            $myUsuario = Usuario::getUsuarioBynombreUsuario($nombreUsuario);

            if ($myUsuario != null && ($myUsuario->getNombreUsuario() == $nombreUsuario && $myUsuario->getContrasena() == $contrasena)) {
                $payload = json_encode(array("mensaje" => "Login exitoso"));
            } else {
                $payload = json_encode(array("mensaje" => "Login fallido"));
            }
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public static function GetInfoByToken($request)
    {
      $header = $request->getHeader('Authorization');
      $token = trim(str_replace("Bearer", "", $header[0]));
      $Usuario = JWTAuthenticator::getTokenData($token);
      
      return $Usuario;
    }
}
?>