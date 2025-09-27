<?php
$currentPage = 'servicios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios | DAITEC &amp; TrazMAPE</title>
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
            <span class="breadcrumb-custom mb-4 d-inline-flex align-items-center"><i class="fas fa-tools me-2"></i> Soluciones DAITEC</span>
            <h1>Servicios integrales para la pequeña minería sostenible</h1>
            <p class="mt-3">Acompañamos a cooperativas, titulares mineros y gobiernos regionales con herramientas digitales, asesoría técnica y articulación estratégica.</p>
        </div>
    </header>

    <main>
        <section class="page-section">
            <div class="container">
                <div class="service-grid">
                    <div class="service-detail">
                        <h3><i class="fas fa-chalkboard-teacher me-2 text-info"></i> Formación especializada</h3>
                        <p>Programas presenciales y virtuales con metodología aplicada a operaciones de pequeña minería.</p>
                        <ul>
                            <li>Seguridad y salud ocupacional en minas subterráneas y aluviales.</li>
                            <li>Gestión ambiental con énfasis en remediación y cierre progresivo.</li>
                            <li>Escuelas de liderazgo para mujeres mineras.</li>
                        </ul>
                    </div>
                    <div class="service-detail">
                        <h3><i class="fas fa-network-wired me-2 text-info"></i> Implementación de TrazMAPE</h3>
                        <p>Desplegamos la plataforma nacional de trazabilidad del oro para asegurar cadenas responsables.</p>
                        <ul>
                            <li>Diagnóstico inicial y plan de implementación por unidad minera.</li>
                            <li>Integración con sistemas ERP, laboratorios y comercializadores.</li>
                            <li>Soporte 24/7 con mesa de ayuda especializada.</li>
                        </ul>
                    </div>
                    <div class="service-detail">
                        <h3><i class="fas fa-handshake me-2 text-info"></i> Articulación territorial</h3>
                        <p>Construimos mesas multiactor para coordinar proyectos y financiamiento de formalización.</p>
                        <ul>
                            <li>Convenios con gobiernos regionales y municipalidades.</li>
                            <li>Gestión de fondos concursables y asistencia crediticia.</li>
                            <li>Planificación participativa con comunidades y aliados.</li>
                        </ul>
                    </div>
                    <div class="service-detail">
                        <h3><i class="fas fa-microscope me-2 text-info"></i> Innovación aplicada</h3>
                        <p>Pilotos tecnológicos y analítica de datos para mejorar productividad, seguridad y transparencia.</p>
                        <ul>
                            <li>Monitoreo remoto con sensores ambientales y geotécnicos.</li>
                            <li>Modelamiento de procesos y tableros de control en tiempo real.</li>
                            <li>Laboratorios vivos con proveedores tecnológicos.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-section pt-0">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="service-card h-100">
                            <i class="fas fa-users-cog"></i>
                            <h4>Programas para gobiernos regionales</h4>
                            <p class="text-secondary">Fortalecemos capacidades para supervisión, asistencia técnica y acompañamiento a pequeños productores mineros.</p>
                            <ul class="text-secondary list-unstyled mb-0">
                                <li><i class="fas fa-check text-info me-2"></i>Rutas de formalización acelerada.</li>
                                <li><i class="fas fa-check text-info me-2"></i>Tableros de control para fiscalización.</li>
                                <li><i class="fas fa-check text-info me-2"></i>Comunicación y participación ciudadana.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service-card h-100">
                            <i class="fas fa-leaf"></i>
                            <h4>Gestión ambiental y social</h4>
                            <p class="text-secondary">Asesoría para implementar planes de manejo ambiental, remediación y relacionamiento comunitario.</p>
                            <ul class="text-secondary list-unstyled mb-0">
                                <li><i class="fas fa-check text-info me-2"></i>Evaluación de impactos y medidas mitigadoras.</li>
                                <li><i class="fas fa-check text-info me-2"></i>Planes de cierre progresivo y pasivos.</li>
                                <li><i class="fas fa-check text-info me-2"></i>Programas de desarrollo con enfoque de género.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="page-section pt-0">
            <div class="container">
                <div class="service-detail">
                    <h2>¿Necesitas un servicio a medida?</h2>
                    <p>Trabajamos con cooperativas, asociaciones y emprendimientos mineros para co-diseñar planes que respondan a sus desafíos específicos. Nuestro equipo multidisciplinario puede desplazarse a tu zona y articular recursos con entidades públicas y privadas.</p>
                    <a href="contacto.php" class="btn btn-primary mt-3">Coordinar una reunión</a>
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
