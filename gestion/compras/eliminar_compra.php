<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha proporcionado el ID de la compra a eliminar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_compra = $_GET['id'];

    // Obtener los detalles de la compra a eliminar
    $sql_compra = "SELECT * FROM Operaciones WHERE ID_Operacion = $id_compra";
    $result_compra = $conn->query($sql_compra);

    if ($result_compra->num_rows > 0) {
        // Mostrar detalles de la compra a eliminar
        $row_compra = $result_compra->fetch_assoc();
    } else {
        echo "No se encontró la compra.";
        exit;
    }
} else {
    echo "ID de compra no proporcionado.";
    exit;
}

// Procesar el formulario de confirmación de eliminación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Eliminar la compra y sus artículos asociados
    $sql_delete_compra = "DELETE FROM Operaciones WHERE ID_Operacion = $id_compra";
    $sql_delete_articulos = "DELETE FROM Productos_Operaciones WHERE ID_Operacion = $id_compra";

    if ($conn->query($sql_delete_compra) === TRUE && $conn->query($sql_delete_articulos) === TRUE) {
        // Redirigir a la página de listado de compras
        header("Location: compras.php");
        exit;
    } else {
        echo "Error al eliminar la compra: " . $conn->error;
    }
}
?>

<h2>Eliminar Compra</h2>
<p>¿Estás seguro de que quieres eliminar la siguiente compra?</p>
<table>
    <tr>
        <th>ID Compra</th>
        <th>Fecha</th>
        <th>Descuento (%)</th>
        <th>Total</th>
        <th>Observaciones</th>
    </tr>
    <tr>
        <td><?php echo $row_compra['ID_Operacion']; ?></td>
        <td><?php echo $row_compra['Fecha']; ?></td>
        <td><?php echo $row_compra['Descuento_Porcentaje']; ?></td>
        <td><?php echo $row_compra['Total']; ?></td>
        <td><?php echo $row_compra['Observaciones']; ?></td>
    </tr>
</table>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $id_compra); ?>">
    <input type="submit" value="Confirmar Eliminación">
</form>

<?php
include '../includes/footer.php';
?>
