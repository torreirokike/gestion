<?php
include '../includes/db_connect.php';
include '../includes/header.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $razon_social = $_POST['razon_social'];
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $observaciones = $_POST['observaciones'];

    $sql = "UPDATE Clientes SET Nombre='$nombre', Razon_Social='$razon_social', Tipo_Documento='$tipo_documento', Documento='$documento', Telefono='$telefono', Direccion='$direccion', Observaciones='$observaciones' WHERE ID_Cliente=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Cliente actualizado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "SELECT * FROM Clientes WHERE ID_Cliente=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        echo "Cliente no encontrado.";
        exit;
    }
}
?>

<h2>Editar Cliente</h2>
<form method="post" action="editar_cliente.php?id=<?php echo $id; ?>">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($cliente['Nombre']) ? $cliente['Nombre'] : ''; ?>" required><br>
    <label for="razon_social">Razón Social:</label><br>
    <input type="text" id="razon_social" name="razon_social" value="<?php echo isset($cliente['Razon_Social']) ? $cliente['Razon_Social'] : ''; ?>" required><br>
    <label for="tipo_documento">Tipo de Documento:</label><br>
    <select id="tipo_documento" name="tipo_documento" required>
        <option value="Cuit" <?php if (isset($cliente['Tipo_Documento']) && $cliente['Tipo_Documento'] == 'Cuit') echo 'selected'; ?>>Cuit</option>
        <option value="DNI" <?php if (isset($cliente['Tipo_Documento']) && $cliente['Tipo_Documento'] == 'DNI') echo 'selected'; ?>>DNI</option>
    </select><br>
    <label for="documento">Documento:</label><br>
    <input type="text" id="documento" name="documento" value="<?php echo isset($cliente['Documento']) ? $cliente['Documento'] : ''; ?>" required><br>
    <label for="telefono">Teléfono:</label><br>
    <input type="text" id="telefono" name="telefono" value="<?php echo isset($cliente['Telefono']) ? $cliente['Telefono'] : ''; ?>"><br>
    <label for="direccion">Dirección:</label><br>
    <input type="text" id="direccion" name="direccion" value="<?php echo isset($cliente['Direccion']) ? $cliente['Direccion'] : ''; ?>"><br>
    <label for="observaciones">Observaciones:</label><br>
    <textarea id="observaciones" name="observaciones"><?php echo isset($cliente['Observaciones']) ? $cliente['Observaciones'] : ''; ?></textarea><br><br>
    <input type="submit" value="Actualizar Cliente">
</form>

<?php
include '../includes/footer.php';
?>
