<?php
//?Luego con un archivo JavaScript para que mediante una peticion AJAX va a llamar a este archivo para que devuelva valores de operaciones

//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "../modelos/Articulo.php";

$articulo = new Articulo();

//si existe existe el objeto lo voy a validar con el metodo limpiar cadena
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]):"";
$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]):"";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]):"";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]):"";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]):"";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]):"";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]):"";
$talle = isset($_POST["talle"]) ? limpiarCadena($_POST["talle"]):"";
$precio_venta = isset($_POST["precio_venta"]) ? limpiarCadena($_POST["precio_venta"]):"";
$precio_compra = isset($_POST["precio_compra"]) ? limpiarCadena($_POST["precio_compra"]):"";


//como vamos hacer peticiones mediante AJAX, es decir, mediante la url, usamos $_GET
//Cuando hagan una peticion a este archivo ajax y le envien mediante el metodo $_GET, mediante la url, 
//...una operacion, se va a evaluar que instruccion ejecutar para devoler un valor  
switch ($_GET["op"]){
    case 'guardaryeditar':
        //si no existe ningun archivo de imagen seleccionado -> !file_exists($_FILES['imagen']['tmp_name']
        //Ó si no ha sido cargado ningun archivo dentro de mi objeto imagen -> || !is_uploaded_file($_FILES['imagen'])
        if(!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
        {
            $imagen = $_POST["imagenactual"];
        }else{ //valido que el archivo subido sea de tipo imagen
            $ext = explode(".", $_FILES['imagen']['name']);//1.Obtengo la extension del archivo, a partir del punto, que se esta subiendo
            if ($_FILES['imagen']['type'] == "image/jpg" || 
                $_FILES['imagen']['type'] == "image/jpeg" ||
                $_FILES['imagen']['type'] == "image/png")
            {
                $imagen = round(microtime(true)) . '.' . end($ext); //renombro la imagen para que no haya repetidos
                move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/articulos/' . $imagen); //subo el archivo imagen
                move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/articulos/'.$imagen); //subo el archivo imagen
            }
        }
        //si yo no estoy enviando ningun $idcategoria significa que yo no estoy editando sino guardando
        if(empty($idarticulo)){
            $rta = $articulo->Insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $talle, $precio_venta, $precio_compra); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Articulo registrado' : 'No se pudo registrar';
        }
        else{
            $rta = $articulo->Editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $talle, $precio_venta, $precio_compra); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Articulo actualizado' : 'No se pudo actualizar';
        }
    break;
    case 'desactivar':
        $rta = $articulo->desactivar($idarticulo);
        echo $rta ? 'Articulo desactivado' : 'No se pudo desactivar';
    break;
    case 'activar':
        $rta = $articulo->activar($idarticulo);
        echo $rta ? 'Articulo activado' : 'No se pudo activar';
    break;
    case 'mostrar':
        $rta = $articulo->Mostrar($idarticulo);        
        echo json_encode($rta);//Codifico el resultado utilizando json
    break;
    case 'listar':
        $data = Array();//declaro un array
        $rta = $articulo->Listar();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger btn-sm" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning btn-sm" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-primary btn-sm" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => $reg->descripcion,
                "4" => $reg->precio_compra,
                "5" => $reg->precio_venta,
                "6" => $reg->talle,
                "7" => $reg->stock,
                "8" => "<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>",
                "9" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desativado</span>',
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
    case "selectCategoria":
        require_once "../modelos/categoria.php";
        $categoria = new Categoria();
        $rspta = $categoria->select();

        while( $reg = $rspta->fetch_object()){
            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
        };
    break;
}


?> 
















