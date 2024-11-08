<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $id = intval($_POST['id_editar']); var_dump($id);
    $instancia = $_POST["instancia"]; var_dump($instancia);
    $nota = floatval($_POST["nota"]);var_dump($nota);
    
    //Verificar que los datos se mandaron
    $data = Alumno::obtener_datos_notas();
    $alertas = Alumno::validar_datos_notas($data);

    //Verifica que los datos se mandaron
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            echo "<p>$alerta</p>";
        }
        exit();
    }

    try {
        Alumno::editar_nota($conexion, $instancia, $id, $nota);

        header('Location: .\..\vistas\profesor_notas.php');
        exit();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}