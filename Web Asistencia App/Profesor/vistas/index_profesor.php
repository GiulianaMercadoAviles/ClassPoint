<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$profesor_id = $_SESSION['usuario'];

// Obtener las instituciones registradas en la base de datos en las que el profesor esta registrado
$instituciones = Profesor::obtener_institucion($conexion, $profesor_id);
$profesor = Profesor::datos_profesor($conexion, $profesor_id);
?>                              

<!DOCTYPE html>  
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_index.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href=".\..\..\Recursos\img\escuela.png">
    <link rel="shortcut icon" href=".\..\..\Recursos\img\libros.png">
    <title>Instituciones</title>
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
                    <a href="index_profesor.php">
                        <span> Inicio</span>
                    </a>
                </li>
                <li>
                    <span class="material-symbols-outlined">school</span>
                    <a href="#">
                        <span>Instituciones</span>
                    </a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>  
        </div>    
        <div class="contenedor_principal">
            <div class="header">
                <div></div>
                <div class="usuario">
                    <p>Profesor</p>
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
            </div>
            <div  class="contenedor_listas">
                <div class="tarjeta">
                    <div>
                        <h1> ¡Hola <?php echo $profesor['nombre'] . " " . $profesor['apellido']?>! </h1>
                        <p>Seleccione una Institución para comenzar</p>
                    </div>
                    <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
                </div>
            <div class="lista" id="lista_instituciones">
                <?php foreach ($instituciones as $institucion) {
                    echo '
                    <div class="institucion" data-id="' . $institucion['id'] . '">
                        <div class="lista_div">
                            <div class="lista_header">
                                <h2>' . ucfirst($institucion['nombre']) . '</h2> 
                                <div class="linea"></div>
                            </div>
                            <div class="informacion">  
                                <p>Dirección: '. $institucion['direccion'] .'</p>
                            </div>
                        </div>
                    </div>';
                    } ?></div>
                    <form id="formInstitucion" action="profesor_institucion.php" method="post" style="display: none;">
                        <input type="hidden" name="institucion" id="inputInstitucion">
                    </form>
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_instituciones.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
