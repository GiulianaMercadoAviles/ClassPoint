<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

session_start();

$database = new Database();
$conexion = $database->connect();
   
if (isset($_POST['eliminacion'])) {
    $id_eliminar = $_POST['eliminacion'];
    $id_materia = $_POST['desinscribir_materia'];
    
    try {
        Alumno::desinscribir_alumnos($conexion, $id_eliminar, $id_materia);

        header('Location: .\..\vistas\profesor_alumnos.php');
        exit();
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }

} else {
    echo "Error al eliminar el alumno.";
}