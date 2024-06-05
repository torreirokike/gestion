<?php
include '../includes/db_connect.php';
include '../includes/header.php';

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

    $sql = "INSERT INTO Productos (Nombre, Precio_Costo, Precio_Venta, Marca, Proveedor_ID, Observaciones) VALUES ('$nombre', '$precio_costo', '$precio_venta', '$marca', '$proveedor_id', '$observaciones')";

    if ($conn->query($sql) === TRUE) {
        echo "Producto agregado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h2>Agregar Producto</h2>
<form method="post" action="alta_producto.php">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" required><br>
    <label for="precio_costo">Precio de Costo:</label><br>
    <input type="number" step="0.01" id="precio_costo" name="precio_costo" required><br>
    <label for="precio_venta">Precio de Venta:</label><br>
    <input type="number" step="0.01" id="precio_venta" name="precio_venta" required><br>
    <label for="marca">Marca:</label><br>
    <input type="text" id="marca" name="marca"><br>
    <label for="proveedor_id">Proveedor:</label><br>
    <select id="proveedor_id" name="proveedor_id" required>
        <?php foreach ($proveedores as $proveedor): ?>
            <option value="<?php echo $proveedor['ID_Proveedor']; ?>"><?php echo $proveedor['Nombre']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <label for="observaciones">Observaciones:</label><br>
    <textarea id="observaciones" name="observaciones"></textarea><br><br>
    <input type="submit" value="Agregar Producto">
</form>

<?php
include '../includes/footer.php';
?>
