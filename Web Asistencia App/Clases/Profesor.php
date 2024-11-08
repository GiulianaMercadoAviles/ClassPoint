<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/conexion.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Clases/Usuario.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';


class Profesor { 
 
use Web_Asistencia_App\Obtener_datos; // obtener datos
use Web_Asistencia_App\Validar_datos; // Validar que los datos esten completos

    protected $nombre;
    protected $apellido;
    protected $dni;
    protected $email;
    protected $fecha_nacimiento;
    protected $legajo;

    public function __construct($nombre, $apellido, $dni,  $email, $fecha_nacimiento, $legajo) {

        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->email = $email; 
        $this->legajo = $legajo;
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function crear_profesor($conexion) {
        $nombre = $this->nombre;
        $apellido = $this->apellido;
        $dni = $this->dni;
        $email = $this->email;
        $legajo = $this->legajo;
        $fecha_nacimiento = $this->fecha_nacimiento;

        $consulta = $conexion->prepare("INSERT INTO profesores (nombre, apellido, dni, email, fecha_nacimiento, legajo) VALUES (:nombre, :apellido, :dni, :email, :fecha_nacimiento, :legajo)");

        $consulta->bindParam(':nombre', $nombre);
        $consulta->bindParam(':apellido', $apellido);
        $consulta->bindParam(':dni', $dni);
        $consulta->bindParam(':email', $email);
        $consulta->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $consulta->bindParam(':legajo', $legajo);

        if ($consulta->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function datos_profesor($conexion, $id_profesor) {

        $datos_profesor = $conexion->prepare("SELECT * FROM profesores WHERE profesores.id = :id_profesor");
        $datos_profesor->bindParam(':id_profesor' ,$id_profesor);
        $datos_profesor->execute();

        return $datos_profesor->fetch(PDO::FETCH_ASSOC);
    }

    public static function editar_profesor($conexion, $id, $nombre, $apellido, $dni,  $email,  $fecha_nacimiento, $legajo) {
    
        $consulta = $conexion->prepare("UPDATE profesores SET nombre = :nombre, apellido = :apellido, dni = :dni, email = :email, fecha_nacimiento = :fecha_nacimiento WHERE profesores.id = :id"); 
    
        // Vincula los parámetros
        $consulta->bindParam(":id", $id);
        $consulta->bindParam(":nombre", $nombre);
        $consulta->bindParam(":apellido", $apellido);
        $consulta->bindParam(":dni", $dni);
        $consulta->bindParam(":email", $email);
        $consulta->bindParam(":fecha_nacimiento", $fecha_nacimiento);

        $consulta->execute();
    }
    public static function eliminar_profesor($conexion, $id_eliminar) {
        
        $id = $conexion->prepare("SELECT usuarios.id FROM usuarios INNER JOIN profesores ON usuarios.id = profesores.usuario_id WHERE profesores.id = :id_eliminar");
        $id->bindParam("id_eliminar", $id_eliminar);

        $id = $id->fetchColumn();

        if ($id) {
        
            $consulta = $conexion->prepare("DELETE FROM usuarios WHERE id = :id_eliminar");
            $consulta->bindParam("id_eliminar", $id);
        
            $consulta->execute();
        }

        $consulta = $conexion->prepare("DELETE FROM profesores WHERE id = :id_eliminar");
        $consulta->bindParam("id_eliminar", $id_eliminar);
        
        $consulta->execute();
   }

    public static function obtener_institucion($conexion, $profesor_id) {
        
        $consulta = $conexion->prepare("SELECT DISTINCT id, nombre, direccion, cue FROM profesor_institucion INNER JOIN instituciones ON instituciones.id = profesor_institucion.institucion_id WHERE profesor_institucion.profesor_id = :profesor_id");

        $consulta->bindParam(':profesor_id', $profesor_id);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtener_materias($conexion, $institucion_id, $profesor_id) {
        
        $consulta = $conexion->prepare("SELECT DISTINCT materias.id as materia_id, nombre, departamento, curso, profesor_institucion.profesor_id as profesor_id, profesor_institucion.institucion_id as institucion_id FROM profesor_institucion INNER JOIN materias ON materias.id = profesor_institucion.materia_id WHERE profesor_institucion.profesor_id = :profesor_id AND profesor_institucion.institucion_id = :institucion_id");

        $consulta->bindParam(':profesor_id', $profesor_id);
        $consulta->bindParam(':institucion_id', $institucion_id);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtener_materias_asignar($conexion, $id_institucion, $id_profesor) {
        
        $consulta_materia = $conexion->prepare("SELECT materias.* FROM materias LEFT JOIN profesor_institucion ON materias.id = profesor_institucion.materia_id AND profesor_institucion.profesor_id = :id_profesor WHERE profesor_institucion.materia_id IS NULL AND materias.instituciones_id = :id_institucion;");
        $consulta_materia->bindParam(':id_profesor' ,$id_profesor);
        $consulta_materia->bindParam(':id_institucion' ,$id_institucion);
        $consulta_materia->execute();
        
        return $consulta_materia->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtener_profesor($conexion, $profesor_id, $institucion_id) {
        
        $consulta = $conexion->prepare("SELECT DISTINCT * FROM profesores INNER JOIN profesor_institucion ON profesores.id = profesor_institucion.profesor_id WHERE profesor_institucion.profesor_id = :profesor_id AND profesor_institucion.institucion_id = :institucion_id");

        $consulta->bindParam(':profesor_id', $profesor_id);
        $consulta->bindParam(':institucion_id', $institucion_id);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function asignar_institucion($conexion, $dni, $id_institucion) {
        
        $consulta_id = $conexion->prepare("SELECT id FROM profesores WHERE dni = :dni");
        $consulta_id->bindParam(':dni', $dni);

        $consulta_id->execute();
        $profesor_id = $consulta_id->fetchColumn();

        $materia_id = null;

        $consulta = $conexion->prepare("INSERT INTO profesor_institucion (profesor_id, institucion_id, materia_id) VALUES (:profesor_id, :institucion_id, :materia_id)");

        $consulta->bindParam(':profesor_id', $profesor_id);
        $consulta->bindParam(':institucion_id', $id_institucion);
        $consulta->bindParam(':materia_id', $materia_id);

        $consulta->execute();
    }

    public static function asignar_materia($conexion, $profesor_id, $institucion_id, $materia_id) {
        
        $consulta = $conexion->prepare("SELECT COUNT(*) FROM profesor_institucion WHERE profesor_id = :profesor_id AND institucion_id = :institucion_id AND materia_id = :materia_id");
        $consulta->bindParam(':profesor_id', $profesor_id);
        $consulta->bindParam(':institucion_id', $institucion_id);
        $consulta->bindParam(':materia_id', $materia_id);
        $consulta->execute();

        if ($consulta->fetchColumn() === 0) {
        
            $consulta = $conexion->prepare("SELECT * FROM profesor_institucion WHERE profesor_id = :profesor_id AND institucion_id = :institucion_id AND materia_id IS NULL");
            $consulta->bindParam(':institucion_id', $institucion_id);
            $consulta->bindParam(':profesor_id', $profesor_id);
            $consulta->execute();
    
            $asignacion = $consulta->fetch(PDO::FETCH_ASSOC);
    
            if ($asignacion === false) {
                    
                $consulta_asignar = $conexion->prepare("INSERT INTO profesor_institucion (profesor_id, institucion_id, materia_id) VALUES (:profesor_id, :institucion_id, :materia_id)");
            } else {
                
                $consulta_asignar = $conexion->prepare("UPDATE profesor_institucion SET materia_id = :materia_id WHERE profesor_id = :profesor_id AND institucion_id = :institucion_id AND materia_id IS NULL");
            }
    
            $consulta_asignar->bindParam(':profesor_id', $profesor_id);
            $consulta_asignar->bindParam(':institucion_id', $institucion_id);
            $consulta_asignar->bindParam(':materia_id', $materia_id);
            $consulta_asignar->execute();

            return "Profesor asignado con éxito";

        } else {
    
            return "El profesor ya está asignado a esta materia en esta institución."; 
        }
    }

    public static function desasignar_materia($conexion, $profesor_id, $institucion_id, $materia_id) {

        $consulta_desasignar = $conexion->prepare("DELETE FROM profesor_institucion WHERE profesor_id = :profesor_id AND institucion_id = :institucion_id AND materia_id = :materia_id");

        $consulta_desasignar->bindParam(':profesor_id', $profesor_id);
        $consulta_desasignar->bindParam(':institucion_id', $institucion_id);
        $consulta_desasignar->bindParam(':materia_id', $materia_id);
            
        if ($consulta_desasignar->execute()) {
            return true;
        } else {
            return false;
        }
    }
}