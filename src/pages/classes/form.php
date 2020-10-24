<?php
if (isset($GetId)) {
  $Read->FullRead("SELECT * FROM classes WHERE cla_id = {$GetId}");

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
          <h6 class="h2 d-inline-block mb-0">Classes</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-users-class"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Classes</a></li>
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
  <form id="ClassForm" class="j_form" method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="col-xl-12 order-xl-1">
        <div class="card">
          <!-- Card header -->
          <div class="card-header">
            <h3 class="mb-0"><?= ((isset($GetId)) ? "Atualizar" : "Nova"); ?> Classe</h3>
          </div>
          <!-- Light table -->
          <div class="card-body">
            <input type="hidden" name="AjaxFile" value="Classes">
            <input type="hidden" name="AjaxAction" value="save">
            <input type="hidden" name="id" value="<?= (isset($Register) ? $Register['cla_id'] : ""); ?>">

            <div class="form-group">
              <label>*Nome</label>
              <input type="text" name="cla_name" value="<?= (isset($Register) ? $Register['cla_name'] : ""); ?>" placeholder="Nome" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Descrição</label>
              <input type="text" name="cla_description" value="<?= (isset($Register) ? $Register['cla_description'] : ""); ?>" placeholder="Descrição" class="form-control">
            </div>

            <div class="form-group">
              <label>*Chave de Acesso</label>
              <input type="text" name="cla_key" value="<?= (isset($Register) ? $Register['cla_key'] : ""); ?>" placeholder="Chave de Acesso" class="form-control" required>
            </div>

            <div class="form-group">
              <label>*Status</label>
              <select class="form-control" name="cla_allowteam" required>
                <option value="1" <?= (isset($Register) && $Register['cla_allowteam'] == 1 ? "selected" : ""); ?>>Aberto</option>
                <option value="0" <?= (isset($Register) && $Register['cla_allowteam'] == 0 ? "selected" : ""); ?>>Fechado</option>
              </select>
            </div>
          </div>
          <!-- Card footer -->
          <div class="card-footer py-4">
            <button class="btn btn-sm btn-default" type="submit" name="button">Salvar</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <?php include 'src/components/footer.php'; ?>
</div>
