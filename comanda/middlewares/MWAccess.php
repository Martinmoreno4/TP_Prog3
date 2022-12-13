<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class MWAccess 
{
    private $userTypes = [
        "Admin", "Camarera", "Cocinero", "Barman"
    ];


    public function validateToken($request, $rHandler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            JWTAuthenticator::verifyToken($token);
            $response = $rHandler->handle($request);
        }
        else 
        { 
            $response->getBody()->write(json_encode(array("Token error" => "You need the token")));
            $response = $response->withStatus(401);
        }
        return  $response->withHeader('Content-Type', 'application/json');
    }


    public function isAdmin($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = JWTAuthenticator::getTokenData($token);
            
            if ($data->User_Type == 'Admin') 
            {
                $response = $handler->handle($request);
            }
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Only admin has access")));
                $response = $response->withStatus(401);
            }
        }
        else 
        {
            $response->getBody()->write(json_encode(array("Admin error" => "You need the token as Admin")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function isEmployee($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        try 
        {
            if (!empty($header)) 
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = JWTAuthenticator::getTokenData($token);
                if (in_array($data->User_Type, $this->userTypes)) 
                {
                    if ($data->User_Type != "Admin") 
                    {
                        $response = $handler->handle($request);
                    }
                } 
                else 
                {
                    $response->getBody()->write(json_encode(array("error" => "Only registered personnel have access")));
                    $response = $response->withStatus(401);
                }
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "You need the token")));
                $response = $response->withStatus(401);
            }
        }
        catch (\Throwable $th) 
        {
            echo $th->getMessage();
        }
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function isBarman($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = JWTAuthenticator::getTokenData($token);
            if ($data->User_Type == "Barman" 
            || $data->User_Type == "Admin") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Only Barman or Admin has access")));
                $response = $response->withStatus(401);
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("Admin error" => "You need the token as Barman or Admin")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function isCheff($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = JWTAuthenticator::getTokenData($token);
            if ($data->User_Type == "Cocinero" || $data->User_Type == "Admin") 
            {
                $response = $handler->handle($request);
            } 
            else 
            {
                $response->getBody()->write(json_encode(array("error" => "Only Cocinero or Admin has access")));
                $response = $response->withStatus(401);
            }
        } 
        else 
        {
            $response->getBody()->write(json_encode(array("Admin error" => "You need the token as Cocinero or Admin")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function isWaitress($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) 
        {
            $token = trim(explode("Bearer", $header)[1]);
            $data = JWTAuthenticator::getTokenData($token);
            if ($data->User_Type == "Camarera"
            || $data->User_Type == "Admin") 
            {
                $response = $handler->handle($request);
            } 
            else
            {
                $response->getBody()->write(json_encode(array("error" => "Only Camarera or Admin has access")));
                $response = $response->withStatus(401);
            }
        }
        else
        {
            $response->getBody()->write(json_encode(array("Admin error" => "You need the token as Camarera or Admin")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>