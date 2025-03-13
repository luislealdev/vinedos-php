<?php
require_once __DIR__ . '/admin/models/producto.php';
$web = new Producto();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$productos = $web->findAll($id);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <link rel="stylesheet" href="./styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <title>Productos - Viñedos Rusiles</title>
</head>

<body>
  <main>
    <!-- Navbar (igual que en index.html) -->
    <nav class="navbar navbar-expand-md bg-body-tertiary">
      <div class="container-xl">
        <a class="navbar-brand" href="#">
          <img src="./public/img/logo.png" alt="" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="/vinedos/">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/vinedos/productos.html">Productos</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Recorridos
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" href="/vinedos/visitas-guiadas.html">Visitas guiadas</a>
                </li>
                <li><a class="dropdown-item" href="/vinedos/vendimia.html">Vendimia</a></li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a class="dropdown-item" href="/vinedos/reservaciones.html">Reservaciones</a>
                </li>
              </ul>
            </li>
          </ul>
          <div class="contact-info d-md-flex">
            <p>+52 412 171 9136</p>
            <p><a href="mailto:">ventas@rusiles.com</a></p>
          </div>
        </div>
      </div>
    </nav>

    <!-- Header de la página -->
    <div class="container py-5">
      <div class="row">
        <div class="col-12 text-center">
          <h1 class="display-4 mb-4">Nuestros Productos</h1>
          <p class="lead text-secondary">
            Descubre nuestra selección de vinos premium elaborados con las
            mejores uvas.
          </p>
          <!-- Show marca if id -->
          <?php if ($id): ?>
            <h2 class="display-12 mt-4"><?php echo $productos[0]['marca'] ?></h2>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Filtros -->
    <div class="container mb-5">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-4">Filtros</h5>

              <div class="mb-4">
                <h6>Tipo de Vino</h6>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="tinto" />
                  <label class="form-check-label" for="tinto">Tinto</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="blanco" />
                  <label class="form-check-label" for="blanco">Blanco</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="rosado" />
                  <label class="form-check-label" for="rosado">Rosado</label>
                </div>
              </div>

              <div class="mb-4">
                <h6>Precio</h6>
                <input type="range" class="form-range" min="0" max="20000" id="precio" />
                <div class="d-flex justify-content-between">
                  <span>$0</span>
                  <span>$20,000</span>
                </div>
              </div>

              <div>
                <h6>Ordenar por</h6>
                <select class="form-select">
                  <option>Más relevantes</option>
                  <option>Menor precio</option>
                  <option>Mayor precio</option>
                  <option>Más vendidos</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Productos -->
        <div class="col-md-9">
          <div class="row g-4">

            <?php foreach ($productos as $producto): ?>
              <div class="col-md-4">
                <div class="product-single-card">
                  <div class="product-top-area">
                    <div class="product-discount">10%</div>
                    <div class="product-img">
                      <div class="first-view">
                        <img src="<?php echo './uploads/' . $producto['fotografia'] ?>"
                          alt="<?php echo $producto['producto'] ?>" class="img-fluid" />
                      </div>
                      <div class="hover-view">
                        <img src="./uploads/producto.jpg" alt="logo" class="img-fluid" />
                      </div>
                    </div>

                    <div class="sideicons">
                      <button class="sideicons-btn">
                        <i class="fa-solid fa-cart-plus"></i>
                      </button>
                      <button class="sideicons-btn">
                        <i class="fa-solid fa-eye"></i>
                      </button>
                      <button class="sideicons-btn">
                        <i class="fa-solid fa-heart"></i>
                      </button>
                    </div>
                  </div>
                  <div class="product-info">
                    <h6 class="product-category"><a href="#">
                        <?php echo $producto['marca'] ?>
                      </a></h6>
                    <h6 class="product-title text-truncate">
                      <a href="#">
                        <?php echo $producto['producto'] ?>
                      </a>
                    </h6>
                    <div class="d-flex align-items-center">
                      <div class="review-star me-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-regular fa-star"></i>
                      </div>
                      <span class="review-count">(<?php echo rand(1, 100) ?>)</span>
                    </div>
                    <div class="d-flex flex-wrap align-items-center py-2">
                      <div class="old-price">$<?php echo $producto['precio'] ?></div>
                      <div class="new-price">$<?php echo $producto['precio'] * 0.9 ?></div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <!-- Paginación -->
          <nav class="mt-5">
            <ul class="pagination justify-content-center">
              <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Anterior</a>
              </li>
              <li class="page-item active">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item">
                <a class="page-link" href="#">Siguiente</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>

    <!-- Footer (igual que en index.html) -->
    <footer class="py-3">
      <!-- Widgets - Bootstrap Brain Component -->
      <div class="py-6 py-md-8 py-xl-10">
        <div class="container">
          <div class="row gy-3">
            <div class="col-12">
              <div class="footer-logo-wrapper text-center">
                <a href="#!">
                  <img src="./public/img/logo.png" alt="BootstrapBrain Logo" width="500" height="250" />
                </a>
              </div>
            </div>
            <div class="col-12">
              <div class="link-wrapper">
                <ul class="m-0 list-unstyled d-flex justify-content-center gap-3">
                  <li>
                    <a href="#!"
                      class="link-underline-opacity-0 link-opacity-75-hover link-underline-opacity-100-hover link-offset-1 link-dark link-opacity-75 fs-7">
                      Inicio
                    </a>
                  </li>
                  <li>
                    <a href="#!"
                      class="link-underline-opacity-0 link-opacity-75-hover link-underline-opacity-100-hover link-offset-1 link-dark link-opacity-75 fs-7">
                      Productos
                    </a>
                  </li>
                  <li>
                    <a href="#!"
                      class="link-underline-opacity-0 link-opacity-75-hover link-underline-opacity-100-hover link-offset-1 link-dark link-opacity-75 fs-7">
                      Recorridos
                    </a>
                  </li>
                  <li>
                    <a href="#!"
                      class="link-underline-opacity-0 link-opacity-75-hover link-underline-opacity-100-hover link-offset-1 link-dark link-opacity-75 fs-7">
                      Reservaciones
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div>
        <div class="container">
          <div class="row gy-3 gy-sm-0 align-items-sm-center">
            <div class="col-sm-6">
              <div class="copyright-wrapper d-block mb-1 fs-9 text-center text-sm-start">
                &copy; 2025. Todos los derechos reservados.
              </div>
            </div>
            <div class="col-sm-6">
              <div class="social-media-wrapper">
                <ul class="m-0 list-unstyled d-flex justify-content-center justify-content-sm-end gap-3">
                  <li>
                    <a href="#!" class="link-opacity-75-hover link-dark">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-youtube" viewBox="0 0 16 16">
                        <path
                          d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.01 2.01 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.01 2.01 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31 31 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.01 2.01 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A100 100 0 0 1 7.858 2zM6.4 5.209v4.818l4.157-2.408z" />
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a href="#!" class="link-opacity-75-hover link-dark">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-facebook" viewBox="0 0 16 16">
                        <path
                          d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951" />
                      </svg>
                    </a>
                  </li>
                  <li>
                    <a href="#!" class="link-opacity-75-hover link-dark">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-twitter" viewBox="0 0 16 16">
                        <path
                          d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334q.002-.211-.006-.422A6.7 6.7 0 0 0 16 3.542a6.7 6.7 0 0 1-1.889.518 3.3 3.3 0 0 0 1.447-1.817 6.5 6.5 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.32 9.32 0 0 1-6.767-3.429 3.29 3.29 0 0 0 1.018 4.382A3.3 3.3 0 0 1 .64 6.575v.045a3.29 3.29 0 0 0 2.632 3.218 3.2 3.2 0 0 1-.865.115 3 3 0 0 1-.614-.057 3.28 3.28 0 0 0 3.067 2.277A6.6 6.6 0 0 1 .78 13.58a6 6 0 0 1-.78-.045A9.34 9.34 0 0 0 5.026 15" />
                      </svg>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>