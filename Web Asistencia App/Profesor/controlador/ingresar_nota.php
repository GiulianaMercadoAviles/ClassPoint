<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Materia.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Alumno.php';

$database = new Database();
$conexion = $database->connect();

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['instancia_notas']) && isset($_POST['notas'])) {

        $instancia = $_POST['instancia_notas'];
        $notas_alumnos = $_POST['notas'];var_dump($instancia, $notas_alumnos);
    
        try {
            foreach ($notas_alumnos as $id_alumno => $nota_alumno) {
                
                $consulta = $conexion->prepare("SELECT notas.parcial_1, notas.parcial_2, notas.final FROM notas WHERE notas.alumno_id = :id_alumno");
                $consulta->bindParam(':id_alumno', $id_alumno);
                $consulta->execute();

                

                while ($instancias = $consulta->fetch(PDO::FETCH_ASSOC)) {

foreach ($instancias as $instancia_nota => $nota) {

                    if ($instancia == $instancia_nota) {
                        if ($nota == null) {
                            
                            $nota_alumno = floatval($nota_alumno);
                            Materia::ingresar_nota($conexion, $instancia, $id_alumno, $nota_alumno);   
                        } 
                    }
                }
                }
                
            }
            
            header('Location: .\..\vistas\profesor_notas.php');
            exit();
        } catch (PDOException $e) {
            echo "Error de conexión: " . $e->getMessage();
        }
    } else {
        echo 'No se han ingresado las notas.';
    }
}