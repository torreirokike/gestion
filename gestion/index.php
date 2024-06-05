<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'includes/header.php';
?>

<link rel="stylesheet" type="text/css" href="css/styles.css">

<div class="container">
    <h1>Sistema de Gestión</h1>
    <p>Bienvenido, <?php echo $_SESSION['name']; ?>!</p>
    <?php if ($_SESSION['role'] == 'Admin'): ?>
        <p>Tienes acceso de Administrador.</p>
    <?php else: ?>
        <p>Tienes acceso de Usuario.</p>
    <?php endif; ?>
    <div class="columns">
        <div class="column">
            <h2>Ventas</h2>
            <ul>
                <li><a href="ventas/alta_venta.php">Registrar Venta</a></li>
                <li><a href="ventas/ventas.php">Ver Ventas</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Presupuestos</h2>
            <ul>
                <li><a href="presupuestos/alta_presupuesto.php">Registrar Presupuesto</a></li>
                <li><a href="presupuestos/presupuestos.php">Ver Presupuestos</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Compras</h2>
            <ul>
                <li><a href="compras/alta_compra.php">Registrar Compra</a></li>
                <li><a href="compras/compras.php">Ver Compras</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Productos</h2>
            <ul>
                <li><a href="productos/alta_producto.php">Agregar Producto</a></li>
                <li><a href="productos/productos.php">Ver Productos</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Proveedores</h2>
            <ul>
                <li><a href="proveedores/alta_proveedor.php">Agregar Proveedor</a></li>
                <li><a href="proveedores/proveedores.php">Ver Proveedores</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Clientes</h2>
            <ul>
                <li><a href="clientes/alta_cliente.php">Agregar Cliente</a></li>
                <li><a href="clientes/clientes.php">Ver Clientes</a></li>
            </ul>
        </div>
        <div class="column">
            <h2>Caja</h2>
            <ul>
                <li><a href="caja/registro_movimiento.php">Registrar Movimiento</a></li>
                <li><a href="caja/caja.php">Ver Caja</a></li>
            </ul>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
