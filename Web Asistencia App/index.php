<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Recursos\CSS\styles_login.css">
    <link rel="shortcut icon" href="Recursos\img\escuela.png" sizes="50x50">
    <title>ClassPoint</title>
</head>
<body>
    <div class="header">
        <div class="usuario">
            <h1>CLASSPOINT</h1>
        </div>
        <div class="importar">
        <form action="importar_db.php" method="POST">
            <button type="submit">Importar Base de Datos</button>
        </form>
        </div>
    </div>
    <section>
        <div class="contenedor_principal">
            <div class="contenedor">
                <h1>INICIAR SESIÓN</h1>
                <div class="contenedor_formulario">
                   <form action="login_main.php" method="post">
                        <div class="contenedor_input">
                            <label for="email">Correo Electrónico: </label>
                            <input type="text" name="email" id="email" placeholder="Ingrese Correo Electrónico" required>
                            <?php
                            if (isset($_SESSION['error_usuario'])) {
                                // Mandar un mensaje de error
                                echo $_SESSION['error_usuario'];
                                // Eliminar el mensaje de error después de mostrarlo
                                unset($_SESSION['error_usuario']);
                            }?>
                        </div>
                        <div class="contenedor_input">
                            <label for="contrasena">Contraseña: </label>
                            <input type="password" name="contrasena" id="contrasena" placeholder="Ingrese Contraseña" required>
                            <?php
                            if (isset($_SESSION['error_contrasena'])) {
                                // Mandar un mensaje de error
                                echo $_SESSION['error_contrasena'];
                                // Eliminar el mensaje de error después de mostrarlo
                                unset($_SESSION['error_contrasena']);
                            }
                            ?>
                        </div>
                        <button type="submit">Enviar</button>
                </div>
            </div>
            <div class="contenedor_imagen">
                <img src="Recursos\img\escuela.png" alt="escuela" >
            </div>
        </div>
        <div id="error"></div>
    </section>
    <footer>
        <div class="contenedor_footer"></div>
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>
