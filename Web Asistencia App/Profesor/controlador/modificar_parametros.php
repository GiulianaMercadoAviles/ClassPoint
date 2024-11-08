<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';

$database = new Database();
$conexion = $database->connect();

session_start();
        
$id_institucion = intval($_SESSION['institucion']);
var_dump($id_institucion);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nota_promocion = $_POST['nota_promocion'];
    $asistencia_promocion = $_POST['asistencia_promocion'];
    $nota_regular = $_POST['nota_regular'];
    $asistencia_regular = $_POST['asistencia_regular'];

    $data = Institucion::obtener_datos_ram();
    $alertas = Institucion::validar_datos_ram($data);

    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            echo "<p>$alerta</p>";
        }
        exit();
    }

    try {
        $consulta = Institucion::obtener_Ram($conexion, $id_institucion);
        $consulta->execute();

        $ram_institucion = $consulta->fetchAll(PDO::FETCH_ASSOC);

        if (count($alertas) == 0) { 

            if ($ram_institucion != null) {

                Institucion::modificar_Ram($conexion, $id_institucion, $nota_promocion, $asistencia_promocion, $nota_regular, $asistencia_regular);
            } else {
                Institucion::Ram($conexion, $id_institucion, $nota_promocion, $asistencia_promocion, $nota_regular, $asistencia_regular);
            }
        
            header('Location: .\..\vistas\profesor_tabla_ram.php');
            exit();
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
}