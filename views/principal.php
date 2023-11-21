<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <?php require '../util/conexion.php' ?>
    <?php require '../util/producto.php' ?>
    <?php require '../util/depurar.php' ?>
    
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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["idProducto"])) {
            $idProducto = depurar($_POST["idProducto"]);
            if (isset($_POST["cantidad"])) {
                $tem_cantidad = depurar($_POST["cantidad"]);
            }

            $usuario = $_SESSION["user"]; // Recuperamos el usuario de la sesión
            $sql = "SELECT * FROM cestas WHERE usuario = '$usuario'";
            $resultado = $conexion->query($sql);

            if (($tem_cantidad <= 0) || ($tem_cantidad > 5)) {
                $err_cantidad = "La cantidad tiene que ser mayor que 0 y menor que 5";
            } else {
                $cantidad = $tem_cantidad;
            }

            $idCesta = -1;
            while ($row = $resultado->fetch_assoc()) {
                $idCesta = $row["idCesta"];
            }
            if (($idCesta != -1) && (isset($cantidad))) {
                $sqlproductosCesta = "SELECT cantidad FROM productosCestas WHERE idCesta = '$idCesta' AND idProducto = '$idProducto'";
                $filacantidadProductosCesta = $conexion->query($sqlproductosCesta);
                while ($row = $filacantidadProductosCesta->fetch_assoc()) {
                    $cantidadProductosCesta = $row["cantidad"];
                }
                if ($cantidad > 0) {
                    if ($cantidadProductosCesta > 0) {
                        $cantidad += $cantidadProductosCesta;
                        $sql = "UPDATE productosCestas SET cantidad = '$cantidad' WHERE idCesta = '$idCesta' AND idProducto = '$idProducto'";
                    } else {
                        $sql = "INSERT INTO productosCestas (idCesta, idProducto, cantidad) VALUES ('$idCesta', '$idProducto', '$cantidad')";
                    }
                    $conexion->query($sql);

                    $exito = "Producto añadido correctamente";
                    $sqlExtra= "SELECT * FROM productos WHERE idProducto = '$idProducto'";
                    $resultadoPrecio = $conexion->query($sqlExtra);
                    $filaPrecio = $resultadoPrecio->fetch_assoc();
                    $precio = $filaPrecio["precio"];
                    $sqlprecioUnidad = "SELECT precio FROM productos WHERE idProducto = '$idProducto'"; // Obtenemos el precio del producto unitario
                    $salida = $conexion->query($sqlprecioUnidad);
                    $salida = $salida->fetch_assoc();
                    $precioUnidad = $salida["precio"];
                    $precioTotal = $precioUnidad * $cantidad;
                    $sql = "UPDATE cestas SET precioTotal = precioTotal + $precioTotal WHERE idCesta = '$idCesta'";
                    $conexion->query($sql);
                } else { ?>
                    <div class="alert alert-danger" role="alert">La cantidad tiene que ser mayor que 0.</div>
                    <?php
                }

            }
        }
    }
    $idProducto = "";
    $sql = "SELECT * FROM productos WHERE idProducto = '$idProducto'";
    $resultado = $conexion->query($sql);
    while ($row = $resultado->fetch_assoc()) {
        $nombreProducto = $row["nombreProducto"];
        $precio = $row["precio"];
        $descripcion = $row["descripcion"];
        $imagen = $row["imagen"];
    }
    ?>
    <div class="container">
        <h2>Bienvenido/a
            <?php echo $usuario ?>
        </h2>
        <nav class="menu">
            <ul>
                <li><a href="principal.php">Inicio</a></li>
                <?php
                if (isset($_SESSION["rol"])) {
                    if ($_SESSION["rol"] == "admin") {
                        echo "<li><a href='insertar_productos.php'>Añadir Productos</a></li>";
                    }
                }
                ?>
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
            <thead class="table table-striped">
                <tr>
                    <th>ID producto</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Descripcion</th>
                    <th>Cantidad</th>
                    <th>Imagen</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Usando las propiedades del objeto "producto" accedemos a los datos de la base de datos
                foreach ($productos as $producto) { ?>
                    <tr>
                        <td>
                            <?php echo $producto->idProducto; ?>
                        </td>
                        <td>
                            <?php echo $producto->nombreProducto; ?>
                        </td>
                        <td>
                            <?php echo $producto->precio; ?>
                        </td>
                        <td>
                            <?php echo $producto->descripcion; ?>
                        </td>
                        <td>
                            <?php echo $producto->cantidad; ?>
                        </td>
                        <td><img width="50%" height="50%" src="images/<?php echo $producto->imagen; ?>"></td>
                        <td>
                            <form action="" method="POST">
                                <input type="hidden" name="idProducto" value="<?php echo $producto->idProducto; ?>">
                                <select name="cantidad" class="form-select">
                                    <option selected value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                                <input type="submit" class="btn btn-warning" value="Añadir al carrito">
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>