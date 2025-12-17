<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro | EletroSys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>

<body class="page-auth">
  <fieldset class="shadow">
    <legend class="text-center w-100">Cadastro</legend>

    <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger">Ocorreu um erro. Tente novamente.</div>
    <?php endif; ?>

    <form action="../actions/cadastro_user.php" method="POST">
      <div class="mb-3">
        <label class="form-label fw-semibold">Nome completo</label>
        <input type="text" class="form-control form-control-sm rounded-pill"
               name="name" maxlength="30" placeholder="Digite seu nome" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Usuário</label>
        <input type="text" class="form-control form-control-sm rounded-pill"
               name="nickname" maxlength="20" placeholder="Escolha um usuário" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">E-mail</label>
        <input type="email" class="form-control form-control-sm rounded-pill"
               name="email" maxlength="30" placeholder="Digite seu e-mail" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Idade</label>
        <input type="date" class="form-control form-control-sm rounded-pill"
               name="data_nascimento" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Senha</label>
        <input type="password" class="form-control form-control-sm rounded-pill"
               name="pass" placeholder="Crie uma senha" required>
      </div>

      <div class="d-grid gap-2 mb-3">
        <button type="submit" class="btn btn-dark btn-sm fw-bold rounded-pill w-50 mx-auto">Cadastrar</button>
      </div>

      <div class="text-center">
        <a href="index.php" class="text-decoration-none text-muted">Já tem conta? Entrar</a>
      </div>
    </form>
  </fieldset>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../script.js"></script>
</body>
</html>
