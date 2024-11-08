<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';

$database = new Database();
$conexion = $database->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $id = intval($_POST['id_editar']);
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $email = $_POST["email"];
    $fecha_nacimiento =  $_POST["fecha_nacimiento"];
    $legajo =  $_POST["legajo"];
    
    //Verificar que los datos se mandaron
    $data = Profesor::obtener_datos_profesor();
    $alertas = Profesor::validar_datos_profesor($data);

    //Verifica que los datos se mandaron
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            echo "<p>$alerta</p>";
        }
        exit();
    }

    try {
        Profesor::editar_profesor($conexion, $id, $nombre, $apellido, $dni,  $email,  $fecha_nacimiento, $legajo);

        header('Location: .\..\vistas\administrador_perfil_profesor.php');
        exit();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}