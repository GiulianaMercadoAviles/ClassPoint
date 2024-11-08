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

$asistencia_hoy = Materia::obtener_asistencia($conexion, $dia_actual, $id_materia);

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
            <div  class="contenedor_asistencia">
                <div  class="contenedor_listas">
                    <div class="titulo">
                        <h1>Registro de Asistencia</h1>
                    </div>
                </div>
                <?php 
                
                while ($nombre_materia = $consulta_materias->fetch(PDO::FETCH_ASSOC)) {
                echo '<h3>' . $nombre_materia['nombre'] . '</h3>';
                }
           
                if (!$asistencia_hoy) { ?>
                 
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
                    <form action=".\..\controlador\registrar_asistencia.php" method="post">
                        <input type="date" id="fecha" name="fecha" class="input_fecha">
                        <div class="div_tomar_asistencia">
                            <?php 
                                if (isset($_SESSION['fecha'])) {
                                    echo $_SESSION['fecha'];            
                                    unset($_SESSION['fecha']);
                                }
                                while ($alumnos = $consulta_alumnos->fetch(PDO::FETCH_ASSOC)) {
                                    $id_alumno = $alumnos['id'];
                                    $asistencia = Alumno::calcular_asistencia($conexion, $id_alumno, $id_materia);
                                                            
                                    echo '
                                    <div class="lista_check">
                                        <div class="check"><p>' . $alumnos['nombre'] .' '. $alumnos['apellido'] . '</p></div>
                                        <div class="check"><input type="checkbox" id="' . $id_alumno .'" name="asistencia[]" value="' . $id_alumno .'"></div>
                                    </div>';
                                }
                            ?>
                        </div>
                        <button type="submit" id="tomar_asistencia" name="tomar_asistencia">Registrar Asistencia</button>
                    </form>
                </div>
            </div>

                <?php } else { 
                echo '<div class="fecha"><p>Fecha: ' . $dia_actual .'  </p></div>'; 
             
                $consulta_alumnos->execute();
                
                while ($alumnos = $consulta_alumnos->fetch(PDO::FETCH_ASSOC)) {
    
                    $cumplanieros = Alumno::feliz_cumpleanos($conexion, $alumnos['id']);

                    if ($cumplanieros) {
                        foreach ($cumplanieros as $cumplaniero) {
                            echo "<h2>¡Hoy es el cumpleaños de " . $cumplaniero['nombre'] . " " . $cumplaniero['apellido'] . '!</h2>';
                        }
                        
                    }
                }?>
                <nav>
                    <button type="button" id="listado_asistencia" onclick="location.replace('profesor_asistencias.php')">
                        <span>Listado de Asistencia</span>
                    </button>
                    <button type="button" id="registro_anterior" onclick="location.replace('profesor_listado_asistencias.php')">
                        <span>Registros Anteriores</span>
                    </button>
                    <button type="button" id="registro_asistencia" onclick="location.replace('profesor_registrar_asistencias.php')">
                        <span>Registrar asistencia</span>
                    </button>
                </nav>
            </div>
            <div class="lista" id="lista_registrar_asistencias">
                <div class="tarjeta">
                    <div>
                        <h4>Alumnos Presentes</h4>
                    </div>
                </div>
                <div class="lista_div">
                        
                    <?php
                    $alumnos_presentes = Materia::alumnos_presentes($conexion, $id_materia, $dia_actual);
                    
                    if (!$alumnos_presentes) {
                        echo '
                        <div class="div_listado_asistencia">
                            <p>Todos los alumnos estan ausentes el día de hoy</p>
                        </div>';
                    } else { ?>
                        <div class="listado_asistencia_header">
                            <div><p>Nombre</p></div>
                            <div><p>Porcentaje de Asistencia</p></div>
                            <div><p>Acciones</p></div>
                        </div> 
                        <div class="linea"></div>
                        <div class="lista_asistencia">
                            <?php

                            foreach ($alumnos_presentes as $alumno_presente) {

                                $id_alumno = $alumno_presente['alumno_id'];
                                        
                                $asistencia = Alumno::calcular_asistencia($conexion, $id_alumno, $id_materia);

                                echo '
                                <div><p>' . $alumno_presente['nombre'] .' '. $alumno_presente['apellido'] . '</p></div>
                                <div><p>' . $asistencia . '%</p></div>
                                <div><button type="button" id="modificar_asistencia" class="modificar_asistencia" value="' . $id_alumno . '" onclick="Editar_Asistencia(' . $id_alumno . ')">Registrar Salida</button></div>
                                <div><button type="button" id="eliminar_asistencia" class="eliminar_asistencia" value="' . $id_alumno . '" onclick="Eliminar_Asistencia(' . $id_alumno . ')">Eliminar Asistencia</button></div>';  
                            }?>
                        </div> 
                    <?php } ?>
                    
                        <form id="form_eliminar_asistencia" action=".\..\controlador\eliminar_asistencia.php" method="post">
                            <input type="hidden" name="eliminar_asistencia" id="input_eliminar_asistencia">
                        </form>
                        <form id="form_modificar_asistencia" action=".\..\controlador\modificar_asistencia.php" method="post">
                            <input type="hidden" name="modificar_asistencia" id="input_modificar_asistencia">
                        </form>           
                </div>
            </div>   
            <div class="lista" id="lista_registrar_asistencias">
                <div class="tarjeta">
                    <div>
                        <h4>Alumnos Ausentes</h4>
                    </div>
                </div>
                <div class="lista_div">
                        
                    <?php
                    $alumnos_ausentes = Materia::alumnos_ausentes($conexion, $id_materia, $dia_actual);
                        
                    if (!$alumnos_ausentes) {
                        echo '
                        <div class="div_listado_inasistencia">
                            <p>Todos los alumnos estan presentes el día de hoy</p>
                        </div>';
                    } else { ?>
                    <div class="div_listado_inasistencia">        
                        <div class="listado_inasistencia_header">
                            <div><p>Nombre</p></div>
                            <div><p>Porcentaje de Asistencia</p></div>
                            <div><p>Acciones</p></div>
                        </div> 
                        <div class="linea"></div>
                        <div class="lista_inasistencia">
                            <?php
                            foreach ($alumnos_ausentes as $alumno_ausente) {

                                $id_alumno = $alumno_ausente['id'];

                                $asistencia = Alumno::calcular_asistencia($conexion, $id_alumno, $id_materia);

                                echo '
                                <div><p>' . $alumno_ausente['nombre'] .' '. $alumno_ausente['apellido'] . '</p></div>
                                <div><p>' . $asistencia . '%</p></div>
                                <div><button type="button" id="registrar_asistencia" class="registrar_asistencia" value="' . $id_alumno . '" onclick="Registrar_Asistencia(' . $id_alumno .')">Registrar Asistencia</button></div>';
                             } ?> 
                        </div>
                    </div>
                    <?php } ?>
                    <form id="form_asistencias" action=".\..\controlador\modificar_asistencia.php" method="post">
                        <input type="hidden" name="asistencia" id="asistencia">
                    </form>
                </div>
            </div>
        </div>
        <?php } ?> 
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_asistencia.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>