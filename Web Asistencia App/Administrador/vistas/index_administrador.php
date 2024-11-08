<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';

$database = new Database();
$conexion = $database->connect();

session_start();
$usuario_id = $_SESSION['usuario'];

$usuario = Usuario::datos_usuario($conexion, $usuario_id);

// Obtener las instituciones registradas en la base de datos                           
$consulta = $conexion->prepare("SELECT * FROM instituciones");
$consulta->execute();

$instituciones = $consulta->fetchAll(PDO::FETCH_ASSOC)
?>                              

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_index.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="shortcut icon" href=".\..\..\Recursos\img\escuela.png">
    <link rel="shortcut icon" href=".\..\..\Recursos\img\libros.png">
    <title>Instituciones</title>
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
                    <span class="material-symbols-outlined">school</span>
                    <a href="#"><span>Instituciones</span></a>
                </li>
            </ul>
            <div class="logout">
                <button onclick="location.href='./../../index.php'" id="salir" class="salir">Salir</button>
            </div>  
        </div>   
        <div class="contenedor_principal">
            <div class="header">
                <div></div>
                <div class="usuario">
                    <p>Administrador</p>
                    <span class="material-symbols-outlined">account_circle</span>
                </div>
            </div>
            <div  class="contenedor_listas">
                <div class="tarjeta">
                    <div>
                        <h2> ¡Hola <?php echo $usuario['nombre'] . " " . $usuario['apellido']?>! </h2>
                        <p>Seleccione una Institución para comenzar</p>
                    </div>
                    <img src=".\..\..\Recursos\img\libros.png" alt="escuela" >
                </div>
                <nav>
                    <button class="boton_admin" id="boton_instituciones">
                        <span class="material-symbols-outlined">list</span>
                        <span>Listado Instituciones</span>
                    </button>
                    <button class="boton_admin" id="boton_registrar_instituciones">
                    <span class="material-symbols-outlined">add</span>
                        <span>Registrar Nueva Instituciones</span>
                    </button>
                </nav>
                <div class="lista" id="lista_instituciones">
                <?php 
                if ($instituciones) {
                    foreach ($instituciones as $institucion) {
                    echo '
                    <div class="lista_div">
                        <div class="institucion" data-id="' . $institucion['id'] . '">
                            <div class="lista_header">
                                <h2>' . strtoupper($institucion['nombre']) . '</h2> 
                                <div class="linea"></div>
                            </div>
                            <div class="informacion">  
                                <p>Dirección: '. $institucion['direccion'] .'</p>
                                <p>C.U.E: '. $institucion['cue'] .'</p>
                            </div>
                        </div>
                        <div class="botones">
                            <button type="button" id="boton_eliminar_institucion" class="boton_eliminar" value="' . $institucion['id'] . '" onclick="Eliminar_Institucion(' . $institucion['id'] . ')"><span class="material-symbols-outlined">delete</span>Eliminar</button>
                            <button type="button" id="boton_editar_institucion" class="boton_editar" value="' . $institucion['id'] . '" onclick="Editar_Institucion(' . $institucion['id'] . ')"><span class="material-symbols-outlined">edit_square</span>Editar</button>
                        </div>
                    </div>';
                }}
                ?>
                </div>
                <form id="formEliminacion" action=".\..\controlador\eliminar_institucion.php" method="post" style="display: none;">
                    <input type="hidden" name="eliminacion" id="inputEliminacion">
                </form>
                <form id="form_institucion" action="administrador_instituciones.php" method="post" style="display: none;">
                    <input type="hidden" name="institucion" id="input_institucion">
                </form>
            </div>
            <div class="registrar_instituciones" id="registrar_instituciones" style="display: none"> 
                <h3>Registrar Institución</h3> 
                <div class="linea"></div>  
                <form id="form_registrar" action=".\..\controlador\registrar_institucion.php" method="post">
                    <div class="form"> 
                        <div>
                            <label for="nombre">Nombre: </label> <br>
                            <input type="text" id="nombre" name="nombre" placeholder="Ingrese Nombre" maxlength="50" required>
                        </div>
                        <div>
                            <label for="direccion">Dirección: </label> <br>
                            <input type="text" id="direccion" name="direccion" placeholder="Ingrese Direccion de la Institucion" maxlength="100" required>
                        </div>
                        <div>
                            <label for="cue">C.U.E: </label> <br>
                            <input type="number" id="cue" name="cue" placeholder="Ingrese C.U.E" maxlength="9" required>
                        </div>
                    </div>
                    <button type="button" id="boton_registrar" name="boton_registrar" onclick="Registrar_Institucion()">Registrar</button> 
                </form>
                <?php 
                if (isset($_SESSION['mensaje_error'])) {
                    foreach ($_SESSION['mensaje_error'] as $error) {
                        echo $error;   
                    }
                    unset($_SESSION['mensaje_error']);
                }?>  
            </div> 
            <div class="editar_institucion" id="editar_institucion" style="display: none"> 
                <h3>Editar Institución</h3>
                <div class="linea"></div>    
                <form id="form_editar" action=".\..\controlador\editar_institucion.php" method="post">  
                    <div class="form"> 
                        <div>
                            <label for="nombre">Nombre: </label> <br>
                            <input type="text" id="nombre_editar" name="nombre" placeholder="<?php echo $institucion['nombre']?>" maxlength="50" required>
                        </div>
                        <div>
                            <label for="direccion">Dirección: </label> <br>
                            <input type="text" id="direccion_editar" name="direccion" placeholder="<?php echo $institucion['direccion']?>" maxlength="100" required>
                        </div>
                        <div>
                            <label for="cue">C.U.E: </label> <br>
                            <input type="number" id="cue_editar" name="cue" placeholder="<?php echo $institucion['cue']?>" maxlength="9" required>
                        </div>
                    </div> 
                    <input type="hidden" name="id_editar" id="input_editar">
                    <button type="button" id="boton_editar" name="boton_editar" onclick="Guardar_Institucion()">Editar</button>
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
<script src=".\..\..\Recursos\JS\Funciones_instituciones.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>