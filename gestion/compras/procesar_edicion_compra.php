<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_operacion'])) {
    $id_operacion = $_POST['id_operacion'];

    // Procesar datos del formulario
    $id_proveedor = $_POST['id_proveedor'];
    $fecha = $_POST['fecha'];
    $descuento_porcentaje = $_POST['descuento_porcentaje'];
    $observaciones = $_POST['observaciones'];

    // Actualizar datos de la operación en la tabla Operaciones
    $sql_update_operacion = "UPDATE Operaciones SET ID_Proveedor='$id_proveedor', Fecha='$fecha', Descuento_Porcentaje='$descuento_porcentaje', Observaciones='$observaciones' WHERE ID_Operacion='$id_operacion'";
    if ($conn->query($sql_update_operacion) === TRUE) {
        // Eliminar los productos de la operación en la tabla Productos_Operaciones
        $sql_delete_productos = "DELETE FROM Productos_Operaciones WHERE ID_Operacion='$id_operacion'";
        $conn->query($sql_delete_productos);

        // Insertar los nuevos productos de la operación en la tabla Productos_Operaciones
        foreach ($_POST['productos'] as $key => $producto) {
            if ($key !== 'nuevo') {
                $id_producto_operacion = $key;
                $cantidad = $producto['cantidad'];
                $precio_unitario = $producto['precio_unitario'];
                $subtotal = $cantidad * $precio_unitario;

                $sql_insert_producto = "INSERT INTO Productos_Operaciones (ID_Producto_Operacion, ID_Operacion, ID_Producto, Cantidad, Precio_Unitario, Subtotal) VALUES ('$id_producto_operacion', '$id_operacion', '{$producto['id']}', '$cantidad', '$precio_unitario', '$subtotal')";
                $conn->query($sql_insert_producto);
            }
        }

        // Calcular el total de la operación
        $total_operacion = 0;
        foreach ($_POST['productos'] as $producto) {
            $total_operacion += $producto['cantidad'] * $producto['precio_unitario'];
        }
        $total_operacion -= ($total_operacion * $descuento_porcentaje / 100);

        // Actualizar el total de la operación en la tabla Operaciones
        $sql_update_total = "UPDATE Operaciones SET Total='$total_operacion' WHERE ID_Operacion='$id_operacion'";
        $conn->query($sql_update_total);

        // Redirigir a la página de listado de compras
        header("Location: compras.php");
        exit;
    } else {
        echo "Error al procesar la edición de la compra: " . $conn->error;
    }
} else {
    echo "Acceso no autorizado.";
}
?>
