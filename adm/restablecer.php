<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer contraseña | DAITEC &amp; TrazMAPE</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pY1TpX5YhC2LBVmOAXoJ1i8LojRxurx8WcN6iYG3PaY5E9O+V1YxDCEV4VpWw2X2gYdExgkt1PyicdM+o+ljVw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="container h-100 py-5">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="user_card">
                <div class="d-flex justify-content-center">
                    <div class="brand_logo_container">
                        <span class="brand_initials">DT</span>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h2 class="fw-bold mb-1">Restablecer contraseña</h2>
                    <p class="text-muted mb-0">Verificaremos tus datos y actualizaremos tus credenciales.</p>
                </div>
                <div class="form_container">
                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="resetUsuario" class="form-label">Usuario registrado</label>
                            <input type="text" class="form-control" id="resetUsuario" placeholder="Ingresa tu usuario">
                        </div>
                        <div class="mb-3">
                            <label for="resetCorreo" class="form-label">Correo institucional</label>
                            <input type="email" class="form-control" id="resetCorreo" placeholder="correo@organizacion.gob.pe">
                        </div>
                        <div class="mb-3">
                            <label for="resetDocumento" class="form-label">Documento de identidad</label>
                            <input type="text" class="form-control" id="resetDocumento" placeholder="DNI o CE para validar tu identidad">
                        </div>
                        <div class="mb-3">
                            <label for="resetNuevaClave" class="form-label">Nueva contraseña</label>
                            <input type="password" class="form-control" id="resetNuevaClave" placeholder="Crea una contraseña segura">
                        </div>
                        <div class="mb-4">
                            <label for="resetConfirmaClave" class="form-label">Confirma la nueva contraseña</label>
                            <input type="password" class="form-control" id="resetConfirmaClave" placeholder="Vuelve a escribir la contraseña">
                        </div>
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn login_btn">Actualizar contraseña</button>
                            <a href="index.php" class="secondary_btn text-center">Volver al inicio de sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
