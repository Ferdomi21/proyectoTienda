<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require '../util/conexion.php' ?>
    <?php require '../util/producto.php' ?>
</head>

<body>
    <?php
    session_start();
    // Sino estamos registrados nos redirige a la página de login
    if (isset($_SESSION["user"])) {
        $usuario = $_SESSION["user"];
    } else {
        header('location: login.php');
    }
    ?>
    <div class="container">
        <h1>Página principal</h1>
        <h2>Bienvenido/a
            <?php echo $usuario ?>
        </h2>
    </div>
    <nav class="menu">
        <ul>
            <li><a href="#">Inicio</a></li>
            <li><a href="#">Sobre nosotros</a></li>
            <li><a href="#">Contacto</a></li>
        <li><a href="cerrar_sesion.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <?php
    $sql = "SELECT * FROM productos";
    $resultado = $conexion->query($sql);
    $productos = array();
    while ($row = $resultado->fetch_assoc()) {
        $nuevo_producto = new Producto($row["idProducto"], $row["nombreProducto"], $row["precio"], $row["descripcion"], $row["cantidad"], $row["imagen"]);
        array_push($productos, $nuevo_producto);
    }
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
            // Usando las propiedades del objeto "producto" accedemos a los datos de la base de datos
            foreach ($productos as $producto) { ?>
                <tr>
                    <td><?php echo $producto->idProducto; ?></td>
                    <td><?php echo $producto->nombreProducto; ?></td>
                    <td><?php echo $producto->precio; ?></td>
                    <td><?php echo $producto->descripcion; ?></td>
                    <td><?php echo $producto->cantidad; ?></td>
                    <td><img width="50" height="100" src="images/<?php echo $producto->imagen; ?>"></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>