<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar datos del formulario de edición de venta
    // Implementa el código para procesar los cambios y actualizar la venta en la base de datos aquí
}

// Obtener el ID de la operación de la venta a editar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_venta = $_GET['id'];

    // Obtener detalles de la venta desde la base de datos
    $sql_venta = "SELECT * FROM Operaciones WHERE ID_Operacion = $id_venta";
    $result_venta = $conn->query($sql_venta);

    if ($result_venta->num_rows > 0) {
        $row_venta = $result_venta->fetch_assoc();
    } else {
        echo "No se encontró la venta.";
        exit;
    }

    // Obtener los artículos vendidos en la operación original
    $sql_articulos = "SELECT * FROM Productos_Operaciones WHERE ID_Operacion = $id_venta";
    $result_articulos = $conn->query($sql_articulos);
} else {
    echo "ID de venta no proporcionado.";
    exit;
}
?>
<h2>Editar Venta <?php echo $id_venta; ?></h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <!-- Muestra los detalles de la venta para editar -->
    <!-- Implementa el código para mostrar y editar los detalles de la venta aquí -->

    <hr>
    <h3>Artículos Vendidos</h3>
    <!-- Muestra los artículos vendidos en la operación original -->
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>
        <?php while($row_articulo = $result_articulos->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row_articulo['ID_Producto']; ?></td>
            <td><?php echo $row_articulo['Cantidad']; ?></td>
            <td><?php echo $row_articulo['Precio_Unitario']; ?></td>
            <td><?php echo $row_articulo['Subtotal']; ?></td>
            <td><a href="eliminar_articulo.php?id=<?php echo $row_articulo['ID_Producto_Operacion']; ?>">Eliminar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Agrega un botón de editar que lleva a la página de venta nueva con todos los datos cargados -->
    <a href="alta_venta.php?id=<?php echo $id_venta; ?>">Editar Venta</a>

    <!-- Agrega la posibilidad de agregar hasta 10 productos nuevos -->
    <!-- Implementa el código para agregar nuevos productos aquí -->

    <input type="submit" value="Guardar Cambios">
</form>

<?php
include '../includes/footer.php';
?>
