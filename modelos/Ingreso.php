<?php
//Incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';

Class Ingreso
{
    //implementamos nuestro constructor
    public function __construct()
    {
        
    }

    //insertar registros
    public function Insertar($idproveedor, $idusuario , $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, 
    $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta)
    { //idarticulo es un array
        $sql = "INSERT INTO ingreso (idproveedor, idusuario , tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, 
        impuesto, total_compra, estado) VALUES ('$idproveedor', '$idusuario', '$tipo_comprobante', '$serie_comprobante', 
        '$num_comprobante', '$fecha_hora', '$impuesto', '$total_compra', 'Aceptado')";
      
        $idingresonew =  ejecutarConsulta_retornarID($sql);
        $i = 0;
        $bandera = true;

        while ($i < count($idarticulo)) {
            $sql_detalle = "INSERT INTO detalle_ingreso (idingreso, idarticulo, cantidad, precio_compra, precio_venta)
            VALUES ('$idingresonew', '$idarticulo[$i]', '$cantidad[$i]', '$precio_compra[$i]', '$precio_venta[$i]')";
            // ejecutarConsulta($sql_detalle) or $bandera = false;
            ejecutarConsulta($sql_detalle);

            $sql_detalle = "UPDATE articulo SET articulo.precio_venta = '$precio_venta[$i]' WHERE articulo.idarticulo = '$idarticulo[$i]'";
            ejecutarConsulta($sql_detalle);

            $sql_detalle = "UPDATE articulo SET articulo.precio_compra = '$precio_compra[$i]' WHERE articulo.idarticulo = '$idarticulo[$i]'";
            ejecutarConsulta($sql_detalle) or $bandera = false;
            
            $i = $i + 1;
        }
        return $bandera;
    }

    //anular ingreso
    public function anular($idingreso){
        $sql = "UPDATE ingreso SET ingreso.estado = 'Anulado' WHERE ingreso.idingreso = '$idingreso'";
        ejecutarConsulta($sql);

        $sql = "UPDATE articulo INNER JOIN detalle_ingreso ON articulo.idarticulo = detalle_ingreso.idarticulo
				SET articulo.stock = articulo.stock - detalle_ingreso.cantidad
				WHERE detalle_ingreso.idingreso = '$idingreso' AND articulo.stock > 0";
		return ejecutarConsulta($sql);
    }

    //mostrar datos de un registro
    public function Mostrar($idingreso){
        $sql = "SELECT ingreso.idingreso, DATE(ingreso.fecha_hora) as fecha, ingreso.idproveedor, persona.nombre as proveedor,
        usuario.idusuario, usuario.nombre as usuario, ingreso.tipo_comprobante, ingreso.serie_comprobante, ingreso.num_comprobante,
        ingreso.total_compra, ingreso.impuesto, ingreso.estado 
        FROM ingreso INNER JOIN persona ON ingreso.idproveedor = persona.idpersona 
        INNER JOIN usuario ON ingreso.idusuario = usuario.idusuario
        WHERE ingreso.idingreso = '$idingreso'";
        return ejecutarConsultaSimpleFila($sql); 
    }

    //mostrar los detalles del ingreso
    public function listarDetalle($idingreso){
        $sql = "SELECT detalle_ingreso.idingreso, detalle_ingreso.idarticulo, articulo.nombre, detalle_ingreso.cantidad,
        detalle_ingreso.precio_compra, detalle_ingreso.precio_venta FROM detalle_ingreso 
        INNER JOIN articulo ON detalle_ingreso.idarticulo = articulo.idarticulo
        WHERE detalle_ingreso.idingreso = '$idingreso'";
        return ejecutarConsulta($sql);
    }

    //listar todos los registros
    public function Listar(){
        $sql = "SELECT ingreso.idingreso, DATE(ingreso.fecha_hora) as fecha, ingreso.idproveedor, persona.nombre as proveedor,
        usuario.idusuario, usuario.nombre as usuario, ingreso.tipo_comprobante, ingreso.serie_comprobante, ingreso.num_comprobante,
        ingreso.total_compra, ingreso.impuesto, ingreso.estado 
        FROM ingreso INNER JOIN persona ON ingreso.idproveedor = persona.idpersona 
        INNER JOIN usuario ON ingreso.idusuario = usuario.idusuario ORDER BY ingreso.idingreso";
        return ejecutarConsulta($sql);
    }

}

?>