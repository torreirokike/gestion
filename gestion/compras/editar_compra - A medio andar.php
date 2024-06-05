<?php
include '../includes/db_connect.php';
include '../includes/header.php';

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id_operacion = $_GET['id'];

    $sql_compra = "SELECT * FROM Operaciones WHERE ID_Operacion = $id_operacion";
    $result_compra = $conn->query($sql_compra);
    if($result_compra->num_rows == 1) {
        $row_compra = $result_compra->fetch_assoc();

        $sql_productos_operaciones = "SELECT po.*, p.Nombre AS NombreProducto FROM Productos_Operaciones po
                                        JOIN Productos p ON po.ID_Producto = p.ID_Producto
                                        WHERE po.ID_Operacion = $id_operacion";
        $result_productos_operaciones = $conn->query($sql_productos_operaciones);

        $sql_proveedores = "SELECT ID_Proveedor, Nombre FROM proveedores";
        $result_proveedores = $conn->query($sql_proveedores);

        $sql_productos = "SELECT ID_Producto, Nombre, Precio_Costo FROM Productos";
        $result_productos = $conn->query($sql_productos);

        ?>
        <h2>Edición de Compra</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="id_proveedor">Proveedor:</label>
            <select name="id_proveedor" id="id_proveedor">
                <?php if ($result_proveedores !== false): ?>
                    <?php while($row = $result_proveedores->fetch_assoc()): ?>
                        <option value="<?php echo $row['ID_Proveedor']; ?>" <?php if($row['ID_Proveedor'] == $row_compra['ID_Proveedor']) echo "selected"; ?>><?php echo $row['Nombre']; ?></option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">No se pudieron cargar los proveedores</option>
                <?php endif; ?>
            </select><br>
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" value="<?php echo $row_compra['Fecha']; ?>"><br>
            <label for="descuento_porcentaje">Descuento (%):</label>
            <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" value="<?php echo $row_compra['Descuento_Porcentaje']; ?>"><br>
            <label for="observaciones">Observaciones:</label>
            <textarea id="observaciones" name="observaciones"><?php echo $row_compra['Observaciones']; ?></textarea><br>
            <hr>
            <h3>Productos</h3>
            <table>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
                <?php $index = 0; ?>
                <?php while($row_producto_operacion = $result_productos_operaciones->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row_producto_operacion['NombreProducto']; ?></td>
                        <td><input type="number" name="productos[<?php echo $index; ?>][cantidad]" value="<?php echo $row_producto_operacion['Cantidad']; ?>"></td>
                        <td><input type="number" name="productos[<?php echo $index; ?>][precio_unitario]" value="<?php echo $row_producto_operacion['Precio_Unitario']; ?>"></td>
                        <td><?php echo $row_producto_operacion['Cantidad'] * $row_producto_operacion['Precio_Unitario']; ?></td>
                        <td><button type="button" onclick="eliminarProducto(<?php echo $row_producto_operacion['ID_Producto_Operacion']; ?>)">Eliminar</button></td>
                    </tr>
                    <?php $index++; ?>
                <?php endwhile; ?>
                <tr id="nuevoProducto">
                    <td>
                        <select name="productos[<?php echo $index; ?>][id]" onchange="actualizarPrecioUnitario(this)">
                            <option value="">Seleccionar producto</option>
                            <?php mysqli_data_seek($result_productos, 0); ?>
                            <?php while($row_producto = $result_productos->fetch_assoc()): ?>
                                <option value="<?php echo $row_producto['ID_Producto']; ?>"><?php echo $row_producto['Nombre']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </td>
                    <td><input type="number" name="productos[<?php echo $index; ?>][cantidad]" value="1"></td>
                    <td><input type="number" name="productos[<?php echo $index; ?>][precio_unitario]" value="0"></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <button type="button" onclick="agregarFila()">Agregar Producto</button>
            <hr>
            <label for="total_operacion">Total de la Operación:</label>
            <input type="text" id="total_operacion" name="total_operacion" value="<?php echo $row_compra['Total']; ?>" readonly>
            <input type="submit" value="Guardar">
            <input type="hidden" name="id_operacion" value="<?php echo $id_operacion; ?>">
        </form>
        <script>
            function actualizarPrecioUnitario(select) {
                var precioUnitario = select.options[select.selectedIndex].getAttribute('data-precio');
                var inputPrecioUnitario = select.parentNode.parentNode.querySelector('input[name^="productos["][name$="][precio_unitario]"]');
                inputPrecioUnitario.value = precioUnitario; // Establecer el valor del precio unitario
                calcularTotal();
            }

            function calcularTotal() {
                var totalOperacion = 0;
                var productos = document.querySelectorAll('input[name^="productos["][name$="][cantidad]');
                var precios = document.querySelectorAll('input[name^="productos["][name$="][precio_unitario]');
                productos.forEach(function(producto, index) {                    var cantidad = producto.value;
                    var precioUnitario = precios[index].value;
                    var subtotal = cantidad * precioUnitario;
                    totalOperacion += subtotal;
                    var subtotalField = producto.parentNode.nextElementSibling.nextElementSibling;
                    subtotalField.textContent = subtotal.toFixed(2);
                });
                var descuentoPorcentaje = document.getElementById('descuento_porcentaje').value;
                totalOperacion -= (totalOperacion * descuentoPorcentaje / 100);
                document.getElementById('total_operacion').value = totalOperacion.toFixed(2);
            }

            function agregarFila() {
                var newRow = document.getElementById('nuevoProducto').cloneNode(true);
                newRow.id = ''; // Eliminar el ID para que no se duplique al agregar otra fila
                var selects = newRow.querySelectorAll('select');
                selects.forEach(function(select) {
                    select.value = ''; // Limpiar el valor del select
                });
                var inputs = newRow.querySelectorAll('input[type="number"]');
                inputs.forEach(function(input) {
                    input.value = ''; // Limpiar el valor de los inputs
                });
                var tbody = document.querySelector('table tbody');
                tbody.appendChild(newRow);
            }

            function eliminarProducto(idProductoOperacion) {
                var confirmation = confirm('¿Estás seguro de que deseas eliminar este producto de la compra?');
                if(confirmation) {
                    // Aquí puedes hacer una solicitud AJAX para eliminar el producto de la base de datos
                    // Y después eliminar la fila de la tabla
                    var row = document.getElementById('producto_' + idProductoOperacion);
                    row.parentNode.removeChild(row);
                }
            }
        </script>
        <?php
    } else {
        echo "La compra seleccionada no existe.";
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Guardar datos";
} else {
    echo "No se proporcionó una ID de compra para editar.";
}

include '../includes/footer.php';
?>

