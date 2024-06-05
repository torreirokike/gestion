<?php
include '../includes/db_connect.php'; // Incluir el archivo de conexión a la base de datos

// Obtener la lista de clientes para el filtro
$sql_clientes = "SELECT ID_Cliente, Nombre, Saldo FROM Clientes";
$result_clientes = $conn->query($sql_clientes);

// Obtener el valor del parámetro "cliente" del formulario
$cliente_id = isset($_GET['cliente']) ? $_GET['cliente'] : '';

// Consulta para obtener las ventas con saldo distinto de 0 y filtrar por cliente si se ha seleccionado uno
$sql_ventas_saldo = "SELECT o.ID_Operacion, o.Fecha, c.Nombre AS Cliente, o.Total,
                            (o.Total - COALESCE(SUM(m.Monto), 0)) AS Saldo
                    FROM Operaciones o
                    LEFT JOIN Clientes c ON o.ID_Cliente = c.ID_Cliente
                    LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion
                    WHERE o.Tipo = 'Venta'";
if (!empty($cliente_id)) {
    $sql_ventas_saldo .= " AND c.ID_Cliente = '$cliente_id'";
}
$sql_ventas_saldo .= " GROUP BY o.ID_Operacion
                      HAVING Saldo != 0";

$result_ventas_saldo = $conn->query($sql_ventas_saldo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cobranzas</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script>
        function calcularTotal() {
            var totalPagar = 0;
            var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            checkboxes.forEach(function(checkbox) {
                var valor = parseFloat(checkbox.value);
                totalPagar += valor;
            });
            document.getElementById('total_pagar').value = totalPagar.toFixed(2);
        }

        function habilitarParcial() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            var parcialInput = document.getElementById('monto_parcial');
            checkboxes.forEach(function(checkbox) {
                if (checkbox.value === 'parcial' && checkbox.checked) {
                    parcialInput.disabled = false;
                } else {
                    parcialInput.disabled = true;
                    parcialInput.value = '';
                }
            });
        }
    </script>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2>Cobranzas</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <label for="cliente">Filtrar por Cliente:</label>
            <select name="cliente" id="cliente" onchange="this.form.submit()">
                <option value="">Todos los clientes</option>
                <?php while ($row_cliente = $result_clientes->fetch_assoc()): ?>
                    <option value="<?php echo $row_cliente['ID_Cliente']; ?>" <?php echo ($cliente_id == $row_cliente['ID_Cliente']) ? 'selected' : ''; ?>>
                        <?php echo $row_cliente['Nombre'] . ' - Saldo: $' . number_format($row_cliente['Saldo'], 2); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <input type="submit" value="Filtrar">
        </form>

        <form action="#" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Operación</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Monto</th>
                        <th>Saldo</th>
                        <th>Pagar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_ventas_saldo->num_rows > 0): ?>
                        <?php while ($row_venta = $result_ventas_saldo->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row_venta['ID_Operacion']; ?></td>
                                <td><?php echo $row_venta['Fecha']; ?></td>
                                <td><?php echo $row_venta['Cliente']; ?></td>
                                <td>$<?php echo isset($row_venta['Total']) ? number_format($row_venta['Total'], 2) : '0.00'; ?></td>
                                <td>$<?php echo number_format($row_venta['Saldo'], 2); ?></td>
                                <td>
                                    <input type="checkbox" name="pagar_<?php echo $row_venta['ID_Operacion']; ?>" value="<?php echo $row_venta['Saldo']; ?>" onclick="calcularTotal(); habilitarParcial();"> Total
                                    <input type="checkbox" name="pagar_<?php echo $row_venta['ID_Operacion']; ?>" value="parcial" onclick="calcularTotal(); habilitarParcial();"> Parcial
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No se encontraron ventas con saldo distinto de 0.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <label for="total_pagar">Total a Pagar:</label>
            <input type="text" id="total_pagar" name="total_pagar" readonly>

            <label for="monto_parcial">Monto Parcial:</label>
            <input type="number" id="monto_parcial" name="monto_parcial" disabled>

            <input type="submit" value="Pagar">
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>
