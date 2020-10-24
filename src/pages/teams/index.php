<div class="header pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 d-inline-block mb-0">Times</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-users"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Times</a></li>
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
          <h3 class="mb-0">Lista de Times</h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="5">#</th>
                <th>Nome</th>
                <th>Membros</th>
                <th>Descrição</th>
                <th>Classe</th>
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
              $Pager->ExePager($GetPag, 50);
              $SQL = "SELECT
                	tea_id,
                	tea_name,
                	tea_description,
                	tea_createdat,
                	tea_updatedat,
                	GROUP_CONCAT(mem_name + ', ' ORDER BY mem_name) AS members
                FROM
                	teams
                	LEFT JOIN teams_members ON tme_idteam = tea_id
                	LEFT JOIN members ON mem_id = tme_idmember
                	LEFT JOIN classes ON cla_id = mem_idclass
                WHERE
                	cla_iduser = {$_SESSION['userlogin']['use_id']}
                GROUP BY
                	tea_id,
                	tea_name,
                	tea_description,
                	tea_createdat,
                	tea_updatedat
              ";

              $Read->FullRead("{$SQL} LIMIT :limit OFFSET :offset", "limit={$Pager->getLimit()}&offset={$Pager->getOffset()}");
              if ($Read->getResult()) {
                foreach ($Read->getResult() as $key => $value) {
                  echo "<tr class='single_team' id='{$value['tea_id']}'>
                    <td>{$value['tea_id']}</td>
                    <td>{$value['tea_name']}</td>
                    <td>{$value['members']}</td>
                    <td>{$value['tea_description']}</td>
                    <td>{$value['cla_name']}</td>
                    <td>".(!empty($value['tea_createdat']) ? date("d/m/Y H:i:s", strtotime($value['tea_createdat'])) : "")."</td>
                    <td>".(!empty($value['tea_updatedat']) ? date("d/m/Y H:i:s", strtotime($value['tea_updatedat'])) : "")."</td>
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
