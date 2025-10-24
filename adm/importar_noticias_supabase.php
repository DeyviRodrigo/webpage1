<?php

declare(strict_types=1);

session_start();

if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

$nivelUsuario = (int) ($_SESSION['nivel'] ?? 0);
if (!in_array($nivelUsuario, [1, 3], true)) {
    $mensaje = urlencode('No tiene permisos para gestionar noticias.');
    header("Location: user.php?mensaje={$mensaje}");
    exit();
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Location: news.php');
    exit();
}

$usuarioId = (int) ($_SESSION['idUser'] ?? 0);

require_once __DIR__ . '/../integracion_supabase_gpt.php';
require_once __DIR__ . '/includes/repositories/PermissionRepository.php';

$canManageNews = false;
$connection = null;

try {
    $connection = new MySQLcn();
    $permissionRepository = new PermissionRepository($connection);
    $canManageNews = $permissionRepository->userCanManageNews($usuarioId);
} catch (Throwable $throwable) {
    if ($connection instanceof MySQLcn) {
        $connection->Close();
    }

    header('Location: news.php?error=' . urlencode('No fue posible verificar los permisos del usuario.'));
    exit();
}

if (!$canManageNews) {
    $connection->Close();
    $mensaje = urlencode('No tiene permisos para gestionar noticias.');
    header("Location: user.php?mensaje={$mensaje}");
    exit();
}

$summary = null;

try {
    $integrator = new SupabaseNewsIntegrator($connection);
    $summary = $integrator->import();
} catch (Throwable $throwable) {
    $connection->Close();
    header('Location: news.php?error=' . urlencode('La importación desde Supabase falló: ' . $throwable->getMessage()));
    exit();
}

$connection->Close();

$processed = (int) ($summary['processed'] ?? 0);
$created   = (int) ($summary['created_news'] ?? 0);
$skipped   = (int) ($summary['skipped'] ?? 0);
$errors    = $summary['errors'] ?? [];

$mensajePartes = [
    sprintf('Importación completada. Procesadas: %d.', $processed),
    sprintf('Nuevas: %d.', $created),
    sprintf('Omitidas: %d.', $skipped),
];

if (is_array($errors) && count($errors) > 0) {
    $mensajePartes[] = sprintf('Con %d errores.', count($errors));
}

$mensaje = urlencode(implode(' ', $mensajePartes));

header('Location: news.php?mensaje=' . $mensaje);
exit();

