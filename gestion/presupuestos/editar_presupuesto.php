<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha proporcionado un ID de presupuesto válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_presupuesto = $_GET['id'];

    // Consulta SQL para obtener los detalles del presupuesto
    $sql_presupuesto = "SELECT * FROM Presupuestos WHERE ID_Presupuesto = ?";
    $stmt = $conn->prepare($sql_presupuesto);
    $stmt->bind_param("i", $id_presupuesto);
    $stmt->execute();
    $result_presupuesto = $stmt->get_result();

    if ($result_presupuesto->num_rows == 1) {
        $row_presupuesto = $result_presupuesto->fetch_assoc();

        // Consulta SQL para obtener los productos asociados al presupuesto
        $sql_productos_presupuesto = "SELECT p.Nombre, pp.Cantidad, pp.Precio_Unitario
                                      FROM Productos p
                                      INNER JOIN Productos_Presupuestos pp ON p.ID_Producto = pp.ID_Producto_Presupuesto
                                      WHERE pp.ID_Presupuesto = ?";
        $stmt = $conn->prepare($sql_productos_presupuesto);
        $stmt->bind_param("i", $id_presupuesto);
        $stmt->execute();
        $result_productos_presupuesto = $stmt->get_result();
    } else {
        echo "Presupuesto no encontrado.";
        exit;
    }
} else {
    echo "ID de presupuesto no proporcionado.";
    exit;
}
?>

<h2>Editar Presupuesto <?php echo $id_presupuesto; ?></h2>
<form method="post" action="actualizar_presupuesto.php">
    <input type="hidden" name="id_presupuesto" value="<?php echo $id_presupuesto; ?>">
    <label for="tipo">Tipo:</label>
    <input type="text" id="tipo" name="tipo" value="<?php echo $row_presupuesto['Tipo']; ?>"><br>
    <label for="cliente">Cliente:</label>
    <input type="text" id="cliente" name="cliente" value="<?php echo obtenerNombreCliente($row_presupuesto['ID_Cliente']); ?>" readonly><br>
    <label for="fecha">Fecha:</label>
    <input type="date" id="fecha" name="fecha" value="<?php echo $row_presupuesto['Fecha']; ?>"><br>
    <label for="descuento_porcentaje">Descuento (%):</label>
    <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" value="<?php echo $row_presupuesto['Descuento_Porcentaje']; ?>"><br>
    <label for="observaciones">Observaciones:</label>
    <textarea id="observaciones" name="observaciones"><?php echo $row_presupuesto['Observaciones']; ?></textarea><br>

    <hr>
    <h3>Productos</h3>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
        </tr>
        <?php while ($row_producto = $result_productos_presupuesto->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row_producto['Nombre']; ?></td>
            <td><?php echo $row_producto['Cantidad']; ?></td>
            <td><?php echo $row_producto['Precio_Unitario']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <hr>
    <label for="total_presupuesto">Total del Presupuesto:</label>
    <input type="text" id="total_presupuesto" name="total_presupuesto" value="<?php echo calcularTotalPresupuesto($id_presupuesto); ?>" readonly>
    <input type="submit" value="Guardar">
</form>

<?php
include '../includes/footer.php';

// Función para obtener el nombre del cliente
function obtenerNombreCliente($id_cliente) {
    global $conn;
    $sql = "SELECT Nombre FROM Clientes WHERE ID_Cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Nombre'];
    } else {
        return "Cliente no encontrado";
    }
}

// Función para calcular el total del presupuesto
function calcularTotalPresupuesto($id_presupuesto) {
    global $conn;
    $sql = "SELECT SUM(Cantidad * Precio_Unitario) AS Total FROM Productos_Presupuestos WHERE ID_Presupuesto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_presupuesto);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row['Total'];
    } else {
        return 0;
    }
}
?>
