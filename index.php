<?php
require_once("adm/script/conex.php");

$currentPage = 'inicio';

// Obtener banners activos
$banners = [];
try {
    $cn = new MySQLcn();
    $sql = "SELECT Titulo, Describir, Enlace, Imagen FROM banner WHERE estado = 1 ORDER BY fecha DESC";
    $cn->Query($sql);
    $banners = $cn->Rows();
    $cn->Close();
} catch (Throwable $e) {
    $banners = [];
}
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
                    <img src="images/news/news_1.png" class="d-block w-100" alt="Banner por defecto">
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
                            <a href="servicios.php" class="btn btn-primary">Conoce nuestros servicios</a>
                            <a href="noticias.php" class="btn btn-outline-light">Últimas novedades</a>
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

    <section class="page-section quick-links">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column">
                        <i class="fas fa-newspaper"></i>
                        <h4 class="text-white">Noticias y alertas</h4>
                        <p>Entérate de los avances de la formalización, despliegues de TrazMAPE y convocatorias regionales para la pequeña minería responsable.</p>
                        <a href="noticias.php" class="btn btn-primary">Ver noticias</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column">
                        <i class="fas fa-tools"></i>
                        <h4 class="text-white">Servicios especializados</h4>
                        <p>Capacitaciones, soporte técnico y articulación territorial diseñados por DAITEC para fortalecer operaciones mineras sostenibles.</p>
                        <a href="servicios.php" class="btn btn-primary">Explorar servicios</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 d-flex flex-column">
                        <i class="fas fa-comments"></i>
                        <h4 class="text-white">Conecta con nosotros</h4>
                        <p>Agenda reuniones, solicita asistencia y descubre cómo integrarte a las cadenas de valor responsables que impulsa DAITEC.</p>
                        <a href="contacto.php" class="btn btn-primary">Ir a contacto</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page-section pt-0">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="service-detail h-100">
                        <span class="breadcrumb-custom mb-3">DAITEC en acción</span>
                        <h2>Formalización minera con impacto territorial</h2>
                        <p>Guiamos a la pequeña minería y minería artesanal del Perú hacia la sostenibilidad. Nuestros equipos despliegan TrazMAPE para asegurar trazabilidad del oro, fortalecen la seguridad ocupacional y articulan proyectos con aliados estratégicos.</p>
                        <ul>
                            <li>Acompañamiento personalizado a cooperativas y unidades productivas.</li>
                            <li>Monitoreo ambiental y social integrado a plataformas digitales.</li>
                            <li>Enlaces con mercados responsables y programas de financiamiento.</li>
                        </ul>
                        <div class="tag-list">
                            <span>Pequeña Minería</span>
                            <span>Trazabilidad</span>
                            <span>Innovación Pública</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="card-title text-white">Resultados recientes</h4>
                            <p class="card-text text-secondary">Más de 2,000 mineros capacitados en los últimos 12 meses, 15 oficinas regionales integradas a TrazMAPE y nuevas rutas comerciales para oro responsable.</p>
                            <div class="d-flex flex-column gap-3 mt-4">
                                <div>
                                    <span class="text-secondary">Cobertura TrazMAPE</span>
                                    <div class="progress" style="height: 10px; background: rgba(148, 163, 184, 0.15);">
                                        <div class="progress-bar" role="progressbar" style="width: 78%; background: var(--accent);"></div>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-secondary">Cooperativas acompañadas</span>
                                    <div class="progress" style="height: 10px; background: rgba(148, 163, 184, 0.15);">
                                        <div class="progress-bar" role="progressbar" style="width: 64%; background: var(--accent-strong);"></div>
                                    </div>
                                </div>
                            </div>
                            <a href="noticias.php" class="btn btn-outline-light mt-4">Conocer casos de éxito</a>
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
