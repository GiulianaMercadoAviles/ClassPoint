<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

session_start();   

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['id_eliminar'])) {
        $id_eliminar = $_POST['id_eliminar'];
        $instancia_notas = $_POST['instancia'];var_dump($id_eliminar, $instancia_notas);
        
        try {

            $consulta_notas = $conexion->prepare("UPDATE notas SET $instancia_notas = NULL WHERE alumno_id = :alumno_id");                    
            $consulta_notas->bindParam(':alumno_id', $id_eliminar);
                              
            $consulta_notas->execute();

            header('Location: .\..\vistas\profesor_notas.php');
            exit();

        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
    
    } else {
        echo "Error al eliminar el alumno.";
    }
}