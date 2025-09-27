<?php
//================================================//
//===========INICIAR SESION==========//
//================================================//
//require "login.php";

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['mensaje'])) {
    // Obtener el mensaje de la URL si existe
    $mensaje = urldecode($_GET['mensaje']);
} else {
    $mensaje = "Inicie sesión";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso administrativo | DAITEC &amp; TrazMAPE</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pY1TpX5YhC2LBVmOAXoJ1i8LojRxurx8WcN6iYG3PaY5E9O+V1YxDCEV4VpWw2X2gYdExgkt1PyicdM+o+ljVw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script language='JavaScript' type='text/javascript' src='js/generax.js'></script>
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
                    <h2 class="fw-bold mb-1">Inicia sesión</h2>
                    <p class="text-muted mb-0">Panel administrativo de DAITEC &amp; TrazMAPE</p>
                </div>
                <div class="form_container">
                    <form name='sesion' action='login.php' onsubmit='return iniciar();' method='POST'>
                        <div id="mensaje">
                            <?php echo htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="users" class="form-control input_user" placeholder="Nombre de usuario" autocomplete="username">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="pass" class="form-control input_pass" placeholder="Contraseña" autocomplete="current-password">
                        </div>
                        <div class="mb-3 text-center">
                            <img class="captcha-image" width="140" height="36" src='script/generax.php?img=true' alt="Código de verificación">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                            <input type="text" name="clave" class="form-control input_user" placeholder="Escribe el código de la imagen">
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="customControlInline">
                                <label class="custom-control-label" for="customControlInline">Recordar mi acceso</label>
                            </div>
                        </div>
                        <div class="d-grid">
                            <input type="submit" name="button" class="btn login_btn" value="Ingresar">
                        </div>
                    </form>
                </div>
                <div class="mt-4">
                    <p class="text-center text-muted mb-1">¿No cuentas con acceso?</p>
                    <p class="text-center mb-2"><a href="registro.php" class="fw-semibold text-decoration-none">Solicitar inscripción</a></p>
                    <p class="text-center mb-0"><a href="restablecer.php" class="text-decoration-none">¿Olvidaste tu contraseña?</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
