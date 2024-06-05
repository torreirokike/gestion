<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha proporcionado un ID de presupuesto válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_presupuesto = $_GET['id'];

    // Consulta SQL para obtener los detalles del presupuesto
    $sql_presupuesto = "SELECT p.Fecha, c.Nombre AS Cliente, p.Descuento_Porcentaje, p.Observaciones,
                        pp.ID_Producto, prod.Nombre AS Producto, pp.Cantidad, pp.Precio_Unitario, pp.Subtotal
                        FROM Presupuestos p
                        JOIN Clientes c ON p.ID_Cliente = c.ID_Cliente
                        JOIN Productos_Presupuestos pp ON p.ID_Presupuesto = pp.ID_Presupuesto
                        JOIN Productos prod ON pp.ID_Producto = prod.ID_Producto
                        WHERE p.ID_Presupuesto = $id_presupuesto";
    $result_presupuesto = $conn->query($sql_presupuesto);

    // Verificar si se encontró el presupuesto
    if ($result_presupuesto->num_rows > 0) {
        $presupuesto = $result_presupuesto->fetch_assoc();
?>
        <h2>Detalle del Presupuesto</h2>
        <p><strong>Fecha:</strong> <?php echo $presupuesto['Fecha']; ?></p>
        <p><strong>Cliente:</strong> <?php echo $presupuesto['Cliente']; ?></p>
        <p><strong>Descuento (%):</strong> <?php echo $presupuesto['Descuento_Porcentaje']; ?></p>
        <p><strong>Observaciones:</strong> <?php echo $presupuesto['Observaciones']; ?></p>
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
            <?php
            // Mostrar los productos asociados al presupuesto
            $total_presupuesto = 0;
            while ($row = $result_presupuesto->fetch_assoc()) {
                $subtotal = $row['Cantidad'] * $row['Precio_Unitario'];
                $total_presupuesto += $subtotal;
            ?>
                <tr>
                    <td><?php echo $row['Producto']; ?></td>
                    <td><?php echo $row['Cantidad']; ?></td>
                    <td><?php echo $row['Precio_Unitario']; ?></td>
                    <td><?php echo $subtotal; ?></td>
                </tr>
            <?php } ?>
            <tr>
                <th colspan="3">Total Presupuesto</th>
                <td><?php echo $total_presupuesto; ?></td>
            </tr>
        </table>
<?php
    } else {
        echo "<p>No se encontró el presupuesto.</p>";
    }
} else {
    echo "<p>ID de presupuesto no válido.</p>";
}

include '../includes/footer.php';
?>
