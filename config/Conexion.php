<?php
//si este archivo esta incluido no volver a incluirlo otra vez 
require_once "global.php";

//instancia de mysqli que va a ser el controlador para la base de datos
$conexion = new mysqli  (DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

//mysqli_query( $conexion, 'SET NAMES "utf8"');
mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');

//si tenemos un posible error en la conexion lo mostramos
if (mysqli_connect_errno()){
    printf("Falló conexión a la base de datos: %s\n", mysqli_connect_error());
    exit();
}
//si queres llevar a cabo consultar sql vamos a llamar a esta funcion parametrizando la consulta
if (!function_exists('ejecutarConsulta')){
    //Consulta de todo el query. Retorna 0 (error) ó 1 (éxito)
    function ejecutarConsulta($sql)
    {
        global $conexion;    
        $query = $conexion->query($sql);
        return $query;
    }
    //consulta devuelve una sola fila
    function ejecutarConsultaSimpleFila($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        $row = $query->fetch_assoc();
        return $row;
    }

     //consulta recuperar id
     function ejecutarConsulta_retornarID($sql)
    {
        global $conexion;
        $query = $conexion->query($sql);
        return $conexion->insert_id;
    }
    
    //escapar a los caracteres de una cadena para usarlos en la sentencia sql
    function limpiarCadena($str)
    {
        global $conexion;
        $str = mysqli_real_escape_string($conexion, trim($str));
        return htmlspecialchars($str);
    }


    //*********************** */
    function probarConsulta($sql)
    {
        print_r ($sql);
    }
}

?>