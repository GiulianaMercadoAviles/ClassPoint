<?php

class Persona {
    protected $nombre;
    protected $apellido;
    protected $dni;
    protected $email;

    public function __construct($nombre, $apellido, $dni, $email) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->email = $email;  
    }
}