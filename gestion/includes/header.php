<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <h1>Sistema de Gestión</h1>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../ventas/ventas.php">Ventas</a></li>
                <li><a href="../presupuestos/presupuestos.php">Presupuestos</a></li>
                <li><a href="../compras/compras.php">Compras</a></li>
                <li><a href="../productos/productos.php">Productos</a></li>
                <li><a href="../proveedores/proveedores.php">Proveedores</a></li>
                <li><a href="../clientes/clientes.php">Clientes</a></li>
                <li><a href="../caja/caja.php">Caja</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php">Iniciar Sesión</a></li>
                    <li><a href="register.php">Registrarse</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
