<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

session_start();

$database = new Database();
$conexion = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    if (isset($_POST['desinscribir_alumno'])) {
        $id_desincribir = $_POST['desinscribir_alumno'];
        $id_materia = $_POST['desinscribir_materia'];
        
        try {
            Alumno::desinscribir_alumnos($conexion, $id_desincribir, $id_materia);
    
            // header('Location: .\..\vistas\administrador_perfil_alumno.php');
            // exit();
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
    
    } else {
        echo "Error al eliminar el alumno.";
    }

    if (isset($_POST['eliminacion'])) {
        $id_eliminar = $_POST['eliminacion'];
        
        try {
            Alumno::eliminar_alumnos($conexion, $id_eliminar);

            header('Location: .\..\vistas\administrador_alumnos.php');
            exit();
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }

    }
}