<?php
if (isset($GetId)) {
  $Read->FullRead("SELECT * FROM users WHERE use_id = {$GetId}");

  if ($Read->getResult()) {
    $Register = $Read->getResult()[0];
  }else {
    echo triggerRegisterNotFound();
    return;
  }
}
?>

<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Membros</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><i class="ni ni-single-02"></i></li>
              <li class="breadcrumb-item">Membros</li>
              <li class="breadcrumb-item active" aria-current="page">Formulário</li>
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="<?= BASE; ?>/panel.php?url=user/index" class="btn btn-sm btn-neutral">Voltar</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--6">
  <form class="j_form" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-xl-8 order-xl-1">
        <div class="card">
          <!-- Card header -->
          <div class="card-header">
            <h3 class="mb-0"><?= ((isset($GetId)) ? "Atualizar" : "Novo"); ?> Usuário</h3>
          </div>
          <!-- Light table -->
          <div class="card-body">
            <input type="hidden" name="AjaxFile" value="User">
            <input type="hidden" name="AjaxAction" value="save">
            <input type="hidden" name="id" value="<?= (isset($Register) ? $Register['par_id'] : ""); ?>">

            <div class="form-group">
              <label>*Tipo de Documento</label>
              <select class="form-control" name="par_idoperationtypedocument" required>
                <?php
                $Read->FullRead("SELECT oty_id, oty_description FROM operationstype WHERE oty_group = " . OT_GROUP_PERSON_TYPE);
                if ($Read->getResult()) {
                  foreach ($Read->getResult() as $key => $value) {
                    echo "<option value='{$value['oty_id']}' ".(isset($Register) && $Register['par_idoperationtypedocument'] == $value['oty_id'] ? "selected" : "").">{$value['oty_description']}</option>";
                  }
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label>*Documento</label>
              <input type="text" name="par_cpf" value="<?= (isset($Register) ? $Register['par_cpf'] : ""); ?>" placeholder="Documento" class="form-control" required>
            </div>

            <div class="form-group">
              <label>*Status</label>
              <select class="form-control" name="par_idoperationtypestatus" required>
                <?php
                $Read->FullRead("SELECT oty_id, oty_description FROM operationstype WHERE oty_group = " . OT_GROUP_STATUS);
                if ($Read->getResult()) {
                  foreach ($Read->getResult() as $key => $value) {
                    echo "<option value='{$value['oty_id']}' ".(isset($Register) && $Register['par_idoperationtypestatus'] == $value['oty_id'] ? "selected" : "").">{$value['oty_description']}</option>";
                  }
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label>*Nome Completo</label>
              <input type="text" name="par_companyname" value="<?= (isset($Register) ? $Register['par_companyname'] : ""); ?>" placeholder="Nome" class="form-control" required>
            </div>

            <div class="form-group">
              <label>*Email</label>
              <input type="text" name="par_email" value="<?= (isset($Register) ? $Register['par_email'] : ""); ?>" placeholder="Email" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Senha</label>
              <input type="password" name="par_password" value="" placeholder="Senha" class="form-control">
            </div>

            <div class="form-group">
              <label>*Permissão</label>
              <select class="form-control" name="par_idpermissiongroup" required>
                <?php
                $Read->FullRead("SELECT pgr_id, pgr_name FROM permissionsgroups WHERE pgr_idoperationtypestatus = " . OTY_STATUS_SIMPLE_ACTIVE);
                if ($Read->getResult()) {
                  foreach ($Read->getResult() as $key => $value) {
                    echo "<option value='{$value['pgr_id']}' ".(isset($Register) && $Register['par_idpermissiongroup'] == $value['pgr_id'] ? "selected" : "").">{$value['pgr_name']}</option>";
                  }
                }
                ?>
              </select>
            </div>


          </div>
          <!-- Card footer -->
          <div class="card-footer py-4">
            <button class="btn btn-sm btn-default" type="submit" name="button">Salvar</button>
          </div>
        </div>
      </div>

      <div class="col-xl-4 order-xl-2">
        <div class="card card-profile">
          <img src="assets/argon/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
          <div class="row justify-content-center">
            <div class="col-lg-3 order-lg-2">
              <div class="card-profile-image">
                <a href="#">
                  <img id="j_show_img" src="<?= (isset($Register) && !empty($Register['par_picture']) ? BASE . "/tim.php?w=150&h=150&src=uploads/" . $Register['par_picture'] : BASE . "/tim.php?w=150&h=150&src=assets/img/noimg.png"); ?>" class="rounded-circle">
                </a>
              </div>
            </div>
          </div>
          <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">

          </div>
          <div class="card-body pt-0">
            <div class="row">
              <div class="form-group">
                <br>
                <br>
                <input onchange="showThumbnail(this);" type="file" name="par_picture" class="form-control">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>

  <?php include 'components/panel/footer.php'; ?>
</div>
