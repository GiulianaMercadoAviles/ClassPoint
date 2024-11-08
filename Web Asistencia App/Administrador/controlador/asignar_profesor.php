<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_instituciones = intval($_SESSION['institucion']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['asignar_materia'])) {

        $profesor = intval($_POST['asignar_profesor']);
        $materia = intval($_POST['asignar_materia']);

        try {
            Profesor::asignar_materia($conexion, $profesor, $id_instituciones, $materia);

            $_SESSION['message'] = "Materia asignada con éxito.";
            header('Location: .\..\vistas\administrador_perfil_profesor.php');
            exit();

        } catch (Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage());
        }
    }
}
