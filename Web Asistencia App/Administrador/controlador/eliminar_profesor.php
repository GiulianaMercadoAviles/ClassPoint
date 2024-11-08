<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

session_start();

$database = new Database();
$conexion = $database->connect();
   
if (isset($_POST['eliminacion'])) {
    $id_eliminar = $_POST['eliminacion'];
    
    try {
        Profesor::eliminar_profesor($conexion, $id_eliminar);

        header('Location: .\..\vistas\administrador_profesores.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }

}