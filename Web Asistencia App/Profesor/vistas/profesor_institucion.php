<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_profesor = $_SESSION['usuario']; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              
    if (isset($_POST['institucion'])) {
    
        $id_institucion = $_POST['institucion'];
        $_SESSION['institucion'] = $id_institucion;
    }
} else {
    $id_institucion = $_SESSION['institucion'];
}
                        
// Obtener el nombre de la institucion
$institucion = Institucion::obtener_institucion($conexion, $id_institucion);

// Obtener las materias de la institucion
$materias = Institucion::obtener_materias_profesor($conexion, $id_institucion, $id_profesor);

$profesor = Profesor::datos_profesor($conexion, $id_profesor);
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_instituciones.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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
                    <a href="index_profesor.php"><span> Inicio</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">school</span>
                    <a href="#"><span> Materias</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">square_foot</span>
                    <a href="profesor_tabla_ram.php"><span> RAM</span></a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='index_profesor.php'" id="volver" class="volver">Volver</button>
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>
        </div>
        <div class="contenedor_principal">
            <div class="header">
                <div></div>
                <div class="usuario">
                    <p><?php echo $profesor['nombre'] . " " . $profesor['apellido']?></p>
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
            </div>
            <div  class="contenedor_listas">
                <div class="tarjeta">
                    <div>
                        <?php
                        echo '<h1>' . ucfirst($institucion['nombre']) . '</h1>
                        <p>Seleccione una materia</p>'; 
                        ?>
                    </div>
                    <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
                </div>
                <div class="lista" id="lista_materias">
                    <?php
                    foreach ($materias as $materia) {
                    
                    echo '
                    <div class="materia" data-id="' . $materia['materia_id'] . '">
                        <div class="lista_div">
                            <div class="lista_header">
                                <h2>' . ucfirst($materia['materia_nombre']) . '</h2>
                                <div class="linea"></div>
                            </div> 
                            <div class="informacion">  
                                <p>'. $materia['departamento'] .'</p>
                                <p>Curso: '. $materia['curso'] .'</p>
                            </div>
                        </div>
                    </div>';
                    } ?>
                </div>
                <form id="formMateria" action="profesor_asistencias.php" method="post">
                    <input type="hidden" name="materia" id="inputMateria">
                </form>  
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
 