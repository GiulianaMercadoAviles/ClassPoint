<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $id = intval($_POST['id_editar']);
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
        Alumno::editar_alumno($conexion, $id, $nombre, $apellido, $dni, $fecha_nacimiento);

        header('Location: .\..\vistas\profesor_alumnos.php');
        exit();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}