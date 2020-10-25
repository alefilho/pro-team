<div class="header pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 d-inline-block mb-0">Membros</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="far fa-users"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Membros</a></li>
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
          <h3 class="mb-0">Lista de Membros</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="5"></th>
                <th width="5">#</th>
                <th>Nome</th>
                <th>Classe</th>
                <th>Feedbacks</th>
                <th>Email</th>
                <th>Data Criação</th>
                <th>Atualização</th>
              </tr>
            </thead>
            <tbody class="list">
              <?php
              if (!isset($GetPag)) {
                $GetPag = 1;
              }
              $Pager = new Pager(BASE . '/panel.php?url=members/index&pag=');
              $Pager->ExePager($GetPag, LIMIT_PAGE);
              $SQL = "SELECT
                	mem_id,
                	mem_email,
                	mem_name,
                	mem_idclass,
                	mem_createdat,
                	mem_updatedat,
                  cla_name,
                	COUNT( DISTINCT fee_id ) AS feedbacks
                FROM
                	members
                	LEFT JOIN classes ON cla_id = mem_idclass
                	LEFT JOIN sessions_topics_feedbacks ON fee_idmembertarget = mem_id
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}
                GROUP BY
                	mem_id,
                	mem_email,
                	mem_name,
                	mem_idclass,
                	mem_createdat,
                	mem_updatedat,
                  cla_name
              ";

              $Read->FullRead("{$SQL} LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
              if ($Read->getResult()) {
                foreach ($Read->getResult() as $key => $value) {
                  echo "<tr class='single_member' id='{$value['mem_id']}'>
                    <td>
                      <a href='".BASE."/panel.php?page=members/feedbacks&id={$value['mem_id']}' class='btn btn-info btn-sm'>VER FEEDBACKS</a>
                    </td>
                    <td>{$value['mem_id']}</td>
                    <td>{$value['mem_name']}</td>
                    <td>{$value['cla_name']}</td>
                    <td>{$value['feedbacks']}</td>
                    <td>{$value['mem_email']}</td>
                    <td>".(!empty($value['mem_createdat']) ? date("d/m/Y H:i:s", strtotime($value['mem_createdat'])) : "")."</td>
                    <td>".(!empty($value['mem_updatedat']) ? date("d/m/Y H:i:s", strtotime($value['mem_updatedat'])) : "")."</td>
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
