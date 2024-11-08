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

            $_SESSION['inscribir_alumnos'] = "EL alumno Ya esta inscripto";
              
        } else {

            if (count($alertas) == 0) { 

                $alumnos = new Alumno($nombre, $apellido, $dni, $fecha_nacimiento);
                $alumnos->crear_alumno($conexion); 

                header('Location: .\..\vistas\administrador_alumnos.php');
                exit();
            }   
        }
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }    
}


 