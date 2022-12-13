<?php


require_once './interfaces/IApiUsable.php';
require_once './models/Empleado.php';
require_once './models/Area.php';
require_once './models/Usuario.php';
require_once 'UsuarioController.php';  
//require_once 'AreaController.php';

class EmpleadoController extends Empleado implements IApiUsable 
{


    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        var_dump($params);
        $Empleado_name = $params['Name'];
        $Empleado_area = $params['Area'];
        $Empleado_Usuario = $params['Usuario']; 
        $Empleado_area = Area::getAreaPorNombre($Empleado_area);
        $Empleado_Usuario_id = Usuario::getUsuarioBynombreUsuario($Empleado_Usuario)->getId();
        $newEmpleado = Empleado::crearEmpleado($Empleado_Usuario_id, $Empleado_area->getAreaId(), $Empleado_name, date("Y-m-d H:i:s"));
        
        echo 'Empleado a Crear:<br>';
        $newEmpleado->printUnaEntidadComoMesa();

        if (Empleado::insertEmpleado($newEmpleado) > 0) {
            $payload = json_encode(array("mensaje" => "Usuario creado con exito"));
            $response->getBody()->write("Empleado creado con exito"); 
        }
        else
        {
            $response->getBody()->write("Error al crear Empleado");
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function TraerUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $Empleado_id = $params['Id'];
        $Empleado = Empleado::getEmpleadPorId($Empleado_id);
        $payload = json_encode($Empleado);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function TraerTodos($request, $response, $args)
    {
        $Empleados = Empleado::getTodosEmpleados();
        echo 'Empleados: <br>';
        Empleado::printEntidadesComoMesa($Empleados);

        $payload = json_encode(array("Empleados" => $Empleados));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function BorrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $Empleado_id = $params['Id'];
        $Empleado = Empleado::getEmpleadPorId($Empleado_id);
        if (Empleado::deleteEmpleado($Empleado) > 0) 
        {
            $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));
            $response->getBody()->write("Empleado borrado con exito");
        }
        else
        {
            $response->getBody()->write("Error al borrar Empleado");
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public function ModificarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $Empleado_id = $params['Id'];
        $Empleado = Empleado::getEmpleadPorId($Empleado_id);
        $Empleado_name = $params['Name'];
        $Empleado_area_id = Area::getAreaPorNombre($params['Area'])->getAreaId();
        $Empleado_Usuario_id = Usuario::getUsuarioBynombreUsuario($params['Usuario'])->getId();

        $Empleado->setNombre($Empleado_name);
        $Empleado->setEmpleadoAreaID($Empleado_area_id);
        $Empleado->setIdUsuario($Empleado_Usuario_id);

        if (Empleado::updateEmpleado($Empleado) > 0)
        {
            $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
            $response->getBody()->write("Empleado actualizado con exito");
        }
        else
        {
            $response->getBody()->write("Error al actualizar Empleado");
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>