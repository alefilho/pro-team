<?php
$pages = [
  "dashboard" => [
    "dashboard/index"
  ],
  "members" => [
    "members/index"
  ],
  "sessions" => [
    "sessions/index",
    "sessions/form"
  ],
  "classes" => [
    "classes/index",
    "classes/form"
  ],
  "teams" => [
    "teams/index"
  ]
];
?>

<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-dark" id="sidenav-main">
  <div class="scrollbar-inner">
    <!-- Brand -->
    <div class="sidenav-header d-flex align-items-center">
      <a class="navbar-brand" href="<?= BASE; ?>">
        <img src="assets/img/logo2.svg" class="navbar-brand-img" alt="...">
      </a>
      <div class="ml-auto">
        <!-- Sidenav toggler -->
        <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
          <div class="sidenav-toggler-inner">
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
            <i class="sidenav-toggler-line"></i>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar-inner">
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Nav items -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link <?= (((!empty($GetURL) && in_array($GetURL, $pages['dashboard'])) || empty($GetURL)) ? "active-clean" : ""); ?>" href="<?= BASE; ?>/panel.php?page=dashboard/index">
              <i class="fas fa-chart-line text-primary"></i>
              <span class="nav-link-text">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <!-- class = "active-clean" -->
            <!-- aria-expanded = "true" -->
            <a class="nav-link <?= ((!empty($GetURL) && in_array($GetURL, $pages['members'])) ? "active-clean" : ""); ?>" href="#navbar-members" data-toggle="collapse" role="button" aria-expanded="<?= !empty($GetURL) && in_array($GetURL, $pages['members']) ? "true" : "false"; ?>" aria-controls="navbar-members">
              <i class="far fa-users" style="color: #fb6340;"></i>
              <span class="nav-link-text">Membros</span>
            </a>
            <!-- class = "show" -->
            <div class="collapse <?= !empty($GetURL) && in_array($GetURL, $pages['members']) ? "show" : ""; ?>" id="navbar-members">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="<?= BASE; ?>/panel.php?page=members/index" class="nav-link active">Gerenciar</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ((!empty($GetURL) && in_array($GetURL, $pages['classes'])) ? "active-clean" : ""); ?>" href="#navbar-classes" data-toggle="collapse" role="button" aria-expanded="<?= !empty($GetURL) && in_array($GetURL, $pages['classes']) ? "true" : "false"; ?>" aria-controls="navbar-classes">
              <i class="fas fa-users-class" style="color: #f5365c;"></i>
              <span class="nav-link-text">Classes</span>
            </a>
            <div class="collapse <?= !empty($GetURL) && in_array($GetURL, $pages['classes']) ? "show" : ""; ?>" id="navbar-classes">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="<?= BASE; ?>/panel.php?page=classes/index" class="nav-link">Gerenciar</a>
                  <a href="<?= BASE; ?>/panel.php?page=classes/form" class="nav-link">Criar</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ((!empty($GetURL) && in_array($GetURL, $pages['sessions'])) ? "active-clean" : ""); ?>" href="#navbar-sessions" data-toggle="collapse" role="button" aria-expanded="<?= !empty($GetURL) && in_array($GetURL, $pages['sessions']) ? "true" : "false"; ?>" aria-controls="navbar-sessions">
              <i class="fas fa-atom" style="color: #2dce89;"></i>
              <span class="nav-link-text">Sessões</span>
            </a>
            <div class="collapse <?= !empty($GetURL) && in_array($GetURL, $pages['sessions']) ? "show" : ""; ?>" id="navbar-sessions">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="<?= BASE; ?>/panel.php?page=sessions/index" class="nav-link">Gerenciar</a>
                  <a href="<?= BASE; ?>/panel.php?page=sessions/form" class="nav-link">Criar</a>
                </li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ((!empty($GetURL) && in_array($GetURL, $pages['teams'])) ? "active-clean" : ""); ?>" href="#navbar-teams" data-toggle="collapse" role="button" aria-expanded="<?= !empty($GetURL) && in_array($GetURL, $pages['teams']) ? "true" : "false"; ?>" aria-controls="navbar-teams">
              <i class="fas fa-users" style="color: #11cdef;"></i>
              <span class="nav-link-text">Times</span>
            </a>
            <div class="collapse <?= !empty($GetURL) && in_array($GetURL, $pages['teams']) ? "show" : ""; ?>" id="navbar-teams">
              <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                  <a href="<?= BASE; ?>/panel.php?page=teams/index" class="nav-link">Gerenciar</a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
