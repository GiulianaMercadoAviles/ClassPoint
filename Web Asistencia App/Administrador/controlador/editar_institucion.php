<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';

$database = new Database();
$conexion = $database->connect();

$_SESSION['alertas'] = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $id = intval($_POST['id_editar']);
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $cue = $_POST["cue"];
    
    //Verificar que los datos se mandaron
    $data = Institucion::obtener_datos_institucion();
    $alertas = Institucion::validar_datos_institucion($data);

    //Verifica que los datos se mandaron
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            $_SESSION['alertas'][] = $alerta;
        }
        header('Location: .\..\vistas\index_administrador.php');
        exit();
    }

    try {
        Institucion::editar_institucion($conexion, $id, $nombre, $direccion, $cue);

        header('Location: .\..\vistas\index_administrador.php');
        exit();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}