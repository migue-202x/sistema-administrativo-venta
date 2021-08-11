<?php
//Incluimos inicialmente la conexión a la base de datos
require '../config/Conexion.php';

Class Persona
{
    //implementamos nuestro constructor
    public function __construct()
    {
        
    }

    //insertar persona
    public function Insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "INSERT INTO persona (tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email)
        VALUES ('$tipo_persona', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email')";
        return ejecutarConsulta($sql);
    }

    //editar persona
    public function Editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email){
        $sql = "UPDATE persona SET tipo_persona = '$tipo_persona',
                nombre = '$nombre',
                tipo_documento = '$tipo_documento',
                num_documento = '$num_documento',
                direccion = '$direccion',
                telefono = '$telefono',
                email = '$email'
        WHERE idpersona = '$idpersona'";
        return ejecutarConsulta($sql);
    }

    //eliminar persona 
    public function eliminar($idpersona){
        $sql = "DELETE FROM persona 
        WHERE persona.idpersona = '$idpersona'";
        return ejecutarConsulta($sql);
    }

    //mostrar datos de un registro
    public function Mostrar($idpersona){
        $sql = "SELECT * FROM persona WHERE persona.idpersona = '$idpersona'";
        return ejecutarConsultaSimpleFila($sql); 
    }

    //listar todos los proveedores
    public function Listarproveedores(){
        $sql = "SELECT * FROM persona WHERE persona.tipo_persona ='Proveedor'";
        return ejecutarConsulta($sql);
    }

      //listar todos los clientes
      public function Listarclientes(){
        $sql = "SELECT * FROM persona WHERE persona.tipo_persona ='Cliente'";
        return ejecutarConsulta($sql);
    }
}

?>