<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Consulta para obtener los movimientos de caja con información del proveedor o cliente
$sql_movimientos = "SELECT m.*, 
                    CASE 
                        WHEN o.Tipo = 'Compra' THEN p.Nombre 
                        WHEN o.Tipo = 'Venta' THEN c.Nombre 
                    END AS Nombre_Proveedor_Cliente 
                    FROM Movimientos m
                    LEFT JOIN Operaciones o ON m.ID_Operacion = o.ID_Operacion 
                    LEFT JOIN Proveedores p ON o.ID_Proveedor = p.ID_Proveedor
                    LEFT JOIN Clientes c ON o.ID_Cliente = c.ID_Cliente";
$result_movimientos = $conn->query($sql_movimientos);

// Consulta para calcular el saldo de la caja
$sql_saldo = "SELECT SUM(CASE WHEN Tipo = 'Cobro' THEN Monto ELSE -Monto END) as Saldo FROM Movimientos";
$result_saldo = $conn->query($sql_saldo);
$row_saldo = $result_saldo->fetch_assoc();
$saldo_caja = $row_saldo['Saldo'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caja</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Caja</h2>
        <p>Saldo de Caja: $<?php echo $saldo_caja; ?></p>
        <a href="registro_movimiento.php">Agregar Nuevo Movimiento</a> <!-- Enlace a registro_movimiento.php -->
        <h3>Lista de Movimientos de Caja</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Proveedor/Cliente</th>
                    <th>ID_Operacion</th>
                    <th>Monto</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_movimiento = $result_movimientos->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_movimiento['Fecha']; ?></td>
                        <td><?php echo $row_movimiento['Tipo']; ?></td>
                        <td><?php echo $row_movimiento['Nombre_Proveedor_Cliente']; ?></td>
                        <td>
                            <?php
                                $tipo_movimiento = $row_movimiento['Tipo'];
                                $id_operacion = $row_movimiento['ID_Operacion'];

                                    if ($tipo_movimiento === 'Cobro') {
                                        echo '<a href="../ventas/ventas.php?id=' . $id_operacion . '">';
                                    } else {
                                        echo '<a href="../compras/compras.php?id=' . $id_operacion . '">';
                                    }

                                echo $id_operacion;
                                echo '</a>';
                            ?>                        </td>
                        <td>$<?php echo $row_movimiento['Monto']; ?></td>
                        <td><?php echo $row_movimiento['Observaciones']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
include '../includes/footer.php';
?>
