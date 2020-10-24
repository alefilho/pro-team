<?php
session_start();
require('_app/Config.inc.php');

if (!empty($_GET['token'])) {
  $Read = new Read;
  $Read->FullRead("SELECT * FROM users WHERE use_tokenrecovery = '{$_GET['token']}'");
  if (!$Read->getResult()) {
    echo 'Token Invalido';
    return;
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= TITLE; ?>">
  <meta name="author" content="<?= AUTHOR; ?>">
  <title><?= TITLE; ?> - RECUPERAÇÃO DE SENHA</title>
  <link rel="base" href="<?= BASE; ?>"/>

  <link rel="shortcut icon" href="assets/img/favicon.png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/argon/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/argon/css/argon.css?v=1.2.0" type="text/css">
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="bg-default">
  <nav id="navbar-main" class="navbar navbar-horizontal navbar-transparent navbar-main navbar-expand-lg navbar-light">
    <div class="container">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content">
    <!-- Header -->
    <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 px-5">
              <img src="assets/img/logo.svg" style="width: 130px;" alt="">
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary border-0 mb-0">
            <div class="card-body px-lg-5 py-lg-5">
              <?php if (!empty($_GET['token'])): ?>
                <form id="RecoverToken" role="form">
                  <input type="hidden" name="token" value="<?= $_GET['token']; ?>">

                  <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                      </div>
                      <input name="use_password" class="form-control" placeholder="Nova Senha" type="password">
                    </div>
                  </div>
                  <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                      </div>
                      <input name="password_confirm" class="form-control" placeholder="Repita sua Nova Senha" type="password">
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary my-4">Alterar Senha</button>
                  </div>
                </form>
              <?php endif; ?>
              <?php if (empty($_GET['token'])): ?>
                <form id="Recover" role="form">
                  <div class="form-group mb-3">
                    <div class="input-group input-group-merge input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                      </div>
                      <input name="use_email" class="form-control" placeholder="Qual seu Email?" type="email">
                    </div>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn btn-primary my-4">Recuperar Senha</button>
                  </div>
                </form>
              <?php endif; ?>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="<?= BASE; ?>" class="text-light"><small>Logar</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="<?= BASE; ?>/signup.php" class="text-light"><small>Criar uma Nova Conta</small></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <footer class="py-5" id="footer-main">
    <div class="container">
      <div class="row align-items-center justify-content-xl-between">
        <div class="col-xl-6">
          <div class="copyright text-center text-xl-left text-muted">
            &copy; <?= date("Y"); ?> <a href="#" class="font-weight-bold ml-1" target="_blank"><?= TITLE; ?></a>
          </div>
        </div>
        <div class="col-xl-6">

        </div>
      </div>
    </div>
  </footer>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="assets/argon/vendor/jquery/dist/jquery.min.js"></script>
  <script src="assets/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/argon/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/argon/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/argon/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Argon JS -->
  <script src="assets/argon/js/argon.js?v=1.2.0"></script>
  <script src="assets/js/login.js"></script>
</body>
</html>
