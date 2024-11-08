<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_instituciones = $_SESSION['institucion'];
$id_profesor = $_SESSION['usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['materia'])) {
    
    $id_materia = $_POST['materia'];
} else {
    $id_materia = $_SESSION['materia'];
}

$_SESSION['materia'] = $id_materia;

if (!empty($id_materia)) {
        
    // Obtener el nombre de la materia
    $consulta_materias = Materia::Buscar_materia($conexion, $id_materia);
    $consulta_materias->execute();
    // Obtener los alumnos
    $consulta_alumnos = Materia::Obtener_alumnos($conexion, $id_materia);
}

$profesor = Profesor::datos_profesor($conexion, $id_profesor);
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_alumnos.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
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
                    <a href="#"><span>Alumnos</span></a>
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
            <div  class="contenedor_listas">
                <div class="tarjeta">
                <h1>Alumnos</h1>
            </div>
                <nav>
                    <button type="button" id="boton_lista_alumnos">
                        <span>Lista de Alumnos</span>
                    </button>
                    <button type="button" id="boton_registrar_alumnos">
                        <span>Registrar Alumnos</span>
                    </button>
                </nav>
                <?php 

                $filtro = "";
                $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";

                if (strlen($busqueda) > 3) {
                    $filtro = " AND (alumnos.nombre LIKE :busqueda OR alumnos.apellido LIKE :busqueda)";
                }

                if ($filtro) {
                    $consulta_alumnos->queryString .= $filtro;
                }

                $resultados = $conexion->prepare($consulta_alumnos->queryString);

                if ($filtro) {
                    $busqueda = '%' . $busqueda . '%';
                    $resultados->bindParam(':busqueda', $busqueda);
                }

                $resultados->bindParam(':materia_id', $id_materia);
                $resultados->execute();

                $resultado = $resultados->fetchAll(PDO::FETCH_ASSOC);
                ?>

                <div class="lista" id="lista_alumnos"> 

                    <div class="titulo_form">
                        <form action="#" method="get">
                            <p>Buscar Alumno</p>
                            <input type="search" name="busqueda">
                            <button type="submit">Buscar</button> 
                            <button type="reset" onclick="location.href='profesor_alumnos.php';">Borrar Búsqueda</button> 
                        </form>
                    </div>
                    <div class="lista_alumnos">
                        <div class="lista_div_header">
                        <?php if ($resultado) { ?>
                            <div class="div1">Nombre</div>
                            <div class="div2">DNI</div>
                            <div class="div3">Fecha de Nacimiento</div>
                            <div class="div4">Acciones</div>
                        </div>
                        <div class="linea"></div>
                        <div class="lista_div">
                        <?php
                        
                        foreach ($resultado as $alumnos) {
                        
                            $id_alumno = $alumnos['id'];
                            $nombre = $alumnos['nombre'] .' '. $alumnos['apellido'];
                            echo '
                            <div class="div1"><p>' . $nombre . '</p></div>
                            <div class="div2"><p>' . $alumnos['dni'] . '</p></div>
                            <div class="div3"><p>' . $alumnos['fecha_nacimiento'] . '</p></div>
                            <div class="div4"><button type="button" id="boton_editar_alumno" class="boton_editar_alumno" value="' . $id_alumno . '" onclick="Modificar_datos(' . $id_alumno . ')"><span class="material-symbols-outlined">person_edit</span></button></div>
                            <div class="div5"><button type="button" id="boton_eliminar_alumno" class="boton_eliminar_alumno" value="' . $id_alumno . '" onclick="Desinscribir_Alumno(' . $id_alumno . ')"><span class="material-symbols-outlined">person_remove</span></button>
                            </div>';
                        }} else {
                            echo "<p>No se han encontrado alumnos</p>";
                        }
                        ?>
                        </div>
                    </div> 
                    <form id="form_desinscribir" action=".\..\controlador\desinscribir_alumno.php" method="post" style="display: none;">
                        <input type="hidden" name="eliminacion" id="input_desinscribir_materia" >
                        <input type="hidden" name="desinscribir_materia" id="input_desinscribir" value="<?= $id_materia; ?>">  
                    </form>
                </div>
            </div>
            <div class="dar_alta_alumnos" id="dar_alta_alumnos" style="display: none;">
                <h3>Registrar Alumno</h3>   
                <div class="linea"></div>
                <form id="form_registrar_alumno" action=".\..\controlador\registrar_alumnos.php" method="post">   
                    <div class="form">
                        <div>
                            <label for="nombre">Nombre: </label> <br>
                            <input type="text" id="nombre" name="nombre" placeholder="Ingrese Nombre" maxlength="30" required>
                        </div>
                        <div>
                            <label for="apellido">Apellido: </label> <br>
                            <input type="text" id="apellido" name="apellido" placeholder="Ingrese Apellido" maxlength="30" required>
                        </div>
                        <div>
                            <label for="dni">D.N.I: </label> <br>
                            <input type="number" id="dni" name="dni" placeholder="Ingrese D.N.I" required>
                        </div>
                        <div>
                            <label for="fecha_nacimiento">Fecha de Nacimiento: </label> <br>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Ingrese fecha de nacimiento" required>
                        </div>
                    </div> 
                    <button type="button" id="registrar_alumno" class="registrar_alumno" onclick="Registrar_Alumno()">Registrar</button>
                </form>
            </div>  
            <?php
            if (isset($_SESSION['error_alumno'])) {
                // Mandar un mensaje de error
                echo $_SESSION['error_alumno'];
                // Eliminar el mensaje de error después de mostrarlo            
                unset($_SESSION['error_alumno']);
            }
            if (isset($_SESSION['mensaje_alumno'])) {
                echo $_SESSION['mensaje_alumno'];
                unset($_SESSION['mensaje_alumno']);
            }
            ?> 
            <div class="editar_alumno" id="editar_alumno" style="display: none;">
                <h3>Editar información del Alumno</h3>   
                <div class="linea"></div>
                <form id="form_editar_alumno" action=".\..\controlador\editar_alumno.php" method="post"> 
                    <div class="form"> 
                        <div>
                            <label for="nombre">Nombre: </label> <br>
                            <input type="text" id="nombre_editar" name="nombre" placeholder="<?php echo $alumnos['nombre']?>" maxlength="30" required>
                        </div>
                        <div>
                            <label for="apellido">Apellido: </label> <br>
                            <input type="text" id="apellido_editar" name="apellido" placeholder="<?php echo $alumnos['apellido']?>" maxlength="30" required>
                        </div>
                        <div>
                            <label for="dni">D.N.I: </label> <br>
                            <input type="number" id="dni_editar" name="dni" placeholder="<?php echo $alumnos['dni']?>"required>
                        </div>
                        <div>
                            <label for="fecha_nacimiento">Fecha de Nacimiento: </label> <br>
                            <input type="date" id="fecha_nacimiento_editar" name="fecha_nacimiento" placeholder="Ingrese fecha de nacimiento" required>
                        </div>
                        <input type="hidden" name="id_editar" id="input_editar">
                    </div> 
                    <button type="button" id="boton_editar_alumno" class="boton_editar_alumno" onclick="Editar_Alumno()">Guardar</button>
                </form> 
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_alumnos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
