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
    <title>Solicitar inscripción | DAITEC &amp; TrazMAPE</title>
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
                    <h2 class="fw-bold mb-1">Solicitar acceso</h2>
                    <p class="text-muted mb-0">Comparte tu información para habilitar una cuenta administrativa.</p>
                </div>
                <div class="form_container">
                    <form action="#" method="post">
                        <div class="mb-3">
                            <label for="registroNombre" class="form-label">Nombre completo</label>
                            <input type="text" class="form-control" id="registroNombre" placeholder="Nombre y apellidos">
                        </div>
                        <div class="mb-3">
                            <label for="registroCorreo" class="form-label">Correo institucional</label>
                            <input type="email" class="form-control" id="registroCorreo" placeholder="nombre@organizacion.gob.pe">
                        </div>
                        <div class="mb-3">
                            <label for="registroEntidad" class="form-label">Entidad / Organización</label>
                            <input type="text" class="form-control" id="registroEntidad" placeholder="Dirección, gerencia u organización">
                        </div>
                        <div class="mb-3">
                            <label for="registroTelefono" class="form-label">Teléfono de contacto</label>
                            <input type="tel" class="form-control" id="registroTelefono" placeholder="Ej. +51 999 888 777">
                        </div>
                        <div class="mb-3">
                            <label for="registroRol" class="form-label">Rol dentro de la organización</label>
                            <select class="form-select" id="registroRol">
                                <option selected disabled>Selecciona una opción</option>
                                <option value="coordinador">Coordinador/a regional</option>
                                <option value="especialista">Especialista técnico</option>
                                <option value="directivo">Directivo / Responsable</option>
                                <option value="otro">Otro rol vinculado a la formalización</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="registroMotivo" class="form-label">¿Cómo apoyarás la formalización minera?</label>
                            <textarea class="form-control" id="registroMotivo" rows="3" placeholder="Describe brevemente el motivo de tu solicitud y los proyectos con los que trabajas."></textarea>
                        </div>
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn login_btn">Enviar solicitud</button>
                            <a href="index.php" class="secondary_btn text-center">Volver al inicio de sesión</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
