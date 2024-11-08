<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_instituciones = intval($_SESSION['institucion']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    var_dump($_POST['desasignar_materia']);
    if (isset($_POST['desasignar_materia'])) {

        $profesor = intval($_POST['desasignar_profesor']);
        $materia = intval($_POST['desasignar_materia']);

        try {

            $consulta = $conexion->prepare("SELECT * FROM profesor_institucion WHERE profesor_id = :profesor_id AND institucion_id = :institucion_id AND materia_id = :materia_id");
            $consulta->bindParam(':profesor_id', $profesor);
            $consulta->bindParam(':institucion_id', $id_instituciones);
            $consulta->bindParam(':materia_id', $materia);
            $consulta->execute();

            if ($consulta->fetch(PDO::FETCH_ASSOC) != null) {

                Profesor::desasignar_materia($conexion, $profesor, $id_instituciones, $materia);

                $_SESSION['message'] = "Materia desasignada con éxito.";
                header('Location: .\..\vistas\administrador_perfil_profesor.php');
                exit();

            } else {
                $_SESSION['message'] = "No se encontró la asignación para desasignar.";
            }
        } catch (Exception $e) {
            echo 'Error: ' . htmlspecialchars($e->getMessage());
        }
    }
}