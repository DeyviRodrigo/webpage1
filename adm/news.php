<?php
session_start();

if (!isset($_SESSION['login'])) {
    header('location:login.php');
    exit();
}

$nivelUsuario = (int) ($_SESSION['nivel'] ?? 0);
if (!in_array($nivelUsuario, [1, 3], true)) {
    $mensaje = urlencode('No tiene permisos para gestionar noticias.');
    header("Location: user.php?mensaje={$mensaje}");
    exit();
}

$usuarioId = (int) ($_SESSION['idUser'] ?? 0);
$nombreUsuario = $_SESSION['nombre'] ?? 'Usuario';
$mensajeFlash = isset($_GET['mensaje']) ? trim((string) $_GET['mensaje']) : '';
$errorFlash   = isset($_GET['error']) ? trim((string) $_GET['error']) : '';
$supabaseImportSummary = $_SESSION['supabase_import_summary'] ?? null;
if (isset($_SESSION['supabase_import_summary'])) {
    unset($_SESSION['supabase_import_summary']);
}
$hasImportSummary = is_array($supabaseImportSummary);
$canUploadBanner = in_array($nivelUsuario, [1, 2], true);
$canPublishNews = true;
$canGrantPermissions = ($nivelUsuario === 1);
$canManageBanners = ($nivelUsuario === 1);
$canManageNews = ($nivelUsuario === 1);

require_once __DIR__ . '/../includes/repositories/PermissionRepository.php';

$permissionConnection = null;
try {
    $permissionConnection = new MySQLcn();
    $permissionRepository = new PermissionRepository($permissionConnection);
    $canManageBanners = $permissionRepository->userCanManageBanners($usuarioId);
    $canManageNews = $permissionRepository->userCanManageNews($usuarioId);
} catch (Throwable $exception) {
    $permissionRepository = null;
}

if ($permissionConnection !== null) {
    $permissionConnection->Close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicar noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/dashboard-theme.css">
</head>
<body class="dashboard-body">
<div class="dashboard-shell">
    <?php
    $activeSidebar = 'publish_news';
    require __DIR__ . '/includes/dashboard_sidebar.php';
    ?>

    <main class="dashboard-main">
        <div class="dashboard-main-inner">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php if ($mensajeFlash !== ''): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($mensajeFlash, ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($errorFlash !== ''): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($errorFlash, ENT_QUOTES, 'UTF-8'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <div class="card dashboard-card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h4 class="mb-0">Publicar nueva noticia</h4>
                    <form id="bulkUploadForm" action="importar_noticias_supabase.php" method="POST" class="m-0">
                        <button type="submit" class="btn btn-outline-light d-flex align-items-center gap-2">
                            <i class="fas fa-cloud-download-alt"></i>
                            <span>Subir en bloque</span>
                        </button>
                    </form>
                </div>
                <div class="card-body">
                    <form action="procesar_noticia.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="titulo" class="form-label mb-0">Título</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="estado" name="estado" checked>
                                    <label class="form-check-label" for="estado">Publicar inmediatamente</label>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="titulo" name="titulo" maxlength="250" required>
                        </div>

                        <div class="mb-3">
                            <label for="enlace" class="form-label">Enlace externo (opcional)</label>
                            <input
                                type="url"
                                class="form-control"
                                id="enlace"
                                name="enlace"
                                placeholder="https://ejemplo.com/noticia-completa"
                                maxlength="500"
                                inputmode="url"
                            >
                            <div class="form-text">Si agregas una URL, el botón «Leer más» llevará a esa página.</div>
                        </div>

                        <div class="mb-3">
                            <label for="contenido" class="form-label">Contenido</label>
                            <textarea class="form-control" id="contenido" name="contenido" rows="8" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Imagen destacada (opcional)</label>
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                <p class="mb-0">Arrastra y suelta una imagen aquí o haz clic para seleccionar</p>
                                <input type="file" class="d-none" id="imagen" name="imagen" accept="image/*">
                            </div>
                            <div id="preview" class="text-center mt-3"></div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Publicar noticia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('imagen');
    const preview = document.getElementById('preview');

    uploadArea.addEventListener('click', () => fileInput.click());

    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            fileInput.files = e.dataTransfer.files;
            showPreview(file);
        }
    });

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            showPreview(file);
        }
    });

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.innerHTML = `<img src="${e.target.result}" class="preview-image" alt="Vista previa">`;
        };
        reader.readAsDataURL(file);
    }

    const bulkUploadForm = document.getElementById('bulkUploadForm');
    if (bulkUploadForm) {
        bulkUploadForm.addEventListener('submit', (event) => {
            const confirmation = confirm('¿Deseas importar todas las noticias disponibles desde Supabase?');
            if (!confirmation) {
                event.preventDefault();
                return;
            }

            const submitButton = bulkUploadForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('disabled');
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="ms-2">Importando…</span>';
            }
        });
    }
