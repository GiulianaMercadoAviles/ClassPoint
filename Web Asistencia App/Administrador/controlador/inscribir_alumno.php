<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_instituciones = intval($_SESSION['institucion']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['materia'])) {

        $alumno = intval($_POST['inscribir_alumnos']); var_dump($alumno);
        $materia = intval($_POST['materia']);

        try {
            ALumno::matricular_alumno($conexion, $materia, $alumno); 

            $_SESSION['message'] = "Alumno inscripto con éxito.";
            header('Location: .\..\vistas\administrador_perfil_alumno.php');
            exit();

        } catch (Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage());
        }
    }
}