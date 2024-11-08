<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';

session_start();

$database = new Database();
$conexion = $database->connect();
   
if (isset($_POST['eliminacion'])) {
    $id_eliminar = $_POST['eliminacion'];
    
    try {
        Institucion::eliminar_institucion($conexion, $id_eliminar);

        header('Location: .\..\vistas\index_administrador.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }

} else {
    echo "Error al eliminar el alumno.";
}