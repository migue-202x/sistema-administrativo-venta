<?php
//?Luego con un archivo JavaScript para que mediante una peticion AJAX va a llamar a este archivo para que devuelva valores de operaciones

//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "../modelos/Persona.php";

$persona = new Persona();

//si existe existe el objeto lo voy a validar con el metodo limpiar cadena
$idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona = isset($_POST["tipo_persona"]) ? limpiarCadena($_POST["tipo_persona"]):"";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]):"";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]):"";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]):"";

//como vamos hacer peticiones mediante AJAX, es decir, mediante la url, usamos $_GET
//Cuando hagan una peticion a este archivo ajax y le envien mediante el metodo $_GET, mediante la url, 
//...una operacion, se va a evaluar que instruccion ejecutar para devoler un valor  
switch ($_GET["op"]){
    case 'guardaryeditar':
        //si yo no estoy enviando ningun $idcategoria significa que yo no estoy editando sino guardando
        if(empty($idpersona)){
            $rta = $persona->insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Persona registrada' : 'No se pudo registrar';
        }
        else{
            $rta = $persona->Editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Persona actualizada' : 'No se pudo actualizar';
        }
    break;
    case 'eliminar':
        $rta = $persona->eliminar($idpersona);
        echo $rta ? 'Persona eliminada' : 'No se pudo eliminar';
    break;
    case 'mostrar': 
        $rta = $persona->mostrar($idpersona);        
        echo json_encode($rta);//Codificar el resultado utilizando json
    break;
    case 'listarproveedores':
        $data = Array();//declaro un array
        $rta = $persona->listarproveedores();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" =>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash "></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->direccion,
                "5" => $reg->telefono,
                "6" => $reg->email,
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar 
            "aaData"=>$data,
        );
        echo json_encode($results); //el listado y la paginacion de registros lo vamos hacer con datatables, que va usar el array $results
    break;
    case 'listarclientes':
        $data = Array();//declaro un array
        $rta = $persona->Listarclientes();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" =>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash "></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->direccion,
                "5" => $reg->telefono,
                "6" => $reg->email,
            );
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar 
            "aaData"=>$data,
        );
        echo json_encode($results); //el listado y la paginacion de registros lo vamos hacer con datatables, que va usar el array $results
    break;
}

?> 
















