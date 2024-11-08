<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
class Usuario {
    protected $nombre;
    protected $apellido;
    protected $email;
    private $contrasena;
    protected $condicion;

    public function __construct($nombre, $apellido, $email, $contrasena, $condicion) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->email = $email;
        $this->contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        $this->condicion = $condicion;
    }
    
    public function crear_usuario($conexion) {
        $nombre = $this->nombre;
        $apellido = $this->apellido;
        $email = $this->email;
        $contrasena = $this->contrasena;
        $condicion = $this->condicion;

        $consulta_id = $conexion->prepare("SELECT id FROM profesores WHERE email = :email_usuario");
        $consulta_id->bindParam(':email_usuario', $email);

        $consulta_id->execute();
        $profesor_id = $consulta_id->fetchColumn();


        $consulta = "INSERT INTO usuarios (nombre, apellido, email, contrasena, condicion, profesor_id) 
                     VALUES (:nombre, :apellido, :email, :contrasena, :condicion, :profesor_id)";
        
        $stmt = $conexion->prepare($consulta);
        
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':condicion', $condicion);
        $stmt->bindParam(':profesor_id', $profesor_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function datos_usuario($conexion, $id_usuario) {

        $datos_usuario = $conexion->prepare("SELECT * FROM usuarios WHERE usuarios.id = :id_usuario");
        $datos_usuario->bindParam(':id_usuario' ,$id_usuario);
        $datos_usuario->execute();

        return $datos_usuario->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function obtener_usuario($conexion, $email) {
       
        $consulta = $conexion->prepare("SELECT * FROM usuarios WHERE email = ':email'");
        $consulta->bindParam(':email', $email);

        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

}