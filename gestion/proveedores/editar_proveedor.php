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

    $sql = "UPDATE Proveedores SET Nombre='$nombre', Razon_Social='$razon_social', Tipo_Documento='$tipo_documento', Documento='$documento', Telefono='$telefono', Direccion='$direccion', Observaciones='$observaciones' WHERE ID_Proveedor=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Proveedor actualizado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "SELECT * FROM Proveedores WHERE ID_Proveedor=$id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $proveedor = $result->fetch_assoc();
    } else {
        echo "Proveedor no encontrado.";
        exit;
    }
}
?>

<h2>Editar Proveedor</h2>
<form method="post" action="editar_proveedor.php?id=<?php echo $id; ?>">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($proveedor['Nombre']) ? $proveedor['Nombre'] : ''; ?>" required><br>
    <label for="razon_social">Razón Social:</label><br>
    <input type="text" id="razon_social" name="razon_social" value="<?php echo isset($proveedor['Razon_Social']) ? $proveedor['Razon_Social'] : ''; ?>" required><br>
    <label for="tipo_documento">Tipo de Documento:</label><br>
    <select id="tipo_documento" name="tipo_documento" required>
        <option value="Cuit" <?php if (isset($proveedor['Tipo_Documento']) && $proveedor['Tipo_Documento'] == 'Cuit') echo 'selected'; ?>>Cuit</option>
        <option value="DNI" <?php if (isset($proveedor['Tipo_Documento']) && $proveedor['Tipo_Documento'] == 'DNI') echo 'selected'; ?>>DNI</option>
    </select><br>
    <label for="documento">Documento:</label><br>
    <input type="text" id="documento" name="documento" value="<?php echo isset($proveedor['Documento']) ? $proveedor['Documento'] : ''; ?>" required><br>
    <label for="telefono">Teléfono:</label><br>
    <input type="text" id="telefono" name="telefono" value="<?php echo isset($proveedor['Telefono']) ? $proveedor['Telefono'] : ''; ?>"><br>
    <label for="direccion">Dirección:</label><br>
    <input type="text" id="direccion" name="direccion" value="<?php echo isset($proveedor['Direccion']) ? $proveedor['Direccion'] : ''; ?>"><br>
    <label for="observaciones">Observaciones:</label><br>
    <textarea id="observaciones" name="observaciones"><?php echo isset($proveedor['Observaciones']) ? $proveedor['Observaciones'] : ''; ?></textarea><br><br>
    <input type="submit" value="Actualizar Proveedor">
</form>

<?php
include '../includes/footer.php';
?>
