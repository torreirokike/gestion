<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Obtener datos de la compra a editar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $id_operacion = $_GET['id'];
    $sql_compra = "SELECT * FROM Operaciones WHERE ID_Operacion = '$id_operacion'";
    $result_compra = $conn->query($sql_compra);

    if ($result_compra->num_rows > 0) {
        $row_compra = $result_compra->fetch_assoc();

        // Obtener lista de proveedores desde la base de datos
        $sql_proveedores = "SELECT ID_Proveedor, Nombre FROM proveedores";
        $result_proveedores = $conn->query($sql_proveedores);

        // Verificar si la consulta de proveedores tiene errores
        if (!$result_proveedores) {
            die("Error al obtener proveedores: " . $conn->error);
        }

        // Obtener lista de productos desde la base de datos
        $sql_productos = "SELECT ID_Producto, Nombre, Precio_Venta FROM Productos";
        $result_productos = $conn->query($sql_productos);

        // Verificar si la consulta de productos tiene errores
        if (!$result_productos) {
            die("Error al obtener productos: " . $conn->error);
        }

        // Obtener productos asociados a la operación
        $sql_productos_operacion = "SELECT * FROM Productos_Operaciones WHERE ID_Operacion = '$id_operacion'";
        $result_productos_operacion = $conn->query($sql_productos_operacion);

        // Verificar si la consulta de productos de la operación tiene errores
        if (!$result_productos_operacion) {
            die("Error al obtener productos de la operación: " . $conn->error);
        }
?>
        <h2>Editar Compra</h2>
        <form method="post" action="procesar_edicion_compra.php">
            <input type="hidden" name="id_operacion" value="<?php echo $id_operacion; ?>">
            <label for="id_proveedor">Proveedor:</label>
            <select name="id_proveedor" id="id_proveedor">
                <?php if ($result_proveedores->num_rows > 0): ?>
                    <?php while($row = $result_proveedores->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_Proveedor']; ?>" <?php if($row['ID_Proveedor'] == $row_compra['ID_Proveedor']) echo 'selected="selected"'; ?>><?php echo $row['Nombre']; ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No se pudieron cargar los proveedores</option>
                <?php endif; ?>
            </select><br>
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $row_compra['Fecha']; ?>"><br>
            <label for="descuento_porcentaje">Descuento (%):</label>
            <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" value="<?php echo $row_compra['Descuento_Porcentaje']; ?>" required><br>
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones"><?php echo $row_compra['Observaciones']; ?></textarea><br>
            <hr>
            <h3>Productos</h3>
            <ul id="productos_list">
                <?php while($row_producto_operacion = $result_productos_operacion->fetch_assoc()): ?>
                    <li>
                        <span><?php echo obtenerNombreProducto($row_producto_operacion['ID_Producto']); ?></span>
                        <span>Cantidad: <?php echo $row_producto_operacion['Cantidad']; ?></span>
                        <span>Precio Unitario: <?php echo $row_producto_operacion['Precio_Unitario']; ?></span>
                        <span>Subtotal: <?php echo $row_producto_operacion['Subtotal']; ?></span>
                        <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
                    </li>
                <?php endwhile; ?>
            </ul>
            <button type="button" onclick="agregarProducto()">Agregar Producto</button>
            <hr>
            <label for="total_operacion">Total de la Operación:</label>
            <input type="text" id="total_operacion" name="total_operacion" value="<?php echo $row_compra['Total']; ?>" readonly>
            <input type="submit" value="Guardar">
        </form>
        <script>
            function agregarProducto() {
                var table = document.getElementById('productos_list');
                var newRow = document.createElement('li');
                newRow.innerHTML = `
                    <span>Nombre del Producto</span>
                    <span>Cantidad: 1</span>
                    <span>Precio Unitario: </span>
                    <span>Subtotal: 0</span>
                    <button type="button" onclick="eliminarProducto(this)">Eliminar</button>
                `;
                table.appendChild(newRow);
            }

            function eliminarProducto(button) {
                var row = button.parentNode;
                row.parentNode.removeChild(row);
            }
        </script>
<?php
    } else {
        echo "No se encontró la compra especificada.";
    }
} else {
    echo "Acceso no autorizado.";
}

include '../includes/footer.php';
?>
