<?php
require_once("adm/script/conex.php");

// Obtener banners activos
$cn = new MySQLcn();
$sql = "SELECT Titulo, Describir, Enlace, Imagen FROM banner WHERE estado = 1 ORDER BY fecha DESC";
$cn->Query($sql);
$banners = $cn->Rows();
$cn->Close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAITEC | TrazMAPE</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .carousel-item {
            height: 500px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body>
    <!-- Navigation Menu -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#inicio">DAITEC &amp; TrazMAPE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#noticias">Noticias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#servicios">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="adm/index.php">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner Slider -->
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicadores -->
        <div class="carousel-indicators">
            <?php for($i = 0; $i < count($banners); $i++): ?>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="<?php echo $i; ?>"
                    <?php echo $i === 0 ? 'class="active"' : ''; ?>></button>
            <?php endfor; ?>
        </div>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php if(!empty($banners)): ?>
                <?php foreach($banners as $index => $banner): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="images/banner/<?php echo htmlspecialchars($banner['Imagen']); ?>"
                         class="d-block w-100" alt="<?php echo htmlspecialchars($banner['Titulo']); ?>">
                    <div class="carousel-caption">
                        <h3><?php echo htmlspecialchars($banner['Titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($banner['Describir']); ?></p>
                        <?php if(!empty($banner['Enlace'])): ?>
                        <a href="<?php echo htmlspecialchars($banner['Enlace']); ?>" class="btn btn-primary" target="_blank">
                            Ver más <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="carousel-item active">
                    <img src="images/banner/default.jpg" class="d-block w-100" alt="Banner por defecto">
                    <div class="carousel-caption">
                        <h3>Impulsando la Pequeña Minería Responsable</h3>
                        <p>DAITEC y TrazMAPE articulan soluciones digitales para la formalización minera en el Perú.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Controles -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Inicio Section -->
    <section id="inicio">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="hero-content">
                        <div class="hero-badges mb-3">
                            <span><i class="fas fa-globe-americas"></i> Perú Minero</span>
                            <span><i class="fas fa-seedling"></i> Formalización Responsable</span>
                        </div>
                        <h1 class="display-5 fw-bold mb-4">Tecnología y trazabilidad para una pequeña minería sostenible</h1>
                        <p class="lead text-secondary">La Dirección de Asistencia Técnica para la Formalización (DAITEC) del Ministerio de Energía y Minas impulsa iniciativas que fortalecen la pequeña minería y la minería artesanal. Con TrazMAPE, la plataforma de trazabilidad para materiales auríferos, acompañamos a las organizaciones mineras en su transición hacia operaciones responsables, sostenibles y competitivas.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a href="#servicios" class="btn btn-primary">Conoce nuestros servicios</a>
                            <a href="#noticias" class="btn btn-outline-light border-0" style="background: rgba(148, 163, 184, 0.12);">Últimas novedades</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Aliados de la pequeña minería</h5>
                            <p class="card-text">Promovemos prácticas seguras, acceso a mercados responsables y cumplimiento normativo para miles de familias mineras a escala nacional.</p>
                            <ul class="list-unstyled text-secondary mb-0">
                                <li class="mb-2"><i class="fas fa-check-circle text-info me-2"></i> Acompañamiento técnico integral</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-info me-2"></i> Digitalización de la trazabilidad con TrazMAPE</li>
                                <li><i class="fas fa-check-circle text-info me-2"></i> Articulación con cadenas de valor responsables</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News Section -->
    <section id="noticias" class="news-section">
        <div class="container">
            <h2 class="section-heading">Noticias <span>Destacadas</span></h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="images/news/news_1.png" class="card-img-top" alt="Implementación de TrazMAPE">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">TrazMAPE llega a nuevas regiones</h5>
                            <p class="card-text">Equipos de DAITEC capacitan a cooperativas de pequeña minería en Madre de Dios, Ayacucho y La Libertad para registrar operaciones y dar seguimiento responsable al oro.</p>
                            <a href="#contacto" class="btn btn-primary mt-auto">Solicitar información</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="images/news/news_2.jpg" class="card-img-top" alt="Innovación tecnológica">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Innovación para la formalización</h5>
                            <p class="card-text">El laboratorio de innovación de DAITEC impulsa pilotos de sensores ambientales y control de producción, integrados con la plataforma TrazMAPE para mejorar la transparencia.</p>
                            <a href="#servicios" class="btn btn-primary mt-auto">Ver programas</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="images/news/news_3.jpg" class="card-img-top" alt="Pequeña minería responsable">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Pequeña minería con enfoque social</h5>
                            <p class="card-text">Más de 2,000 mineros artesanales reciben asistencia técnica en salud ocupacional, cierre de pasivos y comercialización justa gracias a alianzas público-privadas lideradas por DAITEC.</p>
                            <a href="#contacto" class="btn btn-primary mt-auto">Únete como aliado</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Servicios Section -->
    <section id="servicios">
        <div class="container">
            <h2 class="section-heading">Servicios para la <span>Pequeña Minería</span></h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="service-card h-100">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <h4 class="text-white">Capacitaciones especializadas</h4>
                        <p class="text-secondary">Diseñamos programas de formación en seguridad minera, gestión ambiental y trazabilidad digital para titulares y cooperativas.</p>
                        <ul class="text-secondary list-unstyled mb-0">
                            <li><i class="fas fa-check text-info me-2"></i>Talleres presenciales y virtuales</li>
                            <li><i class="fas fa-check text-info me-2"></i>Certificación por competencias</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card h-100">
                        <i class="fas fa-network-wired"></i>
                        <h4 class="text-white">Gestión con TrazMAPE</h4>
                        <p class="text-secondary">Implementamos la plataforma nacional de trazabilidad para asegurar origen responsable, control de inventarios y reportes en tiempo real.</p>
                        <ul class="text-secondary list-unstyled mb-0">
                            <li><i class="fas fa-check text-info me-2"></i>Integración con cadenas de suministro</li>
                            <li><i class="fas fa-check text-info me-2"></i>Soporte técnico 24/7</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="service-card h-100">
                        <i class="fas fa-handshake"></i>
                        <h4 class="text-white">Articulación territorial</h4>
                        <p class="text-secondary">Coordinamos con gobiernos regionales, gremios mineros y aliados internacionales para impulsar proyectos de formalización y acceso a mercados.</p>
                        <ul class="text-secondary list-unstyled mb-0">
                            <li><i class="fas fa-check text-info me-2"></i>Mesas de diálogo multiactor</li>
                            <li><i class="fas fa-check text-info me-2"></i>Planificación participativa</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contacto Section -->
    <section id="contacto">
        <div class="container">
            <h2 class="section-heading">Conversemos sobre <span>Formalización</span></h2>
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact-card h-100">
                        <h4>Oficina de Asistencia Técnica</h4>
                        <p class="text-secondary">Nuestro equipo está listo para acompañar a organizaciones mineras, gobiernos regionales y aliados estratégicos.</p>
                        <div class="contact-detail">
                            <i class="fas fa-envelope"></i>
                            <span>contacto@daitec.gob.pe</span>
                        </div>
                        <div class="contact-detail">
                            <i class="fas fa-phone"></i>
                            <span>+51 1 123 4567</span>
                        </div>
                        <div class="contact-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Av. De la Minería 150, Lima, Perú</span>
                        </div>
                        <div class="contact-social mt-4">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title">Agenda una reunión</h4>
                            <p class="card-text text-secondary">Cuéntanos sobre tus operaciones o iniciativas para la pequeña minería y coordinemos una sesión de trabajo.</p>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre y organización</label>
                                    <input type="text" class="form-control" id="name" placeholder="Ej. Cooperativa Minera Andina">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control" id="email" placeholder="nombre@organizacion.pe">
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Interés específico</label>
                                    <textarea class="form-control" id="message" rows="4" placeholder="Implementar TrazMAPE, capacitación, articulación..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white py-4">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between">
            <p class="mb-0">&copy; 2024 DAITEC &amp; TrazMAPE. Impulsando la pequeña minería responsable en el Perú.</p>
            <small>Ministerio de Energía y Minas | <a href="mailto:contacto@daitec.gob.pe">contacto@daitec.gob.pe</a></small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/main.js"></script>
</body>
</html>
