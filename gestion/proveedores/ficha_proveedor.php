<?php
include '../includes/db_connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM Proveedores WHERE ID_Proveedor=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $proveedor = $result->fetch_assoc();
} else {
    echo "Proveedor no encontrado.";
    exit;
}
?>

<h2>Ficha del Proveedor</h2>
<p><strong>Nombre:</strong> <?php echo $proveedor['Nombre']; ?></p>
<p><strong>Razón Social:</strong> <?php echo $proveedor['Razon_Social']; ?></p>
<p><strong>Tipo de Documento:</strong> <?php echo $proveedor['Tipo_Documento']; ?></p>
<p><strong>Documento:</strong> <?php echo $proveedor['Documento']; ?></p>
<p><strong>Teléfono:</strong> <?php echo $proveedor['Telefono']; ?></p>
<p><strong>Dirección:</strong> <?php echo $proveedor['Direccion']; ?></p>
<p><strong>Observaciones:</strong> <?php echo $proveedor['Observaciones']; ?></p>

<button onclick="window.print()">Imprimir</button>

<?php

?>
