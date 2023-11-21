<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require 'conexion.php' ?>
</head>

<body>
    <?php
    session_start();

    if (isset($_SESSION["user"])) {
        $usuario = $_SESSION["user"];
    } else {
        //header('location: iniciar_sesion.php');
        $_SESSION["user"] = "invitado";
        $usuario = $_SESSION["user"];
    }
    ?>
    <div class="container">
        <h1>Página principal</h1>
        <h2>Bienvenido/a
            <?php echo $usuario ?>
        </h2>

        <a href="cerrar_sesion.php">Cerrar sesión</a>
    </div>
    <nav class="menu">
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Sobre nosotros</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
    </nav>

    <?php
    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);
    ?>
    <table class="table table-info table-hover">
        <thead class="table table-success">
            <tr>
                <th>ID producto</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Descripcion</th>
                <th>Cantidad</th>
                <th>Imagen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Guarda la informacion de la primera fila y busca la siguiente
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["idProducto"] . "</td>";
                echo "<td>" . $row["nombreProducto"] . "</td>";
                echo "<td>" . $row["precio"] . "</td>";
                echo "<td>" . $row["descripcion"] . "</td>";
                echo "<td>" . $row["cantidad"] . "</td>";
                ?>
                 <img witdh="50" height="100" src="<?php echo $row["imagen"] ?>">
                 <?php
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>