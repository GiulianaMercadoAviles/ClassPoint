<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $fecha_nacimiento =  $_POST["fecha_nacimiento"];

    if (isset($_POST["materia"])) {
        $id_materia = $_POST["materia"]; 
    } else {
        $id_materia = $_SESSION['materia'];
    }
    
    //Verificar que los datos se mandaron
    $data = Alumno::obtener_datos_alumno();
    $alertas = Alumno::validar_datos_alumno($data);

    //Verifica que los datos se mandaron
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            echo "<p>$alerta</p>";
        }
        exit();
    }

    try {
        // Verificar si el alumno ya esta registrado en la base de datos
        $consulta_alumno = $conexion->prepare("SELECT * FROM alumnos WHERE dni = :dni");
        $consulta_alumno->bindParam(':dni', $dni, PDO::PARAM_INT);
        $consulta_alumno->execute();

        // Almacenar el resutado
        $alumno_db = $consulta_alumno->fetch(PDO::FETCH_ASSOC);

        // Si el alumno ya existe
        if ($alumno_db != null) { 

            // Verificar si el alumno ya esta registrado en la materia
            $consulta = $conexion->prepare("SELECT * FROM notas INNER JOIN alumnos ON notas.alumno_id = alumnos.id WHERE alumnos.dni = :dni AND notas.materia_id = :id_materia"); 
            $consulta->bindParam(':id_materia', $id_materia);
            $consulta->bindParam(':dni', $dni);
            $consulta->execute();

            $alumno_materia = $consulta->fetch(PDO::FETCH_ASSOC); 
            
            // Si el alumno no esta registrado en la materia y los datos estan bien se registra el alumno en la materia
            if ($alumno_materia == null) { 

                if (count($alertas) == 0) { 

                    $alumnos = new Alumno($nombre, $apellido, $dni, $fecha_nacimiento);
                    ALumno::matricular_alumno($conexion, $id_materia, $dni);  
                    
                    header('Location: .\..\vistas\profesor_alumnos.php');
                    exit();
                }   
            } else {
                
                header('Location: .\..\vistas\profesor_alumnos.php');
                exit();
            }
        } else { // Si el resutado es nulo se registra un nuevo alumno
            
            // Verificar que no haya errores en los datos
            if (count($alertas) == 0) { 

                $alumnos = new Alumno($nombre, $apellido, $dni, $fecha_nacimiento);
                $alumnos->crear_alumno($conexion); 
                ALumno::matricular_alumno($conexion, $id_materia, $dni); 

                header('Location: .\..\vistas\profesor_alumnos.php');
                exit();
            }   
        }
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }    
}


 