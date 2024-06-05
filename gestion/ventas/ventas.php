<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Variable para almacenar la consulta SQL
$sql_ventas = "";

// Verificar si se ha hecho clic en el enlace "Ver todas las ventas"
if(isset($_GET['ver_todas'])) {
    // Consulta para obtener todas las ventas
    $sql_ventas = "SELECT o.ID_Operacion, o.Fecha, c.Nombre AS Cliente, o.Total, 
                    o.Total - COALESCE(SUM(m.Monto), 0) AS Saldo
                    FROM Operaciones o 
                    JOIN Clientes c ON o.ID_Cliente = c.ID_Cliente 
                    LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion 
                    WHERE o.Tipo = 'Venta'
                    GROUP BY o.ID_Operacion";
} else {
    // Consulta para obtener la lista de ventas con saldo distinto de 0
    $sql_ventas = "SELECT o.ID_Operacion, o.Fecha, c.Nombre AS Cliente, o.Total, 
                    o.Total - COALESCE(SUM(m.Monto), 0) AS Saldo
                    FROM Operaciones o 
                    JOIN Clientes c ON o.ID_Cliente = c.ID_Cliente 
                    LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion 
                    WHERE o.Tipo = 'Venta'
                    GROUP BY o.ID_Operacion
                    HAVING Saldo != 0";
}

// Ejecutar la consulta SQL
$result_ventas = $conn->query($sql_ventas);
?>

<h2>Ventas</h2>
<div>
    <a class="btn-add" href="alta_venta.php">Agregar Venta</a>
    <a class="btn-view-all" href="ventas.php?ver_todas=1">Ver todas las ventas</a>
</div>
<table>
    <tr>
        <th>Fecha</th>
        <th>ID</th>
        <th>Cliente</th>
        <th>Total</th>
        <th>Saldo</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result_ventas->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['Fecha']; ?></td>
        <td><?php echo $row['ID_Operacion']; ?></td>
        <td><?php echo $row['Cliente']; ?></td>
        <td><?php echo $row['Total']; ?></td>
        <td><?php echo $row['Saldo']; ?></td>
        <td>
            <a href="editar_venta.php?id=<?php echo $row['ID_Operacion']; ?>">Editar</a>
            <a href="eliminar_venta.php?id=<?php echo $row['ID_Operacion']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta venta?')">Eliminar</a>
            <a href="..//caja/registro_movimiento.php?id=<?php echo $row['ID_Operacion']; ?>">Cobrar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php
include '../includes/footer.php';
?>
