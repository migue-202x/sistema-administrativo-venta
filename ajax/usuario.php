<?php
//?Luego con un archivo JavaScript para que mediante una peticion AJAX va a llamar a este archivo para que devuelva valores de operaciones

session_start();
//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "../modelos/Usuario.php";

$usuario = new Usuario();

//si existe existe el objeto lo voy a validar con el metodo limpiar cadena
$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]):"";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]):"";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]):"";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]):"";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]):"";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]):"";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]):"";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]):"";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]):"";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]):"";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]):"";


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
                move_uploaded_file($_FILES['imagen']['tmp_name'], '../files/usuarios/'.$imagen); //subo el archivo imagen
            }
        }
        //SHA256 a la contraseña
        $clavehash = hash("SHA256", $clave);

        //si yo no estoy enviando ningun $idusuario significa que yo no estoy editando sino guardando
        if(empty($idusuario)){            
            $rta = $usuario->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $_POST['permiso']); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Usuario registrado' : 'No se pudieron registrar todos los datos del usuario';
        }
        else{
            $rta = $usuario->Editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $_POST['permiso']); //$rta alamacenara un 1 ó un 0
            echo $rta ? 'Usuario actualizado' : 'No se pudo actualizar';
        }
    break;
    case 'desactivar':
        $rta = $usuario->desactivar($idusuario);
        echo $rta ? 'Usuario desactivado' : 'No se pudo desactivar';
    break;
    case 'activar':
        $rta = $usuario->activar($idusuario);
        echo $rta ? 'Usuario activado' : 'No se pudo activar';
    break;
    case 'mostrar':
        $rta = $usuario->mostrar($idusuario);        
        echo json_encode($rta);//Codificar el resultado utilizando json
    break;
    case 'listar':
        $data = Array();//declaro un array
        $rta = $usuario->listar();
        
        //fetch_object devuelve la fila actual de un conjunto como un objeto
        while ($reg = $rta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-danger" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>'
                :
                '<button class="btn btn-warning" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.
                ' <button class="btn btn-primary" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
                "1" => $reg->nombre,
                "2" => $reg->tipo_documento,
                "3" => $reg->num_documento,
                "4" => $reg->telefono,
                "5" => $reg->email,
                "6" => $reg->login,
                "7" => "<img src='../files/usuarios/".$reg->imagen."' height='50px' width='50px'>",
                "8" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' : '<span class="label bg-red">Desativado</span>',
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
    case 'permisos':
        //obtenemos todos los permisos de la tabla permisos
        require_once "../modelos/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();

        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $usuario->listarmarcados($id);
        $valores = array();

        while ($permiso = $marcados->fetch_object()) {
            array_push($valores, $permiso->idpermiso);
        }
        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($reg = $rspta->fetch_object()) {

            //condicional una sola linea: "Si el idpermiso esta dentro del array $valores se le asignará checked sino ''"
            $check = in_array($reg->idpermiso, $valores)? 'checked' : '';
            
            echo '<li><input type="checkbox" '.$check.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</input></li>';
        }
    break;
    case 'verificar':
        $login_acceso = $_POST['login_acceso'];
        $clave_acceso = $_POST['clave_acceso'];

        //encriptamos la clave ingresada para conpararla con la clave hash almacenada en la base de datos
        $clavehash= hash("SHA256", $clave_acceso);

        $rspta = $usuario->verificar($login_acceso, $clavehash);

        $fetch = $rspta->fetch_object();

        if (isset($fetch)){
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;

            //Obtenemos los permisos del usuario
            $permisos = $usuario->listarmarcados($fetch->idusuario);

            //Declaramos array para almacenar todos los permisos marcados
            $valores = array();

            while ($item = $permisos->fetch_object()) {
                array_push($valores, $item->idpermiso);
            }

            //Determinamos los accesos del usuario
            in_array(1, $valores)? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0; 
            in_array(2, $valores)? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0; 
            in_array(3, $valores)? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0; 
            in_array(4, $valores)? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0; 
            in_array(5, $valores)? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0; 
            in_array(6, $valores)? $_SESSION['consulta_compras'] = 1 : $_SESSION['consulta_compras'] = 0; 
            in_array(7, $valores)? $_SESSION['consulta_ventas'] = 1 : $_SESSION['consulta_ventas'] = 0; 
        }

        echo json_encode($fetch);
    break;
    case 'salir':
        //Limpiamos las variables de sesión
        session_unset();

        //Destruimos la sesión
        session_destroy();

        //Redireccionamos al login
        header("Location: ../index.php");
    break;
}


?> 
















