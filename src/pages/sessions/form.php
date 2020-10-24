<?php
if (isset($GetId)) {
  $Read->FullRead("SELECT * FROM sessions WHERE ses_id = {$GetId}");

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
          <h6 class="h2 d-inline-block mb-0">Sessões</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-atom"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Sessões</a></li>
              <li class="breadcrumb-item active" aria-current="page">Formulário</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="<?= BASE; ?>/panel.php?url=classes/index" class="btn btn-sm btn-neutral">Voltar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
  <div class="row">
    <div class="col-xl-12 order-xl-1">
      <div class="card">
        <!-- Card header -->
        <div class="card-header">
          <h3 class="mb-0"><?= ((isset($GetId)) ? "Atualizar" : "Nova"); ?> Sessão</h3>
        </div>
        <!-- Light table -->
        <div class="card-body">
          <form id="SessionForm" class="j_form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="AjaxFile" value="Sessions">
            <input type="hidden" name="AjaxAction" value="save">
            <input type="hidden" name="id" value="<?= (isset($Register) ? $Register['ses_id'] : ""); ?>">

            <div class="form-group">
              <label>*Nome</label>
              <input type="text" name="ses_name" value="<?= (isset($Register) ? $Register['ses_name'] : ""); ?>" placeholder="Nome" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Encerramento</label>
              <input type="text" name="ses_endat" value="<?= (isset($Register) ? date("d/m/Y H:i:s", strtotime($Register['ses_endat'])) : ""); ?>" placeholder="Encerramento" class="form-control jwc_datepicker date_time" data-timepicker="true">
            </div>

            <div class="form-group">
              <label>*Classe</label>
              <select class="form-control" name="ses_idclass" required>
                <?php
                $Read->FullRead("SELECT cla_id, cla_name FROM classes WHERE cla_iduser = {$_SESSION['userlogin']['use_id']}");
                if ($Read->getResult()) {
                  foreach ($Read->getResult() as $key => $value) {
                    echo "<option value='{$value['cla_id']}' ".(isset($Register) && $Register['ses_idclass'] == $value['cla_id'] ? "selected" : "").">{$value['cla_name']}</option>";
                  }
                }
                ?>
              </select>
            </div>
          </form>
        </div>
        <!-- Card footer -->
        <div class="card-footer py-4">
          <button class="btn btn-sm btn-default" type="submit" name="button">Salvar</button>
        </div>
      </div>
    </div>
  </div>

  <?php if (isset($Register)): ?>
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <!-- Card header -->
          <div class="card-header">
            <h3 class="mb-0">Tópicos</h3>
          </div>
          <!-- Light table -->
          <form id="TopicForm" class="j_form" action="" method="post">
            <div class="card-body">
              <input type="hidden" name="AjaxFile" value="Sessions">
              <input type="hidden" name="AjaxAction" value="saveTopic">
              <input type="hidden" name="id" value="">
              <input type="hidden" name="top_idsession" value="<?= $Register['ses_id']; ?>">

              <div class="row">
                <div class="col-xl-12">
                  <div class="form-group">
                    <label>*Descrição</label>
                    <textarea name="top_description" rows="3" cols="80" placeholder="Descrição" class="form-control" required></textarea>
                  </div>
                </div>
              </div>

              <button class="btn btn-sm btn-default" type="submit" name="button">Salvar</button>
            </div>
          </form>
          <!-- Card footer -->
          <div class="card-footer py-4">
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th width="5"></th>
                    <th width="5">#</th>
                    <th>Descrição</th>
                  </tr>
                </thead>
                <tbody class="list tbody_topics">
                  <?php
                  $SQL = "SELECT
                      top_id,
                      top_description
                    FROM
                      sessions_topics
                    WHERE
                      top_idsession = {$Register['ses_id']}
                  ";

                  $Read->FullRead($SQL);
                  if ($Read->getResult()) {
                    foreach ($Read->getResult() as $key => $value) {
                      echo "<tr class='single_topic' id='{$value['top_id']}'>
                        <td>
                          <div class='dropdown'>
                            <a class='btn btn-sm btn-icon-only text-light' href='#' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                              <i class='fas fa-ellipsis-v'></i>
                            </a>
                            <div class='dropdown-menu dropdown-menu-right dropdown-menu-arrow' style=''>
                              <button ajaxfile='Sessions' ajaxaction='getTopic' ajaxdata='id={$value['top_id']}' class='dropdown-item j_ajax_generic'>Editar</button>
                            </div>
                          </div>
                        </td>
                        <td>{$value['top_id']}</td>
                        <td>{$value['top_description']}</td>
                      </tr>";
                    }
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <?php include 'src/components/footer.php'; ?>
</div>
