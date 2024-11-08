<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();
                        
session_start();
$id_institucion = $_SESSION['institucion'];          
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id);
// Obtener el nombre de la institucion
$consulta_institucion = $conexion->prepare("SELECT DISTINCT instituciones.nombre, instituciones.id FROM instituciones WHERE instituciones.id = :institucion_id");
$consulta_institucion->bindParam(':institucion_id', $id_institucion);
$consulta_institucion->execute();

$consulta_ram = Institucion::obtener_Ram($conexion, $id_institucion);
$consulta_ram->execute();

$institucion = $consulta_institucion->fetch(PDO::FETCH_ASSOC);

$ram = $consulta_ram->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_parametros.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href=".\..\..\Recursos\img\escuela.png">
    <title>Parametros</title>
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
                    <h1>Parametros de evaluación</h1>
                </div>
                <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
            </div>
            <nav>
                <button type="button" id="boton_lista_parametros"><span>Parametros</span></button>
                <button type="button" id="boton_modificar_parametros"><span>Modificar Parametros</span></button>
            </nav>
            <div  class="contenedor_listas">  
                <div class="lista" id="lista_parametros">
                    <div class="lista_div" id="parametros_promocion">
                        <div class="lista_header">
                            <h4>Promocion</h4>    
                            <div class="linea"></div>
                        </div>
                        <div class="informacion" id="parametros">
                            <div class="div1">Nota</div>
                            <div class="div2">Asistencias</div>
                            <?php
                            if (!empty($ram)) {
                                foreach ($ram as $parametro) {
                                    
                                    echo '           
                                    <div class="div3"><p>' . $parametro['nota_promocion'] .'</p></div>
                                    <div class="div4"><p>' . $parametro['asistencia_promocion'] . '</p></div>';
                                } 
                            } else {
                                    echo '           
                                    <div class="div3"><p>No se han establecido los parametros</p></div>
                                    <div class="div4"><p>No se han establecido los parametros</p></div>';
                            } ?>
                        </div>
                    </div>
                    
                    <div class="lista" id="lista_regular">
                        <div class="lista_div" id="parametros_regular">
                        <div class="lista_header">
                                <h4>Regular</h4>
                                <div class="linea"></div>
                            </div>
                            <div class="informacion" id="parametros">
                                <div class="div1">Nota</div>
                                <div class="div2">Asistencias</div>
                                <?php
                                if (!empty($ram)) {
                                    foreach ($ram as $parametro) {
                                    echo '           
                                    <div class="div3"><p>' . $parametro['nota_regular'] .'</p></div>
                                    <div class="div4"><p>' . $parametro['asistencia_regular'] . '</p></div>';
                                    } 
                                } else {
                                    echo '           
                                    <div class="div3"><p>No se han establecido los parametros</p></div>
                                    <div class="div4"><p>No se han establecido los parametros</p></div>';
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="modificar_parametros" class="modificar_parametros" style="display: none;">
                <h3>Formulario de modificación de Parametros RAM</h3>
                <div class="linea"></div>
                <form id="form_ram" action=".\..\controlador\modificar_ram.php" method="post">
                    <div class="form">
                        <div>
                            <label for="nota_promocion"><p>Nota para Promoción</p></label>
                            <input type="number" id="nota_promocion" name="nota_promocion" min="1" max="10" step="0.01" maxlength="2"> 
                        </div>
                        <div>
                            <label for="asistencia_promocion"><p>Procentaje de asistencia para Promoción</p></label>
                            <input type="number" id="asistencia_promocion" name="asistencia_promocion" min="1" max="100" step="0.01" maxlength="3"> 
                        </div>
                        <div>
                            <label for="nota_regular"><p>Nota para Regularidad</p></label>
                            <input type="number" id="nota_regular" name="nota_regular" min="1" max="10" step="0.01" maxlength="2"> 
                        </div>
                        <div>
                            <label for="asistencia_regular"><p>Procentaje de asistencia para Regularidad</p></label>
                            <input type="number" id="asistencia_regular" name="asistencia_regular" min="1" max="100" step="0.01" maxlength="3"> 
                        </div>
                        <?php
                        $_SESSION['institucion'] = $id_institucion; 
                        ?>
                    </div>
                    <input type="button" class="boton_ram" onclick="Modificar_ram()" value="Modificar">
                </form>
                <?php
                if (isset($_SESSION['parametros'])) {
                    echo $_SESSION['parametros'];          
                    unset($_SESSION['parametros']);
                }
                ?>
            </div>
        </div>
    </div>
</body>
<script src=".\..\..\Recursos\JS\Funciones.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>