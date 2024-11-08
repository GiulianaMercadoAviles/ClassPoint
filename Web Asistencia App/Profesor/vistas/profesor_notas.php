<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Institucion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Profesor.php';

$database = new Database();
$conexion = $database->connect();

session_start(); 

$id_materia = $_SESSION['materia'];
$id_profesor = $_SESSION['usuario'];

if (!empty($id_materia)) {
        
    // Obtener el nombre de la materia
    $consulta_materias = Materia::Buscar_materia($conexion, $id_materia);
    $consulta_materias->execute();
    // Obtener los alumnos
    $consulta_alumnos = Materia::Obtener_alumnos($conexion, $id_materia);
    $consulta_alumnos->bindParam(':materia_id', $id_materia);
    $consulta_alumnos->execute();
    $alumnos = $consulta_alumnos->fetchAll(PDO::FETCH_ASSOC);
}   

$profesor = Profesor::datos_profesor($conexion, $id_profesor);
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href=".\..\..\Recursos\CSS\styles_notas.css">
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
                    <a href="profesor_espacio_curricular.php"><span>Espacio Curricular</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">groups</span>
                    <a href="profesor_alumnos.php"><span>Alumnos</span></a>
                </li>
                <li>
                    <span class="material-symbols-outlined">text_increase</span>
                    <a href="#"><span>Notas</span></a>
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
                <h1>Notas</h1>
            </div>
            <nav>
                <button type="button" id="boton_ingresar_nota">
                    <span>Calificar</span>
                </button>
                <button type="button" id="boton_lista_notas">
                    <span>Listado de Calificaciones</span>
                </button>
            </nav>
            <div class="lista_notas" id="lista_notas"> 
                <div class="lista_div_header">
                    <div class="div1">
                        <p>Nombre</p>
                    </div>
                    <div class="div2">
                        <p>Parcial 1</p>
                    </div>
                    <div class="div3">
                        <p>Parcial 2</p>
                    </div>
                    <div class="div4">
                        <p>Final</p>
                    </div>
                    <div class="div5">
                        <p>Acciones</p>
                    </div>
                    </div>
                        <div class="linea"></div>
                        <div class="lista_div">
                    <?php

                    foreach ($alumnos as $alumno) {
                        
                        $id_alumno = $alumno['id'];

                        $promedio = Alumno::calcular_promedio($conexion, $id_alumno, $id_materia);

                        echo '
                        <div class="div1">
                            <p>' . $alumno['nombre'] .' '. $alumno['apellido'] . '</p>
                        </div>
                        <div class="div2">
                            <p>' . $alumno['parcial_1'] .'</p>
                        </div>
                        <div class="div3">
                            <p>'. $alumno['parcial_2'] . '</p>
                        </div>
                        <div class="div4">
                            <p>' . $alumno['final'] . '</p>
                        </div>
                        <div>
                            <button type="button" id="editar_nota" class="editar_nota" value="' . $id_alumno . '" onclick="Editar_nota(' . $id_alumno .')">
                                <span class="material-symbols-outlined">edit_square</span>
                            </button>
                        </div>
                        <div>    
                            <button type="button" id="eliminar_nota" class="eliminar_nota" value="' . $id_alumno . '" onclick="Eliminar_nota(' . $id_alumno .')">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </div>';
                    }
                    ?></div>
                </div>
            </div>
            <div class="ingresar_notas" id="ingresar_notas"  style="display: none">
            <h3>Calificar</h3>   
                <form class="form_notas" id="form_notas" action=".\..\controlador\ingresar_nota.php" method="post"> 

                    <select name="instancia_notas" id="instancia_notas" required>
                        <option value="">Seleccionar instancia de evaluación</option>
                        <option value="parcial_1">Parcial 1</option>
                        <option value="parcial_2">Parcial 2</option>
                        <option value="final">Final</option>
                    </select>

                    <div class="div_calificar_header">
                        <div class="div_calificar1">
                            <p>Nombre</p>
                        </div>
                        <div class="div_calificar2">
                            <p>Calificar</p>
                        </div>
                    </div>
                    <div class="linea"></div>
                    <div class="div_calificar">
                        <?php

                        foreach ($alumnos as $alumno) {
                            $id_alumno = $alumno['id'];
                            
                            echo '
                            <div class="div_calificar1">
                                <p>' . $alumno['nombre'] .' '. $alumno['apellido'] . '</p>
                                </div>
                            <div class="div_calificar2">
                                <input type="number" id="input_nota" name="notas[' . $alumno['id'] . ']" id="' . $alumno['id'] . '" min="1" max="10" step="0.01">
                            </div>';
                        }
                    ?>
                    </div>
                    <button type="button" id="guardar_notas" onclick="Guardar_notas()">Guardar Calificaciones</button>
                </form> 
            </div> 
            <div class="div_editar_nota" id="div_editar_nota" style="display: none;">
                <h3>Editar Nota</h3>
                <div class="linea"></div>   
                <form class="form_editar_notas" action=".\..\controlador\editar_nota.php" method="post"> 
                <div class="form">  
                    <div>
                        <label for="instancia_notas"><b>Seleccione la instancia de evalucación</b></label><br>
                        <select name="instancia" id="instancia" required>
                            <option value="">Seleccionar instancia de evaluación</option>
                            <option value="parcial_1">Parcial 1</option>
                            <option value="parcial_2">Parcial 2</option>
                            <option value="final">Final</option>
                        </select>
                    </div>
                    <div>
                        <label for="nota"><b>Nueva Nota</b></label><br>
                        <input type="number" name="nota" id="input_nota_editar" required min="1" max="10" step="0.01">
                    </div>
                    <div>
                        <input type="hidden" name="id_editar" id="input_editar">
                    </div>
                    </div> 
                    <button onclick="Guardar_edicion()" class="guardar">Guardar</button>
                </form>
            </div>     

            <div class="div_eliminar_nota" id="div_eliminar_nota" style="display: none;">
                <h3>Eliminar Nota</h3>
                <div class="linea"></div>   
                <form id="form_eliminar_notas" action=".\..\controlador\eliminar_nota.php" method="post">
                    <div class="form">   
                        <div>
                            <label for="instancia_notas"><b>Seleccione la instancia de evalucación</b></label><br>
                            <select name="instancia" id="instancia" required>
                                <option value="">Seleccionar instancia de evaluación</option>
                                <option value="parcial_1">Parcial 1</option>
                                <option value="parcial_2">Parcial 2</option>
                                <option value="final">Final</option>
                            </select>
                        </div>
                        <div>
                            <input type="hidden" name="id_eliminar" id="input_eliminar">
                        </div>
                    </div>
                    <button type="button" class="boton_eliminar" id="boton_eliminar" onclick="Eliminacion()">Guardar</button>
                </form>
            </div>     
        </section>
    </main>
</body>
<script src=".\..\..\Recursos\JS\Funciones_notas.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
