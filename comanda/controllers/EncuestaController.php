<?php

 require_once './models/Encuesta.php'; 

 class EncuestaController{


    public function createEncuesta($request, $response, $args){
        $params = $request->getParsedBody();
        $payload = json_encode(array("message" => "Something faile while creating the encuesta."));
        if (isset($params['puntuacion_mesa']) && isset($params['puntuacion_chef'])
        && isset($params['puntuacion_mozo']) && isset($params['puntuacion_restaurante'])
        && isset($params['pedido_id']) && isset($params['comentario'])) {
            $pedido_id = $params['pedido_id'];
            $puntuacion_mesa = $params['puntuacion_mesa'];
            $puntuacion_restaurante = $params['puntuacion_restaurante'];
            $puntuacion_mozo = $params['puntuacion_mozo'];
            $puntuacion_chef = $params['puntuacion_chef'];
            $comentario = $params['comentario'];

            $encuesta = Encuesta::createEncuesta($pedido_id, $puntuacion_mesa, $puntuacion_restaurante, $puntuacion_mozo, $puntuacion_chef, $comentario);
            $encuesta->printUnaEntidadComomesa();

            if(Encuesta::insertencuesta($encuesta) > 0)
            {
                echo '<h1>encuesta created successfully, Thanks for choosing us!</h1>';
                $payload = json_encode(array("encuesta" => $encuesta, "message" => "encuesta created successfully, Thanks for choosing us!"));
            } 
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function getEncuestasMasAltas($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $payload = json_encode(array("message" => 'Error while loading encuestas'));
        if (isset($params['cantidad']))
        {
            $cantidad = $params['cantidad'];
            $encuestas = encuesta::getEncuestasMasAltas($cantidad);
            encuesta::printEntidadesComomesa($encuestas);
            $payload = json_encode(array("Best encuestas" => $encuestas));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
 }
?>