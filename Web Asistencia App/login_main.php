<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if(isset($_POST["email"]) && isset($_POST["contrasena"])) {

        $email = $_POST['email'];
        $contrasena = $_POST['contrasena'];

        try {
            // Verifica si existe el usuario en la base de datos
            $stmt = $conexion->prepare('SELECT * FROM usuarios WHERE email = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si el usuario existe
            if ($usuario_db != null) {

                // Verifica que sea correcta la contraseña
                if (password_verify($contrasena, $usuario_db['contrasena'])) {
  
                
                // Si la condidicion del usuario es administrador lo manda al index de administrador
                    if ($usuario_db['condicion'] == 'administrador') {
                        
                        $_SESSION['usuario'] = $usuario_db['id'];
                        
                        header('Location: Administrador\vistas\index_administrador.php');
                        exit();   
                    }

                    // Si la condidicion del usuario es profesor lo manda al index del profesor
                    if ($usuario_db['condicion'] == 'profesor') {

                        $consulta = $conexion->prepare('SELECT profesores.id FROM usuarios INNER JOIN profesores ON usuarios.profesor_id = profesores.id WHERE usuarios.email = :email');
                        $consulta->bindParam(':email', $email, PDO::PARAM_STR);
                        $consulta->execute();
            
                        $profesor_id = $consulta->fetchColumn();
                        var_dump($profesor_id);
                        $_SESSION['usuario'] = $profesor_id; 
                        
                        header('Location: Profesor\vistas\index_profesor.php');
                        exit();
                    }
                } else {

                    $_SESSION['error_contrasena'] = 'Contraseña Incorrecta';
                    $_SESSION['error_usuario'] = '';
                    header('Location: index.php');
                    exit();
                }
            } else {

                $_SESSION['error_usuario'] = 'Error: No se ha encontrado en usuario';
                $_SESSION['error_contrasena'] = '';
                header('Location: index.php');
                exit();
            }
        } catch (PDOException $e) {
            echo 'Error de conexión: ' . $e->getMessage();
        }
    }
} else {
    echo 'Error: No se recibieron los valores por POST.';
}

