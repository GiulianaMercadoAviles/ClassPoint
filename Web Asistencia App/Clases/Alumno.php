<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/Web Asistencia App/Traits/Validacion.php';

class Alumno {

    use Web_Asistencia_App\Obtener_datos;
    use Web_Asistencia_App\Validar_datos;

    protected $nombre;
    protected $apellido;
    protected $dni;
    protected $fecha_nacimiento;

    public function __construct($nombre, $apellido, $dni, $fecha_nacimiento) {

        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->fecha_nacimiento = $fecha_nacimiento;
    }

    public function crear_alumno($conexion) {
        $nombre = $this->nombre;
        $apellido = $this->apellido;
        $dni = $this->dni;
        $fecha_nacimiento = $this->fecha_nacimiento;

        $consulta = "INSERT INTO alumnos (nombre, apellido, dni, fecha_nacimiento) 
                     VALUES (:nombre, :apellido, :dni, :fecha_nacimiento)";
        
        $stmt = $conexion->prepare($consulta);
         
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':dni', $dni);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function datos_alumnos($conexion, $id_alumno) {

        $datos_alumno = $conexion->prepare("SELECT * FROM alumnos WHERE alumnos.id = :id_alumno");
        $datos_alumno->bindParam(':id_alumno', $id_alumno);
        $datos_alumno->execute();

        return $datos_alumno->fetch(PDO::FETCH_ASSOC);
    }

