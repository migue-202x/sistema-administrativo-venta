<?php
//?Luego con un archivo JavaScript para que mediante una peticion AJAX va a llamar a este archivo para que devuelva valores de operaciones

//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "../modelos/Permiso.php";

$permiso = new Permiso();

switch ($_GET["op"]){
    case 'listar':
        $data = Array();//declaro un array
        $rta = $permiso->listar();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" => $reg->nombre
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
















