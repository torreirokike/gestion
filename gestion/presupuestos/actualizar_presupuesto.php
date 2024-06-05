<?php
include '../includes/db_connect.php';

// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_presupuesto = $_POST['id_presupuesto'];
    $fecha = $_POST['fecha'];
    $id_cliente = $_POST['id_cliente'];
    $descuento_porcentaje = $_POST['descuento_porcentaje'];
    $observaciones = $_POST['observaciones'];

    // Actualizar el presupuesto en la base de datos
    $sql_update_presupuesto = "UPDATE Presupuestos 
                                SET Fecha='$fecha', 
                                    ID_Cliente='$id_cliente', 
                                    Descuento_Porcentaje='$descuento_porcentaje', 
                                    Observaciones='$observaciones' 
                                WHERE ID_Presupuesto=$id_presupuesto";

    if ($conn->query($sql_update_presupuesto) === TRUE) {
        // Redirigir a la página de ver presupuesto
        header("Location: ver_presupuesto.php?id=$id_presupuesto");
        exit;
    } else {
        echo "Error al actualizar el presupuesto: " . $conn->error;
    }
} else {
    echo "No se han recibido datos para actualizar.";
}

$conn->close();
?>
