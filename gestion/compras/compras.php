<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Variable para almacenar la consulta SQL
$sql_compras = "";

// Verificar si se ha hecho clic en el enlace "Ver todas las compras"
if(isset($_GET['ver_todas'])) {
    // Consulta para obtener todas las compras
    $sql_compras = "SELECT o.ID_Operacion, o.Fecha, p.Nombre AS Proveedor, o.Total, 
                    o.Total - COALESCE(SUM(m.Monto), 0) AS Saldo
                    FROM Operaciones o 
                    JOIN Proveedores p ON o.ID_Proveedor = p.ID_Proveedor 
                    LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion 
                    WHERE o.Tipo = 'Compra'
                    GROUP BY o.ID_Operacion";
} else {
    // Consulta para obtener la lista de compras con saldo distinto de 0
    $sql_compras = "SELECT o.ID_Operacion, o.Fecha, p.Nombre AS Proveedor, o.Total, 
                    o.Total - COALESCE(SUM(m.Monto), 0) AS Saldo
                    FROM Operaciones o 
                    JOIN Proveedores p ON o.ID_Proveedor = p.ID_Proveedor 
                    LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion 
                    WHERE o.Tipo = 'Compra'
                    GROUP BY o.ID_Operacion
                    HAVING Saldo != 0";
}

// Ejecutar la consulta SQL
$result_compras = $conn->query($sql_compras);
?>

<h2>Compras</h2>
<div>
    <a class="btn-add" href="alta_compra.php">Agregar Compra</a>
    <a class="btn-view-all" href="compras.php?ver_todas=1">Ver todas las compras</a>
</div>
<table>
    <tr>
        <th>Fecha</th>
        <th>Proveedor</th>
        <th>Total</th>
        <th>Saldo</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result_compras->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['Fecha']; ?></td>
        <td><?php echo $row['Proveedor']; ?></td>
        <td><?php echo $row['Total']; ?></td>
        <td><?php echo $row['Saldo']; ?></td>
        <td>
            <a href="editar_compra.php?id=<?php echo $row['ID_Operacion']; ?>">Editar</a>
            <a href="eliminar_compra.php?id=<?php echo $row['ID_Operacion']; ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta compra?')">Eliminar</a>
            <a href="..//caja/registro_movimiento.php?id=<?php echo $row['ID_Operacion']; ?>">Pagar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php
include '../includes/footer.php';
?>
