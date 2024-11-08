<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_profesor = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materia'])) {
    $id_materia = $_POST['materia'];
    $_SESSION['materia'] = $id_materia;
} else {
    $id_materia = $_SESSION['materia'];
}

if (!empty($id_materia)) {
        
    // Obtener el nombre de la materia
    $consulta_materias = Materia::Buscar_materia($conexion, $id_materia);
    $consulta_materias->execute();
    // Obtener los alumnos
    $consulta_alumnos = Materia::Obtener_alumnos($conexion, $id_materia);
    $consulta_alumnos->execute();
}

date_default_timezone_set('America/Argentina/Buenos_Aires'); 
$dia_actual = date('Y-m-d');

$consulta_asistencia = $conexion->prepare("SELECT * FROM asistencias WHERE asistencias.fecha = :fecha AND asistencias.materia_id = :id_materia");

$consulta_asistencia->bindParam(":fecha", $dia_actual);
$consulta_asistencia->bindParam(":id_materia", $id_materia);

$consulta_asistencia->execute();
$asistencia_hoy = $consulta_asistencia->fetch(PDO::FETCH_ASSOC);

$profesor = Profesor::datos_profesor($conexion, $id_profesor);
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_asistencias.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href=".\..\..\Recursos\img\escuela.png">
    <link rel="shortcut icon" href=".\..\..\Recursos\img\libros.png">
    <title>Registro de Asistencia</title>
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
                    <span class="material-symbols-outlined">import_contacts</span>
                    <a href="profesor_espacio_curricular.php">
                        <span>Espacio Curricular</span>
                    </a>
                </li>
                <li>
                    <span class="material-symbols-outlined">groups</span>
                    <a href="profesor_alumnos.php">
                        <span>Alumnos</span>
                    </a>
                </li>
                <li>
                    <span class="material-symbols-outlined">text_increase</span>
                    <a href="profesor_notas.php">
                        <span>Notas</span>
                    </a>
                </li>
                <li>
                <span class="material-symbols-outlined">patient_list</span>
                    <a href="#">
                        <span>Asistencia</span>
                    </a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='index_profesor.php'" id="volver" class="volver">Volver</button>
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>
        </div>
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
                    <span class="material-symbols-outlined">import_contacts</span>
                    <a href="profesor_espacio_curricular.php"><span>Espacio Curricular</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">groups</span>
                    <a href="profesor_alumnos.php"><span>Alumnos</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">text_increase</span>
                    <a href="profesor_notas.php"><span>Notas</span></a>
                </li>
                <li>
                <span class="material-symbols-outlined">patient_list</span>
                    <a href="#"><span>Asistencia</span></a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='profesor_institucion.php'" id="volver" class="volver">Volver</button>
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>
        </div>
        <div  class="contenedor_principal">
        <div class="header">
                <div><?php
                while ($nombre_materia = $consulta_materias->fetch(PDO::FETCH_ASSOC)) {
                echo '<h3>' . ucfirst($nombre_materia['nombre']) . '</h3>';
                }
                ?></div>
                <div class="usuario">
                    <p><?php echo $profesor['nombre'] . " " . $profesor['apellido']?></p>
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
            </div>
            <div  class="contenedor_asistencia">
                <div class="titulo">
                    <h1>Registro de Asistencia</h1>
                </div>
                <div>
                    <button type="button" id="boton_listado" class="boton_listado" onclick="location.replace('profesor_asistencias.php')">
                        <span>Listado de Asistencia</span>
                    </button>
                </div>
                <nav>
                    <p>Seleccione los estudiantes presentes:</p>
                    <button type="button" id="seleccionar" name="seleccionar" onclick="Seleccionar()">
                        <span>Seleccionar Todos</span>
                    </button>
                </nav>
                <div class="lista" id="lista_registrar_asistencias">
                    <div class="lista_div">
                        <div class="div_tomar_asistencia_header">
                            <div><p>Nombre</p></div>
                            <div><p>Asistencia</p></div>
                        </div>
                        <div class="linea"></div>
                        <form id="form_registrar_asistencia" action=".\..\controlador\registrar_asistencia.php" method="POST">
                            <input type="date" id="fecha" name="fecha" class="input_fecha">
                            <div class="div_tomar_asistencia">
                            <?php 
                            if (isset($_SESSION['fecha_error'])) {
                                echo $_SESSION['fecha_error'];            
                                unset($_SESSION['fecha_error']);
                            }
                                    
                            while ($alumnos = $consulta_alumnos->fetch(PDO::FETCH_ASSOC)) {
                                $id_alumno = $alumnos['id'];

                                $asistencia = Alumno::calcular_asistencia($conexion, $id_alumno, $id_materia);
                                                        
                                echo '
                                <div class="lista_check">
                                    <div><p>' . $alumnos['nombre'] .' '. $alumnos['apellido'] . '</p></div>
                                    <div><input type="checkbox" id="' . $id_alumno .'" name="asistencia_anterior[]" value="' . $id_alumno .'"></div>
                                </div>';
                                }
                                ?>
                            </div>
                            <button type="button" id="tomar_asistencia" name="tomar_asistencia" onclick="Guardar_Asistencia()">Registrar Asistencia</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_asistencia.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>