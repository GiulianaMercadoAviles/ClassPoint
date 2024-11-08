<?php

namespace Web_Asistencia_App;
trait Obtener_datos {

    // Obtener los datos del formulario
    public static function obtener_datos_profesor() {
         
        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $datos = [];

        if (isset($_POST["nombre"])) {
            $datos["nombre"] = clean_input($_POST["nombre"]);
        } else {
            $datos["nombre"] = "";
        }
        if (isset($_POST["apellido"])) {
            $datos["apellido"] = clean_input($_POST["apellido"]);
        } else {
            $datos["apellido"] = "";
        }
        if (isset($_POST["dni"])) {
            $datos["dni"] = clean_input($_POST["dni"]);
        } else {
            $datos["dni"] = "";
        }
        if (isset($_POST["email"])) {
            $datos["email"] = clean_input($_POST["email"]);
        } else {
            $datos["email"] = "";
        }
        if (isset($_POST["fecha_nacimiento"])) {
            $datos["fecha_nacimiento"] = clean_input($_POST["fecha_nacimiento"]);
        } else {
            $datos["fecha_nacimiento"] = "";
        }
        if (isset($_POST["legajo"])) {
            $datos["legajo"] = clean_input($_POST["legajo"]);
        } else {
            $datos["legajo"] = "";
        }
        if (isset($_POST["contrasena"])) {
            $datos["contrasena"] = clean_input($_POST["contrasena"]);
        } else {
            $datos["contrasena"] = "";
        }
        return $datos;
    }

    public static function obtener_datos_alumno() {

        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $datos = [];

        if (isset($_POST["nombre"])) {
            $datos["nombre"] = clean_input($_POST["nombre"]);
        } else {
            $datos["nombre"] = "";
        }
        if (isset($_POST["apellido"])) {
            $datos["apellido"] = clean_input($_POST["apellido"]);
        } else {
            $datos["apellido"] = "";
        }
        if (isset($_POST["dni"])) {
            $datos["dni"] = clean_input($_POST["dni"]);
        } else {
            $datos["dni"] = "";
        }
        if (isset($_POST["fecha_nacimiento"])) {
            $datos["fecha_nacimiento"] = clean_input($_POST["fecha_nacimiento"]);
        } else {
            $datos["fecha_nacimiento"] = "";
        }
        
        return $datos;
    }

    public static function obtener_datos_institucion() { 

        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $datos = [];

        if (isset($_POST["nombre"])) {
            $datos["nombre"] = clean_input($_POST["nombre"]);
        } else {
            $datos["nombre"] = "";
        }
        if (isset($_POST["direccion"])) {
            $datos["direccion"] = clean_input($_POST["direccion"]);
        } else {
            $datos["direccion"] = "";
        }
        if (isset($_POST["cue"])) {
            $datos["cue"] = clean_input($_POST["cue"]);
        } else {
            $datos["cue"] = "";
        }
        return $datos;
    }

    public static function obtener_datos_materia() {  

        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $datos = [];

        if (isset($_POST["nombre"])) {
            $datos["nombre"] = clean_input($_POST["nombre"]);
        } else {
            $datos["nombre"] = "";
        }
        return $datos;
    }

    public static function obtener_datos_notas() {  

        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $datos = [];

        if (isset($_POST["nota"])) {
            $datos["nota"] = clean_input($_POST["nota"]);
        } else {
            $datos["nombre"] = "";
        }
        if (isset($_POST["instancia"])) {
            $datos["instancia"] = clean_input($_POST["instancia"]);
        } else {
            $datos["instancia"] = "";
        }
        return $datos;
    }

    public static function obtener_datos_ram() {

        function clean_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        
        $datos = [];

        if (isset($_POST["nota_promocion"])) {
            $datos["nota_promocion"] = clean_input($_POST["nota_promocion"]);
        } else {
            $datos["nota_promocion"] = "";
        }
        if (isset($_POST["asistencia_promocion"])) {
            $datos["asistencia_promocion"] = clean_input($_POST["asistencia_promocion"]);
        } else {
            $datos["asistencia_promocion"] = "";
        }
        if (isset($_POST["nota_regular"])) {
            $datos["nota_regular"] = clean_input($_POST["nota_regular"]);
        } else {
            $datos["nota_regular"] = "";
        }
        if (isset($_POST["asistencia_regular"])) {
            $datos["asistencia_regular"] = clean_input($_POST["asistencia_regular"]);
        } else {
            $datos["asistencia_regular"] = "";
        }
        return $datos;
    }
}

trait Validar_datos {

    // Verifica que los datos esten completos
    public static function validar_datos_profesor($data) {
        $alerta = [];

        if (empty($data["nombre"])) {
            array_push($alerta, "Se requiere ingresar un nombre");
        } else if (!preg_match('/^[a-zA-Z\s]+$/', $data["nombre"])) {
            array_push($alerta, "El nombre solo puede contener letras y espacios.");
        }

        if (empty($data["apellido"])) {
            array_push($alerta, "Se requiere ingresar un apellido");
        } else if (!preg_match('/^[a-zA-Z\s]+$/', $data["nombre"])) {
            array_push($alerta, "El apellido solo puede contener letras y espacios.");
        }

        if (empty($data["dni"])) {
            array_push($alerta, "Se requiere ingresar numero de dni");
        } else if (!is_numeric($data["dni"]) || strlen($data["dni"]) < 8 || strlen($data["dni"]) > 8) {
            array_push($alerta, "El D.N.I debe ser un número válido de 8 dígitos");
        }

        if (empty($data["email"])) {
            array_push($alerta, "Se requiere ingresar un email");
        } else if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            array_push($alerta, "El correo electrónico no es válido");
        }

