<?php

use GuzzleHttp\Psr7\Log;
use Slim\Handlers\Strategies\RequestHandler;

class Logger
{
    public static function OperationLog($request, $response, $next)
    {
        $retorno = $next($request, $response);
        return $retorno;
    }

    public static function validateGP($request, $handler)
    {
        
        $requestType = $request->getMethod();
        $response = $handler->handle($request);

        if($requestType == 'GET')
        {
            $response->getBody()->write('<h1>GET Method</h1>');
        }
        else if($requestType == 'POST')
        {
            $response->getBody()->write('<h1>POST Method</h1>');
            $data = $request->getParsedBody();
            $name = $data['name'];
            $type = $data['type'];

            if ($type == 'Admin')
            {
                $response->getBody()->write('<h1> Welcome '.$name.'!</h1>');
            }
            else
            {
                $response->getBody()->write('<h1> You are not allowed to access this page!</h1>');
            }
        }

        return $response;
    }

}
?>