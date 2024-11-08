<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_institucion = $_SESSION['institucion'];
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['alumno'])) {

        $id_alumno = $_POST['alumno'];
        $_SESSION['alumno'] = $id_alumno;
    }
} else {
    $id_alumno = $_SESSION['alumno'];
}

$datos_alumno = Alumno::datos_alumnos($conexion, $id_alumno);

$materia_alumno = Alumno::obtener_materias($conexion, $id_institucion, $id_alumno);

$consulta_materia = $conexion->prepare("SELECT materias.* FROM materias LEFT JOIN materia_alumno ON materias.id = materia_alumno.materia_id AND materia_alumno.alumno_id = :id_alumno WHERE materia_alumno.materia_id IS NULL AND materias.instituciones_id = :id_institucion;");
$consulta_materia->bindParam(':id_alumno' ,$id_alumno);
$consulta_materia->bindParam(':id_institucion' ,$id_institucion);
$consulta_materia->execute();
$materias = $consulta_materia->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_alumnos.css">
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
                <button onclick="location.href='administrador_alumnos.php'" id="volver" class="volver">Volver</button>
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
                    <h1>Editar Alumno</h1>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            
            <div class="datos_alumno" id="datos_alumno">
                <h2>Datos Institucionales</h2>
                    <div class="lista_alumnos" id="lista_alumnos"> 
                    <h3>Materias Asignadas</h3> 
                    <div class="linea"></div> 
                    <div class="div_lista">
                        <?php
                        foreach ($materia_alumno as $materia) {
                        echo '
                        <div class="datos">
                            <p>Materia: ' . ucfirst($materia['nombre']) . '</p>
                            <p>Departamento: ' . ucfirst($materia['departamento']) . '</p>
                            <p>Curso: ' . $materia['curso'] . '</p>
                            <button id="boton_desinscribir_alumno" class="boton_desinscribir" value="'. $materia['materia_id'].'" onclick="Desinscribir_Alumno('.$materia['materia_id'].')"><span class="material-symbols-outlined">delete</span></button>
                        </div>'; }
                        ?>
                        <form id="form_desinscribir" action=".\..\controlador\eliminar_alumno.php" method="post" style="display: none;">
                            <input type="hidden" name="desinscribir_alumno" id="input_desinscribir_alumno" value="<?= $id_alumno; ?>">
                            <input type="hidden" name="desinscribir_materia" id="input_desinscribir_materia">
                            <button type="button" id="boton_desinscribir" onclick="Desinscribir_Alumno()">Asignar<span class="material-symbols-outlined">assignment_add</span></button> 
                        </form>
                    </div>
                </div>
                <div class="inscribir_alumno" id="inscribir_alumno">
                    <h3>Inscribir alumno a una materia</h3>
                    <div class="linea"></div>
                    <form id="form_inscribir_alumno" action=".\..\controlador\inscribir_alumno.php" method="POST">
                        <div class="form">
                            <select name="materia" id="materia">
                                <option value="">Seleccione la materia</option>
                                <?php
                                foreach ($materias as $materia) {
                                    echo '<option value="'. $materia['id'].'">'. $materia['nombre'].'</option>';
                                }
                                ?>
                            </select>
                        </div> 
                        <input type="hidden" name="inscribir_alumnos" id="inscribir_alumnos" value="<?= $datos_alumno['dni']; ?>">
                        <button type="button" id="boton_inscribir" onclick="Inscribir_Alumno()">Asignar<span class="material-symbols-outlined">assignment_add</span></button>
                    </form>
                </div>
                <div class="editar_alumno" id="editar_alumno" >
                    <h3>Editar información del Alumno</h3>   
                    <div class="linea"></div>
                    <form id="form_editar_alumno" action=".\..\controlador\editar_alumno.php" method="post">   
                        <div class="form">
                            <div>
                                <label for="nombre">Nombre: </label> 
                                <input type="text" id="nombre_editar" name="nombre" placeholder="<?php echo $datos_alumno['nombre']?>" maxlength="30" required>
                            </div>
                            <div>
                                <label for="apellido">Apellido: </label> 
                                <input type="text" id="apellido_editar" name="apellido" placeholder="<?php echo $datos_alumno['apellido']?>" maxlength="30" required>
                            </div>
                            <div>
                                <label for="dni">D.N.I: </label> 
                                <input type="number" id="dni_editar" name="dni" placeholder="<?php echo $datos_alumno['dni']?>" maxlength="8" required>
                            </div>
                            <div>
                                <label for="fecha_nacimiento">Fecha de Nacimiento: </label> 
                                <input type="date" id="fecha_nacimiento_editar" name="fecha_nacimiento" placeholder="<?php echo $datos_alumno['fecha_nacimiento']?>" required>
                            </div>
                            <input type="hidden" name="id_editar" id="input_editar" value="<?= $id_alumno; ?>">
                        </div> 
                        <button type="button" id="boton_editar_alumno" class="boton_editar" onclick="Editar_Alumno()">Guardar</button>
                    </form> 
                </div>
                <div class="eliminar_alumno" id="eliminar_alumno">
                    <h3>Eliminar Alumno</h3>
                    <div class="linea"></div>
                    <button type="boton" id="boton_eliminar_alumno" class="boton_eliminar_alumno" onclick="Eliminar_Alumno()"><span class="material-symbols-outlined">person_remove</span>Eliminar Registro del Alumno</button>
                    <form id="form_eliminar_alumno" action=".\..\controlador\eliminar_alumno.php" method="POST">
                        <input type="hidden" name="eliminacion" id="eliminar_alumnos" value="<?= $datos_alumno['id']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_alumnos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>


