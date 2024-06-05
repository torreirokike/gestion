<?php
include '../includes/db_connect.php';
include '../includes/header.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar datos del formulario de modificación de compra
    $id_compra = $_POST['id_compra'];
    $id_proveedor = $_POST['id_proveedor'];
    $fecha = $_POST['fecha'];
    $descuento_porcentaje = $_POST['descuento_porcentaje'];

    // Actualizar datos en la tabla de Operaciones
    $sql_update = "UPDATE Operaciones SET ID_Proveedor='$id_proveedor', Fecha='$fecha', Descuento_Porcentaje='$descuento_porcentaje' WHERE ID_Operacion='$id_compra'";
    if ($conn->query($sql_update) === TRUE) {
        // Redirigir a la página de listado de compras
        header("Location: compras.php");
        exit;
    } else {
        echo "Error actualizando compra: " . $conn->error;
        // Imprimir el valor de $id_compra
        echo "<br>ID de compra: " . $id_compra;
    }
} else {
    // Verificar si se ha pasado el ID de la compra a modificar
    if (!empty($_GET['id'])) {
        $id_compra = $_GET['id'];
        // Obtener datos de la compra de la base de datos
        $sql_select = "SELECT * FROM Operaciones WHERE ID_Operacion='$id_compra'";
        $result = $conn->query($sql_select);
        if ($result->num_rows == 1) {
            $compra = $result->fetch_assoc();
        } else {
            echo "No se encontró la compra.";
            // Imprimir el valor de $id_compra
            echo "<br>ID de compra: " . $id_compra;
            exit;
        }
    } else {
        echo "ID de compra no especificado.";
        // Imprimir el valor de $id_compra
        echo "<br>ID de compra: " . $id_compra;
        exit;
    }
}
?>

<h2>Modificar Compra</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="id_compra" value="<?php echo $id_compra; ?>">
    <label for="id_proveedor">Proveedor:</label>
    <select name="id_proveedor" id="id_proveedor">
        <!-- Aquí debes llenar el select con los proveedores desde la base de datos y seleccionar el proveedor de la compra -->
    </select><br>
    <label for="fecha">Fecha:</label>
    <input type="date" id="fecha" name="fecha" value="<?php echo $compra['Fecha']; ?>"><br>
    <label for="descuento_porcentaje">Descuento (%):</label>
    <input type="number" id="descuento_porcentaje" name="descuento_porcentaje" value="<?php echo $compra['Descuento_Porcentaje']; ?>"><br>
    <input type="submit" value="Guardar">
</form>

<?php
include '../includes/footer.php';
?>
