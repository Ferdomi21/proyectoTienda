<?php
class Producto {
    private $idProducto;
    private $nombre;
    private $precio;
    private $descripcion;
    private $cantidad;

    public function __construct($idProducto, $nombre, $precio, $descripcion, $cantidad) {
        $this->idProducto = $idProducto;
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->cantidad = $cantidad;
    }
}
?>