<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database;
$conexion = $database->connect();

session_start(); 
$id_materia = $_SESSION['materia'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST['eliminar_asistencia'])) {

        try {

            $asistencia_eliminar = $_POST['eliminar_asistencia'];
            var_dump($_POST['eliminar_asistencia']);

            date_default_timezone_set('America/Argentina/Buenos_Aires');    
            $fecha = date('Y-m-d'); 
            var_dump($fecha);
            
            $consulta = $conexion->prepare("UPDATE asistencias SET asistencias.asistencia = 0 WHERE asistencias.alumno_id = :alumnos_id and asistencias.fecha = :fecha");
            $consulta->bindParam(":alumnos_id" , $asistencia_eliminar);
            $consulta->bindParam(":fecha" , $fecha);

            $consulta->execute();

            header('Location: .\..\vistas\profesor_asistencias.php');
            exit();

        } catch (PDOException $e) {
            
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    if (isset($_POST['eliminar_asistencia_anterior'])) {

        try {

            $asistencia_eliminar = $_POST['eliminar_asistencia_anterior'];
            $fecha = $_SESSION['fecha']; var_dump($fecha);
            
            $consulta = $conexion->prepare("UPDATE asistencias SET asistencias.asistencia = 0 WHERE asistencias.alumno_id = :alumnos_id and asistencias.fecha = :fecha");
            $consulta->bindParam(":alumnos_id" , $asistencia_eliminar);
            $consulta->bindParam(":fecha" , $fecha);

            $consulta->execute();

            header('Location: .\..\vistas\profesor_listado_asistencias.php');
            exit();

        } catch (PDOException $e) {
            
            echo "Error de conexión: " . $e->getMessage();
        }
    }
}  else {
    echo 'Error: No se recibieron los valores por POST.';
}