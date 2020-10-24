<?php
session_start();
require_once '_app/Config.inc.php';
require_once 'src/functions/utils.php';

$Login = new Login;
if (!$Login->CheckLogin()):
  unset($_SESSION['userlogin']);
  header('Location: '.BASE.'/index.php?exe=restrito');
  die;
endif;

$PostData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
$GetURL = strip_tags(trim(filter_input(INPUT_GET, 'page', FILTER_DEFAULT)));
$GetId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$GetPag = filter_input(INPUT_GET, 'pag', FILTER_VALIDATE_INT);

$logoff = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
if ($logoff == 1):
  unset($_SESSION['userlogin']);
  header('Location: index.php?exe=logoff');
  die;
endif;

// AUTO INSTANCE OBJECT READ
if (empty($Read)):
  $Read = new Read;
endif;

// AUTO INSTANCE OBJECT CREATE
if (empty($Create)):
  $Create = new Create;
endif;

// AUTO INSTANCE OBJECT UPDATE
if (empty($Update)):
  $Update = new Update;
endif;

// AUTO INSTANCE OBJECT DELETE
if (empty($Delete)):
  $Delete = new Delete;
endif;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?= TITLE; ?>">
  <meta name="author" content="<?= AUTHOR; ?>">
  <title><?= TITLE; ?> - PAINEL</title>

  <link rel="base" href="<?= BASE; ?>"/>
  <link rel="icon" href="assets/img/favicon.png" type="image/png">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <link rel="stylesheet" href="assets/argon/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="assets/argon/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <link rel="stylesheet" href="assets/argon/css/argon.css?v=1.1.0" type="text/css">
  <link rel="stylesheet" href="cdn/confirm/jquery-confirm.css">
  <link rel="stylesheet" href="assets/css/panel.css">
  <link rel="stylesheet" href="assets/fontawesome/css/pro.min.css">
  <link rel="stylesheet" href="cdn/select2/select2.min.css">

  <script src="assets/argon/vendor/jquery/dist/jquery.min.js"></script>
  <script src="cdn/confirm/jquery-confirm.js"></script>
  <script src="cdn/jquery.mask.js"></script>
  <script src="cdn/mask.js"></script>
  <script src="cdn/select2/select2.min.js"></script>
  <script src="https://cdn.tiny.cloud/1/2y6ml6p6pd2rq7upsfquajhq0qt1x8n0zspu49bn5hrxsmoy/tinymce/5/tinymce.min.js" referrerpolicy="origin"/></script>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="assets/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/argon/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/argon/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/argon/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Optional JS -->
  <script src="assets/argon/vendor/chart.js/dist/Chart.min.js"></script>
  <script src="assets/argon/vendor/chart.js/dist/Chart.extension.js"></script>
  <script src="assets/argon/vendor/jvectormap-next/jquery-jvectormap.min.js"></script>
  <script src="assets/argon/js/vendor/jvectormap/jquery-jvectormap-world-mill.js"></script>
  <script src="assets/js/panel.js"></script>

  <script src="cdn/swalert.js"></script>
</head>
<!-- g-sidenav-show g-sidenav-pinned -->
<body class="">
  <?php include 'src/components/navigation.php'; ?>

  <!-- Main content -->
  <div class="main-content" id="panel">
    <?php include 'src/components/topnav.php'; ?>

    <?php
    //QUERY STRING
    if (!empty($GetURL)):
      $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . $GetURL . '.php';
    else:
      $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'pages' . DIRECTORY_SEPARATOR . 'dashboard/index.php';
    endif;

    if (file_exists($includepatch)):
      require_once($includepatch);
    endif;
    ?>
  </div>

  <script src="assets/argon/js/argon.js?v=1.1.0"></script>
</body>
</html>