<?php if ($hasImportSummary): ?>
    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('supabaseImportSummaryModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    });
<?php endif; ?>
</script>
<?php if ($hasImportSummary): ?>
<div class="modal fade" id="supabaseImportSummaryModal" tabindex="-1" aria-labelledby="supabaseImportSummaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="supabaseImportSummaryModalLabel">Resumen de importación desde Supabase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><strong>Procesadas:</strong> <?php echo (int) ($supabaseImportSummary['processed'] ?? 0); ?></p>
                <p class="mb-2"><strong>Nuevas:</strong> <?php echo (int) ($supabaseImportSummary['created_news'] ?? 0); ?></p>
                <p class="mb-3"><strong>Omitidas:</strong> <?php echo (int) ($supabaseImportSummary['skipped'] ?? 0); ?></p>

                <?php $modalErrors = is_array($supabaseImportSummary['errors'] ?? null) ? $supabaseImportSummary['errors'] : []; ?>
                <?php if (count($modalErrors) === 0): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle me-2"></i> La importación se completó sin errores.
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Se encontraron <?php echo count($modalErrors); ?> errores durante la importación.
                    </div>
                    <div class="accordion" id="supabaseImportErrors">
                        <?php foreach ($modalErrors as $index => $errorItem): ?>
                            <?php
                                $errorId = 'supabaseImportError' . $index;
                                $collapseId = 'supabaseImportErrorCollapse' . $index;
                                $recordJson = isset($errorItem['record_json']) && is_string($errorItem['record_json']) ? $errorItem['record_json'] : '';
                                $details = isset($errorItem['details']) && is_array($errorItem['details']) ? $errorItem['details'] : [];
                                $sql = isset($details['sql']) ? (string) $details['sql'] : '';
                                $parameters = isset($details['parameters']) && is_array($details['parameters']) ? $details['parameters'] : [];
                                $databaseError = isset($details['database_error']) ? (string) $details['database_error'] : '';
                            ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="<?php echo htmlspecialchars($errorId, ENT_QUOTES, 'UTF-8'); ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8'); ?>" aria-expanded="false" aria-controls="<?php echo htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8'); ?>">
                                        Error <?php echo $index + 1; ?>: <?php echo htmlspecialchars((string) ($errorItem['message'] ?? 'Error desconocido'), ENT_QUOTES, 'UTF-8'); ?>
                                    </button>
                                </h2>
                                <div id="<?php echo htmlspecialchars($collapseId, ENT_QUOTES, 'UTF-8'); ?>" class="accordion-collapse collapse" aria-labelledby="<?php echo htmlspecialchars($errorId, ENT_QUOTES, 'UTF-8'); ?>" data-bs-parent="#supabaseImportErrors">
                                    <div class="accordion-body">
                                        <p class="mb-1"><strong>Mensaje:</strong> <?php echo htmlspecialchars((string) ($errorItem['message'] ?? 'Error desconocido'), ENT_QUOTES, 'UTF-8'); ?></p>
                                        <?php if ($recordJson !== ''): ?>
                                            <p class="mb-1"><strong>Registro recibido:</strong></p>
                                            <pre class="bg-light p-3 border rounded small text-break"><?php echo htmlspecialchars($recordJson, ENT_QUOTES, 'UTF-8'); ?></pre>
                                        <?php endif; ?>
                                        <?php if ($sql !== ''): ?>
                                            <p class="mb-1"><strong>SQL ejecutado:</strong></p>
                                            <pre class="bg-light p-3 border rounded small text-break"><?php echo htmlspecialchars($sql, ENT_QUOTES, 'UTF-8'); ?></pre>
                                        <?php endif; ?>
                                        <?php if (!empty($parameters)): ?>
                                            <p class="mb-1"><strong>Parámetros:</strong></p>
                                            <pre class="bg-light p-3 border rounded small text-break"><?php echo htmlspecialchars(json_encode($parameters, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES, 'UTF-8'); ?></pre>
                                        <?php endif; ?>
                                        <?php if ($databaseError !== ''): ?>
                                            <p class="mb-1"><strong>Error de la base de datos:</strong></p>
                                            <pre class="bg-light p-3 border rounded small text-break"><?php echo htmlspecialchars($databaseError, ENT_QUOTES, 'UTF-8'); ?></pre>
                                        <?php endif; ?>
                                        <?php if (!empty($errorItem['error_class'])): ?>
                                            <p class="mb-0"><strong>Tipo de error:</strong> <?php echo htmlspecialchars((string) $errorItem['error_class'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
</body>
</html>
