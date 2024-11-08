<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_materia = $_SESSION['materia'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
    try {
        if (isset($_POST['asistencia'])) { var_dump($_POST['asistencia']);

            date_default_timezone_set('America/Argentina/Buenos_Aires');    
            $fecha = date('Y-m-d');

            $consulta = $conexion->prepare('SELECT DISTINCT asistencias.fecha FROM asistencias WHERE asistencias.fecha = :fecha_hoy AND asistencias.materia_id = :id_materia');

            $consulta->bindParam(':fecha_hoy', $fecha);
            $consulta->bindParam(':id_materia', $id_materia);
            $consulta->execute();
                        
            $fechas_asistencias = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $consulta_alumno = $conexion->prepare('SELECT materia_alumno.alumno_id FROM materia_alumno WHERE materia_alumno.materia_id = :id_materia');

            $consulta_alumno->bindParam(':id_materia', $id_materia);
            $consulta_alumno->execute();
                            
            $alumnos = $consulta_alumno->fetchAll(PDO::FETCH_COLUMN);
                    
            if (empty($fechas_asistencias)) {

                if (!empty($_POST['asistencia'])) {  
                    
                    // Almacena los id de los alumnos a los que se les tomo asistencia
                    $asistencia = $_POST['asistencia'];
                            
                    foreach ($alumnos as $alumno) {

                        if (in_array($alumno, $asistencia)) {
                            $estado = 1; 
                        } else {
                            $estado = 0; 
                        }
                                
                        Materia::listar_asistencia($conexion, $alumno, $id_materia, $fecha, $estado);  
                    }
                    header('Location: .\..\vistas\profesor_asistencias.php');
                    exit();

                } else {
                            
                foreach ($alumnos as $alumno) {
                    $estado = 0;
                    Materia::listar_asistencia($conexion, $alumno, $id_materia, $fecha, $estado);  
                }

                header('Location: .\..\vistas\profesor_asistencias.php');
                exit();
                }
            }
        }
        
        else if (isset($_POST['asistencia_anterior'])) {

            $fecha = $_POST['fecha']; 

            date_default_timezone_set('America/Argentina/Buenos_Aires');    
            $fecha_hoy = date('Y-m-d');

            if ($fecha < $fecha_hoy) {
            
            $consulta = $conexion->prepare('SELECT DISTINCT asistencias.fecha FROM asistencias WHERE asistencias.fecha = :fecha_hoy AND asistencias.materia_id = :id_materia');

            $consulta->bindParam(':fecha_hoy', $fecha);
            $consulta->bindParam(':id_materia', $id_materia);
            $consulta->execute();
                        
            $fechas_asistencias = $consulta->fetchAll(PDO::FETCH_ASSOC);

            $consulta_alumno = $conexion->prepare('SELECT materia_alumno.alumno_id FROM materia_alumno WHERE materia_alumno.materia_id = :id_materia');

            $consulta_alumno->bindParam(':id_materia', $id_materia);
            $consulta_alumno->execute();
                            
            $alumnos = $consulta_alumno->fetchAll(PDO::FETCH_COLUMN);
                    
            if (empty($fechas_asistencias)) {

                if (!empty($_POST['asistencia_anterior'])) {  
                    
                    // Almacena los id de los alumnos a los que se les tomo asistencia
                    $asistencias = $_POST['asistencia_anterior'];var_dump($asistencias);
                            
                    foreach ($alumnos as $alumno) {

                        if (in_array($alumno, $asistencias)) {
                            $estado = 1; 
                        } else {
                            $estado = 0; 
                        }
                                
                        Materia::listar_asistencia($conexion, $alumno, $id_materia, $fecha, $estado);  
                    }
                    header('Location: .\..\vistas\profesor_asistencias.php');
                    exit();

                } else {
                            
                    foreach ($alumnos as $alumno) {
                        $estado = 0;
                        Materia::listar_asistencia($conexion, $alumno, $id_materia, $fecha, $estado);  
                    }

                    header('Location: .\..\vistas\profesor_listado_asistencias.php');
                    exit();
                }
            } 
        } else {
            $_SESSION['fecha_error'] = "No es posible registrar una asistencia con fecha posterior a la fecha actual";
            header('Location: .\..\vistas\profesor_registrar_asistencias.php');
            exit();
        }
        } else {
            header('Location: .\..\vistas\profesor_listado_asistencias.php');
            exit();
        }
       
    } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
    }
} else {
    
echo 'Error: No se recibieron los valores por POST.';
}