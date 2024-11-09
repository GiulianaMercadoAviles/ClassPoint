<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sqlFile = 'Base de Datos.sql';  

    $servername = "localhost";
    $username = "root"; 
    $password = ""; 
    $dbname = "web_asistencias_app";

    try {

        $conn = new PDO("mysql:host=$servername", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $result = $conn->query("SHOW DATABASES LIKE '$dbname'");
        
        if ($result->rowCount() == 0) {
   
            $createDb = "CREATE DATABASE `$dbname`";
            $conn->exec($createDb);
            echo "Base de datos '$dbname' creada con éxito. ";
        } else {
            echo "La base de datos '$dbname' ya existe. ";
        }

        $conn->exec("USE `$dbname`");

 
        if (file_exists($sqlFile)) {
            $sqlContent = file_get_contents($sqlFile);

            foreach (explode(';', $sqlContent) as $query) {
                if (trim($query)) {
                    $conn->exec(trim($query));
                }
            }
            echo "La base de datos ha sido importada correctamente.";
            header("Location: index.php");
            exit;
        } else {
            echo "El archivo SQL no se encuentra.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    } finally {

        $conn = null;
    }
}
