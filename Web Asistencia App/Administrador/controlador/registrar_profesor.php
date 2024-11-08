<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_instituciones = $_SESSION['institucion'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $dni = $_POST["dni"];
    $email = $_POST["email"];
    $fecha_nacimiento =  $_POST["fecha_nacimiento"];
    $legajo =  $_POST["legajo"];
    $contrasena = $_POST["contrasena"];
    $condicion = "profesor"; 

    // Verificar que los datos este completos
    $data = Profesor::obtener_datos_profesor();
    $alertas = Profesor::validar_datos_profesor($data);

    // Mostrar error si existe
    if (!empty($alertas)) {
        foreach ($alertas as $alerta) {
            echo "<p>$alerta</p>";
        }
        header('Location: .\..\vistas\administrador_profesores.php');
        exit();
    }

    try {
        // Verificar si eciste el profesor en la base de datos
        $consulta = $conexion->prepare('SELECT * FROM profesores WHERE dni = :dni');
        $consulta->bindParam(':dni', $dni, PDO::PARAM_STR);
        $consulta->execute();

        $profesor_db = $consulta->fetch(PDO::FETCH_ASSOC);

        // Si no se encontro el profesor
        if (!$profesor_db) {

            // Si los datos son correctos se crea un profesor y un usuario para ese profesor
            if (count($alertas) == 0) { 
                
                $profesores = new Profesor($nombre, $apellido, $dni,  $email,  $fecha_nacimiento, $legajo);
                $profesores->crear_profesor($conexion); 

                $usuario_profesor = new Usuario($nombre, $apellido, $email, $contrasena, $condicion);
                $usuario_profesor->crear_usuario($conexion);
                
                Profesor::asignar_institucion($conexion, $dni, $id_instituciones);

                header('Location: .\..\vistas\administrador_profesores.php');
                exit();
            }   
            
        } else {

            $consulta_profesor = $conexion->prepare("SELECT * FROM profesor_institucion WHERE profesor_institucion.profesor_id = :id_profesor AND profesor_institucion.institucion_id = :id_institucion");
            $consulta_profesor->bindParam(':id_profesor', $profesor_db['id']);
            $consulta_profesor->bindParam(':id_institucion', $id_institucion);
            $consulta_profesor->execute();

            $profesor = $consulta_profesor->fetch(PDO::FETCH_ASSOC);

            if (!$profesor) {
                Profesor::asignar_institucion($conexion, $dni, $id_instituciones); 
            }
            

            header('Location: .\..\vistas\administrador_profesores.php');
            exit();
        }
    } catch (PDOException $e) {
        echo 'Error de conexión: ' . $e->getMessage();
    }
}
    

   