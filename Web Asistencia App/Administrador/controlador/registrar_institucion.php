<?php
    
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';


$database = new Database();
$conexion = $database->connect();

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $cue = $_POST["cue"];

    //Verifica que los datos se mandaron
    $data = Institucion::obtener_datos_institucion(); 
    $alertas = Institucion::validar_datos_institucion($data); 

    //Verifica que los datos se mandaron
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            $_SESSION['mensaje_error'][] = $alerta;
        }
        header('Location: .\..\vistas\index_administrador.php');
        exit();
    }

    try {
        // Verificar si existe la institucion
        $consulta = $conexion->prepare('SELECT * FROM instituciones WHERE cue = :cue');
        $consulta->bindParam(':cue', $cue, PDO::PARAM_STR);
        $consulta->execute();

        $instituciones_db = $consulta->fetch(PDO::FETCH_ASSOC);

        // Si no se encentra la institucion ...
        if ($instituciones_db == null) {

            // Si los datos esta correctos se crea una nueva institucion
            if (count($alertas) == 0) { 
                
                $instituciones = new Institucion($nombre, $direccion, $cue);
                $instituciones->crear_institucion($conexion);
                
                header('Location: .\..\vistas\index_administrador.php');
                exit();  
            }            
        } else {
            
            $_SESSION['error_institucion'] = 'La Institucion ya esta registrada';
            header('Location: .\..\vistas\index_administrador.php');
            exit();
        }
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }
}
    