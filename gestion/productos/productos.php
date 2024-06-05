<?php
include '../includes/db_connect.php';
include '../includes/header.php';

$sql = "SELECT * FROM Productos";
$result = $conn->query($sql);
?>

<h2>Listado de Productos</h2>

<!-- Agregar el botón de alta producto -->
<a href="alta_producto.php" class="btn btn-primary">Agregar Producto</a>

<table>
    <tr>
        <th>Nombre</th>
        <th>Precio de Costo</th>
        <th>Precio de Venta</th>
        <th>Marca</th>
        <th>Proveedor</th>
        <th>Acciones</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['Nombre']; ?></td>
        <td><?php echo $row['Precio_Costo']; ?></td>
        <td><?php echo $row['Precio_Venta']; ?></td>
        <td><?php echo $row['Marca']; ?></td>
        <td><?php echo $row['Proveedor_ID']; ?></td>
        <td>
            <a href="editar_producto.php?id=<?php echo $row['ID_Producto']; ?>">Editar</a>
            <a href="eliminar_producto.php?id=<?php echo $row['ID_Producto']; ?>">Eliminar</a>
            <a href="ficha_producto.php?id=<?php echo $row['ID_Producto']; ?>" target="_blank">Imprimir</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
include '../includes/footer.php';
?>
