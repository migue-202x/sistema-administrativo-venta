<?php
//Incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';

Class Articulo
{
    //implementamos nuestro constructor
    public function __construct()
    {
        
    }

    //insertar articulo
    public function Insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $talle, $precio_venta, $precio_compra)
    {
        $sql = "INSERT INTO articulo (idcategoria, codigo, nombre, stock, descripcion, imagen, condicion, talle, precio_venta, precio_compra)
        VALUES ('$idcategoria', '$codigo','$nombre', '$stock', '$descripcion', '$imagen', '1', '$talle', '$precio_venta', '$precio_compra')";
        return ejecutarConsulta($sql);  
    }

    //editar articulo
    public function Editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen, $talle, $precio_venta, $precio_compra){
        $sql = "UPDATE articulo SET idcategoria = '$idcategoria', codigo = '$codigo', nombre = '$nombre', stock = '$stock', 
        descripcion = '$descripcion', imagen = '$imagen', talle = '$talle', precio_venta = '$precio_venta', precio_compra = '$precio_compra'
        WHERE idarticulo = '$idarticulo'";
        return ejecutarConsulta($sql);

        // $sql = "UPDATE detalle_ingreso "
    }

    //desactivar articulo (no se eliminan)
    public function Desactivar($idarticulo){
        $sql = "UPDATE articulo SET condicion = '0' 
        WHERE articulo.idarticulo = '$idarticulo'";
        return ejecutarConsulta($sql);
    }

      //activar articulo 
      public function Activar($idarticulo){
        $sql = "UPDATE articulo SET condicion = '1' 
        WHERE articulo.idarticulo = '$idarticulo'";
        return ejecutarConsulta($sql);
    }

    //mostrar datos de un registro
    public function Mostrar($idarticulo){
        $sql = "SELECT * FROM articulo WHERE articulo.idarticulo = '$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function Listar(){
        $sql = "SELECT articulo.idarticulo, articulo.idcategoria, categoria.nombre as categoria, 
        articulo.codigo, articulo.nombre, articulo.stock, articulo.descripcion, articulo.imagen, articulo.condicion, 
        articulo.talle, articulo.precio_venta, articulo.precio_compra 
        FROM articulo INNER JOIN categoria ON articulo.idcategoria = categoria.idcategoria";
        return ejecutarConsulta($sql);
    }

    //listar todos los registros activos
    public function ListarActivos(){
        $sql = "SELECT articulo.idarticulo, articulo.idcategoria, categoria.nombre as categoria, 
        articulo.codigo, articulo.nombre, articulo.stock, articulo.precio_venta, articulo.descripcion, 
        articulo.imagen, articulo.condicion, articulo.talle FROM articulo INNER JOIN categoria 
        ON articulo.idcategoria = categoria.idcategoria WHERE articulo.condicion = '1'";
        return ejecutarConsulta($sql);
    }

    //listar todos los registros activos
    public function ListarActivosVenta(){
        $sql = "SELECT articulo.stock AS stock, articulo.idarticulo, articulo.idcategoria, categoria.nombre as categoria, 
        articulo.codigo, articulo.nombre, articulo.stock, articulo.precio_venta, articulo.descripcion, 
        articulo.imagen, articulo.condicion, articulo.talle FROM articulo INNER JOIN categoria 
        ON articulo.idcategoria = categoria.idcategoria WHERE articulo.condicion = '1' AND articulo.stock>0";
        return ejecutarConsulta($sql);

        // $sql = "SELECT articulo.idarticulo, articulo.idcategoria, categoria.nombre as categoria, 
        // articulo.codigo, articulo.nombre, articulo.stock, 
        // (SELECT precio_venta FROM detalle_ingreso WHERE idarticulo = articulo.idarticulo ORDER BY iddetalle_ingreso DESC limit 0,1)
        // AS precio_venta, articulo.descripcion, articulo.imagen, articulo.condicion, articulo.talle FROM articulo
        // INNER JOIN categoria ON articulo.idcategoria = categoria.idcategoria WHERE articulo.condicion = '1' AND articulo.stock>0";
        // return ejecutarConsulta($sql);
    }

    //En caso de que ya exista el detalle_ingreso de ese articulo va a funcionar
    public function precioDeVenta($idarticulo){
        $sql = "SELECT detalle_ingreso.precio_compra FROM articulo 
        INNER JOIN detalle_ingreso ON detalle_ingreso.idarticulo = articulo.idarticulo 
        WHERE detalle_ingreso.iddetalle_ingreso = (SELECT MAX(iddetalle_ingreso) FROM detalle_ingreso 
        WHERE detalle_ingreso.idarticulo = '$idarticulo') 
        GROUP BY detalle_ingreso.idarticulo ORDER BY detalle_ingreso.precio_compra DESC";
        return ejecutarConsultaSimpleFila($sql);
    }


}

?>