<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_instituciones = $_SESSION['institucion'];
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id); 
        
// Obtener el nombre de la materia
$consulta_materias = $conexion->prepare("SELECT DISTINCT materias.id as materia_id, materias.nombre as materia_nombre, profesores.nombre as profesor_nombre, profesores.apellido as profesor_apellido, materias.departamento, materias.curso FROM (materias LEFT JOIN profesor_institucion ON materias.id = profesor_institucion.materia_id) LEFT JOIN profesores ON profesores.id = profesor_institucion.profesor_id WHERE materias.instituciones_id = :institucion_id");
$consulta_materias->bindParam(':institucion_id', $id_instituciones);
$consulta_materias->execute();

$materias = $consulta_materias->fetchAll(PDO::FETCH_ASSOC);

?>   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_materias.css">
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
                    <h1>Materias</h1>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            <nav>
                <button type="button" id="boton_materias"><span>Listado de Materias</span></button>
                <button type="button" id="boton_registrar_materias"><span>Registrar Nueva Materia</span></button>
            </nav>
            <div class="lista" id="lista_materias">
                <?php 

                if ($materias) {
                    foreach ($materias as $materia) {
                        echo '
                        <div class="lista_div">
                        <div class="materia" data-id="' . $materia['materia_id'] . '">
                        <div class="lista_header">
                        <h3>' . strtoupper($materia['materia_nombre']) . '</h3>
                        <div class="linea"></div>
                        </div>
                        <div class="informacion">
                            <p>Profesor: '. $materia['profesor_nombre'] .' '. $materia['profesor_apellido'] .'</p>
                            <p>Departamento: '. $materia['departamento'] .'</p>
                            <p>Curso: '. $materia['curso'] .'</p>
                         </div>
                         <div class="botones"> 
                         <button type="button" id="boton_eliminar_materia" class="boton_eliminar" value="' . $materia['materia_id'] . '" onclick="Eliminar_Materia(' . $materia['materia_id'] . ')"><span class="material-symbols-outlined">delete</span>Eliminar</button>
                         <button type="button" id="boton_editar_materia" class="boton_editar" value="' . $materia['materia_id'] . '" onclick="Editar_Materia(' . $materia['materia_id'] . ')"><span class="material-symbols-outlined">edit_square</span>Editar</button>
                            </div>
                        </div></div>';
                    }
                } else {
                    echo 'La institucion no tiene materias registradas';
                }
                ?>      
                <form id="form_eliminar_materia" action=".\..\controlador\eliminar_institucion.php" method="post" style="display: none;">
                    <input type="hidden" name="id_eliminar" id="input_eliminar">
                </form>
                <form id="form_editar_materia" action=".\..\controlador\editar_institucion.php" method="post" style="display: none;">
                    <input type="hidden" name="id_edicion" id="input_editar">
                </form>
            </div>
            <div class="registrar_materia" id="registrar_materia" style="display:none"> 
                <h3>Registrar Materia</h3>   
                <div class="linea"></div>
                <form id="form_registrar_materia" action=".\..\controlador\registrar_materia.php" method="post">   
                    <div class="form">
                        <div>
                            <label for="nombre">Nombre: </label> <br>
                            <input type="text" id="nombre" name="nombre" placeholder="Ingrese Nombre" maxlength="50" required>
                        </div>
                        <div>
                            <label for="departamento">Departamento: </label> <br>
                            <input type="text" id="departamento" name="departamento" placeholder="Ingrese Departamento" maxlength="30" required>
                        </div>
                        <div>
                            <label for="curso">Curso </label> <br>
                            <input type="text" id="curso" name="curso" placeholder="Ingrese Curso" maxlength="30" required>
                        </div>
                    </div>
                    <button type="button" id="registrar_materia" class="registrar_materia" onclick="Registrar_Materia()">Registrar</button>
                </form>   
                <?php
                if (isset($_SESSION['mensaje_error'])) {
                    foreach ($_SESSION['mensaje_error'] as $error) {
                        echo $error;   
                    }
                    unset($_SESSION['mensaje_error']);
                }?>
            </div> 
            <div class="editar_materia" id="editar_materia" style="display: none"> 
                <h3>Editar Materia</h3>
                <div class="linea"></div>    
                <form id="form_editar" action=".\..\controlador\editar_materia.php" method="post">  
                    <div class="form"> 
                        <div>
                            <label for="nombre">Nombre: </label> <br>
                            <input type="text" id="nombre_editar" name="nombre" placeholder="<?php echo $materia['materia_nombre']?>" maxlength="50" required>
                        </div>
                        <div>
                            <label for="departamento">Departamento: </label> <br>
                            <input type="text" id="departamento_editar" name="departamento" placeholder="<?php echo $materia['departamento']?>" maxlength="30" required>
                        </div>
                        <div>
                            <label for="curso">Curso: </label> <br>
                            <input type="text" id="curso_editar" name="curso" placeholder="<?php echo $materia['curso']?>" maxlength="30" required>
                        </div>
                    </div> 
                    <input type="hidden" name="id_editar" id="input_editar" value="<?php echo $materia['materia_id']?>">
                    <button type="button" id="boton_editar" name="boton_editar" onclick="Guardar_Materia()">Editar</button>
                </form>
                <?php
                if (isset($_SESSION['mensaje_error'])) {
                    foreach ($_SESSION['mensaje_error'] as $error) {
                        echo $error;   
                    }
                    unset($_SESSION['mensaje_error']);
                }?>
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_materias.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>

