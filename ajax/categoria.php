<?php
//?Luego con un archivo JavaScript para que mediante una peticion AJAX va a llamar a este archivo para que devuelva valores de operaciones

//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "../modelos/Categoria.php";

$categoria = new Categoria();

//si existe existe el objeto lo voy a validar con el metodo limpiar cadena
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]):"";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]):"";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]):"";

//como vamos hacer peticiones mediante AJAX, es decir, mediante la url, usamos $_GET
//Cuando hagan una peticion a este archivo ajax y le envien mediante el metodo $_GET, mediante la url, 
//...una operacion, se va a evaluar que instruccion ejecutar para devoler un valor  
switch ($_GET["op"]){
    case 'guardaryeditar':
        //si yo no estoy enviando ningun $idcategoria significa que yo no estoy editando sino guardando
        if(empty($idcategoria)){
            $rta = $categoria->insertar($nombre, $descripcion); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Categoria registrada' : 'No se pudo registrar';
        }
        else{
            $rta = $categoria->Editar($nombre, $descripcion, $idcategoria); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Categoria actualizada' : 'No se pudo actualizar';
        }
    break;
    case 'desactivar':
        $rta = $categoria->desactivar($idcategoria);
        echo $rta ? 'Categoria desactivada' : 'No se pudo desactivar';
    break;
    case 'activar':
        $rta = $categoria->activar($idcategoria);
        echo $rta ? 'Categoria activada' : 'No se pudo activar';
    break;
    case 'mostrar':
        $rta = $categoria->mostrar($idcategoria);        
        echo json_encode($rta);//Codificar el resultado utilizando json
    break;
    case 'listar':
        $data = Array();//declaro un array
        $rta = $categoria->listar();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idcategoria.')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idcategoria.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idcategoria.')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->descripcion,
                "3" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desativado</span>',
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
















