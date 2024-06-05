<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha proporcionado el ID de la venta a eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_venta = $_GET['id'];

    // Obtener los detalles de la venta a eliminar
    $sql_venta = "SELECT * FROM Operaciones WHERE ID_Operacion = $id_venta";
    $result_venta = $conn->query($sql_venta);

    if ($result_venta->num_rows > 0) {
        // Mostrar detalles de la venta a eliminar
        $row_venta = $result_venta->fetch_assoc();
    } else {
        echo "No se encontró la venta.";
        exit;
    }
} else {
    echo "ID de venta no proporcionado.";
    exit;
}

// Procesar el formulario de confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eliminar la venta y sus artículos asociados
    $sql_delete_venta = "DELETE FROM Operaciones WHERE ID_Operacion = $id_venta";
    $sql_delete_articulos = "DELETE FROM Productos_Operaciones WHERE ID_Operacion = $id_venta";

    if ($conn->query($sql_delete_venta) === TRUE && $conn->query($sql_delete_articulos) === TRUE) {
        // Redirigir a la página de listado de ventas
        header("Location: ventas.php");
        exit;
    } else {
        echo "Error al eliminar la venta: " . $conn->error;
    }
}
?>

<h2>Eliminar Venta</h2>
<p>¿Estás seguro de que quieres eliminar la siguiente venta?</p>
<table>
    <tr>
        <th>ID Venta</th>
        <th>Fecha</th>
        <th>Descuento (%)</th>
        <th>Total</th>
        <th>Observaciones</th>
    </tr>
    <tr>
        <td><?php echo $row_venta['ID_Operacion']; ?></td>
        <td><?php echo $row_venta['Fecha']; ?></td>
        <td><?php echo $row_venta['Descuento_Porcentaje']; ?></td>
        <td><?php echo $row_venta['Total']; ?></td>
        <td><?php echo $row_venta['Observaciones']; ?></td>
    </tr>
</table>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id_venta); ?>">
    <input type="submit" value="Confirmar Eliminación">
</form>

<?php
include '../includes/footer.php';
?>
