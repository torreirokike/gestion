<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha proporcionado el ID del artículo a eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_articulo = $_GET['id'];

    // Obtener los detalles del artículo a eliminar
    $sql_articulo = "SELECT * FROM Productos_Operaciones WHERE ID_Producto_Operacion = $id_articulo";
    $result_articulo = $conn->query($sql_articulo);

    if ($result_articulo->num_rows > 0) {
        // Mostrar detalles del artículo a eliminar
        $row_articulo = $result_articulo->fetch_assoc();
    } else {
        echo "No se encontró el artículo.";
        exit;
    }
} else {
    echo "ID de artículo no proporcionado.";
    exit;
}

// Procesar el formulario de confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_venta = $row_articulo['ID_Operacion'];

    // Eliminar el artículo de la venta
    $sql_delete = "DELETE FROM Productos_Operaciones WHERE ID_Producto_Operacion = $id_articulo";
    if ($conn->query($sql_delete) === TRUE) {
        // Redirigir a la página de editar venta con el ID de la venta
        header("Location: editar_venta.php?id=$id_venta");
        exit;
    } else {
        echo "Error al eliminar el artículo: " . $conn->error;
    }
}
?>

<h2>Eliminar Artículo de Venta</h2>
<p>¿Estás seguro de que quieres eliminar el siguiente artículo de la venta?</p>
<table>
    <tr>
        <th>ID Producto</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
    </tr>
    <tr>
        <td><?php echo $row_articulo['ID_Producto']; ?></td>
        <td><?php echo $row_articulo['Cantidad']; ?></td>
        <td><?php echo $row_articulo['Precio_Unitario']; ?></td>
        <td><?php echo $row_articulo['Subtotal']; ?></td>
    </tr>
</table>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id_articulo); ?>">
    <input type="submit" value="Confirmar Eliminación">
</form>

<?php
include '../includes/footer.php';
?>
