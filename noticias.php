<?php
$currentPage = 'noticias';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias | DAITEC &amp; TrazMAPE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
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

    <header class="page-hero">
        <div class="container">
            <span class="breadcrumb-custom mb-4 d-inline-flex align-items-center"><i class="fas fa-newspaper me-2"></i> Actualidad DAITEC</span>
            <h1>Noticias y reportes sobre la formalización minera</h1>
            <p class="mt-3">Historias de campo, despliegues tecnológicos y alianzas público-privadas que impulsan a la pequeña minería responsable en el Perú.</p>
        </div>
    </header>

    <main>
        <section class="page-section">
            <div class="container">
                <div class="news-grid">
                    <article class="news-article">
                        <img src="images/news/news_1.png" alt="Equipos capacitando en TrazMAPE">
                        <span class="news-meta">Madre de Dios · Mayo 2024</span>
                        <h3>TrazMAPE llega a nuevas regiones amazónicas</h3>
                        <p>Especialistas de DAITEC entrenaron a 45 cooperativas auríferas en la actualización de registros, control de inventario y verificación de la cadena de custodia. El despliegue incluye soporte remoto y monitoreo satelital.</p>
                        <a href="contacto.php" class="btn btn-outline-light mt-auto">Solicitar acompañamiento</a>
                    </article>
                    <article class="news-article">
                        <img src="images/news/news_2.jpg" alt="Laboratorio de innovación de DAITEC">
                        <span class="news-meta">Lima · Abril 2024</span>
                        <h3>Laboratorio de innovación integra sensores ambientales</h3>
                        <p>El laboratorio DAITEC conectó sensores de calidad de aire y agua a la plataforma TrazMAPE para entregar tableros en tiempo real a gobiernos regionales. La iniciativa mejora la toma de decisiones sobre operaciones responsables.</p>
                        <a href="servicios.php" class="btn btn-outline-light mt-auto">Ver programa de innovación</a>
                    </article>
                    <article class="news-article">
                        <img src="images/news/news_3.jpg" alt="Mineros artesanales en sesión de trabajo">
                        <span class="news-meta">Ayacucho · Marzo 2024</span>
                        <h3>Alianzas para fortalecer la pequeña minería familiar</h3>
                        <p>DAITEC firmó convenios con gremios regionales y cooperativas para ampliar la asistencia técnica en seguridad ocupacional, cierre de pasivos y comercialización justa en corredores mineros priorizados.</p>
                        <a href="servicios.php" class="btn btn-outline-light mt-auto">Explorar servicios territoriales</a>
                    </article>
                    <article class="news-article">
                        <img src="images/news/news_2.png" alt="Reunión de autoridades y mineros">
                        <span class="news-meta">Piura · Febrero 2024</span>
                        <h3>Mesas multiactor articulan proyectos de formalización</h3>
                        <p>Autoridades regionales, representantes de la pequeña minería y aliados internacionales definieron hojas de ruta conjuntas para reducir la informalidad y asegurar cadenas de suministro con enfoque social.</p>
                        <a href="noticias.php" class="btn btn-outline-light mt-auto">Descargar informe</a>
                    </article>
                </div>
            </div>
        </section>

        <section class="page-section pt-0">
            <div class="container">
                <div class="service-detail">
                    <h2>Recibe nuestras alertas y boletines</h2>
                    <p>Suscríbete para recibir novedades, oportunidades de capacitación y recursos metodológicos para tu organización minera. Compartimos guías, webinars y estudios desarrollados por DAITEC y sus aliados.</p>
                    <form class="row g-3 mt-4">
                        <div class="col-md-6">
                            <label for="suscriptorNombre" class="form-label">Nombre completo</label>
                            <input type="text" id="suscriptorNombre" class="form-control" placeholder="Nombre y apellido">
                        </div>
                        <div class="col-md-6">
                            <label for="suscriptorCorreo" class="form-label">Correo institucional</label>
                            <input type="email" id="suscriptorCorreo" class="form-control" placeholder="nombre@organizacion.pe">
                        </div>
                        <div class="col-12">
                            <label for="suscriptorInteres" class="form-label">Temas de interés</label>
                            <select id="suscriptorInteres" class="form-select">
                                <option selected>Selecciona una opción</option>
                                <option value="trazmape">Implementación de TrazMAPE</option>
                                <option value="formalizacion">Formalización minera</option>
                                <option value="ambiental">Gestión ambiental y social</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Quiero suscribirme</button>
                        </div>
                    </form>
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
