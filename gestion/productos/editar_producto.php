<?php
include '../includes/db_connect.php';
include '../includes/header.php';

$id = $_GET['id'];

// Obtener lista de proveedores
$proveedores = [];
$sql = "SELECT ID_Proveedor, Nombre FROM Proveedores";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $proveedores[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $marca = $_POST['marca'];
    $proveedor_id = $_POST['proveedor_id'];
    $observaciones = $_POST['observaciones'];

    $sql = "UPDATE Productos SET Nombre='$nombre', Precio_Costo='$precio_costo', Precio_Venta='$precio_venta', Marca='$marca', Proveedor_ID='$proveedor_id', Observaciones='$observaciones' WHERE ID_Producto=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Producto actualizado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "SELECT * FROM Productos WHERE ID_Producto=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $producto = $result->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit;
    }
}
?>

<h2>Editar Producto</h2>
<form method="post" action="editar_producto.php?id=<?php echo $id; ?>">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($producto['Nombre']) ? $producto['Nombre'] : ''; ?>" required><br>
    <label for="precio_costo">Precio de Costo:</label><br>
    <input type="number" step="0.01" id="precio_costo" name="precio_costo" value="<?php echo isset($producto['Precio_Costo']) ? $producto['Precio_Costo'] : ''; ?>" required><br>
    <label for="precio_venta">Precio de Venta:</label><br>
    <input type="number" step="0.01" id="precio_venta" name="precio_venta" value="<?php echo isset($producto['Precio_Venta']) ? $producto['Precio_Venta'] : ''; ?>" required><br>
    <label for="marca">Marca:</label><br>
    <input type="text" id="marca" name="marca" value="<?php echo isset($producto['Marca']) ? $producto['Marca'] : ''; ?>"><br>
    <label for="proveedor_id">Proveedor:</label><br>
    <select id="proveedor_id" name="proveedor_id" required>
        <?php foreach ($proveedores as $proveedor): ?>
            <option value="<?php echo $proveedor['ID_Proveedor']; ?>" <?php if (isset($producto['Proveedor_ID']) && $producto['Proveedor_ID'] == $proveedor['ID_Proveedor']) echo 'selected'; ?>>
                <?php echo $proveedor['Nombre']; ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <label for="observaciones">Observaciones:</label><br>
    <textarea id="observaciones" name="observaciones"><?php echo isset($producto['Observaciones']) ? $producto['Observaciones'] : ''; ?></textarea><br><br>
    <input type="submit" value="Actualizar Producto">
</form>

<?php
include '../includes/footer.php';
?>
