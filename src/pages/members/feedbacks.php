<?php
if (isset($GetId)) {
  $Read->FullRead("SELECT mem_id, mem_name FROM members WHERE mem_id = {$GetId}");

  if ($Read->getResult()) {
    $Register = $Read->getResult()[0];
  }else {
    echo triggerRegisterNotFound();
    return;
  }
}
?>
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
              <li class="breadcrumb-item active" aria-current="page">Feedbacks</li>
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
    <div class="col-xl-12 col-md-6">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Média</h5>
              <span class="h2 font-weight-bold mb-0" id="Media"></span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                <i class="ni ni-atom"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col">
      <div class="card">
        <!-- Card header -->
        <div class="card-header border-0">
          <h3 class="mb-0"><?= $Register['mem_name']; ?></h3>
        </div>
        <!-- Light table -->
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th width="5">#</th>
                <th>Sessão</th>
                <th>Tópico</th>
                <th>Membro</th>
                <th>Estrelas</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody class="list">
              <?php
              $SQL = "SELECT
                	fee_id,
                	ses_name,
                	top_description,
                	mem_name,
                	fee_stars,
                	fee_createdat
                FROM
                	sessions_topics_feedbacks
                	LEFT JOIN sessions_topics ON fee_idtopic = top_id
                	LEFT JOIN sessions ON ses_id = top_idsession
                	LEFT JOIN members ON mem_id = fee_idmember
                WHERE
                	fee_idmembertarget = {$Register['mem_id']}
              ";

              $Read->FullRead($SQL);
              $total = 0;
              if ($Read->getResult()) {
                foreach ($Read->getResult() as $key => $value) {
                  $total += $value['fee_stars'];

                  $stars = "<ul class='ul_stars j_star_feed'>";
                  for ($i=1; $i <= 5; $i++) {
                    if ($value['fee_stars'] >= $i) {
                      $stars .= "<li><i class='fas fa-star' rel='1'></i></li>";
                    }else {
                      $stars .= "<li><i class='far fa-star' rel='2'></i></li>";
                    }
                  }
                  $stars .= "</ul>";

                  echo "<tr class='single_feedback' id='{$value['fee_id']}'>
                    <td>{$value['fee_id']}</td>
                    <td>{$value['ses_name']}</td>
                    <td>{$value['top_description']}</td>
                    <td>{$value['mem_name']}</td>
                    <td>{$stars}</td>
                    <td>".(!empty($value['fee_createdat']) ? date("d/m/Y H:i:s", strtotime($value['fee_createdat'])) : "")."</td>
                  </tr>";
                }
              }
              ?>
            </tbody>
          </table>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">

        </div>
      </div>
    </div>
  </div>

  <?php include 'src/components/footer.php'; ?>
</div>

<script>
  $(function() {
    $("#Media").html("<?= $total / $Read->getRowCount(); ?>")
  })
</script>
