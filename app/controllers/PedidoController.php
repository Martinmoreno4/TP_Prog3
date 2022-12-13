<?php

require_once './interfaces/IApiUsable.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
require_once './models/UploadManager.php';
require_once './controllers/UsuarioController.php';
 
class PedidoController extends Pedido implements IApiUsable 
{

    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['Id'];
        $pedido = pedido::getPedidoPorId($id);
        $payload = json_encode($pedido);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerTodos($request, $response, $args)
    {
        $pedidos = pedido::getAll();

        echo 'pedidos: <br>';
        pedido::printEntidadesComoMesa($pedidos);

        $payload = json_encode(array("pedidos" => $pedidos));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function TraerSegunArea($request, $response, $args) 
    {
        $usuario_tipo = UserController::GetInfoByToken($request)->getUserType();

        $Comidas = Comida::getcomidasPorTipoDeUsuario($usuario_tipo);

        echo 'Comidas for: '.${$usuario_tipo}.'<br>';
        Comida::printEntidadesComoMesa($Comidas);

        $payload = json_encode(array("Comidas" => $Comidas)); 
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function TraerPedidosTiempo($request, $response, $args)
    {
        $pedidos = pedido::getPedidosConTiempo();
        echo 'pedidos: <br>';
        pedido::printEntidadesEstandarComomesa($pedidos);

        $payload = json_encode(array("pedidos" => $pedidos));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function CargarUno($request, $response, $args)
    {
        $imagesDirectory = "./imagenDePedido/";
        $params = $request->getParsedBody();
        
        $table_id = $params['table_id'];
        
        $pedido = pedido::crearPedido(
            $table_id, 
            $params['pedido_status'], 
            $params['customer'], 
            $params['pedido_cost']
        );
        
        $payload = json_encode($pedido);
        $pedido_id = pedido::insertpedido($pedido); 
        if($pedido_id > 0)
        { 
            $payload = json_encode(array("mensaje" => "Pedido Creado con exito"));
            $response->getBody()->write("pedido Creado correctamente");
            $fileManager = new UploadManager($imagesDirectory, $pedido_id, $_FILES);
            $pedido = pedido::getPedidoPorId($pedido_id);
            $pedido->setImagenDePedido(UploadManager::getOrderImageNameExt($fileManager, $pedido_id));
            pedido::updateImagen($pedido);
            echo 'pedido Creado: <br>'; 
            $pedido->printUnaEntidadComoMesa();
        }
        else
        {
            $response->getBody()->write("Error al Borrar el pedido");
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }


	public function BorrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['Id'];
        $pedido = pedido::getPedidoPorId($id);
        $payload = json_encode($pedido);
        if(pedido::deletePedidoPorId($id) > 0)
        {
            $payload = json_encode(array("mensaje" => "Orden Eliminada con exito"));
            $response->getBody()->write("pedido borrado correctamente");
        }
        else
        {
            $response->getBody()->write("Error al Borrar el pedido");
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['Id'];
        
        $pedido = pedido::getPedidoPorId($id);
        $pedido->setEstadoDePedido($params['Status']);
        $pedido->setPrecioPedido(Comida::getSumaDePreciosPorPedido($pedido->getId()));

        echo 'New pedido data: <br>';
        $pedido->printUnaEntidadComoMesa();

        if (pedido::updatepedido($pedido) > 0)
        {
            $payload = json_encode(array("mensaje" => "Orden modificada con exito"));
            $response->getBody()->write("pedido actualizado correctamente");
        }
        else 
        {
            $response->getBody()->write("Error al actualizar el pedido");
        }
    }
}
?>