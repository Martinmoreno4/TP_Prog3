<?php


 require_once './models/Usuario.php'; 

 class LoginController extends Usuario
 {


    public function verifyUsuario($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $nombreUsuario = $params['nombreUsuario'];
        $pass = $params['contrasena'];
        
        $Usuario = Usuario::getUsuarioBynombreUsuario($nombreUsuario);
        $Usuario->printSingleEntityAsTable();
        $payload = json_encode(array('status' => 'Invalid Usuario'));
        
        if(!is_null($Usuario)){
            if(contrasena_verify($pass, $Usuario->getContrasena()))
            {
                $UsuarioData = array(
                    'id' => $Usuario->getId(),
                    'nombreUsuario' => $Usuario->getNombreUsuario(),
                    'contrasena' => $Usuario->getContrasena(),
                    'esAdmin' => $Usuario->getEsAdmin(),
                    'tipoDeUsuario' => $Usuario->getUsuarioTipo());
                
                    $payload = json_encode(array(
                    'Token' => JWTAuthenticator::createToken($UsuarioData), 
                    'response' => 'Valid_Usuario', 
                    'Admin_Status' => $Usuario->getEsAdmin(),
                    'tipoDeUsuario' => $Usuario->getUsuarioTipo()));
                $idLoginInserted = Usuario::insertHistorialIngresos($Usuario);

                if($idLoginInserted > 0)
                {
                    echo "Login inserted successfully";
                }
            }
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
 }
?>