        if (empty($data["legajo"])) {
            array_push($alerta, "Se requiere ingresar numero de legajo");
        } else if (!is_numeric($data["legajo"]) || strlen($data["legajo"]) < 8 || strlen($data["legajo"]) > 8) {
            array_push($alerta, "El Legajo debe ser un número válido de 8 dígitos");
        }

        if (empty($data["contrasena"])) {
            array_push($alerta, "Se requiere ingresar una contraseña");
        }
        return $alerta;
    }

    public static function validar_datos_alumno($data) {
        $alerta = [];

        if (empty($data["nombre"])) {
            array_push($alerta, "Se requiere ingresar un nombre");
        } else if (!preg_match('/^[a-zA-Z\s]+$/', $data["nombre"])) {
            array_push($alerta, "El nombre solo puede contener letras y espacios.");
        }

        if (empty($data["apellido"])) {
            array_push($alerta, "Se requiere ingresar un apellido");
        } else if (!preg_match('/^[a-zA-Z\s]+$/', $data["apellido"])) {
            array_push($alerta, "El apellido solo puede contener letras y espacios.");
        }

        if (empty($data["dni"])) {
            array_push($alerta, "Se requiere ingresar numero de dni");
        } else if (!is_numeric($data["dni"]) || strlen($data["dni"]) < 8 || strlen($data["dni"]) > 8) {
            array_push($alerta, "El D.N.I debe ser un número válido de 8 dígitos");
        }

        if (empty($data["fecha_nacimiento"])) {
            array_push($alerta, "Se requiere ingresar fecha de nacimiento");
        } else {
            $fecha_actual = date("Y-m-d");
            if ($data["fecha_nacimiento"] > $fecha_actual) {
                array_push($alerta, "La fecha de nacimiento no puede ser una fecha futura.");
            }
        return $alerta;
    }
}  
    public static function validar_datos_institucion($data) {
        $alerta = [];

        if (empty($data["nombre"])) {
            array_push($alerta, "Se requiere ingresar un nombre");
        } else if (!preg_match('/^[a-zA-Z0-9\s]+$/', $data["nombre"])) {
            array_push($alerta, "El nombre solo puede contener letras, números y espacios.");
        }

        if (empty($data["direccion"])) {
            array_push($alerta, "Se requiere ingresar una direccion");
        }

        if (empty($data["cue"])) {
            array_push($alerta, "Se requiere ingresar el c.u.e");
        } else if (!is_numeric($data["cue"]) || strlen($data["cue"]) < 9 || strlen($data["cue"]) > 9) {
            array_push($alerta, "El C.U.E debe ser un número válido de 9 dígitos");
        }
        return $alerta;
    }

    public static function validar_datos_materia($data) {
        $alerta = [];

        if (empty($data["nombre"])) {
            array_push($alerta, "Se requiere ingresar el nombre de una materia");
        } else if (!preg_match('/^[a-zA-Z0-9\s]+$/', $data["nombre"])) {
            array_push($alerta, "El nombre de la materia  solo puede contener letras, números y espacios.");
        }

        return $alerta;
    }

    public static function validar_datos_notas($data) {
        $alerta = [];

        if (empty($data["nota"])) {
            array_push($alerta, "Se requiere ingresar una nota");
        } else if (!is_numeric($data["nota"]) || strlen($data["nota"]) < 1 || strlen($data["nota"]) > 2) {
            array_push($alerta, "La nota debe ser un numero del 1 al 10.");
        }
        if (empty($data["instancia"])) {
            array_push($alerta, "Se debe seleccionar una instancia");
        }
        
        return $alerta;
    }

    public static function validar_datos_ram($data) {
        $alerta = [];

        if (empty($data["nota_promocion"])) {
            array_push($alerta, "Se requiere ingresar una nota para promocion");
        } else if (!is_numeric($data["nota_promocion"]) || ($data["nota_promocion"]) < 1 || ($data["nota_promocion"]) > 10) {
            array_push($alerta, "Nota de Promocion Invalida");
        }

        if (empty($data["asistencia_promocion"])) {
            array_push($alerta, "Se requiere ingresar un porcentaje de asistencia para Promocion");
        } else if (!is_numeric($data["asistencia_promocion"]) || ($data["asistencia_promocion"]) < 0 || ($data["asistencia_promocion"]) > 100) {
            array_push($alerta, "Procentaje de Promocion Invalido");
        }

        if (empty($data["nota_regular"])) {
            array_push($alerta, "Se requiere ingresar una nota de regularidad");
        } else if (!is_numeric($data["nota_regular"]) || ($data["nota_regular"]) < 1 || ($data["nota_regular"]) > 10) {
                array_push($alerta, "Nota de Regularidad Invalida");
        }

        if (empty($data["asistencia_regular"])) {
            array_push($alerta, "Se requiere ingresar una porcentaje de asistencia de regularidad");
        } else if (!is_numeric($data["asistencia_regular"]) || ($data["asistencia_regular"]) < 0 || ($data["asistencia_regular"]) > 100) {
            array_push($alerta, "Procentaje de Regultaridad Invalido");
        }
        return $alerta;
    }
}