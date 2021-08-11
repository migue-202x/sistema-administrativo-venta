<?php
//?Luego con un archivo JavaScript para que mediante una peticion AJAX va a llamar a este archivo para que devuelva valores de operaciones

if(strlen(session_id()) < 1){
    session_start();
}

//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "../modelos/Ingreso.php";

$ingreso = new Ingreso();

//si existe existe el objeto lo voy a validar con el metodo limpiar cadena
$idingreso = isset($_POST["idingreso"]) ? limpiarCadena($_POST["idingreso"]):""; //no mando idingreso, siempre inserto aqui
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]):"";
$idusuario = $_SESSION['idusuario'];
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]):"";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]):"";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]):"";
$total_compra = isset($_POST["total_compra"]) ? limpiarCadena($_POST["total_compra"]):"";



//como vamos hacer peticiones mediante AJAX, es decir, mediante la url, usamos $_GET
//Cuando hagan una peticion a este archivo ajax y le envien mediante el metodo $_GET, mediante la url, 
//...una operacion, se va a evaluar que instruccion ejecutar para devoler un valor  
switch ($_GET["op"]){
    case 'guardaryeditar':
        // echo '<script>console.log('.json_encode($_POST["idarticulo"]).')</script>';
        // si yo no estoy enviando ningun $idcategoria significa que yo no estoy editando sino guardando
        if(empty($idingreso)){
            $rta = $ingreso->Insertar($idproveedor, $idusuario , $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, 
            $impuesto, $total_compra, $_POST['idarticulo'], $_POST['cantidad'], $_POST['precio_compra'], $_POST['precio_venta']); 
            echo $rta ? 'Ingreso registrada' : 'No se pudo registrar todos los datos del ingreso';
        }
        else{

        }
    break;
    case 'anular':
        $rta = $ingreso->anular($idingreso);
        echo $rta ? 'Ingreso anulado' : 'Ingreso no se puede anular';
    break;
    case 'mostrar':
        $rta = $ingreso->mostrar($idingreso);        
        echo json_encode($rta);//Codificar el resultado utilizando json
    break;
    case 'listarDetalle':
        //Recibimos el idingreso
        $id = $_GET['id'];

        $rspta = $ingreso->listarDetalle($id);

        //Muestro cabecera de tabla
        echo '<thead style="background-color:#A9D0F5">
            <th>Opciones</th>
            <th>Articulo</th>
            <th>Cantidad</th>
            <th>Precio Compra</th>
            <th>Precio Venta</th>
            <th>Subtotal</th>
        </thead>';

        $total = 0; 
        //Muestro filas con los detalles
        while ($reg = $rspta->fetch_object()){
            $total = $total + $reg->precio_compra*$reg->cantidad;            
            echo '<tr class="filas">
            <td></td>
            <td>'.$reg->nombre.'</td>
            <td>'.$reg->cantidad.'</td>
            <td>'.$reg->precio_compra.'</td>
            <td>'.$reg->precio_venta.'</td>
            <td>'.$total.'</td>
            </tr>';
        }
        //Muestro pie de tabla
        echo '<tfoot>
            <th>TOTAL</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
                <h4 id="total">S/. '.$total.'</h4>
                <input type="hidden" name="total_compra" id="total_compra">
            </th>
        </tfoot>';

    break; 
    case 'listar':
        $rta = $ingreso->Listar();
        //declaro un array
        $data = Array();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? '<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.
                ' <button class="btn btn-danger" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>':
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>',
                "1" => $reg->fecha,
                "2" => $reg->proveedor,
                "3" => $reg->usuario,
                "4" => $reg->tipo_comprobante,
                "5" => $reg->serie_comprobante.'-'.$reg->num_comprobante,
                "6" => $reg->total_compra,
                "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' : '<span class="label bg-red">Anulado</span>',
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
    case 'selectProveedor':
        require_once "../modelos/Persona.php";
        $persona = new Persona();
        //la respuesta es un array
        $rspta = $persona->Listarproveedores();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
        }
    break;
    case 'listarArticulos':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $data = Array();//declaro un array
        $rta = $articulo->ListarActivos();
        $i = 0; 
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" =>'<button id="agregarDetalle'.$i.'" name="agregarDetalle" class="btn btn-success" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\',\''.$i.'\')"><span class="fa fa-check"></span></button>',
                "1" =>$reg->nombre,
                "2" =>$reg->descripcion,
                "3" =>$reg->talle,
                "4" =>$reg->categoria,
                "5" =>$reg->codigo,
                "6" =>$reg->stock,
                "7" =>$reg->precio_venta,
                "8" =>"<img src='../files/articulos/".$reg->imagen."' height='50px' width='50px'>"
            );
            $i++;
        }
        $results = array(
            "sEcho"=>1, //informacion para el datatables
            "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
            "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar 
            "aaData"=>$data,
        );
        echo json_encode($results); //el listado y la paginacion de registros lo vamos hacer con datatables, que va usar el array $results
    break;
    case 'precioDeVenta':
        require_once '../modelos/Articulo.php';
        $idarticulo = $_GET['idarticulo'];
        $articulo = NEW Articulo();
        $rta = $articulo->precioDeVenta($idarticulo);
        echo json_encode($rta);
    break; 
}

?> 
















