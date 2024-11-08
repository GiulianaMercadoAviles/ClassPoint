<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start();  
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              
    if (isset($_POST['institucion'])) {
    
        $id_institucion = $_POST['institucion'];
        $_SESSION['institucion'] = $id_institucion;
    }
} else {
    $id_institucion = $_SESSION['institucion'];
}

$consulta_institucion = $conexion->prepare("SELECT DISTINCT instituciones.nombre, instituciones.id FROM instituciones WHERE instituciones.id = :institucion_id");
$consulta_institucion->bindParam(':institucion_id', $id_institucion);
$consulta_institucion->execute();

$consulta_alumnos = Institucion::obtener_cantidad_alumnos($conexion, $id_institucion);
$consulta_materias = Institucion::obtener_cantidad_materias($conexion, $id_institucion);
$consulta_profesor = Institucion::obtener_cantidad_profesores($conexion, $id_institucion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_instituciones.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href=".\..\..\Recursos\img\escuela.png">
    <link rel="shortcut icon" href=".\..\..\Recursos\img\libros.png">
    <title>Document</title>
</head>
<body>
    <div class="contenedor">
        <div class="sidebar">
            <div class="contenedor_imagen">
                <img src=".\..\..\Recursos\img\escuela.png" alt="escuela" >
                <h1>CLASSPOINT</h1> 
            </div>
            <ul>
                <li>
                    <span class="material-symbols-outlined">home </span>
                    <a href="index_administrador.php"><span> Inicio</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">apps</span>
                    <a href="administrador_instituciones.php"><span> Dashboard</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">import_contacts</span>
                    <a href="administrador_materias.php"><span> Materias</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">local_library</span>
                    <a href="administrador_profesores.php"><span> Profesores</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">groups</span>
                    <a href="administrador_alumnos.php"><span> Alumnos</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">square_foot</span>
                    <a href="administrador_tabla_ram.php"><span> RAM</span></a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='index_administrador.php'" id="volver" class="volver">Volver</button>
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>
        </div>
        <div class="contenedor_principal">
            <div class="header">
                <div></div>
                <div class="usuario">
                    <p><?php echo $usuario['nombre'] . " " . $usuario['apellido']?></p>
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
            </div>
            <div class="tarjeta">
                <div>
                <?php
                    $institucion = $consulta_institucion->fetch(PDO::FETCH_ASSOC);
                    echo '<h1>' . $institucion['nombre'] . '</h1>';
                ?>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            <div class="contenedor_cantidades">
                <div class="lista" id="materias">
                    <div class="lista_cantidad">
                        <h3>Cantidad de Materias</h3>
                        <div class="cantidad">
                        <?php
                            $consulta_materias->execute();
                            $materias = $consulta_materias->fetch(PDO::FETCH_ASSOC);
                            if ($materias) {
                            echo '
                            <p>'. $materias['total_materias'] .'</p>';} 
                            ?></div>
                    </div>
                </div>
                <div class="lista" id="profesores">
                    <div class="lista_cantidad">
                        <h3>Cantidad de Profesores</h3>
                        <div class="cantidad">
                        <?php
                        $consulta_profesor->execute();
                        $profesores = $consulta_profesor->fetch(PDO::FETCH_ASSOC);
                        if ($profesores) {
                            echo '
                            <p>'. $profesores['total_profesores'] .'</p>';} 
                        ?>
                        </div>
                    </div>
                </div>
                <div class="lista" id="alumnos">
                    <div class="lista_cantidad">
                        <h3>Cantidad de Alumno</h3>
                        <div class="cantidad">
                        <?php
                            $consulta_alumnos->execute();
                            $alumnos = $consulta_alumnos->fetch(PDO::FETCH_ASSOC);
                            if ($alumnos) {
                            echo '
                            <p>'. $alumnos['total_alumnos'] .'</p>';} 
                        ?></div>
                    </div>
                </div>     
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>