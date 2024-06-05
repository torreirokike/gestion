<?php
include '../includes/db_connect.php';


$id = $_GET['id'];

$sql = "SELECT Productos.*, Proveedores.Nombre AS Proveedor_Nombre FROM Productos LEFT JOIN Proveedores ON Productos.Proveedor_ID = Proveedores.ID_Proveedor WHERE ID_Producto=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $producto = $result->fetch_assoc();
} else {
    echo "Producto no encontrado.";
    exit;
}
?>

<h2>Ficha del Producto</h2>
<p><strong>Nombre:</strong> <?php echo $producto['Nombre']; ?></p>
<p><strong>Precio de Costo:</strong> <?php echo $producto['Precio_Costo']; ?></p>
<p><strong>Precio de Venta:</strong> <?php echo $producto['Precio_Venta']; ?></p>
<p><strong>Marca:</strong> <?php echo $producto['Marca']; ?></p>
<p><strong>Proveedor:</strong> <?php echo $producto['Proveedor_Nombre']; ?></p>
<p><strong>Observaciones:</strong> <?php echo $producto['Observaciones']; ?></p>

<button onclick="window.print()">Imprimir</button>

<?php
?>
