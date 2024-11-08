<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$id_institucion = $_SESSION['institucion'];
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id);

$consulta_alumnos = $conexion->prepare("SELECT * FROM alumnos");

$consulta_materia= Materia::Obtener_materias($conexion, $id_institucion);
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
                    <h1>Alumnos</h1>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            <nav>
                <button type="button" id="boton_lista_alumnos"><span>Lista de Alumnos</span></button>
                <button type="button" id="boton_registrar_alumnos"><span>Registrar Alumnos</span></button>
            </nav>
            <?php 

            $filtro = "";
            $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : "";

            if (strlen($busqueda) > 3) {
                $filtro = " AND (alumnos.nombre LIKE :busqueda OR alumnos.apellido LIKE :busqueda OR materias.nombre LIKE :busqueda OR materias.curso LIKE :busqueda OR materias.departamento LIKE :busqueda)";
            }

            if ($filtro) {
                $consulta_alumnos->queryString .= $filtro;
            }

            $consulta = $conexion->prepare($consulta_alumnos->queryString);

            if ($filtro) {
                $busqueda = '%' . $busqueda . '%';
                $consulta->bindParam(':busqueda', $busqueda);
            }

            $consulta->execute();
            $alumnos = $consulta->fetchAll(PDO::FETCH_ASSOC);

            if ($alumnos != null) { ?>

                <div class="lista" id="lista_alumnos"> 
                    <h4>Buscar Alumno</h4>
                    <div class="titulo_form">
                        <form action="#" method="get">
                            <input type="search" name="busqueda">
                            <button type="submit">Buscar</button>
                            <button type="reset" onclick="location.href='administrar_alumnos.php'">Borrar</button>  
                        </form>
                    </div>
                    <div class="lista_alumnos">
                        <div class="div_lista_header">
                            <div class="div1">Nombre</div>
                            <div class="div2">DNI</div>
                            <div class="div3">Fecha de Nacimiento</div>
                            <div class="div4">Acciones</div> 
                        </div>
                        <div class="linea"></div>
                        <div class="div_lista">
                        <?php
                            
                        foreach ($alumnos as $alumno) { 
                            $id_alumno = $alumno['id'];
                            $nombre = $alumno['nombre'] .' '. $alumno['apellido'];
                            echo '
                            <div class="div1"><p>' . $nombre . '</p></div>
                            <div class="div2"><p>' . $alumno['dni'] . '</p></div>
                            <div class="div3"><p>' . $alumno['fecha_nacimiento'] . '</p></div>
                            <div class="div4">
                                <button type="button" id="boton_editar_alumno" class="boton_editar_alumno" value="' . $id_alumno . '" onclick="Perfil_Alumnos(' . $id_alumno . ')"><span class="material-symbols-outlined">manage_accounts</span>Administrar</button>
                            </div>';
                        } ?>
                        </div>
                    </div> 
                    <form id="form_alumnos" action="administrador_perfil_alumno.php" method="post" style="display: none;">
                        <input type="hidden" name="alumno" id="input_alumno">
                    </form>
                    <?php 
                } else {
                    echo '<div class="div_vacio"><p>No hay alumnos registrados</p></div>';
                } ?>
                </div>
        <div class="contenedor_secundario">
        <div class="dar_alta_alumnos" id="dar_alta_alumnos" style="display: none;">
            <h3>Dar Alta Alumno</h3> 
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
                        <input type="number" id="dni" name="dni" placeholder="Ingrese D.N.I" maxlength="8" required>
                    </div>
                    <div>
                        <label for="fecha_nacimiento">Fecha de Nacimiento: </label> <br>
                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" placeholder="Ingrese fecha de nacimiento" required>
                    </div>
                </div>
                <button type="button" id="registrar_alumno" class="registrar_alumno" onclick="Registrar_Alumno()">Registrar</button>
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
            // Mandar mensaje de alumno creado con exito
            echo $_SESSION['mensaje_alumno'];
            // Eliminar el mensaje después de mostrarlo            
            unset($_SESSION['mensaje_alumno']);
        } ?>
    </div>
</div> 
</body>
<script src=".\..\..\Recursos\JS\Funciones_alumnos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>