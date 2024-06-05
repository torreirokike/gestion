<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Obtener la fecha actual
$fecha_actual = date('Y-m-d');

// Consulta para obtener las operaciones con saldo distinto de 0
$sql_operaciones = "SELECT o.ID_Operacion, o.Tipo, (o.Total - COALESCE(SUM(m.Monto), 0)) as Saldo, 
                    CONCAT_WS(' - ', CASE WHEN c.ID_Cliente IS NOT NULL THEN 'Cliente: ' ELSE 'Proveedor: ' END,
                    COALESCE(c.Nombre, p.Nombre)) AS Nombre 
                    FROM Operaciones o 
                    LEFT JOIN Clientes c ON o.ID_Cliente = c.ID_Cliente 
                    LEFT JOIN Proveedores p ON o.ID_Proveedor = p.ID_Proveedor 
                    LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion 
                    GROUP BY o.ID_Operacion 
                    HAVING Saldo != 0";

$result_operaciones = $conn->query($sql_operaciones);

// Consulta para obtener los movimientos no asociados a operaciones
$sql_movimientos = "SELECT * FROM Movimientos WHERE ID_Operacion IS NULL";
$result_movimientos = $conn->query($sql_movimientos);

// Procesar el formulario de registro de movimiento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha = $_POST['fecha'];
    $monto = $_POST['monto'];
    $id_operacion = $_POST['id_operacion'];
    $observaciones = $_POST['observaciones'];

    // Determinar el tipo de movimiento
    $tipo = "";

    if (!empty($id_operacion)) {
        // Si se eligió una operación, se determina el tipo de movimiento según el saldo de la operación
        $sql_saldo_operacion = "SELECT (o.Total - COALESCE(SUM(m.Monto), 0)) as Saldo FROM Operaciones o 
                                LEFT JOIN Movimientos m ON o.ID_Operacion = m.ID_Operacion 
                                WHERE o.ID_Operacion = $id_operacion";
    
        $saldo_result = $conn->query($sql_saldo_operacion);
        $saldo_row = $saldo_result->fetch_assoc();
        $saldo = $saldo_row['Saldo'];
    
        $tipo = ($saldo >= 0) ? "Cobro" : "Pago"; // Cambia la lógica aquí
    } else {
        // Si no se eligió una operación, se determina el tipo de movimiento según el signo del monto
        if ($monto >= 0) {
            $tipo = "Cobro";
        } else {
            $tipo = "Pago";
            $monto = abs($monto); // Convertir el monto a positivo
        }
        $id_operacion = "NULL"; // Establecer el ID_Operacion como NULL para indicar un movimiento de caja libre
    }
    // Insertar el nuevo movimiento en la base de datos
    $sql_insert = "INSERT INTO Movimientos (Fecha, Tipo, Monto, ID_Operacion, Observaciones) VALUES ('$fecha', '$tipo', '$monto', $id_operacion, '$observaciones')";
    if ($conn->query($sql_insert) === TRUE) {
        // Redirigir para evitar la recarga de la página y mostrar solo los movimientos no asociados a operaciones
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error al registrar el movimiento: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Movimiento</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registro de Movimiento</h2>
        <div>
            <a class="btn-add" href="caja.php">Ver Movimientos caja</a>
        </div>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $fecha_actual; ?>"><br>
            <label for="id_operacion">Operación:</label>
            <select name="id_operacion" id="id_operacion">
                <option value="">Seleccionar operación</option>
                <?php while ($row_operacion = $result_operaciones->fetch_assoc()): ?>
                    <option value="<?php echo $row_operacion['ID_Operacion']; ?>">
                        <?php echo $row_operacion['Tipo'] . " - " . $row_operacion['Nombre'] . " - Saldo: $" . $row_operacion['Saldo']; ?>
                    </option>
                <?php endwhile; ?>
            </select><br>
            <label for="monto">Monto:</label>
            <input type="number" id="monto" name="monto" min="<?php echo (!empty($id_operacion)) ? '0' : '-999999999'; ?>" step="0.01"><br>
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones"></textarea><br>
            <input type="submit" value="Registrar Movimiento">
        </form>

        <h3>Movimientos no Asociados a Operaciones:</h3>
        <ul>
            <?php while ($row_movimiento = $result_movimientos->fetch_assoc()): ?>
                <li>Fecha: <?php echo $row_movimiento['Fecha']; ?> - Tipo: <?php echo $row_movimiento['Tipo']; ?> - Monto: $<?php echo $row_movimiento['Monto']; ?> - Observaciones: <?php echo $row_movimiento['Observaciones']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>

<?php
include '../includes/footer.php';
?>
