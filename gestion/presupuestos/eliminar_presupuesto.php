<?php
include '../includes/db_connect.php';

// Verificar si se ha proporcionado un ID de presupuesto válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_presupuesto = $_GET['id'];

    // Consulta SQL para eliminar el presupuesto
    $sql_delete_presupuesto = "DELETE FROM Presupuestos WHERE ID_Presupuesto = $id_presupuesto";

    if ($conn->query($sql_delete_presupuesto) === TRUE) {
        // Redirigir a la página de listado de presupuestos
        header("Location: presupuestos.php");
        exit;
    } else {
        echo "Error al intentar eliminar el presupuesto: " . $conn->error;
    }
} else {
    echo "ID de presupuesto no válido.";
}

$conn->close();
?>
