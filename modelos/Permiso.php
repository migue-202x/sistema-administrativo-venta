<?php
//Incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';

Class Permiso
{
    //implementamos nuestro constructor
    public function __construct()
    {
        
    }

    //listar todos los registros
    public function Listar(){
        $sql = "SELECT * FROM permiso";
        return ejecutarConsulta($sql);
    }


}

?>