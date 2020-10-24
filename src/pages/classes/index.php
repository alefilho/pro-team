<div class="header pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 d-inline-block mb-0">Classes</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-users-class"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Classes</a></li>
              <li class="breadcrumb-item active" aria-current="page">Lista</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <!-- <a href="#" class="btn btn-sm btn-neutral">New</a>
          <a href="#" class="btn btn-sm btn-neutral">Filters</a> -->
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0">Lista de Classes</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="5"></th>
                <th width="5">#</th>
                <th>Nome</th>
                <th>Chave de Acesso</th>
                <th>Status</th>
                <th>Membros</th>
                <th>Sessões</th>
                <th>Tópicos</th>
                <th>Feedbacks</th>
                <th>Data Criação</th>
                <th>Atualização</th>
              </tr>
            </thead>
            <tbody class="list">
              <?php
              if (!isset($GetPag)) {
                $GetPag = 1;
              }
              $Pager = new Pager(BASE . '/panel.php?page=members/index&pag=');
              $Pager->ExePager($GetPag, 50);
              $SQL = "SELECT
                	cla_id,
                	cla_name,
                	cla_key,
                	cla_allowteam,
                	cla_createdat,
                	cla_updatedat,
                  COUNT( DISTINCT mem_id ) AS members,
                	COUNT( DISTINCT ses_id ) AS sessions,
                	COUNT( DISTINCT top_id ) AS topics,
                	COUNT( DISTINCT fee_id ) AS feedbacks
                FROM
                	classes
                  LEFT JOIN members ON mem_idclass = cla_id
                	LEFT JOIN sessions ON ses_idclass = cla_id
                	LEFT JOIN sessions_topics ON top_idsession = ses_id
                	LEFT JOIN sessions_topics_feedbacks ON fee_idtopic = top_id
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}
                GROUP BY
                	cla_id,
                	cla_name,
                	cla_key,
                	cla_allowteam,
                	cla_createdat,
                	cla_updatedat
              ";

              $Read->FullRead("{$SQL} LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
              if ($Read->getResult()) {
                foreach ($Read->getResult() as $key => $value) {
                  $status = '<span class="btn btn-outline-warning btn-sm">Fechado</span>';
                  if ($value['cla_allowteam'] == 1) {
                    $status = '<span class="btn btn-outline-primary btn-sm">Aberto</span>';
                  }

                  echo "<tr class='single_class' id='{$value['cla_id']}'>
                    <td>
                      <div class='dropdown'>
                        <a class='btn btn-sm btn-icon-only text-light' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                          <i class='fas fa-ellipsis-v'></i>
                        </a>
                        <div class='dropdown-menu dropdown-menu-right dropdown-menu-arrow' style=''>
                          <a class='dropdown-item' href='".BASE."/panel.php?page=classes/form&id={$value['cla_id']}'>Editar</a>
                        </div>
                      </div>
                    </td>
                    <td>{$value['cla_id']}</td>
                    <td>{$value['cla_name']}</td>
                    <td>{$value['cla_key']}</td>
                    <td>{$status}</td>
                    <td>{$value['members']}</td>
                    <td>{$value['sessions']}</td>
                    <td>{$value['topics']}</td>
                    <td>{$value['feedbacks']}</td>
                    <td>".(!empty($value['cla_createdat']) ? date("d/m/Y H:i:s", strtotime($value['cla_createdat'])) : "")."</td>
                    <td>".(!empty($value['cla_updatedat']) ? date("d/m/Y H:i:s", strtotime($value['cla_updatedat'])) : "")."</td>
                  </tr>";
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">
          <?php
          $Pager->ExePaginator(null, null, null, $SQL);
          echo $Pager->getPaginator();
          ?>
        </div>
      </div>
    </div>
  </div>

  <?php include 'src/components/footer.php'; ?>
</div>
