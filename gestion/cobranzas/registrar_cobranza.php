<?php
include '../includes/db_connect.php'; // Incluir el archivo de conexión a la base de datos

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $cobrar = $_POST['cobrar']; // Un array con los IDs de las operaciones a cobrar
    $monto = $_POST['monto']; // El monto a cobrar

    // Verificar que se ha seleccionado al menos una operación a cobrar y que el monto es válido
    if (!empty($cobrar) && $monto > 0) {
        // Iterar sobre las operaciones seleccionadas y registrar cada cobranza
        foreach ($cobrar as $id_pago_cobro) {
            // Consultar la información de la operación a cobrar
            $sql_operacion = "SELECT * FROM pagos_cobros WHERE ID_PagoCobro = $id_pago_cobro";
            $result_operacion = $conn->query($sql_operacion);

            if ($result_operacion->num_rows == 1) {
                $row_operacion = $result_operacion->fetch_assoc();
                $saldo_pendiente = $row_operacion['SaldoPendiente'];
                $monto_actualizado = $saldo_pendiente - $monto;

                // Actualizar el saldo pendiente de la operación
                $sql_actualizar_saldo = "UPDATE pagos_cobros SET SaldoPendiente = $monto_actualizado WHERE ID_PagoCobro = $id_pago_cobro";

                if ($conn->query($sql_actualizar_saldo) === TRUE) {
                    // Registro exitoso
                    echo "La cobranza se registró correctamente.";
                } else {
                    // Error al registrar la cobranza
                    echo "Error al registrar la cobranza: " . $conn->error;
                }
            } else {
                // No se encontró la operación
                echo "La operación seleccionada no existe.";
            }
        }
    } else {
        // Mostrar un mensaje de error si no se ha seleccionado ninguna operación o el monto es inválido
        echo "Por favor, seleccione al menos una operación a cobrar y asegúrese de ingresar un monto válido.";
    }
} else {
    // Redirigir si se intenta acceder directamente a este archivo sin enviar el formulario
    header("Location: cobranzas.php");
    exit();
}

?>
