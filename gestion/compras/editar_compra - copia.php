<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar datos del formulario de edición de compra
    if(isset($_POST['id_compra'])) {
        $id_compra = $_POST['id_compra'];
        // Implementa el código para procesar los cambios y actualizar la compra en la base de datos aquí
    } else {
        echo "ID de compra no proporcionado.";
        exit;
    }
}

// Obtener el ID de la operación de la compra a editar
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_compra = $_GET['id'];

    // Depuración: Imprimir el valor de $_GET['id']
    echo "ID de compra desde URL: " . $_GET['id'];

    // Obtener detalles de la compra desde la base de datos
    $sql_compra = "SELECT * FROM Operaciones WHERE ID_Operacion = $id_compra";
    $result_compra = $conn->query($sql_compra);

    if ($result_compra->num_rows > 0) {
        $row_compra = $result_compra->fetch_assoc();
    } else {
        echo "No se encontró la compra.";
        exit;
    }

    // Obtener los artículos comprados en la operación original
    $sql_articulos = "SELECT * FROM Productos_Operaciones WHERE ID_Operacion = $id_compra";
    $result_articulos = $conn->query($sql_articulos);
} else {
    echo "ID de compra no proporcionado en la URL.";
    exit;
}
?>

<h2>Editar Compra <?php echo $id_compra; ?></h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <!-- Campo oculto para almacenar el ID de la compra -->
    <input type="hidden" name="id_compra" value="<?php echo $id_compra; ?>">

    <!-- Lista desplegable para seleccionar el proveedor -->
    <label for="proveedor">Proveedor:</label>
    <select id="proveedor" name="proveedor">
        <?php
        // Consulta para obtener la lista de proveedores
        $sql_proveedores = "SELECT ID_Proveedor, Nombre FROM Proveedores";
        $result_proveedores = $conn->query($sql_proveedores);
        if ($result_proveedores->num_rows > 0) {
            while($row_proveedor = $result_proveedores->fetch_assoc()) {
                // Comprueba si este proveedor es el proveedor original de la operación
                $selected = ($row_compra['ID_Proveedor'] == $row_proveedor['ID_Proveedor']) ? "selected" : "";
                // Crea una opción para este proveedor
                echo "<option value='" . $row_proveedor['ID_Proveedor'] . "' $selected>" . $row_proveedor['Nombre'] . "</option>";
            }
        }
        ?>
    </select><br>

    <!-- Campo para editar el Descuento_Porcentaje -->
    <label for="Descuento_Porcentaje">Descuento_Porcentaje (%):</label>
    <input type="number" id="Descuento_Porcentaje" name="Descuento_Porcentaje" value="<?php echo $row_compra['Descuento_Porcentaje']; ?>"><br>

    <hr>
    <h3>Artículos Comprados</h3>
    <!-- Muestra los artículos comprados en la operación original -->
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Unitario</th>
            <th>Subtotal</th>
            <th>Acciones</th>
        </tr>
        <?php while($row_articulo = $result_articulos->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row_articulo['ID_Producto']; ?></td>
            <td><?php echo $row_articulo['Cantidad']; ?></td>
            <td><?php echo $row_articulo['Precio_Unitario']; ?></td>
            <td><?php echo $row_articulo['Subtotal']; ?></td>
            <td><a href="eliminar_articulo.php?id=<?php echo $row_articulo['ID_Producto_Operacion']; ?>">Eliminar</a></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Agrega la posibilidad de agregar hasta 10 productos nuevos -->
    <!-- Implementa el código para agregar nuevos productos aquí -->

    <input type="submit" value="Guardar Cambios">
</form>

<?php
include '../includes/footer.php';
?>
