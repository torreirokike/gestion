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
            <table id="productos_table">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
                <?php while($row_producto_operacion = $result_productos_operacion->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <select name="productos[<?php echo $row_producto_operacion['ID_Producto_Operacion']; ?>][id]" onchange="calcularSubtotal(this)">
                                <option value="">Seleccionar producto</option>
                                <?php mysqli_data_seek($result_productos, 0); ?>
                                <?php while($row = $result_productos->fetch_assoc()): ?>
                                    <option value="<?php echo $row['ID_Producto']; ?>" <?php if($row['ID_Producto'] == $row_producto_operacion['ID_Producto']) echo 'selected="selected"'; ?>><?php echo $row['Nombre']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td><input type="number" name="productos[<?php echo $row_producto_operacion['ID_Producto_Operacion']; ?>][cantidad]" value="<?php echo $row_producto_operacion['Cantidad']; ?>" onchange="calcularSubtotal(this)"></td>
                        <td><input type="number" name="productos[<?php echo $row_producto_operacion['ID_Producto_Operacion']; ?>][precio_unitario]" value="<?php echo $row_producto_operacion['Precio_Unitario']; ?>" onchange="calcularSubtotal(this)"></td>
                        <td class="subtotal"><?php echo $row_producto_operacion['Subtotal']; ?></td>
                        <td><button type="button" onclick="eliminarProducto(this)">Eliminar</button></td>
                    </tr>
                <?php endwhile; ?>
                <tr id="productos_nuevos_row">
                    <td colspan="5"><button type="button" onclick="agregarProducto()">Agregar Producto</button></td>
                </tr>
            </table>
            <hr>
            <label for="total_operacion">Total de la Operación:</label>
            <input type="text" id="total_operacion" name="total_operacion" value="<?php echo $row_compra['Total']; ?>" readonly>
            <input type="submit" value="Guardar">
        </form>
        <script>
            function calcularSubtotal(select) {
                var row = select.parentNode.parentNode;
                var cantidad = row.querySelector('input[name^="productos["][name$="][cantidad]"]').value;
                var precioUnitario = row.querySelector('input[name^="productos["][name$="][precio_unitario]"]').value;
                var subtotal = cantidad * precioUnitario;
                row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                calcularTotal();
            }

            function calcularTotal() {
                var totalOperacion = 0;
                var subtotales = document.querySelectorAll('.subtotal');
                subtotales.forEach(function(subtotal) {
                    totalOperacion += parseFloat(subtotal.textContent);
                });
                var descuentoPorcentaje = document.getElementById('descuento_porcentaje').value;
                totalOperacion -= (totalOperacion * descuentoPorcentaje / 100);
                document.getElementById('total_operacion').value = totalOperacion.toFixed(2);
            }

            function agregarProducto() {
                var table = document.getElementById('productos_table');
                var newRow
                = table.insertRow(table.rows.length - 1); // Insertar antes de la última fila (botón de agregar)
                newRow.innerHTML = `
                    <td>
                        <select name="productos[nuevo][id]" onchange="calcularSubtotal(this)">
                            <option value="">Seleccionar producto</option>
                            <?php mysqli_data_seek($result_productos, 0); ?>
                            <?php while($row = $result_productos->fetch_assoc()): ?>
                                <option value="<?php echo $row['ID_Producto']; ?>" data-precio-venta="<?php echo $row['Precio_Venta']; ?>"><?php echo $row['Nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                    <td><input type="number" name="productos[nuevo][cantidad]" value="1" onchange="calcularSubtotal(this)"></td>
                    <td><input type="number" name="productos[nuevo][precio_unitario]" value="" onchange="calcularSubtotal(this)"></td>
                    <td class="subtotal">0</td>
                    <td><button type="button" onclick="eliminarProducto(this)">Eliminar</button></td>
                `;
            }

            function eliminarProducto(button) {
                var row = button.parentNode.parentNode;
                row.parentNode.removeChild(row);
                calcularTotal();
            }

            // Calcular el total al cargar la página
            window.addEventListener('load', calcularTotal);
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
