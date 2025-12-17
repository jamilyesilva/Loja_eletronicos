<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | EletroSys</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>

<body class="page-auth">
  <fieldset class="shadow">
    <legend class="text-center w-100">Login</legend>

    <form method="POST" action="../actions/valida_usuario.php">
      <?php if (isset($_GET['error']) && $_GET['error'] === 'credentials'): ?>
        <div class="alert alert-danger">Usuário ou senha incorretos.</div>
      <?php endif; ?>
      <?php if (isset($_GET['registered'])): ?>
        <div class="alert alert-success">Cadastro realizado com sucesso. Faça login.</div>
      <?php endif; ?>

      <div class="mb-3">
        <label for="nickname" class="form-label fw-semibold">Usuário</label>
        <input type="text" class="form-control" id="nickname" name="nickname"
               placeholder="Digite o usuário" maxlength="20" autocomplete="off" required autofocus>
      </div>

      <div class="mb-3">
        <label for="pass" class="form-label fw-semibold">Senha</label>
        <input type="password" class="form-control" id="pass" name="pass"
               placeholder="Digite a senha" maxlength="255" autocomplete="off" required>
      </div>

      <div class="d-grid gap-2 mb-3">
        <button type="submit" name="valida_user" class="btn btn-dark btn-sm fw-bold rounded-pill w-50 mx-auto">Entrar</button>
      </div>

      <div class="text-center">
        <a href="form_user.php" class="text-decoration-none text-muted">Novo por aqui? Cadastre-se</a>
      </div>
    </form>
  </fieldset>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../script.js"></script>
</body>
</html>
