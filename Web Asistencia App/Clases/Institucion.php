<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';
class Institucion {

use Web_Asistencia_App\Obtener_datos; // obtener datos
use Web_Asistencia_App\Validar_datos; // Validar que los datos esten completos
    
    protected $nombre;
    protected $direccion;
    protected $cue;

    public function __construct($nombre, $direccion, $cue) {
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->cue = $cue;
    }

    public function crear_institucion($conexion) {
        $nombre = $this->nombre;
        $direccion = $this->direccion;
        $cue = $this->cue;

        $consulta = "INSERT INTO instituciones (nombre, direccion, cue)
        VALUES (:nombre, :direccion, :cue);";

        $consulta_institucion = $conexion->prepare($consulta);

        $consulta_institucion->bindParam(":nombre", $nombre);
        $consulta_institucion->bindParam(":direccion", $direccion);
        $consulta_institucion->bindParam(":cue", $cue);

        if ($consulta_institucion->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function editar_institucion($conexion, $id, $nombre, $direccion, $cue) {
    
        $consulta = $conexion->prepare("UPDATE instituciones SET nombre = :nombre, direccion = :direccion, cue = :cue WHERE instituciones.id = :id"); 
    
        // Vincula los parámetros
        $consulta->bindParam(":id", $id);
        $consulta->bindParam(":nombre", $nombre);
        $consulta->bindParam(":direccion", $direccion);
        $consulta->bindParam(":cue", $cue);

        $consulta->execute();
    }

    public static function eliminar_institucion($conexion, $id_eliminar) {
        
        $consulta = $conexion->prepare("DELETE FROM instituciones WHERE id = :id_eliminar");
        $consulta->bindParam("id_eliminar", $id_eliminar);
        
        $consulta->execute();
    }

    public static function Buscar_instituciones($conexion) {

        $consulta_instituciones = $conexion->prepare("SELECT * FROM instituciones"); 
        $institucion = $consulta_instituciones->execute();

        return $institucion;
    }

    public static function obtener_institucion($conexion, $id_institucion) {

        $consulta = $conexion->prepare("SELECT * FROM instituciones WHERE id = :id_institucion");
        $consulta->bindParam("id_institucion", $id_institucion); 
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtener_Ram($conexion, $instituciones_id) {

        $consulta_ram = $conexion->prepare("SELECT * FROM ram WHERE ram.institucion_id = :instituciones_id"); 
        $consulta_ram->bindParam(":instituciones_id", $instituciones_id);
        
        return $consulta_ram;
    }

    public static function Ram($conexion, $instituciones_id, $nota_promocion, $asistencia_promocion, $nota_regular, $asistencia_regular) {

        $consulta_ram = $conexion->prepare("INSERT INTO ram (nota_regular, nota_promocion, asistencia_regular, asistencia_promocion, institucion_id) VALUES (:nota_regular, :nota_promocion, :asistencia_regular, :asistencia_promocion, :instituciones_id)"); 

        $consulta_ram->bindParam(":instituciones_id", $instituciones_id);
        $consulta_ram->bindParam(":nota_promocion", $nota_promocion);
        $consulta_ram->bindParam(":asistencia_promocion", $asistencia_promocion);
        $consulta_ram->bindParam(":nota_regular", $nota_regular);
        $consulta_ram->bindParam(":asistencia_regular", $asistencia_regular);
        
        $consulta_ram->execute();
    }

    public static function modificar_Ram($conexion, $instituciones_id, $nota_promocion, $asistencia_promocion, $nota_regular, $asistencia_regular) {

        $consulta_ram = $conexion->prepare("UPDATE ram SET ram.nota_promocion = :nota_promocion,
        ram.asistencia_promocion = :asistencia_promocion, ram.nota_regular = :nota_regular, ram.asistencia_regular = :asistencia_regular WHERE ram.institucion_id = :instituciones_id"); 

        $consulta_ram->bindParam(":instituciones_id", $instituciones_id);
        $consulta_ram->bindParam(":nota_promocion", $nota_promocion);
        $consulta_ram->bindParam(":asistencia_promocion", $asistencia_promocion);
        $consulta_ram->bindParam(":nota_regular", $nota_regular);
        $consulta_ram->bindParam(":asistencia_regular", $asistencia_regular);
        
        $consulta_ram->execute();
    }  
    
    public static function obtener_cantidad_materias($conexion, $id_institucion) {
    
        $consulta_materias = $conexion->prepare("SELECT count(DISTINCT materias.id) as total_materias FROM instituciones INNER JOIN materias ON instituciones.id = materias.instituciones_id WHERE instituciones.id = :instituciones_id");
        $consulta_materias->bindParam(":instituciones_id", $id_institucion);
        

        return $consulta_materias;
    }

   public static function obtener_cantidad_alumnos($conexion, $id_institucion) {
    
        $consulta_alumnos = $conexion->prepare("SELECT count(DISTINCT notas.alumno_id) as total_alumnos FROM notas INNER JOIN materias ON notas.materia_id = materias.id WHERE materias.instituciones_id = :instituciones_id");
        $consulta_alumnos->bindParam(":instituciones_id", $id_institucion);
        
        return $consulta_alumnos;
    }

    public static function obtener_cantidad_profesores($conexion, $id_institucion) {
    
        $consulta_profesor = $conexion->prepare("SELECT count(DISTINCT profesor_institucion.profesor_id) as total_profesores FROM instituciones INNER JOIN profesor_institucion ON instituciones.id = profesor_institucion.institucion_id WHERE instituciones.id = :instituciones_id");
        $consulta_profesor->bindParam(":instituciones_id", $id_institucion);

        return $consulta_profesor;
    }

    public static function obtener_materias_profesor($conexion, $id_institucion, $id_profesor) {
    
        $consulta_materias = $conexion->prepare("SELECT DISTINCT materias.id as materia_id, materias.nombre as materia_nombre, materias.departamento, materias.curso FROM (materias INNER JOIN profesor_institucion ON materias.id = profesor_institucion.materia_id) INNER JOIN profesores ON profesores.id = profesor_institucion.profesor_id WHERE materias.instituciones_id = :institucion_id AND profesor_institucion.profesor_id = :profesor_id");
        $consulta_materias->bindParam(':institucion_id', $id_institucion);
        $consulta_materias->bindParam(':profesor_id', $id_profesor);

        $consulta_materias->execute();

        return $consulta_materias->fetchAll(PDO::FETCH_ASSOC);
    }
}