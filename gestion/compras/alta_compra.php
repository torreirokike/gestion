<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Obtener lista de proveedores desde la base de datos
$sql_proveedores = "SELECT ID_Proveedor, Nombre FROM proveedores";
$result_proveedores = $conn->query($sql_proveedores);

// Verificar si la consulta de proveedores tiene errores
if (!$result_proveedores) {
    die("Error al obtener proveedores: " . $conn->error);
}

// Obtener lista de productos desde la base de datos
$sql_productos = "SELECT ID_Producto, Nombre, Precio_Costo FROM Productos";
//echo $sql_productos; // Imprimir consulta SQL para verificar si está formada correctamente
$result_productos = $conn->query($sql_productos);

// Verificar si la consulta de productos tiene errores
if (!$result_productos) {
    die("Error al obtener productos: " . $conn->error);
}

// Obtener fecha actual
$fecha_actual = date("Y-m-d");

// Variables para cálculos
$total_operacion = 0;

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
//    echo "Connected successfully";
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar datos del formulario de alta de compra
    $tipo = "Compra";
    $id_proveedor = $_POST['id_proveedor'];
    $fecha = $_POST['fecha'];
    $descuento_porcentaje = isset($_POST['descuento_porcentaje']) ? $_POST['descuento_porcentaje'] : 0; // Asigna 0 si no se proporciona un descuento
    $observaciones = $_POST['observaciones'];

    // Insertar datos en la tabla de Operaciones
    $sql_insert = "INSERT INTO Operaciones (Tipo, ID_Proveedor, Fecha, Descuento_Porcentaje, Observaciones) VALUES ('$tipo', '$id_proveedor', '$fecha', '$descuento_porcentaje', '$observaciones')";
    if ($conn->query($sql_insert) === TRUE) {
        $id_operacion = $conn->insert_id;

        // Insertar productos de la compra en la tabla Productos_Operaciones
        for ($i = 0; $i < 10; $i++) {
            if (!empty($_POST['productos'][$i]['id'])) {
                $id_producto = $_POST['productos'][$i]['id'];
                $cantidad = $_POST['productos'][$i]['cantidad'];
                $precio_unitario = $_POST['productos'][$i]['precio_unitario'];
                $subtotal = $cantidad * $precio_unitario;
                $sql_insert_producto = "INSERT INTO Productos_Operaciones (ID_Operacion, ID_Producto, Cantidad, Precio_Unitario, Subtotal) VALUES ('$id_operacion', '$id_producto', '$cantidad', '$precio_unitario', '$subtotal')";
                $conn->query($sql_insert_producto);

                // Calcular total de la operación
                $total_operacion += $subtotal;
            }
        }

        // Aplicar descuento
        $descuento = ($total_operacion * $descuento_porcentaje) / 100;
        $total_operacion -= $descuento;

        // Actualizar total de la operación en la tabla Operaciones
        $sql_update_total = "UPDATE Operaciones SET Total='$total_operacion' WHERE ID_Operacion='$id_operacion'";
        $conn->query($sql_update_total);

        // Redirigir a la página de listado de compras
        header("Location: compras.php");
        exit;
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error;
    }
}
?>

<h2>Alta de Compra</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label for="id_proveedor">Proveedor:</label>
    <select name="id_proveedor" id="id_proveedor">
        <?php if ($result_proveedores !== false): ?>
            <?php while($row = $result_proveedores->fetch_assoc()): ?>
                <option value="<?php echo $row['ID_Proveedor']; ?>"><?php echo $row['Nombre']; ?></option>
            <?php endwhile; ?>
        <?php else: ?>
            <option value="">No se pudieron cargar los proveedores</option>
        <?php endif; ?>
    </select><br>
    <label for="fecha">Fecha:</label>
    <input type="date" id="fecha" name="fecha" value="<?php echo $fecha_actual; ?>"><br>
    <label for="descuento_porcentaje">Descuento (%):</label>
    <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" onchange="calcularTotal()" value="0" required><br>
    <label for="observaciones">Observaciones:</label>
    <textarea id="observaciones" name="observaciones"></textarea><br>
    <hr>
    <h3>Productos</h3>
    <?php for ($i = 0; $i < 10; $i++): ?>
        <div>
            <label for="productos[<?php echo $i; ?>][id]">Producto:</label>
            <select name="productos[<?php echo $i; ?>][id]" onchange="actualizarPrecioUnitario(this)">
                <option value="">Seleccionar producto</option>
                <?php if ($result_productos !== false): ?>
                    <?php mysqli_data_seek($result_productos, 0); ?>
                    <?php while($row = $result_productos->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_Producto']; ?>" data-precio="<?php echo $row['Precio_Costo']; ?>"><?php echo $row['Nombre']; ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No se pudieron cargar los productos</option>
                <?php endif; ?>
            </select>
            <label for="productos[<?php echo $i; ?>][cantidad]">Cantidad:</label>
            <input type="number" name="productos[<?php echo $i; ?>][cantidad]" onchange="calcularTotal()">
            <label for="productos[<?php echo $i; ?>][precio_unitario]">Precio Unitario:</label>
            <input type="number" name="productos[<?php echo $i; ?>][precio_unitario]" onchange="calcularTotal()">
        </div>
    <?php endfor; ?>
    <hr>
    <label for="total_operacion">Total de la Operación:</label>
    <input type="text" id="total_operacion" name="total_operacion" value="0" readonly>
    <input type="submit" value="Guardar">
</form>
<script>
    function actualizarPrecioUnitario(select) {
        var precioUnitario = select.options[select.selectedIndex].getAttribute('data-precio');
        var inputPrecioUnitario = select.parentNode.querySelector('input[name^="productos["][name$="][precio_unitario]"]');
        inputPrecioUnitario.value = precioUnitario; // Establecer el valor del precio unitario
        calcularTotal();
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
