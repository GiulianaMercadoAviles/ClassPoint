<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';

class Materia {

use Web_Asistencia_App\Obtener_datos; // obtener datos
use Web_Asistencia_App\Validar_datos; // Validar que los datos esten completos

    protected $nombre;
    protected $id_institucion;
    protected $departamento;
    protected $curso;
    public function __construct($nombre, $id_institucion, $departamento, $curso) {
        $this->nombre = $nombre;
        $this->id_institucion = $id_institucion;
        $this->departamento = $departamento;
        $this->curso = $curso;
    }

    public function crear_materia($conexion) {
        $nombre = $this->nombre;
        $id_institucion = $this->id_institucion;
        $departamento = $this->departamento;
        $curso = $this->curso;

        $consulta = $conexion->prepare("INSERT INTO materias (nombre, instituciones_id, departamento, curso)
        VALUES (:nombre, :institucion_id, :departamento, :curso)");

        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam('institucion_id', $id_institucion);
        $consulta->bindParam(':departamento', $departamento);
        $consulta->bindParam('curso', $curso);

        $consulta->execute();
    }  

    public function ingresar_materia($conexion) {
        $nombre = $this->nombre;
        $id_institucion = $this->id_institucion;
        $departamento = $this->departamento;
        $curso = $this->curso;

        $consulta = $conexion->prepare("INSERT INTO materias (nombre, instituciones_id, departamento, curso)
        VALUES (:nombre, :institucion_id, :departamento, :curso)");

        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam('institucion_id', $id_institucion);
        $consulta->bindParam(':departamento', $departamento);
        $consulta->bindParam('curso', $curso);

        $consulta->execute();
    }

    public static function Buscar_materia($conexion, $id_materia) {
    
        $consulta_materia = $conexion->prepare("SELECT DISTINCT materias.nombre FROM materias WHERE materias.id = :materia_id");
        $consulta_materia->bindParam(':materia_id', $id_materia);

        return $consulta_materia;
    }

    public static function eliminar_materia($conexion, $id_eliminar) {
        
        $consulta = $conexion->prepare("DELETE FROM materias WHERE id = :id_eliminar");
        $consulta->bindParam("id_eliminar", $id_eliminar);
        
        $consulta->execute();
    }

    public static function editar_materia($conexion, $id, $nombre, $departamento, $curso){
        
        $consulta = $conexion->prepare("UPDATE materias SET nombre = :nombre, departamento = :departamento, curso = :curso WHERE materias.id = :id"); 
    
        $consulta->bindParam(':id', $id);
        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':departamento', $departamento);
        $consulta->bindParam(':curso', $curso);
    
        $consulta->execute();
    }

    public static function Obtener_materias($conexion, $id_institucion) {
    
        $consulta_alumnos = $conexion->prepare("SELECT DISTINCT * FROM materias WHERE materias.instituciones_id = :id_institucion");
        $consulta_alumnos->bindParam(':id_institucion', $id_institucion);

        return $consulta_alumnos;
    }
    public static function Obtener_alumnos($conexion, $id_materia) {
    
        $consulta_alumnos = $conexion->prepare("SELECT DISTINCT alumnos.id, alumnos.nombre, alumnos.dni, alumnos.fecha_nacimiento, alumnos.apellido, parcial_1, parcial_2, final  FROM (materia_alumno LEFT JOIN alumnos ON materia_alumno.alumno_id = alumnos.id) LEFT JOIN notas ON notas.alumno_id = alumnos.id WHERE materia_alumno.materia_id = :materia_id");
        $consulta_alumnos->bindParam(':materia_id', $id_materia);

        return $consulta_alumnos;
    }

    public static function listar_asistencia($conexion, $id_alumno, $id_materia, $fecha, $estado) {
         
        $consulta_asistencia = $conexion->prepare("INSERT INTO asistencias (alumno_id, materia_id, fecha, asistencia) VALUES (:alumno_id, :materia_id, :fecha, :estado)");
                         
        $consulta_asistencia->bindParam(':alumno_id', $id_alumno);
        $consulta_asistencia->bindParam(':materia_id', $id_materia);
        $consulta_asistencia->bindParam(':fecha', $fecha);
        $consulta_asistencia->bindParam(':estado', $estado);
                     
        if ($consulta_asistencia->execute()) {
            return true;
        } else {
            return false;
        } 
    }

    public static function modificar_asistencia($conexion, $id_alumno, $id_materia, $fecha, $estado) {
          
        $consulta_asistencia = $conexion->prepare("UPDATE asistencias SET asistencia = :estado WHERE asistencias.alumno_id = :alumno_id AND asistencias.materia_id = :materia_id AND asistencias.fecha = :fecha");
                         
        $consulta_asistencia->bindParam(':alumno_id', $id_alumno);
        $consulta_asistencia->bindParam(':materia_id', $id_materia);
        $consulta_asistencia->bindParam(':fecha', $fecha);
        $consulta_asistencia->bindParam(':estado', $estado);
                     
        if ($consulta_asistencia->execute()) {
            return true;
        } else {
            return false;
        } 
    }
 
    public static function ingresar_nota($conexion, $instancia_nota, $id_alumno, $nota) {
         
        if ($nota == null) {
            $nota = null;
        }

        $consulta_notas = $conexion->prepare("UPDATE notas SET $instancia_nota = :nota WHERE alumno_id = :alumno_id");
                          
        $consulta_notas->bindParam(':alumno_id', $id_alumno);
        $consulta_notas->bindParam(':nota', $nota);
                      
        if ($consulta_notas->execute()) {
            return true;
        } else {
            return false;
        } 
    }

    public static function alumnos_presentes($conexion, $id_materia, $dia_actual) {

        $consulta_presentes = $conexion->prepare("SELECT * FROM asistencias INNER JOIN alumnos on asistencias.alumno_id = alumnos.id WHERE asistencias.fecha = :fecha AND asistencias.materia_id = :id_materia AND asistencia != 0");
                        
        $consulta_presentes->bindParam(':id_materia', $id_materia);        
        $consulta_presentes->bindParam(':fecha', $dia_actual);
        $consulta_presentes->execute(); 

        return $consulta_presentes->fetchall(PDO::FETCH_ASSOC);
    }
    public static function alumnos_ausentes($conexion, $id_materia, $dia_actual) {

        $consulta_ausentes = $conexion->prepare("SELECT DISTINCT alumnos.nombre, alumnos.apellido, alumnos.id, alumnos.dni, alumnos.fecha_nacimiento FROM alumnos INNER JOIN asistencias ON alumnos.id = asistencias.alumno_id WHERE (asistencias.materia_id = :id_materia AND alumnos.id NOT IN (SELECT alumnos.id FROM asistencias INNER JOIN alumnos ON asistencias.alumno_id = alumnos.id WHERE asistencias.fecha = :fecha AND asistencias.materia_id = :id_materia)) OR (asistencias.materia_id = :id_materia AND asistencia = 0 AND fecha = :fecha)");

        $consulta_ausentes->bindParam(':id_materia', $id_materia);
        $consulta_ausentes->bindParam(':fecha', $dia_actual);
        $consulta_ausentes->execute();

        return $consulta_ausentes->fetchall(PDO::FETCH_ASSOC);
    }

    public static function obtener_asistencia($conexion, $fecha, $id_materia) {

        $consulta_asistencia = $conexion->prepare("SELECT * FROM asistencias WHERE asistencias.fecha = :fecha AND asistencias.materia_id = :id_materia");

        $consulta_asistencia->bindParam(":fecha", $fecha);
        $consulta_asistencia->bindParam(":id_materia", $id_materia);

        $consulta_asistencia->execute();

        return $consulta_asistencia->fetchall(PDO::FETCH_ASSOC);
    }
}