    public static function matricular_alumno($conexion, $id_materia, $dni) {

        // Obtiene el id del alumno que tenga ese dni
        $id = $conexion->query("SELECT alumnos.id FROM alumnos WHERE alumnos.dni = '$dni'"); 
        
        while ($valor = $id->fetch()) { // $valor almacena los resultados de fetch 
            $id_alumno = $valor["id"];
        }

        $consulta = $conexion->prepare("INSERT INTO materia_alumno (alumno_id, materia_id)
        VALUES (:alumno_id, :materia_id);");
                
        $consulta->bindParam(':alumno_id', $id_alumno);
        $consulta->bindParam(':materia_id', $id_materia);
    
        $consulta_notas = $conexion->prepare("INSERT INTO notas (alumno_id, materia_id)
        VALUES (:alumno_id, :materia_id);");
                
        $consulta_notas->bindParam(':alumno_id', $id_alumno);
        $consulta_notas->bindParam(':materia_id', $id_materia);

        $consulta->execute();
        $consulta_notas->execute();
    }

    public static function editar_alumno($conexion, $id, $nombre, $apellido, $dni, $fecha_nacimiento) {
    
        $consulta = $conexion->prepare("UPDATE alumnos SET nombre = :nombre, apellido = :apellido, dni = :dni, fecha_nacimiento = :fecha_nacimiento WHERE alumnos.id = :id"); 
    
        // Vincula los parámetros
        $consulta->bindParam(":id", $id);
        $consulta->bindParam(":nombre", $nombre);
        $consulta->bindParam(":apellido", $apellido);
        $consulta->bindParam(":dni", $dni);
        $consulta->bindParam(":fecha_nacimiento", $fecha_nacimiento);

        $consulta->execute();
    }

    public static function eliminar_alumnos($conexion, $id_eliminar) {

        $consulta = $conexion->prepare("DELETE FROM alumnos WHERE alumnos.id = :id_eliminar");
        $consulta->bindParam(":id_eliminar", $id_eliminar);
        
        $consulta->execute();
    }
    public static function desinscribir_alumnos($conexion, $id_eliminar, $id_materia) {
        
        $consulta_notas = $conexion->prepare("DELETE FROM notas WHERE notas.alumno_id = :id_eliminar AND notas.materia_id = :id_materia");
        $consulta_notas->bindParam(":id_eliminar", $id_eliminar);
        $consulta_notas->bindParam(":id_materia", $id_materia);
        
        $consulta_notas->execute();

        $consulta = $conexion->prepare("DELETE FROM materia_alumno WHERE materia_alumno.alumno_id = :id_eliminar AND materia_alumno.materia_id = :id_materia");
        $consulta->bindParam(":id_eliminar", $id_eliminar);
        $consulta->bindParam(":id_materia", $id_materia);
        
        $consulta->execute();
    }

   public static function calcular_asistencia($conexion, $id_alumno, $id_materia) {

        $consulta_dias = $conexion->prepare("SELECT COUNT(DISTINCT fecha) as total_dias FROM asistencias WHERE materia_id = :id_materia");
        $consulta_dias->bindParam(":id_materia", $id_materia);
        $consulta_dias->execute();
        $resultado_dias = $consulta_dias->fetch(PDO::FETCH_ASSOC);
        
        $dias_clases = $resultado_dias['total_dias'];

        $consulta = $conexion->prepare("SELECT SUM(asistencia) as total_asistencias FROM asistencias WHERE alumno_id = :id_alumno AND materia_id = :id_materia");
        $consulta->bindParam(":id_alumno", $id_alumno);
        $consulta->bindParam(":id_materia", $id_materia);
        
        $consulta->execute();
        $resultado_asistencias = $consulta->fetch(PDO::FETCH_ASSOC);
        
        $asistencia = $resultado_asistencias['total_asistencias'];

        if ($dias_clases > 0) {
            $asistencia_porcentaje = (100 * $asistencia) / $dias_clases;
        } else {
            $asistencia_porcentaje = 0;
        }

        return round($asistencia_porcentaje,2);
    }

    public static function calcular_promedio($conexion, $id_alumno, $id_materia) {

        $consulta_notas = $conexion->prepare("SELECT parcial_1, parcial_2, final FROM notas WHERE notas.alumno_id = :id_alumno AND notas.materia_id = :id_materia");
        $consulta_notas->bindParam("id_alumno", $id_alumno);
        $consulta_notas->bindParam("id_materia", $id_materia);
        $consulta_notas->execute();

        $notas = $consulta_notas->fetch(PDO::FETCH_ASSOC);
        
        if ($notas) {
            $nota1 = $notas['parcial_1'];
            $nota2 = $notas['parcial_2'];
            $final = $notas['final'];

            if ($notas === false || in_array(null, array_values($notas))) {
                return "Faltan Datos";
                } else {
    
                $nota1 = $notas['parcial_1'];
                $nota2 = $notas['parcial_2'];
                $final = $notas['final'];
                
                $promedio = ($nota1 + $nota2 + $final) / 3;
    
                return round($promedio, 2);
            }  
    }}

    public static function estado($conexion, $id_alumno, $id_instituciones, $asistencia, $id_materia) {
        
        $consulta_notas = $conexion->prepare("SELECT parcial_1, parcial_2, final FROM notas WHERE notas.alumno_id = :id_alumno AND notas.materia_id = :id_materia");
        $consulta_notas->bindParam("id_alumno", $id_alumno);
        $consulta_notas->bindParam("id_materia", $id_materia);
        $consulta_notas->execute();

        $consulta_notas->execute();
        $notas = $consulta_notas->fetch(PDO::FETCH_ASSOC);

        $consulta_ram = Institucion::obtener_Ram($conexion, $id_instituciones);
        $ram = $consulta_ram->execute();
        $ram = $consulta_ram->fetch(PDO::FETCH_ASSOC);

        if ($notas === false || in_array(null, array_values($notas))) {
        return "Faltan Datos";
        } else {
            
            $nota1 = $notas['parcial_1'];
            $nota2 = $notas['parcial_2'];
            $final = $notas['final'];
            
            if ($nota1 >= 7 && $nota2 >= 7 && $final >= 7) {
                $estado = "Promoción";
            } elseif ($nota1 >= 6 && $nota2 >= 6 && $final >= 6) {
                $estado = "Regular";
            } else {
                $estado = "Libre";
            }

            if ($estado == 'Promoción' && $asistencia < $ram['asistencia_regular']) {
                $estado = 'Libre'; 
            } else if ($estado == 'Promocion' && $asistencia < $ram['asistencia_promocion']) {
                $estado = 'Regular'; 
            } else if ($estado == 'Regular' && $asistencia < $ram['asistencia_regular']) {
                $estado = 'Libre'; 
            }

            return $estado;
        }  
    }

    public static function editar_nota($conexion, $instancia_nota, $id_alumno, $nota) {

        $consulta_notas = $conexion->prepare("UPDATE notas SET $instancia_nota = :nota WHERE alumno_id = :alumno_id");
                          
        $consulta_notas->bindParam(':alumno_id', $id_alumno);
        $consulta_notas->bindParam(':nota', $nota);
                      
        if ($consulta_notas->execute()) {
            return true;
        } else {
            return false;
        } 
    }

    public static function obtener_materias($conexion, $institucion_id, $alumno_id) {
        
        $consulta = $conexion->prepare("SELECT * FROM (alumnos INNER JOIN materia_alumno ON materia_alumno.alumno_id = alumnos.id) INNER JOIN materias on materias.id = materia_alumno.materia_id WHERE materias.instituciones_id = :institucion_id AND materia_alumno.alumno_id = :alumno_id");

        $consulta->bindParam(':alumno_id', $alumno_id);
        $consulta->bindParam(':institucion_id', $institucion_id);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function feliz_cumpleanos($conexion, $id_alumnos) {

        $fecha_hoy = date('m-d');
        
        $consulta = $conexion->prepare("SELECT * FROM alumnos WHERE alumnos.id = :id_alumnos AND alumnos.fecha_nacimiento LIKE '%$fecha_hoy'");
        $consulta->bindParam(':id_alumnos', $id_alumnos);
        $consulta->execute();
                    
        return $consulta->fetchAll(PDO::FETCH_ASSOC);

    }                
}