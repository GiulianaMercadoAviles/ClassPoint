<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_instituciones = $_SESSION['institucion'];
$id_profesor = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              
    if (isset($_POST['materia'])) {
    
        $id_materia = $_POST['materia'];
        $_SESSION['materia'] = $id_materia;
    }
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

$profesor = Profesor::datos_profesor($conexion, $id_profesor);
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_espacio_curricular.css">
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
                    <span class="material-symbols-outlined">import_contacts</span>
                    <a href="#"><span>Espacio Curricular</span></a>
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
                    <a href="profesor_asistencias.php"><span>Asistencia</span></a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='profesor_institucion.php'" id="volver" class="volver">Volver</button>
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>
        </div>
        <div class="contenedor_principal">
            <div class="header">
                <?php
                while ($nombre_materia = $consulta_materias->fetch(PDO::FETCH_ASSOC)) {
                echo '<h3>' . ucfirst($nombre_materia['nombre']) . '</h3>';
                }
                ?>
                <div class="usuario">
                    <p><?php echo $profesor['nombre'] . " " . $profesor['apellido']?></p>
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
            </div>
            <div  class="contenedor_listas">
                <div class="tarjeta">
                    <h1>Espacio Curricular</h1>
                </div>
                <div class="boton_estado">
                    <form action="#" method="get">
                        <input type="hidden" name="calcular_estado">
                        <button type="submit">Calcular Estado</button> 
                    </form>
                </div>
                <div class="lista" id="lista_alumnos">  
                    <div class="lista_alumnos">
                        <div class="lista_div_header">
                            <div class="div1"><p>Alumno</p></div>
                            <div class="div2"><p>Porcentaje de Asistencias</p></div>
                            <div class="div3"></div>
                            <div class="div4"><p>Notas</p></div>
                            <div class="div5"></div>
                            <div class="div6"><p>Promedio</p></div>
                            <div class="div7"><p>Estado</p></div> 
                        </div> 
                    <div class="linea"></div> 
                    <div class="lista_div">
                    <?php
                    while ($alumnos = $consulta_alumnos->fetch(PDO::FETCH_ASSOC)) {

                        $id_alumno = $alumnos['id'];
                                
                        $asistencia = Alumno::calcular_asistencia($conexion, $id_alumno, $id_materia);

                        if (isset($_GET['calcular_estado'])) {
                            $estado = Alumno::estado($conexion, $id_alumno, $id_instituciones, $asistencia, $id_materia);

                            $promedio = Alumno::calcular_promedio($conexion, $id_alumno, $id_materia);
 
                        } else {
                            $estado = "";
                            $promedio = "";
                        }
                        echo '
                        <div class="div1"><p>' . $alumnos['nombre'] .' '. $alumnos['apellido'] . '</p></div>
                        <div class="div2"><p>' . $asistencia . '%</p></div>
                        <div class="div3"><p>' . $alumnos['parcial_1'] . '</p></div>
                        <div class="div4"><p>' . $alumnos['parcial_2'] . '</p></div>
                        <div class="div5"><p>' . $alumnos['final'] .'</p></div>
                        <div class="div6"><p>' . $promedio . '</p></div>
                        <div class="div7"><p>' . $estado . '</p></div>';
                    } ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>

