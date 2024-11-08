<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 
$id_instituciones = $_SESSION['institucion'];
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id);

$consulta_profesores = $conexion->prepare("SELECT DISTINCT * FROM profesores LEFT JOIN profesor_institucion ON profesores.id = profesor_institucion.profesor_id WHERE profesor_institucion.institucion_id = :institucion_id");

$consulta_materia = $conexion->prepare("SELECT materias.id, materias.nombre FROM materias WHERE materias.instituciones_id = :institucion_id");
$consulta_materia->bindParam(':institucion_id', $id_instituciones);
$consulta_materia->execute();
$materias = $consulta_materia->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Pagina Principal Administrador</title>
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
                    <h1>Profesores</h1>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            <nav>
                <button type="button" id="boton_profesores"><span>Listado de Profesores</span></button>
                <button type="button" id="boton_registrar_profesores"><span>Registrar Profesor</span></button>
                </nav>
                <?php 
                $filtro = "";
                $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";

                if (strlen($busqueda) > 3) {
                    $filtro = " AND (profesores.nombre LIKE :busqueda OR profesores.apellido LIKE :busqueda)";
                }

                if ($filtro) {
                    $consulta_profesores->queryString .= $filtro;
                }

                $consulta = $conexion->prepare($consulta_profesores->queryString);

                if ($filtro) {
                    $busqueda = '%' . $busqueda . '%';
                    $consulta->bindParam(':busqueda', $busqueda);
                }

                $consulta->bindParam(':institucion_id', $id_instituciones);
                $consulta->execute();

                $profesores = $consulta->fetchAll(PDO::FETCH_ASSOC);
                if ($profesores != null) {
                
                ?>
                <div class="lista" id="lista_profesor"> 
                    <h4>Buscar Profesor</h4>
                    <div class="titulo_form">
                        <form action="#" method="get">
                            <input type="search" name="busqueda">
                            <button type="submit">Buscar</button>
                            <button type="reset" onclick="location.href='administrar_alumnos.php'">Borrar</button>  
                        </form>
                    </div>
                    <div class="lista_profesor">
                            <div class="lista_div_header">
                                <div class="div1">Nombre</div>
                                <div class="div2">DNI</div>
                                <div class="div3">Fecha de Nacimiento</div>
                                <div class="div4">Correo Electrónico</div>
                                <div class="div5">Legajo</div>
                                <div class="div6">Acciones</div> 
                            </div>
                            <div class="linea"></div>
                            <div class="lista_div">
                                <?php
                                foreach ($profesores as $profesor) {        
                                    $id_profesor = $profesor['profesor_id'];
                                    $nombre = $profesor['nombre'] .' '. $profesor['apellido'];
                                    echo '
                                    <div class="div1"><p>' . $nombre . '</p></div>
                                    <div class="div2"><p>' . $profesor['dni'] . '</p></div>
                                    <div class="div3"><p>' . $profesor['fecha_nacimiento'] . '</p></div>
                                    <div class="div4"><p>' . $profesor['email'] . '</p></div>
                                    <div class="div5"><p>' . $profesor['legajo'] . '</p></div>
                                    <div><button type="button" id="boton_editar_profesor" class="boton_editar_profesor" value="' . $id_profesor . '" onclick="Perfil_Profesor(' . $id_profesor . ')"><span class="material-symbols-outlined">manage_accounts</span>Administrar</button></div>';
                                } ?>
                            </div>
                        </div> 
                        <form id="form_profesor" action="administrador_perfil_profesor.php" method="post" style="display: none;">
                            <input type="hidden" name="profesor" id="input_profesor">
                        </form>
                        <form id="form_eliminar" action=".\..\controlador\eliminar_profesor.php" method="post" style="display: none;">
                            <input type="hidden" name="eliminacion" id="input_eliminar">
                        </form>
                
                <?php } else {
                    echo '<div class="div_vacio"><p>No hay profesores registrados</p></div>';
                } ?>
                </div>
                <div class="contenedor_secundario">
                    <div class="registrar_profesores" id="registrar_profesores" style="display:none;"> 
                        <h2>Registrar Profesor</h2>  
                        <div class="linea"></div> 
                        <form id="form_registrar_profesor" action=".\..\controlador\registrar_profesor.php" method="post">   
                        <div class="form">
                            <div id="datos_personales">
                                <div>
                                    <label for="nombre">Nombre: </label> <br>
                                    <input type="text" id="nombre" name="nombre" placeholder="Ingrese el Nombre" maxlength="30" required>
                                </div>
                                <div>
                                    <label for="apellido">Apellido: </label> <br>
                                    <input type="text" id="apellido" name="apellido" placeholder="Ingrese el Apellido" maxlength="30" required>
                                </div>
                                <div>
                                    <label for="dni">D.N.I: </label> <br>
                                    <input type="number" id="dni" name="dni" placeholder="Ingrese el D.N.I" maxlength="8" required>
                                </div>
                                <div>
                                    <label for="email">Correo Electronico: </label> <br>
                                    <input type="email" id="email" name="email" placeholder="Ingrese Correo Electronico" required>
                                </div>
                                <div>
                                    <label for="fecha_nacimiento">Fecha de Nacimiento: </label> <br>
                                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Ingrese la Fecha de Nacimiento" required>
                                </div>
                                <div>
                                    <label for="legajo">Número de Legajo: </label> <br>
                                    <input type="number" id="legajo" name="legajo" placeholder="Ingrese Número de Legajo" maxlength="8" required>
                                </div>
                            </div>
                            <div id="datos_usuario">
                                <div>
                                    <label for="contrasena">Contraseña: </label> <br>
                                    <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese Contraseña" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="registrar_profesor" class="registrar_profesor" onclick="Registrar_Profesor()">Registrar</button>
                        <button onclick="location.href='administrador_profesores.php'">Cancelar</button>
                        </form>   
                    </div> 
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
            </div>  
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones_profesores.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>