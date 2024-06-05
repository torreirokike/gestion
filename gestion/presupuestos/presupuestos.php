<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Consulta SQL para obtener la lista de presupuestos
$sql_presupuestos = "SELECT * FROM Presupuestos";
$result_presupuestos = $conn->query($sql_presupuestos);
?>

<h2>Presupuestos</h2>
<div>
    <a class="btn-add" href="alta_presupuesto.php">Agregar Presupuesto</a>
</div>

<table>
    <tr>
        <th>ID</th>
        <th>Tipo</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Descuento (%)</th>
        <th>Observaciones</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result_presupuestos->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['ID_Presupuesto']; ?></td>
        <td><?php echo $row['Tipo']; ?></td>
        <td><?php echo obtenerNombreCliente($row['ID_Cliente']); ?></td>
        <td><?php echo $row['Fecha']; ?></td>
        <td><?php echo $row['Descuento_Porcentaje']; ?></td>
        <td><?php echo $row['Observaciones']; ?></td>
        <td>
            <a href="editar_presupuesto.php?id=<?php echo $row['ID_Presupuesto']; ?>">Editar</a>
            <a href="eliminar_presupuesto.php?id=<?php echo $row['ID_Presupuesto']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este presupuesto?')">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
include '../includes/footer.php';

// Función para obtener el nombre del cliente
function obtenerNombreCliente($id_cliente) {
    global $conn;
    $sql = "SELECT Nombre FROM Clientes WHERE ID_Cliente = $id_cliente";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['Nombre'];
    } else {
        return "Cliente no encontrado";
    }
}
?>
