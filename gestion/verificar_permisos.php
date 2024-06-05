<?php
// Iniciar sesión (si aún no está iniciada)
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    // Si el usuario no está autenticado, redirigirlo al formulario de inicio de sesión
    header("Location: login.php");
    exit();
}

// Obtener el nivel de acceso del usuario desde la sesión o la base de datos
$nivel_acceso = $_SESSION['usuario']['nivel_acceso']; // Suponiendo que 'nivel_acceso' es el campo que indica el nivel de acceso en la sesión del usuario

// Verificar si el usuario es administrador o usuario regular
if ($nivel_acceso !== 'admin') {
    // Si el usuario no es administrador, verificar si la página actual está en la lista de páginas permitidas
    $paginas_permitidas = array('index.php', 'ventas.php', 'compras.php', 'presupuestos.php', 'caja.php', 'proveedores.php', 'clientes.php', 'productos.php');
    $pagina_actual = basename($_SERVER['PHP_SELF']); // Obtener el nombre de la página actual
    if (!in_array($pagina_actual, $paginas_permitidas)) {
        // Si la página actual no está en la lista de páginas permitidas, redirigir al usuario a una página de error o mostrar un mensaje de error
        header("Location: error_permisos.php");
        exit();
    }
}
?>
