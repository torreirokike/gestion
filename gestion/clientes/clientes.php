<?php
include '../includes/db_connect.php';
include '../includes/header.php';

$sql = "SELECT * FROM Clientes";
$result = $conn->query($sql);
?>

<h2>Listado de Clientes</h2>

<!-- Agregar el botón de alta cliente -->
<a href="alta_cliente.php" class="btn btn-primary">Agregar Cliente</a>

<table>
    <tr>
        <th>Nombre</th>
        <th>Razón Social</th>
        <th>Tipo de Documento</th>
        <th>Documento</th>
        <th>Teléfono</th>
        <th>Dirección</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['Nombre']; ?></td>
        <td><?php echo $row['Razon_Social']; ?></td>
        <td><?php echo $row['Tipo_Documento']; ?></td>
        <td><?php echo $row['Documento']; ?></td>
        <td><?php echo $row['Telefono']; ?></td>
        <td><?php echo $row['Direccion']; ?></td>
        <td>
            <a href="editar_cliente.php?id=<?php echo $row['ID_Cliente']; ?>">Editar</a>
            <a href="eliminar_cliente.php?id=<?php echo $row['ID_Cliente']; ?>">Eliminar</a>
            <a href="ficha_cliente.php?id=<?php echo $row['ID_Cliente']; ?>" target="_blank">Imprimir</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
include '../includes/footer.php';
?>
