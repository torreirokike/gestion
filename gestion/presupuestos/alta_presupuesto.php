<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Obtener lista de clientes desde la base de datos
$sql_clientes = "SELECT ID_Cliente, Nombre FROM Clientes";
$result_clientes = $conn->query($sql_clientes);

// Obtener lista de productos desde la base de datos
$sql_productos = "SELECT ID_Producto, Nombre, Precio_Venta FROM Productos";
$result_productos = $conn->query($sql_productos);

// Obtener fecha actual
$fecha_actual = date("Y-m-d");

// Variables para cálculos
$total_operacion = 0;

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar datos del formulario de alta de presupuesto
    $tipo = "Presupuesto";
    $id_cliente = $_POST['id_cliente'];
    $fecha = $_POST['fecha'];
    $descuento_porcentaje = isset($_POST['descuento_porcentaje']) ? $_POST['descuento_porcentaje'] : 0; // Asigna 0 si no se proporciona un descuento
    $observaciones = $_POST['observaciones'];

    // Insertar datos en la tabla de Presupuestos
    $sql_insert = "INSERT INTO Presupuestos (Tipo, ID_Cliente, Fecha, Descuento_Porcentaje, Observaciones) VALUES ('$tipo', '$id_cliente', '$fecha', '$descuento_porcentaje', '$observaciones')";
    if ($conn->query($sql_insert) === TRUE) {
        $id_presupuesto = $conn->insert_id;

        // Insertar productos del presupuesto en la tabla Productos_Presupuestos
        for ($i = 0; $i < 10; $i++) {
            if (!empty($_POST['productos'][$i]['id'])) {
                $id_producto = $_POST['productos'][$i]['id'];
                $cantidad = $_POST['productos'][$i]['cantidad'];
                $precio_unitario = $_POST['productos'][$i]['precio_unitario'];
                $subtotal = $cantidad * $precio_unitario;
                $sql_insert_producto = "INSERT INTO Productos_Presupuestos (ID_Presupuesto, ID_Producto, Cantidad, Precio_Unitario, Subtotal) VALUES ('$id_presupuesto', '$id_producto', '$cantidad', '$precio_unitario', '$subtotal')";
                $conn->query
                ($sql_insert_producto);

                // Calcular total del presupuesto
                $total_operacion += $subtotal;
            }
        }

        // Aplicar descuento
        $descuento = ($total_operacion * $descuento_porcentaje) / 100;
        $total_operacion -= $descuento;

        // Actualizar total del presupuesto en la tabla Presupuestos
        $sql_update_total = "UPDATE Presupuestos SET Total='$total_operacion' WHERE ID_Presupuesto='$id_presupuesto'";
        $conn->query($sql_update_total);

        // Redirigir a la página de listado de presupuestos
        header("Location: presupuestos.php");
        exit;
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}
?>

<h2>Alta de Presupuesto</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="id_cliente">Cliente:</label>
    <select name="id_cliente" id="id_cliente">
        <?php while($row = $result_clientes->fetch_assoc()): ?>
        <option value="<?php echo $row['ID_Cliente']; ?>"><?php echo $row['Nombre']; ?></option>
        <?php endwhile; ?>
    </select><br>
    <label for="fecha">Fecha:</label>
    <input type="date" id="fecha" name="fecha" value="<?php echo $fecha_actual; ?>"><br>
    <label for="descuento_porcentaje">Descuento (%):</label>
    <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" onchange="calcularTotal()" Value="0" required><br>
    <label for="observaciones">Observaciones:</label>
    <textarea id="observaciones" name="observaciones"></textarea><br>
    <hr>
    <h3>Productos</h3>
    <?php for ($i = 0; $i < 10; $i++): ?>
    <div>
        <label for="productos[<?php echo $i; ?>][id]">Producto:</label>
        <select name="productos[<?php echo $i; ?>][id]" onchange="actualizarPrecioUnitario(this)">
            <option value="">Seleccionar producto</option>
            <?php mysqli_data_seek($result_productos, 0); ?>
            <?php while($row = $result_productos->fetch_assoc()): ?>
            <option value="<?php echo $row['ID_Producto']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php endwhile; ?>
        </select>
        <label for="productos[<?php echo $i; ?>][cantidad]">Cantidad:</label>
        <input type="number" name="productos[<?php echo $i; ?>][cantidad]" onchange="calcularTotal()">
        <label for="productos[<?php echo $i; ?>][precio_unitario]">Precio Unitario:</label>
       
        <input type="number" name="productos[<?php echo $i; ?>][precio_unitario]" onchange="calcularTotal()">
    </div>
    <?php endfor; ?>
    <hr>
    <label for="total_operacion">Total del Presupuesto:</label>
    <input type="text" id="total_operacion" name="total_operacion" value="0" readonly>
    <input type="submit" value="Guardar">
</form>
<script>
function actualizarPrecioUnitario(select) {
    // Aquí puedes agregar código para actualizar el precio unitario si lo necesitas.
    // En este ejemplo, no es necesario porque el precio unitario se obtiene del formulario.
}

function calcularTotal() {
    var totalOperacion = 0;
    var productos = document.querySelectorAll('select[name^="productos["][name$="][id]"]');
    productos.forEach(function(producto) {
        var cantidadInput = producto.parentNode.querySelector('input[name^="productos["][name$="][cantidad]"]');
        var precioUnitarioInput = producto.parentNode.querySelector('input[name^="productos["][name$="][precio_unitario]"]');
        var cantidad = cantidadInput.value;
        var precioUnitario = precioUnitarioInput.value; // Obtener el precio del input en lugar de obtenerlo del select
        totalOperacion += cantidad * precioUnitario;
    });
    var descuentoPorcentaje = document.getElementById('descuento_porcentaje').value;
    totalOperacion -= (totalOperacion * descuentoPorcentaje / 100);
    document.getElementById('total_operacion').value = totalOperacion.toFixed(2);
}
</script>

<?php
include '../includes/footer.php';
?>
