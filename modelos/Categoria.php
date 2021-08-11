<?php
//Incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';

Class Categoria
{
    //implementamos nuestro constructor
    public function __construct()
    {
        
    }

    //insertar registros
    public function Insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO categoria (nombre, descripcion, condicion)
        VALUES ('$nombre', '$descripcion', '1')";
        return ejecutarConsulta($sql);
    }

    //editar registros
    public function Editar($nombre, $descripcion, $idcategoria){
        $sql = "UPDATE categoria SET nombre = '$nombre', descripcion = '$descripcion'
        WHERE idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);
    }

    //desactivar categoria (no se eliminan)
    public function Desactivar($idcategoria){
        $sql = "UPDATE categoria SET condicion = '0' 
        WHERE categoria.idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);
    }

      //activar categoria 
      public function Activar($idcategoria){
        $sql = "UPDATE categoria SET condicion = '1' 
        WHERE categoria.idcategoria = '$idcategoria'";
        return ejecutarConsulta($sql);
    }

    //mostrar datos de un registro
    public function Mostrar($idcategoria){
        $sql = "SELECT * FROM categoria WHERE categoria.idcategoria = '$idcategoria'";
        return ejecutarConsultaSimpleFila($sql); 
    }

    //listar todos los registros
    public function Listar(){
        $sql = "SELECT * FROM categoria";
        return ejecutarConsulta($sql);
    }

     //listar los registros y mostrar en el select
     public function select(){
        $sql = "SELECT * FROM categoria WHERE condicion = 1";
        return ejecutarConsulta($sql);
    }

}

?>