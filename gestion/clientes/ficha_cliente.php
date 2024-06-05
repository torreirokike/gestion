<?php
include '../includes/db_connect.php';

$id = $_GET['id'];

$sql = "SELECT * FROM Clientes WHERE ID_Cliente=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cliente = $result->fetch_assoc();
} else {
    echo "Cliente no encontrado.";
    exit;
}
?>

<h2>Ficha del Cliente</h2>
<p><strong>Nombre:</strong> <?php echo $cliente['Nombre']; ?></p>
<p><strong>Razón Social:</strong> <?php echo $cliente['Razon_Social']; ?></p>
<p><strong>Tipo de Documento:</strong> <?php echo $cliente['Tipo_Documento']; ?></p>
<p><strong>Documento:</strong> <?php echo $cliente['Documento']; ?></p>
<p><strong>Teléfono:</strong> <?php echo $cliente['Telefono']; ?></p>
<p><strong>Dirección:</strong> <?php echo $cliente['Direccion']; ?></p>
<p><strong>Observaciones:</strong> <?php echo $cliente['Observaciones']; ?></p>

<button onclick="window.print()">Imprimir</button>

<?php
?>
