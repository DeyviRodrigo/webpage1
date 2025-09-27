<?php
$currentPage = 'contacto';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto | DAITEC &amp; TrazMAPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">DAITEC &amp; TrazMAPE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'inicio' ? 'active' : ''; ?>" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'noticias' ? 'active' : ''; ?>" href="noticias.php">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'servicios' ? 'active' : ''; ?>" href="servicios.php">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage === 'contacto' ? 'active' : ''; ?>" href="contacto.php">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adm/index.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="page-hero">
        <div class="container">
            <span class="breadcrumb-custom mb-4 d-inline-flex align-items-center"><i class="fas fa-envelope me-2"></i> Conversemos</span>
            <h1>Coordinemos acciones para formalizar la pequeña minería</h1>
            <p class="mt-3">Nuestro equipo está disponible para asesorías, despliegues de TrazMAPE y articulaciones regionales con enfoque sostenible.</p>
        </div>
    </header>

    <main>
        <section class="page-section">
            <div class="container contact-layout">
                <div class="contact-info-block">
                    <h3>Oficina de Asistencia Técnica</h3>
                    <p>Atendemos consultas de cooperativas mineras, gobiernos regionales, aliados privados y organizaciones de la sociedad civil.</p>
                    <ul class="list-unstyled mt-4">
                        <li><i class="fas fa-envelope"></i><span>contacto@daitec.gob.pe</span></li>
                        <li><i class="fas fa-phone"></i><span>+51 1 123 4567</span></li>
                        <li><i class="fas fa-map-marker-alt"></i><span>Av. De la Minería 150, Lima, Perú</span></li>
                    </ul>
                    <div class="tag-list">
                        <span>Atención 8:30 - 17:30</span>
                        <span>Asistencia en campo</span>
                        <span>Soporte TrazMAPE</span>
                    </div>
                    <div class="contact-social mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="contact-form-card">
                    <h3>Agenda una reunión</h3>
                    <p class="text-secondary">Cuéntanos tu necesidad y coordinaremos la mejor ruta de acompañamiento para tu organización.</p>
                    <form class="mt-4">
                        <div class="mb-3">
                            <label for="organizacion" class="form-label">Nombre y organización</label>
                            <input type="text" id="organizacion" class="form-control" placeholder="Ej. Cooperativa Minera Andina">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo electrónico</label>
                            <input type="email" id="correo" class="form-control" placeholder="nombre@organizacion.pe">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono de contacto</label>
                            <input type="tel" id="telefono" class="form-control" placeholder="Ej. +51 912 345 678">
                        </div>
                        <div class="mb-3">
                            <label for="servicio" class="form-label">Servicio de interés</label>
                            <select id="servicio" class="form-select">
                                <option selected>Selecciona una opción</option>
                                <option value="trazmape">Implementación de TrazMAPE</option>
                                <option value="formalizacion">Plan de formalización</option>
                                <option value="capacitacion">Capacitaciones especializadas</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mensaje" class="form-label">Mensaje</label>
                            <textarea id="mensaje" rows="4" class="form-control" placeholder="Describe brevemente tu necesidad"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="page-section pt-0">
            <div class="container">
                <div class="service-detail">
                    <h2>Oficinas regionales y puntos de atención</h2>
                    <p>Contamos con equipos itinerantes en Madre de Dios, Puno, La Libertad, Ayacucho y Piura. Agenda una visita técnica o participa en nuestras mesas de formalización.</p>
                    <div class="row g-4 mt-2">
                        <div class="col-md-4">
                            <div class="service-card h-100">
                                <i class="fas fa-map-marked-alt"></i>
                                <h4>Madre de Dios</h4>
                                <p class="text-secondary">Centro de innovación y trazabilidad para cooperativas auríferas y aluviales.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card h-100">
                                <i class="fas fa-mountain"></i>
                                <h4>Puno</h4>
                                <p class="text-secondary">Acompañamiento a mineros filonianos y articulación con refinerías responsables.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="service-card h-100">
                                <i class="fas fa-seedling"></i>
                                <h4>La Libertad</h4>
                                <p class="text-secondary">Programas de remediación y seguridad con enfoque comunitario.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="text-white py-4">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="mb-0">&copy; 2024 DAITEC &amp; TrazMAPE. Impulsando la pequeña minería responsable en el Perú.</p>
            <small>Ministerio de Energía y Minas | <a href="mailto:contacto@daitec.gob.pe">contacto@daitec.gob.pe</a></small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
