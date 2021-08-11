<?php
//Incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';

Class Usuario
{
    //implementamos nuestro constructor
    public function __construct()
    {
        
    }

    //insertar registros
    public function Insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
    {
        $sql = "INSERT INTO usuario (nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion)
        VALUES ('$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email', '$cargo', '$login', '$clave', '$imagen', '1')";
      
        $idusuarionew =  ejecutarConsulta_retornarID($sql);
        $i = 0;
        $bandera = true;

        while ($i < count($permisos)) {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES ('$idusuarionew', '$permisos[$i]')";
            ejecutarConsulta($sql_detalle) or $bandera = false;
            $i = $i + 1;
        }
        return $bandera;
    }

    //editar registros
    public function Editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos){
        $sql = "UPDATE usuario SET nombre = '$nombre', tipo_documento = '$tipo_documento',
        num_documento = '$num_documento', 
        direccion = '$direccion', 
        telefono = '$telefono', 
        email = '$email', 
        cargo = '$cargo', 
        login = '$login', 
        clave = '$clave', 
        imagen = '$imagen' WHERE idusuario = '$idusuario'";
        ejecutarConsulta($sql);

        // La mejor manera es eliminar primero todos los permisos asignados para volverlos a registrar
        $sqldelete = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
        ejecutarConsulta($sqldelete);

        // Ahora volvemos a insertar todos los permisos asignados
        $i = 0;
        $bandera = true;

        while ($i < count($permisos)) {
            $sql_detalle = "INSERT INTO usuario_permiso(idusuario, idpermiso) VALUES ('$idusuario', '$permisos[$i]')";
            ejecutarConsulta($sql_detalle) or $bandera = false;
            $i = $i + 1;
        }
        return $bandera;
    }

    //desactivar categoria (no se eliminan)
    public function Desactivar($idusuario){
        $sql = "UPDATE usuario SET condicion = '0' 
        WHERE usuario.idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

      //activar categoria 
      public function Activar($idusuario){
        $sql = "UPDATE usuario SET condicion = '1' 
        WHERE usuario.idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    //mostrar datos de un registro
    public function Mostrar($idusuario){
        $sql = "SELECT * FROM usuario WHERE usuario.idusuario = '$idusuario'";
        return ejecutarConsultaSimpleFila($sql); 
    }

    //listar todos los registros
    public function Listar(){
        $sql = "SELECT * FROM usuario";
        return ejecutarConsulta($sql);
    }

    //metodo para listar los permisos marcados
    public function listarmarcados($idusuario){
        $sql = "SELECT * FROM usuario_permiso WHERE idusuario = '$idusuario'";
        return ejecutarConsulta($sql);
    }

    //Funcion para verificar el acceso al sistema 
    public function verificar($login, $clave){
        $sql = "SELECT idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo, imagen, login 
        FROM USUARIO WHERE login='$login' AND clave='$clave' AND condicion='1'";
        return ejecutarConsulta($sql);
    }


}

?>