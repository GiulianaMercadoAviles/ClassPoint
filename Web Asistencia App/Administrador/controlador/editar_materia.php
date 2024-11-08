<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_instituciones = $_SESSION['institucion'];
$_SESSION['alertas'] = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $id = $_POST["id_editar"];
    $nombre = $_POST["nombre"];
    $departamento = $_POST["departamento"];
    $curso = $_POST["curso"];
    var_dump($id, $nombre, $departamento,$curso);
    //Verificar que los datos se mandaron
    $data = Materia::obtener_datos_materia();
    $alertas = Materia::validar_datos_materia($data);

    //Verifica que los datos se mandaron
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            $_SESSION['mensaje_error'][] = $alerta;
        }
        header('Location: .\..\vistas\administrador_materias.php');
        exit();
    }

    try {
        Materia::editar_materia($conexion, $id, $nombre, $departamento, $curso);

        header('Location: .\..\vistas\administrador_materias.php');
        exit();
    } catch (Exception $e) {
        $_SESSION['mensaje_error'] = 'Error: ' . $e->getMessage();
        header('Location: .\..\vistas\administrador_materias.php');
        exit();
    }
}