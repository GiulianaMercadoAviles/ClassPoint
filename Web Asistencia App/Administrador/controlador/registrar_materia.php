<?php
   
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';

$database = new Database();
$conexion = $database->connect(); 

session_start();
$id_institucion = $_SESSION["institucion"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $nombre = $_POST["nombre"];
        var_dump($_POST["nombre"]);
        $departamento = $_POST["departamento"];
        $curso = $_POST["curso"];
        

        $data = Materia::obtener_datos_materia();
        $alertas = Materia::validar_datos_materia($data);

        if (!empty($alertas)) {
            foreach ($alertas as $alerta) {
                echo "<p>$alerta</p>";
            }
            exit();
        }
        
        try {
            // Verifica si la materia ya existe en la base de datos
            $consulta = $conexion->prepare("SELECT * FROM materias WHERE nombre = :nombre AND instituciones_id = :institucion_id");
            $consulta->bindParam(':nombre', $nombre);
            $consulta->bindParam(':institucion_id', $id_institucion);
            $consulta->execute();
        
            $materia_db = $consulta->fetch(PDO::FETCH_ASSOC); 
            // Si la materia no existe
            if ($materia_db == null) {
        
                // Si los datos son correctos se registra una nueva materia en la base de datos
                if (count($alertas) === 0) { 
        
                    $materia = new Materia($nombre, $id_institucion, $departamento, $curso);
                    $materia->crear_materia($conexion);   

                    
                }   header('Location: .\..\vistas\administrador_materias.php');
                    exit();
            } else {
                
                $_SESSION['error_materia'] = 'Materia ya registrado';
                header('Location: .\..\vistas\administrador_materias.php');
                exit();
            }
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
    }
}