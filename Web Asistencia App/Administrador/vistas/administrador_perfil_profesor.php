<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_institucion = $_SESSION['institucion'];
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id);

if (isset($_POST['profesor'])) {

    $id_profesor = $_POST['profesor'];
    $_SESSION['profesor'] = $id_profesor;

} else {
    $id_profesor = $_SESSION['profesor'];
}

$profesor = Profesor::datos_profesor($conexion, $id_profesor);

$materias_profesor = Profesor::obtener_materias($conexion, $id_institucion, $id_profesor);

$materias = Profesor::obtener_materias_asignar($conexion, $id_institucion, $id_profesor);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_profesores.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href=".\..\..\Recursos\img\escuela.png">
    <link rel="shortcut icon" href=".\..\..\Recursos\img\libros.png">
    <title>Document</title>
</head>
<body>
<div>
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
                <button onclick="location.href='administrador_profesores.php'" id="volver" class="volver">Volver</button>
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
                    <h1>Editar Profesor</h1>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            <div class="datos_profesor" id="datos_profesor">
                <div>
                <h2>Datos Institucionales</h2>
                    <div class="lista_profesores" id="lista_profesores"> 
                    <h3>Materias Asignadas</h3> 
                    <div class="linea"></div>
                        <?php
                        foreach ($materias_profesor as $materia) {
                        echo '
                        <div class="div_lista">
                            <p>Materia: ' . ucfirst($materia['nombre']) . '</p>
                            <p>Departamento: ' . ucfirst($materia['departamento']) . '</p>
                            <p>Curso: ' . $materia['curso'] . '</p>
                            <button type="button" id="boton_desasignar_institucion" class="boton_desasignar" value="'. $materia['materia_id'].'" onclick="Desasignar_Profesor('.$materia['materia_id'].')">
                                <span class="material-symbols-outlined">delete</span>Eliminar
                            </button>
                        </div>'; }
                        ?>
                        <form id="form_desasignar" action=".\..\controlador\desasignar_profesor.php" method="post" style="display: none;">
                            <input type="hidden" name="desasignar_profesor" id="input_desasignar_profesor" value="<?= $id_profesor; ?>">
                            <input type="hidden" name="desasignar_materia" id="input_desasignar_materia">
                            <button type="button" id="boton_asignar" onclick="Desasignar_Profesor()">
                                <span class="material-symbols-outlined">assignment_add</span>Asignar
                            </button> 
                        </form>
                    </div>
                    <div id="asignar_profesor" class="asignar_profesor">
                        <h3>Asignar Nueva Materia</h3> 
                        <div class="linea"></div>
                            <form id="form_asignar_profesor" action=".\..\controlador\asignar_profesor.php" method="POST">
                                <div class="form">
                                    <label for="materia">Materia: </label>
                                    <select name="asignar_materia" id="asignar_materia" required>
                                        <option value="">Seleccione la materia</option> 
                                        <?php
                                        foreach ($materias as $materia) {
                                            echo '<option value="'. $materia['id'] .'">'. $materia['nombre'] .'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <input type="hidden" name="asignar_profesor" id="asignar_profesor" value="<?= $id_profesor; ?>">
                                <button type="button" id="boton_asignar" onclick="Asignar_Profesor()"><span class="material-symbols-outlined">assignment_add</span>Asignar
                                </button> 
                            </form>
                    </div>
                </div> 
            </div>
            <div class="editar_profesor" id="editar_profesor">
                <h3>Editar información del Profesor</h3>   
                <div class="linea"></div>  
                <form action=".\..\controlador\editar_profesor.php" method="post">   
                <div class="form">
                        <div>
                            <label for="nombre">Nombre: </label>
                            <input type="text" id="nombre" name="nombre" placeholder="<?php echo $profesor['nombre']?>" maxlength="30" required>
                        </div>
                        <div>
                            <label for="apellido">Apellido: </label>
                            <input type="text" id="apellido" name="apellido" placeholder="<?php echo $profesor['apellido']?>" maxlength="30" required>
                        </div>
                        <div>
                            <label for="dni">D.N.I: </label> 
                            <input type="number" id="dni" name="dni" placeholder="<?php echo $profesor['dni']?>" maxlength="8" required>
                        </div>
                        <div>
                            <label for="email">Correo Electrónico: </label> 
                            <input type="email" id="email" name="email" placeholder="<?php echo $profesor['email']?>"required>
                        </div>
                        <div>
                            <label for="fecha_nacimiento">Fecha de Nacimiento: </label> 
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Ingrese la Fecha de Nacimiento"required>
                        </div>
                        <div>
                            <label for="legajo">Número de Legajo: </label>
                            <input type="number" id="legajo" name="legajo" placeholder="<?php echo $profesor['legajo']?>" maxlength="8" required>
                        </div>
                    </div>
                    <input type="hidden" name="id_editar" id="input_editar" value="<?= $id_profesor; ?>">
                    <button type="button" id="boton_editar_profesor" class="boton_editar_profesor" onclick="Editar_Profesor()">Guardar</button>
                </form>  
            </div>
            <div class="eliminar_profesor" id="eliminar_profesor">
                <h3>Eliminar Profesor</h3>
                <div class="linea"></div>
                <div class="div_lista">
                <button type="button" id="boton_eliminar_profesor" class="boton_eliminar_profesor" value="' . $id_profesor . '" onclick="Eliminar_Profesor(' . $id_profesor . ')"><span class="material-symbols-outlined">person_remove</span>Eliminar los Registros del Profesor</button>
                <form id="form_eliminar_profesor" action=".\..\controlador\eliminar_profesor.php" method="POST">
                    <input type="hidden" name="eliminacion" id="eliminar_profesor" value="<?= $id_profesor ?>">
                </form></div>
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_profesores.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>