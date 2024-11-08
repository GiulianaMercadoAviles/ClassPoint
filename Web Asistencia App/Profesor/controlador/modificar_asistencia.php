<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database;
$conexion = $database->connect(); 

session_start(); 
$id_materia = $_SESSION['materia'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['modificar_asistencia'])) {

        $asistencia_modificar = $_POST['modificar_asistencia'];
        var_dump($asistencia_modificar);

        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $dia_actual = date('Y-m-d');    

        try {

            $consulta_asistencia = $conexion->prepare("SELECT asistencias.alumno_id, asistencias.asistencia FROM asistencias WHERE asistencias.fecha = :fecha AND asistencias.materia_id = :id_materia");

            $consulta_asistencia->bindParam(":fecha", $dia_actual);
            $consulta_asistencia->bindParam(":id_materia", $id_materia);

            $consulta_asistencia->execute();
            $asistencias = $consulta_asistencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($asistencias as $asistencia) {

                if ($asistencia['alumno_id'] == $asistencia_modificar) {
                            
                    if ($asistencia['asistencia'] == 1) {
                        $estado = 0.5;
                    } else {
                        $estado = 0;
                    } 

                    Materia::modificar_asistencia($conexion, $asistencia_modificar, $id_materia, $dia_actual, $estado);   
                }
            }
        
            header('Location: .\..\vistas\profesor_asistencias.php');
            exit();
        
        } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
        }
    }

    if (isset($_POST['modificar_asistencia_anterior'])) {

        $asistencia_modificar = $_POST['modificar_asistencia_anterior'];
        $fecha = $_SESSION['fecha'];

        try {

            $consulta_asistencia = $conexion->prepare("SELECT asistencias.alumno_id, asistencias.asistencia FROM asistencias WHERE asistencias.fecha = :fecha AND asistencias.materia_id = :id_materia");

            $consulta_asistencia->bindParam(":fecha", $fecha);
            $consulta_asistencia->bindParam(":id_materia", $id_materia);

            $consulta_asistencia->execute();
            $asistencias = $consulta_asistencia->fetchAll(PDO::FETCH_ASSOC);

            foreach ($asistencias as $asistencia) {

                if ($asistencia['alumno_id'] == $asistencia_modificar) {
                            
                    if ($asistencia['asistencia'] == 1) {
                        $estado = 0.5;
                    } else {
                        $estado = 0;
                    } 

                    Materia::modificar_asistencia($conexion, $asistencia_modificar, $id_materia, $fecha, $estado);   
                }
            }
            
            header('Location: .\..\vistas\profesor_listado_asistencias.php');
            exit();
        
        } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
        }
    }

    if (isset($_POST['asistencia'])) {

        try {

            $asistencia = $_POST['asistencia'];

            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $dia_actual = date('Y-m-d');
                
            $estado = 0.5; // Ausente
            Materia::listar_asistencia($conexion, $asistencia, $id_materia, $dia_actual, $estado);   

            header('Location: .\..\vistas\profesor_asistencias.php');
            exit();
        
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }

    if (isset($_POST['asistencia_anterior'])) {

        try {

            $asistencia = $_POST['asistencia_anterior'];

            $fecha = $_SESSION['fecha'];
                
            $estado = 0.5; // Ausente
            Materia::listar_asistencia($conexion, $asistencia, $id_materia, $dia_actual, $estado);   
                
            header('Location: .\..\vistas\profesor_listado_asistencias.php');
            exit();
            
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    }
    
}
    
