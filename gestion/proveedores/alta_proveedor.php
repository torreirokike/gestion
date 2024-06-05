<?php
include '..//includes/db_connect.php';
include '..//includes/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $razon_social = $_POST['razon_social'];
    $tipo_documento = $_POST['tipo_documento'];
    $documento = $_POST['documento'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $observaciones = $_POST['observaciones'];

    $sql = "INSERT INTO Proveedores (Nombre, Razon_Social, Tipo_Documento, Documento, Telefono, Direccion, Observaciones) 
            VALUES ('$nombre', '$razon_social', '$tipo_documento', '$documento', '$telefono', '$direccion', '$observaciones')";

    if ($conn->query($sql) === TRUE) {
        echo "Nuevo proveedor agregado exitosamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<h2>Alta de Proveedor</h2>
<form method="post" action="alta_proveedor.php">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" required><br>
    <label for="razon_social">Razón Social:</label><br>
    <input type="text" id="razon_social" name="razon_social" required><br>
    <label for="tipo_documento">Tipo de Documento:</label><br>
    <select id="tipo_documento" name="tipo_documento" required>
        <option value="CUIT">CUIT</option>
        <option value="DNI">DNI</option>
    </select><br>
    <label for="documento">Documento:</label><br>
    <input type="text" id="documento" name="documento" required><br>
    <label for="telefono">Teléfono:</label><br>
    <input type="text" id="telefono" name="telefono"><br>
    <label for="direccion">Dirección:</label><br>
    <input type="text" id="direccion" name="direccion"><br>
    <label for="observaciones">Observaciones:</label><br>
    <textarea id="observaciones" name="observaciones"></textarea><br><br>
    <input type="submit" value="Agregar Proveedor">
</form>

<?php
include '..//includes/footer.php';
?>
